<?php

namespace App\Services;

use App\Models\PredictionHistory;
use Illuminate\Support\Collection;

class PredictionAnalyticsService
{
    public function dashboardData(): array
    {
        $histories = PredictionHistory::query()->latest()->limit(50)->get();
        $total = PredictionHistory::query()->count();
        $churnCount = PredictionHistory::query()->where('prediction_result', 'Churn')->count();
        $nonChurnCount = $total - $churnCount;
        $churnRate = $total > 0 ? round(($churnCount / $total) * 100, 1) : 0;

        $highRiskCount = PredictionHistory::query()->where('risk_level', 'Tinggi')->count();
        $mediumRiskCount = PredictionHistory::query()->where('risk_level', 'Sedang')->count();
        $lowRiskCount = PredictionHistory::query()->where('risk_level', 'Rendah')->count();

        $averageProbability = $total > 0 ? (float) PredictionHistory::query()->avg('probability') : 0;
        $avgCsCalls = $total > 0 ? round((float) PredictionHistory::query()->avg('customer_service_calls'), 1) : 0;
        $avgDayMinutes = $total > 0 ? round((float) PredictionHistory::query()->avg('total_day_minutes'), 1) : 0;
        $avgAccountLength = $total > 0 ? round((float) PredictionHistory::query()->avg('account_length'), 0) : 0;

        $riskDist = $this->riskDistribution($total, $lowRiskCount, $mediumRiskCount, $highRiskCount);
        $featureCorrelations = $this->featureCorrelations();
        $insights = $this->generateInsights($total, $churnRate, $highRiskCount, $averageProbability, $featureCorrelations);

        return [
            'metrics' => [
                'total' => $total,
                'churn_count' => $churnCount,
                'non_churn_count' => $nonChurnCount,
                'churn_rate' => $churnRate,
                'average_probability' => round($averageProbability, 1),
                'high_risk_count' => $highRiskCount,
                'medium_risk_count' => $mediumRiskCount,
                'low_risk_count' => $lowRiskCount,
                'avg_cs_calls' => $avgCsCalls,
                'avg_day_minutes' => $avgDayMinutes,
                'avg_account_length' => $avgAccountLength,
            ],
            'stats' => [
                [
                    'label' => 'Total Klasifikasi',
                    'value' => number_format($total),
                    'trend' => 'Riwayat tersimpan',
                    'sub' => $churnCount.' Pelanggan Churn',
                    'icon' => 'users',
                    'badge' => 'PRED',
                    'color' => 'rose',
                ],
                [
                    'label' => 'Tingkat Churn (Rate)',
                    'value' => $churnRate.'%',
                    'trend' => $churnCount.' dari '.$total.' terindikasi churn',
                    'sub' => $nonChurnCount.' Pelanggan Bertahan',
                    'icon' => 'trending-down',
                    'badge' => 'RATE',
                    'color' => $churnRate > 30 ? 'red' : ($churnRate > 15 ? 'amber' : 'emerald'),
                ],
                [
                    'label' => 'Avg Probability',
                    'value' => number_format($averageProbability, 1).'%',
                    'trend' => 'Rata-rata risiko sistem',
                    'sub' => $averageProbability >= 60 ? 'Risiko Tinggi' : ($averageProbability >= 35 ? 'Risiko Sedang' : 'Risiko Rendah'),
                    'icon' => 'activity',
                    'badge' => 'AVG',
                    'color' => 'blue',
                ],
                [
                    'label' => 'Prioritas Intervensi',
                    'value' => number_format($highRiskCount),
                    'trend' => 'Risiko tinggi (High Risk)',
                    'sub' => 'Butuh tindakan retensi',
                    'icon' => 'shield-alert',
                    'badge' => 'PRIO',
                    'color' => 'red',
                ],
            ],
            'riskDistribution' => $riskDist,
            'dailyTrend' => $this->dailyTrend(),
            'featureCorrelations' => $featureCorrelations,
            'insights' => $insights,
            'recentHistory' => $this->formatHistory($histories->take(10)),
        ];
    }

    public function recentHistory(int $limit = 10): array
    {
        return $this->formatHistory(PredictionHistory::query()->latest()->limit($limit)->get());
    }

    private function riskDistribution(int $total, int $low, int $med, int $high): array
    {
        return [
            [
                'label' => 'Rendah',
                'count' => $low,
                'percentage' => $total > 0 ? round(($low / $total) * 100, 1) : 0,
                'color' => '#10B981',
                'border' => '#059669',
                'bg' => '#ECFDF5',
                'text' => '#065F46',
            ],
            [
                'label' => 'Sedang',
                'count' => $med,
                'percentage' => $total > 0 ? round(($med / $total) * 100, 1) : 0,
                'color' => '#F59E0B',
                'border' => '#D97706',
                'bg' => '#FFFBEB',
                'text' => '#92400E',
            ],
            [
                'label' => 'Tinggi',
                'count' => $high,
                'percentage' => $total > 0 ? round(($high / $total) * 100, 1) : 0,
                'color' => '#DB5A8D',
                'border' => '#BE185D',
                'bg' => '#FFF0F6',
                'text' => '#9D174D',
            ],
        ];
    }

    private function dailyTrend(): array
    {
        $rows = PredictionHistory::query()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total, SUM(CASE WHEN prediction_result = "Churn" THEN 1 ELSE 0 END) as churn_total, AVG(probability) as avg_prob')
            ->groupBy('day')
            ->orderBy('day', 'ASC')
            ->limit(10)
            ->get();

        if ($rows->isEmpty()) {
            return [];
        }

        return $rows
            ->map(fn ($row) => [
                'label' => date('d M', strtotime($row->day)),
                'date' => $row->day,
                'total' => (int) $row->total,
                'churn' => (int) ($row->churn_total ?? 0),
                'safe' => (int) $row->total - (int) ($row->churn_total ?? 0),
                'avg_prob' => round((float) ($row->avg_prob ?? 0), 1),
            ])
            ->all();
    }

    private function featureCorrelations(): array
    {
        // 1. Customer Service Calls breakdown
        $csLow = PredictionHistory::query()->where('customer_service_calls', '<=', 1)->get();
        $csMed = PredictionHistory::query()->whereBetween('customer_service_calls', [2, 3])->get();
        $csHigh = PredictionHistory::query()->where('customer_service_calls', '>=', 4)->get();

        $calcRate = function ($collection) {
            $count = $collection->count();
            if ($count === 0) return ['total' => 0, 'churn' => 0, 'rate' => 0];
            $churn = $collection->where('prediction_result', 'Churn')->count();
            return [
                'total' => $count,
                'churn' => $churn,
                'rate' => round(($churn / $count) * 100, 1),
            ];
        };

        // 2. International Plan breakdown
        $intlYes = PredictionHistory::query()->where('international_plan', 1)->get();
        $intlNo = PredictionHistory::query()->where('international_plan', 0)->get();

        // 3. Voice Mail Plan breakdown
        $vmailYes = PredictionHistory::query()->where('voice_mail_plan', 1)->get();
        $vmailNo = PredictionHistory::query()->where('voice_mail_plan', 0)->get();

        return [
            'cs_calls' => [
                'low' => array_merge(['label' => '0-1 Panggilan'], $calcRate($csLow)),
                'med' => array_merge(['label' => '2-3 Panggilan'], $calcRate($csMed)),
                'high' => array_merge(['label' => '≥ 4 Panggilan'], $calcRate($csHigh)),
            ],
            'intl_plan' => [
                'yes' => array_merge(['label' => 'Paket Internasional: Ya'], $calcRate($intlYes)),
                'no' => array_merge(['label' => 'Paket Internasional: Tidak'], $calcRate($intlNo)),
            ],
            'vmail_plan' => [
                'yes' => array_merge(['label' => 'Voicemail: Ya'], $calcRate($vmailYes)),
                'no' => array_merge(['label' => 'Voicemail: Tidak'], $calcRate($vmailNo)),
            ],
        ];
    }

    private function generateInsights(int $total, float $churnRate, int $highRiskCount, float $avgProb, array $correlations): array
    {
        $insights = [];

        if ($total === 0) {
            return [
                [
                    'type' => 'info',
                    'title' => 'Mulai Klasifikasi Pelanggan',
                    'text' => 'Belum ada data klasifikasi yang tersimpan. Jalankan klasifikasi pertama Anda untuk melihat analitik dan pola retensi pelanggan.',
                    'icon' => 'sparkles',
                ],
            ];
        }

        // CS Calls Insight
        $csHighRate = $correlations['cs_calls']['high']['rate'] ?? 0;
        $csHighTotal = $correlations['cs_calls']['high']['total'] ?? 0;
        if ($csHighTotal > 0 && $csHighRate > 40) {
            $insights[] = [
                'type' => 'danger',
                'title' => 'Faktor Kritis Panggilan CS (≥4 Panggilan)',
                'text' => "Pelanggan dengan 4 atau lebih panggilan customer service memiliki tingkat churn {$csHighRate}%. Disarankan penanganan komplain prioritas dan follow-up langsung oleh supervisor.",
                'icon' => 'phone-call',
            ];
        } else {
            $insights[] = [
                'type' => 'success',
                'title' => 'Korelasi Dukungan Pelanggan Stabil',
                'text' => 'Mayoritas pelanggan memiliki panggilan customer service di bawah ambang batas kritis. Pertahankan kualitas respons agen.',
                'icon' => 'phone-call',
            ];
        }

        // Intl Plan Insight
        $intlYesRate = $correlations['intl_plan']['yes']['rate'] ?? 0;
        $intlYesTotal = $correlations['intl_plan']['yes']['total'] ?? 0;
        if ($intlYesTotal > 0 && $intlYesRate >= 30) {
            $insights[] = [
                'type' => 'warning',
                'title' => 'Sensitivitas Tarif Paket Internasional',
                'text' => "Pelanggan pengguna paket internasional menunjukkan tingkat churn {$intlYesRate}%. Pertimbangkan review tarif roaming atau bundling kuota nelpon luar negeri.",
                'icon' => 'globe',
            ];
        }

        // High Risk Alert
        if ($highRiskCount > 0) {
            $insights[] = [
                'type' => 'danger',
                'title' => "{$highRiskCount} Pelanggan Prioritas Tinggi",
                'text' => "Terdapat {$highRiskCount} pelanggan dengan kategori risiko tinggi yang membutuhkan intervensi segera (diskon loyalitas, penawaran khusus, atau survei kepuasan).",
                'icon' => 'shield-alert',
            ];
        }

        // Overall Health
        if ($churnRate < 20) {
            $insights[] = [
                'type' => 'success',
                'title' => 'Tingkat Retensi Keseluruhan Baik',
                'text' => "Tingkat churn rata-rata berada di angka {$churnRate}%, mencerminkan loyalitas pelanggan yang relatif terjaga.",
                'icon' => 'check-circle-2',
            ];
        }

        return array_slice($insights, 0, 3);
    }

    private function formatHistory(Collection $histories): array
    {
        return $histories
            ->map(function (PredictionHistory $item) {
                $probability = (float) $item->probability;
                $isChurn = $item->prediction_result === 'Churn';

                return [
                    'id' => $item->id,
                    'timestamp' => $item->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') . ' WIB',
                    'date_only' => $item->created_at->setTimezone('Asia/Jakarta')->format('d M Y'),
                    'result' => $item->prediction_result,
                    'probability' => $item->probability,
                    'risiko' => $item->risk_level,
                    'area_code' => $item->area_code,
                    'customer_service_calls' => $item->customer_service_calls,
                    'account_length' => $item->account_length,
                    'international_plan' => (int) $item->international_plan === 1 ? 'Yes' : 'No',
                    'voice_mail_plan' => (int) $item->voice_mail_plan === 1 ? 'Yes' : 'No',
                    'total_day_minutes' => $item->total_day_minutes,
                    'total_day_calls' => $item->total_day_calls,
                    'total_day_charge' => $item->total_day_charge,
                    'total_eve_minutes' => $item->total_eve_minutes,
                    'total_eve_calls' => $item->total_eve_calls,
                    'total_eve_charge' => $item->total_eve_charge,
                    'total_night_minutes' => $item->total_night_minutes,
                    'total_night_calls' => $item->total_night_calls,
                    'total_night_charge' => $item->total_night_charge,
                    'total_intl_minutes' => $item->total_intl_minutes,
                    'total_intl_calls' => $item->total_intl_calls,
                    'total_intl_charge' => $item->total_intl_charge,
                    'description' => $isChurn
                        ? 'Pelanggan masuk ke kelas churn berdasarkan pola durasi panggilan dan layanan yang tersimpan.'
                        : 'Pelanggan masuk ke kelas tidak churn dengan probabilitas retensi yang aman.',
                    'confidence_label' => $probability >= 70 ? 'Keyakinan tinggi' : ($probability >= 40 ? 'Keyakinan sedang' : 'Keyakinan rendah'),
                ];
            })
            ->all();
    }
}
