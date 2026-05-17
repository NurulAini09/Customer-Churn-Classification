from app.ml.predictor import CustomerChurnPredictor


class PredictionService:
    def __init__(self) -> None:
        self.predictor = CustomerChurnPredictor()

    def health_check(self) -> dict:
        return {
            "status": "ok",
            "service": "customer-churn-prediction-service",
            "model_loaded": self.predictor.model_exists(),
            "required_fields": self.predictor.required_fields(),
        }

    def predict(self, payload: dict) -> dict:
        return self.predictor.predict(payload)
