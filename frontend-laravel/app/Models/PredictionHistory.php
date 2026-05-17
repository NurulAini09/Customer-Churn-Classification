<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PredictionHistory extends Model
{
    protected $fillable = [
        'account_length',
        'area_code',
        'international_plan',
        'voice_mail_plan',
        'number_vmail_messages',
        'total_day_minutes',
        'total_day_calls',
        'total_day_charge',
        'total_eve_minutes',
        'total_eve_calls',
        'total_eve_charge',
        'total_night_minutes',
        'total_night_calls',
        'total_night_charge',
        'total_intl_minutes',
        'total_intl_calls',
        'total_intl_charge',
        'customer_service_calls',
        'prediction_result',
        'probability',
        'risk_level',
    ];

    protected function casts(): array
    {
        return [
            'account_length' => 'integer',
            'area_code' => 'integer',
            'international_plan' => 'integer',
            'voice_mail_plan' => 'integer',
            'number_vmail_messages' => 'integer',
            'total_day_minutes' => 'float',
            'total_day_calls' => 'integer',
            'total_day_charge' => 'float',
            'total_eve_minutes' => 'float',
            'total_eve_calls' => 'integer',
            'total_eve_charge' => 'float',
            'total_night_minutes' => 'float',
            'total_night_calls' => 'integer',
            'total_night_charge' => 'float',
            'total_intl_minutes' => 'float',
            'total_intl_calls' => 'integer',
            'total_intl_charge' => 'float',
            'customer_service_calls' => 'integer',
            'probability' => 'float',
        ];
    }
}
