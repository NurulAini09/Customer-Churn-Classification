# Customer Churn Prediction

Production-style customer churn prediction system with Laravel as the main web frontend and FastAPI as the machine learning microservice.

## Final Structure

```text
customer-churn-prediction/
├── backend-python/
│   ├── app/
│   │   ├── api/
│   │   ├── services/
│   │   ├── ml/
│   │   │   ├── models/
│   │   │   └── predictor.py
│   │   ├── utils/
│   │   └── main.py
│   ├── requirements.txt
│   └── .env
├── frontend-laravel/
│   ├── app/
│   ├── resources/
│   ├── routes/
│   └── public/
├── scripts/
│   ├── start-system.bat
│   └── stop-system.bat
├── docs/
│   ├── README.md
│   └── API_DOCUMENTATION.md
└── docker/
```

## Architecture

- `frontend-laravel`: Laravel web dashboard, routing, form validation, prediction history, and analytics UI.
- `backend-python`: FastAPI service that validates prediction payloads and executes the Random Forest + PSO model.
- `frontend-laravel/app/Services/PredictionApiService.php`: Laravel service for clean API communication.
- `frontend-laravel/app/Services/PredictionAnalyticsService.php`: Dashboard metrics and prediction history aggregation.
- `backend-python/app/ml/predictor.py`: ML model loading, feature mapping, prediction, risk interpretation, and feature importance.

## Run Locally

First-time setup:

```powershell
cd "D:\laragon\www\Klasifikasi Churn Pelanggan"
.\scripts\setup-system.bat
```

```powershell
cd "D:\laragon\www\Klasifikasi Churn Pelanggan"
.\scripts\start-system.bat
```

The root `start-system.bat` is kept as a wrapper, so this also works:

```powershell
.\start-system.bat
```

Open:

```text
http://127.0.0.1:8000
```

Stop:

```powershell
.\scripts\stop-system.bat
```

## Manual Run

FastAPI:

```powershell
cd backend-python
.\.venv\Scripts\python.exe -m uvicorn app.main:app --host 127.0.0.1 --port 5001 --env-file .env
```

Laravel:

```powershell
cd frontend-laravel
powershell -ExecutionPolicy Bypass -File .\serve-laravel.ps1
```

## Frontend Stack

- Laravel
- Tailwind CSS
- Vite
- Alpine.js
- Flowbite
- Lucide icons

## Backend Stack

- FastAPI
- Pydantic
- scikit-learn
- pandas
- joblib
- uvicorn
