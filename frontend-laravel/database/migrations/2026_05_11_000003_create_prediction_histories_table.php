<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prediction_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('account_length');
            $table->unsignedInteger('area_code');
            $table->boolean('international_plan');
            $table->boolean('voice_mail_plan');
            $table->unsignedInteger('number_vmail_messages');
            $table->decimal('total_day_minutes', 8, 2);
            $table->unsignedInteger('total_day_calls');
            $table->decimal('total_day_charge', 8, 2);
            $table->decimal('total_eve_minutes', 8, 2);
            $table->unsignedInteger('total_eve_calls');
            $table->decimal('total_eve_charge', 8, 2);
            $table->decimal('total_night_minutes', 8, 2);
            $table->unsignedInteger('total_night_calls');
            $table->decimal('total_night_charge', 8, 2);
            $table->decimal('total_intl_minutes', 8, 2);
            $table->unsignedInteger('total_intl_calls');
            $table->decimal('total_intl_charge', 8, 2);
            $table->unsignedInteger('customer_service_calls');
            $table->string('prediction_result', 32);
            $table->decimal('probability', 5, 2);
            $table->string('risk_level', 32);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prediction_histories');
    }
};
