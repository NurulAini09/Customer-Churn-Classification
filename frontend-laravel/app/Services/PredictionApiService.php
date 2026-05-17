<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PredictionApiService
{
    public function predict(array $payload): array
    {
        try {
            $response = Http::timeout(20)
                ->acceptJson()
                ->post(config('services.prediction.url'), $payload);
        } catch (ConnectionException $exception) {
            throw new RuntimeException(
                'Service prediksi Python belum aktif atau tidak dapat dihubungi. Detail: '.$exception->getMessage(),
                previous: $exception,
            );
        }

        if ($response->failed()) {
            throw new RuntimeException(
                $response->json('message') ?? $response->json('detail') ?? 'Terjadi error saat memproses prediksi.',
            );
        }

        return $response->json('data', []);
    }
}
