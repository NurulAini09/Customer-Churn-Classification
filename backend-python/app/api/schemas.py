from pydantic import BaseModel, Field


class PredictionRequest(BaseModel):
    account_length: float = Field(..., ge=0)
    area_code: float = Field(..., ge=0)
    international_plan: int = Field(..., ge=0, le=1)
    voice_mail_plan: int = Field(..., ge=0, le=1)
    number_vmail_messages: float = Field(..., ge=0)
    total_day_minutes: float = Field(..., ge=0)
    total_day_calls: float = Field(..., ge=0)
    total_day_charge: float = Field(..., ge=0)
    total_eve_minutes: float = Field(..., ge=0)
    total_eve_calls: float = Field(..., ge=0)
    total_eve_charge: float = Field(..., ge=0)
    total_night_minutes: float = Field(..., ge=0)
    total_night_calls: float = Field(..., ge=0)
    total_night_charge: float = Field(..., ge=0)
    total_intl_minutes: float = Field(..., ge=0)
    total_intl_calls: float = Field(..., ge=0)
    total_intl_charge: float = Field(..., ge=0)
    customer_service_calls: float = Field(..., ge=0)


class FactorResponse(BaseModel):
    feature_key: str
    label: str
    importance_percentage: float
    input_value: float | int | None


class PredictionData(BaseModel):
    result: str
    prediction: int
    probability: float
    risiko: str
    keterangan: str
    top_factors: list[FactorResponse]
    factor_note: str
    input: dict


class PredictionResponse(BaseModel):
    success: bool
    data: PredictionData


class ErrorResponse(BaseModel):
    success: bool = False
    message: str
