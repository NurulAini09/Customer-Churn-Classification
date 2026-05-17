from fastapi import APIRouter, HTTPException

from app.api.schemas import ErrorResponse, PredictionRequest, PredictionResponse
from app.services.prediction_service import PredictionService

router = APIRouter()
prediction_service = PredictionService()


@router.get("/")
def home() -> dict:
    return {
        "status": "ok",
        "message": "Customer churn prediction service is running.",
        "health_endpoint": "/health",
        "predict_endpoint": "/predict",
    }


@router.get("/health")
def health() -> dict:
    return prediction_service.health_check()


@router.post(
    "/predict",
    response_model=PredictionResponse,
    responses={422: {"model": ErrorResponse}, 500: {"model": ErrorResponse}},
)
def predict(payload: PredictionRequest) -> dict:
    try:
        result = prediction_service.predict(payload.model_dump())
        return {"success": True, "data": result}
    except ValueError as exc:
        raise HTTPException(status_code=422, detail=str(exc)) from exc
    except Exception as exc:
        raise HTTPException(
            status_code=500,
            detail=f"Terjadi error saat memproses prediksi: {exc}",
        ) from exc
