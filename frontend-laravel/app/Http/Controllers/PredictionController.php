<?php

namespace App\Http\Controllers;

use App\Models\PredictionHistory;
use App\Services\PredictionAnalyticsService;
use App\Services\PredictionApiService;
use Illuminate\Http\Request;
use RuntimeException;

class PredictionController extends Controller
{
    public function __construct(
        private readonly PredictionApiService $predictionApi,
        private readonly PredictionAnalyticsService $analytics,
    ) {
    }

    private const DEFAULT_FORM_VALUES = [
        'account_length' => '',
        'area_code' => '',
        'international_plan' => '0',
        'voice_mail_plan' => '0',
        'number_vmail_messages' => '',
        'total_day_minutes' => '',
        'total_day_calls' => '',
        'total_day_charge' => '',
        'total_eve_minutes' => '',
        'total_eve_calls' => '',
        'total_eve_charge' => '',
        'total_night_minutes' => '',
        'total_night_calls' => '',
        'total_night_charge' => '',
        'total_intl_minutes' => '',
        'total_intl_calls' => '',
        'total_intl_charge' => '',
        'customer_service_calls' => '',
    ];

    private const FORM_FIELDS = [
        ['name' => 'account_length', 'label' => 'Account Length', 'type' => 'number', 'step' => '1', 'group' => 'Profil'],
        ['name' => 'area_code', 'label' => 'Area Code', 'type' => 'number', 'step' => '1', 'group' => 'Profil'],
        ['name' => 'international_plan', 'label' => 'International Plan', 'type' => 'select', 'group' => 'Layanan'],
        ['name' => 'voice_mail_plan', 'label' => 'Voice Mail Plan', 'type' => 'select', 'group' => 'Layanan'],
        ['name' => 'number_vmail_messages', 'label' => 'Number Vmail Messages', 'type' => 'number', 'step' => '1', 'group' => 'Layanan'],
        ['name' => 'total_day_minutes', 'label' => 'Total Day Minutes', 'type' => 'number', 'step' => 'any', 'group' => 'Usage'],
        ['name' => 'total_day_calls', 'label' => 'Total Day Calls', 'type' => 'number', 'step' => '1', 'group' => 'Usage'],
        ['name' => 'total_day_charge', 'label' => 'Total Day Charge', 'type' => 'number', 'step' => 'any', 'group' => 'Usage'],
        ['name' => 'total_eve_minutes', 'label' => 'Total Eve Minutes', 'type' => 'number', 'step' => 'any', 'group' => 'Usage'],
        ['name' => 'total_eve_calls', 'label' => 'Total Eve Calls', 'type' => 'number', 'step' => '1', 'group' => 'Usage'],
        ['name' => 'total_eve_charge', 'label' => 'Total Eve Charge', 'type' => 'number', 'step' => 'any', 'group' => 'Usage'],
        ['name' => 'total_night_minutes', 'label' => 'Total Night Minutes', 'type' => 'number', 'step' => 'any', 'group' => 'Usage'],
        ['name' => 'total_night_calls', 'label' => 'Total Night Calls', 'type' => 'number', 'step' => '1', 'group' => 'Usage'],
        ['name' => 'total_night_charge', 'label' => 'Total Night Charge', 'type' => 'number', 'step' => 'any', 'group' => 'Usage'],
        ['name' => 'total_intl_minutes', 'label' => 'Total Intl Minutes', 'type' => 'number', 'step' => 'any', 'group' => 'International'],
        ['name' => 'total_intl_calls', 'label' => 'Total Intl Calls', 'type' => 'number', 'step' => '1', 'group' => 'International'],
        ['name' => 'total_intl_charge', 'label' => 'Total Intl Charge', 'type' => 'number', 'step' => 'any', 'group' => 'International'],
        ['name' => 'customer_service_calls', 'label' => 'Customer Service Calls', 'type' => 'number', 'step' => '1', 'group' => 'Support'],
    ];

    public function index(Request $request)
    {
        return $this->renderPage($request, 'dashboard');
    }

    public function predictionPage(Request $request)
    {
        return $this->renderPage($request, 'prediction');
    }

    public function historyPage(Request $request)
    {
        return $this->renderPage($request, 'history');
    }

    public function modelPage(Request $request)
    {
        return $this->renderPage($request, 'model');
    }

    public function aboutPage(Request $request)
    {
        return $this->renderPage($request, 'about');
    }

    private function renderPage(Request $request, string $activePage)
    {
        return view('pages.'.$activePage, [
            'activePage' => $activePage,
            'formValues' => array_merge(self::DEFAULT_FORM_VALUES, old()),
            'formFields' => self::FORM_FIELDS,
            'resultData' => session('result_data'),
            'errorMessage' => session('error_message'),
            'history' => $this->analytics->recentHistory(),
            'showHistory' => session('show_history', false),
            'dashboard' => $this->analytics->dashboardData(),
        ]);
    }

    public function predict(Request $request)
    {
        $validated = $request->validate([
            'account_length' => 'required|numeric|min:0',
            'area_code' => 'required|numeric|min:0',
            'international_plan' => 'required|in:0,1',
            'voice_mail_plan' => 'required|in:0,1',
            'number_vmail_messages' => 'required|numeric|min:0',
            'total_day_minutes' => 'required|numeric|min:0',
            'total_day_calls' => 'required|numeric|min:0',
            'total_day_charge' => 'required|numeric|min:0',
            'total_eve_minutes' => 'required|numeric|min:0',
            'total_eve_calls' => 'required|numeric|min:0',
            'total_eve_charge' => 'required|numeric|min:0',
            'total_night_minutes' => 'required|numeric|min:0',
            'total_night_calls' => 'required|numeric|min:0',
            'total_night_charge' => 'required|numeric|min:0',
            'total_intl_minutes' => 'required|numeric|min:0',
            'total_intl_calls' => 'required|numeric|min:0',
            'total_intl_charge' => 'required|numeric|min:0',
            'customer_service_calls' => 'required|numeric|min:0',
        ]);

        try {
            $resultData = $this->predictionApi->predict($validated);
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('prediction.page')
                ->withInput()
                ->with('error_message', $exception->getMessage());
        }

        PredictionHistory::create([
            ...$validated,
            'prediction_result' => $resultData['result'],
            'probability' => $resultData['probability'],
            'risk_level' => $resultData['risiko'],
        ]);

        session([
            'result_data' => $resultData,
            'show_history' => true,
        ]);

        return redirect()
            ->route('prediction.page')
            ->withInput($validated);
    }

    public function clearHistory()
    {
        PredictionHistory::query()->delete();
        session()->forget('show_history');

        return redirect()->route('prediction.history.page');
    }

    public function reset(Request $request)
    {
        session()->forget(['result_data', 'error_message', 'show_history']);
        $request->session()->flash('_old_input', []);

        return redirect()->route('prediction.page');
    }
}
