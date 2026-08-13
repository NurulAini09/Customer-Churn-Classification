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
        $highRiskCount = PredictionHistory::query()->where('risk_level', 'Tinggi')->count();
        $averageProbability = (float) PredictionHistory::query()->avg('probability');

        return [
            'stats' => [
                [
                    'label' => 'Total Klasifikasi',
                    'value' => number_format($total),
                    'trend' => 'Riwayat tersimpan',
                ],
                [
                    'label' => 'Avg Probability',
                    'value' => number_format($averageProbability, 1).'%',
                    'trend' => 'Rata-rata risiko',
                ],
                [
                    'label' => 'Prioritas',
                    'value' => number_format($highRiskCount),
                    'trend' => 'Risiko tinggi',
                ],
            ],
            'riskDistribution' => $this->riskDistribution(),
            'dailyTrend' => $this->dailyTrend(),
            'recentHistory' => $this->formatHistory($histories->take(8)),
        ];
    }

    public function recentHistory(int $limit = 10): array
    {
        return $this->formatHistory(PredictionHistory::query()->latest()->limit($limit)->get());
    }

    private function riskDistribution(): array
    {
        $labels = ['Rendah', 'Sedang', 'Tinggi'];

        return collect($labels)
            ->map(fn (string $label) => [
                'label' => $label,
                'count' => PredictionHistory::query()->where('risk_level', $label)->count(),
            ])
            ->all();
    }

    private function dailyTrend(): array
    {
        $rows = PredictionHistory::query()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->limit(7)
            ->get();

        return $rows
            ->map(fn ($row) => [
                'label' => $row->day,
                'total' => (int) $row->total,
            ])
            ->all();
    }

    private function formatHistory(Collection $histories): array
    {
        return $histories
            ->map(function (PredictionHistory $item) {
                $probability = (float) $item->probability;
                $isChurn = $item->prediction_result === 'Churn';

                return [
                    'id' => $item->id,
                    'timestamp' => $item->created_at->format('d/m/Y'),
                    'result' => $item->prediction_result,
                    'probability' => $item->probability,
                    'risiko' => $item->risk_level,
                    'area_code' => $item->area_code,
                    'customer_service_calls' => $item->customer_service_calls,
                    'account_length' => $item->account_length,
                    'international_plan' => (int) $item->international_plan === 1 ? 'Yes' : 'No',
                    'voice_mail_plan' => (int) $item->voice_mail_plan === 1 ? 'Yes' : 'No',
                    'total_day_minutes' => $item->total_day_minutes,
                    'total_eve_minutes' => $item->total_eve_minutes,
                    'total_night_minutes' => $item->total_night_minutes,
                    'total_intl_minutes' => $item->total_intl_minutes,
                    'description' => $isChurn
                        ? 'Pelanggan masuk ke kelas churn berdasarkan data layanan yang tersimpan.'
                        : 'Pelanggan masuk ke kelas tidak churn berdasarkan data layanan yang tersimpan.',
                    'confidence_label' => $probability >= 70 ? 'Keyakinan tinggi' : ($probability >= 40 ? 'Keyakinan sedang' : 'Keyakinan rendah'),
                ];
            })
            ->all();
    }
}

