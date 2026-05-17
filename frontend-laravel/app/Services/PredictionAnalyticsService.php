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
        $highRiskCount = PredictionHistory::query()->where('risk_level', 'Tinggi')->count();
        $averageProbability = (float) PredictionHistory::query()->avg('probability');

        return [
            'stats' => [
                [
                    'label' => 'Total Prediksi',
                    'value' => number_format($total),
                    'trend' => 'Riwayat tersimpan',
                ],
                [
                    'label' => 'Churn Rate',
                    'value' => $total > 0 ? number_format(($churnCount / $total) * 100, 1).'%' : '0%',
                    'trend' => $churnCount.' pelanggan churn',
                ],
                [
                    'label' => 'Avg Probability',
                    'value' => number_format($averageProbability, 1).'%',
                    'trend' => 'Rata-rata risiko',
                ],
                [
                    'label' => 'High Risk',
                    'value' => number_format($highRiskCount),
                    'trend' => 'Perlu prioritas',
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
            ->map(fn (PredictionHistory $item) => [
                'timestamp' => $item->created_at->format('d/m/Y H:i'),
                'result' => $item->prediction_result,
                'probability' => $item->probability,
                'risiko' => $item->risk_level,
                'area_code' => $item->area_code,
                'customer_service_calls' => $item->customer_service_calls,
            ])
            ->all();
    }
}
