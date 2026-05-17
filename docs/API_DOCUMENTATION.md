# API Documentation

Base URL:

```text
http://127.0.0.1:5001
```

## Health Check

```http
GET /health
```

Response:

```json
{
  "status": "ok",
  "service": "customer-churn-prediction-service",
  "model_loaded": true,
  "required_fields": [
    "account_length",
    "area_code",
    "international_plan",
    "voice_mail_plan",
    "number_vmail_messages",
    "total_day_minutes",
    "total_day_calls",
    "total_day_charge",
    "total_eve_minutes",
    "total_eve_calls",
    "total_eve_charge",
    "total_night_minutes",
    "total_night_calls",
    "total_night_charge",
    "total_intl_minutes",
    "total_intl_calls",
    "total_intl_charge",
    "customer_service_calls"
  ]
}
```

## Prediction

```http
POST /predict
Content-Type: application/json
```

Request:

```json
{
  "account_length": 128,
  "area_code": 415,
  "international_plan": 0,
  "voice_mail_plan": 1,
  "number_vmail_messages": 25,
  "total_day_minutes": 265.1,
  "total_day_calls": 110,
  "total_day_charge": 45.07,
  "total_eve_minutes": 197.4,
  "total_eve_calls": 99,
  "total_eve_charge": 16.78,
  "total_night_minutes": 244.7,
  "total_night_calls": 91,
  "total_night_charge": 11.01,
  "total_intl_minutes": 10.0,
  "total_intl_calls": 3,
  "total_intl_charge": 2.7,
  "customer_service_calls": 1
}
```

Success response:

```json
{
  "success": true,
  "data": {
    "result": "Tidak Churn",
    "prediction": 0,
    "probability": 16.9,
    "risiko": "Rendah",
    "keterangan": "Pelanggan cenderung bertahan berdasarkan data saat ini.",
    "top_factors": [
      {
        "feature_key": "customer service calls",
        "label": "Customer Service Calls",
        "importance_percentage": 16.9,
        "input_value": 1
      }
    ],
    "factor_note": "Faktor berikut menunjukkan variabel yang paling berpengaruh menurut feature importance model.",
    "input": {}
  }
}
```

Error response:

```json
{
  "success": false,
  "message": "Payload prediksi belum valid."
}
```
