import os
from dataclasses import dataclass
from pathlib import Path


BASE_DIR = Path(__file__).resolve().parents[2]


@dataclass(frozen=True)
class Settings:
    app_name: str = os.getenv("APP_NAME", "Customer Churn Prediction API")
    app_env: str = os.getenv("APP_ENV", "local")
    cors_origins: str = os.getenv("CORS_ORIGINS", "http://127.0.0.1:8000,http://localhost:8000")
    model_path: str = os.getenv(
        "MODEL_PATH",
        str(BASE_DIR / "app" / "ml" / "models" / "model.pkl"),
    )

    @property
    def allowed_origins(self) -> list[str]:
        return [origin.strip() for origin in self.cors_origins.split(",") if origin.strip()]


settings = Settings()
