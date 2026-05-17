from functools import cached_property
from pathlib import Path

import joblib
import pandas as pd

from app.utils.config import settings


FIELDS = [
    ("account_length", "account length", float),
    ("area_code", "area code", float),
    ("international_plan", "international plan", int),
    ("voice_mail_plan", "voice mail plan", int),
    ("number_vmail_messages", "number vmail messages", float),
    ("total_day_minutes", "total day minutes", float),
    ("total_day_calls", "total day calls", float),
    ("total_day_charge", "total day charge", float),
    ("total_eve_minutes", "total eve minutes", float),
    ("total_eve_calls", "total eve calls", float),
    ("total_eve_charge", "total eve charge", float),
    ("total_night_minutes", "total night minutes", float),
    ("total_night_calls", "total night calls", float),
    ("total_night_charge", "total night charge", float),
    ("total_intl_minutes", "total intl minutes", float),
    ("total_intl_calls", "total intl calls", float),
    ("total_intl_charge", "total intl charge", float),
    ("customer_service_calls", "customer service calls", float),
]

FEATURE_LABELS = {
    "account length": "Account Length",
    "area code": "Area Code",
    "international plan": "International Plan",
    "voice mail plan": "Voice Mail Plan",
    "number vmail messages": "Number Vmail Messages",
    "total day minutes": "Total Day Minutes",
    "total day calls": "Total Day Calls",
    "total day charge": "Total Day Charge",
    "total eve minutes": "Total Eve Minutes",
    "total eve calls": "Total Eve Calls",
    "total eve charge": "Total Eve Charge",
    "total night minutes": "Total Night Minutes",
    "total night calls": "Total Night Calls",
    "total night charge": "Total Night Charge",
    "total intl minutes": "Total Intl Minutes",
    "total intl calls": "Total Intl Calls",
    "total intl charge": "Total Intl Charge",
    "customer service calls": "Customer Service Calls",
}


class CustomerChurnPredictor:
    def __init__(self, model_path: str | None = None) -> None:
        self.model_path = Path(model_path or settings.model_path).resolve()

    def model_exists(self) -> bool:
        return self.model_path.exists()

    def required_fields(self) -> list[str]:
        return [field[0] for field in FIELDS]

    @cached_property
    def model(self):
        if not self.model_path.exists():
            raise FileNotFoundError(f"File model tidak ditemukan: {self.model_path}")

        return joblib.load(self.model_path)

    def predict(self, payload: dict) -> dict:
        user_input = self._build_user_input(payload)
        user_df = pd.DataFrame([user_input])

        prediction = self.model.predict(user_df)[0]
        probability = float(self.model.predict_proba(user_df)[0][1])
        result = "Churn" if prediction == 1 else "Tidak Churn"
        interpretation = self._interpret_probability(probability)

        return {
            "result": result,
            "prediction": int(prediction),
            "probability": round(probability * 100, 1),
            "risiko": interpretation["risiko"],
            "keterangan": interpretation["keterangan"],
            "top_factors": self._build_feature_factors(user_input),
            "factor_note": "Faktor berikut menunjukkan variabel yang paling berpengaruh menurut feature importance model.",
            "input": payload,
        }

    def _build_user_input(self, payload: dict) -> dict:
        user_input = {}

        for request_key, model_key, caster in FIELDS:
            if request_key not in payload:
                raise ValueError(f"Field '{request_key}' wajib diisi.")

            value = caster(payload[request_key])

            if value < 0:
                raise ValueError("Semua input angka harus bernilai positif atau minimal 0.")

            user_input[model_key] = value

        return user_input

    def _interpret_probability(self, probability: float) -> dict:
        if probability >= 0.75:
            return {
                "risiko": "Tinggi",
                "keterangan": "Pelanggan sangat berpotensi churn dan perlu perhatian segera.",
            }

        if probability >= 0.5:
            return {
                "risiko": "Sedang",
                "keterangan": "Pelanggan memiliki potensi churn dan perlu dipantau.",
            }

        return {
            "risiko": "Rendah",
            "keterangan": "Pelanggan cenderung bertahan berdasarkan data saat ini.",
        }

    def _build_feature_factors(self, user_input: dict, limit: int = 5) -> list[dict]:
        if not hasattr(self.model, "feature_importances_"):
            return []

        feature_names = list(getattr(self.model, "feature_names_in_", [field[1] for field in FIELDS]))
        feature_importances = list(self.model.feature_importances_)
        ranked_features = sorted(
            zip(feature_names, feature_importances),
            key=lambda item: item[1],
            reverse=True,
        )[:limit]

        return [
            {
                "feature_key": feature_name,
                "label": FEATURE_LABELS.get(feature_name, feature_name.title()),
                "importance_percentage": round(float(importance) * 100, 2),
                "input_value": user_input.get(feature_name),
            }
            for feature_name, importance in ranked_features
        ]
