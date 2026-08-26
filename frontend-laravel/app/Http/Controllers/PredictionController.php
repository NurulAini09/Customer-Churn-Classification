<?php

namespace App\Http\Controllers;

use App\Models\PredictionHistory;
use App\Models\User;
use App\Services\PredictionAnalyticsService;
use App\Services\PredictionApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

    public function landingPage(Request $request)
    {
        if ($request->user()) {
            return redirect()->route('prediction.index');
        }

        return view('pages.landing');
    }

    public function predictionPage(Request $request)
    {
        return $this->renderPage($request, 'prediction');
    }

    public function historyPage(Request $request)
    {
        return $this->renderPage($request, 'history');
    }

    public function historyJson(Request $request)
    {
        return response()->json([
            'history' => $this->analytics->recentHistory(),
        ]);
    }

    public function modelPage(Request $request)
    {
        return $this->renderPage($request, 'model');
    }

    public function aboutPage(Request $request)
    {
        return $this->renderPage($request, 'about');
    }

    public function profilePage(Request $request)
    {
        return $this->renderPage($request, 'profile');
    }

    public function usersPage(Request $request)
    {
        return $this->renderPage($request, 'users');
    }

    public function loginPage()
    {
        $this->ensureDefaultAdminUser();

        if (Auth::check()) {
            return redirect()->route('prediction.index');
        }

        return view('pages.login');
    }

    public function login(Request $request)
    {
        $this->ensureDefaultAdminUser();

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (!Auth::attempt($validated)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password tidak sesuai.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('prediction.index'));
    }

    public function registerPage()
    {
        $this->ensureDefaultAdminUser();

        if (Auth::check()) {
            return redirect()->route('prediction.index');
        }

        return view('pages.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:80',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::query()->create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'password' => $validated['password'],
        ]);

        return redirect()->route('login')->with('success_message', 'Akun berhasil dibuat. Silakan login terlebih dahulu.');
    }

    public function resetPasswordPage()
    {
        $this->ensureDefaultAdminUser();

        return view('pages.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::query()->where('email', strtolower($validated['email']))->firstOrFail();
        $user->forceFill(['password' => $validated['password']])->save();

        return redirect()->route('login')->with('success_message', 'Password berhasil direset. Silakan login kembali.');
    }

    public function updateProfilePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|min:6',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!$user || !Hash::check($validated['current_password'], $user->password)) {
            return redirect()->route('profile.page')->withErrors([
                'current_password' => 'Password saat ini tidak sesuai.',
            ]);
        }

        $user->forceFill(['password' => $validated['password']])->save();

        return redirect()->route('profile.page')->with('success_message', 'Password profil berhasil diperbarui.');
    }

    public function updateProfilePhoto(Request $request)
    {
        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar yang diizinkan hanya JPG, JPEG, PNG, dan WEBP.',
            'photo.max' => 'Ukuran foto maksimal adalah 2MB.',
        ]);

        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        $directory = public_path('profile-photos');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $this->deleteExistingProfilePhoto($user->id);

        $photo = $validated['photo'];
        $rawExt = strtolower($photo->getClientOriginalExtension());
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = in_array($rawExt, $allowed, true) ? $rawExt : 'jpg';
        
        $photo->move($directory, sprintf('user-%d.%s', $user->id, $extension));

        return redirect()->route('profile.page')->with('success_message', 'Foto profil berhasil diperbarui.');
    }

    public function deleteProfilePhoto(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $this->deleteExistingProfilePhoto($user->id);
        }

        return redirect()->route('profile.page')->with('success_message', 'Foto profil berhasil dihapus.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success_message', 'Anda berhasil logout.');
    }

    private function renderPage(Request $request, string $activePage)
    {
        $currentUser = $this->currentUserData($request->user());
        $users = User::query()
            ->orderBy('name')
            ->get()
            ->map(fn (User $user) => $this->userSummary($user, $currentUser['email']))
            ->all();

        return view('pages.'.$activePage, [
            'activePage' => $activePage,
            'currentUser' => $currentUser,
            'users' => $users,
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

        $allZeroInput = collect($validated)->every(function ($value) {
            return (float) $value === 0.0;
        });

        if ($allZeroInput) {
            return redirect()
                ->route('prediction.page')
                ->withInput()
                ->withErrors(['input' => 'Data input tidak valid karena seluruh nilai tidak dapat bernilai 0.']);
        }

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

    public function deleteHistory(PredictionHistory $history)
    {
        $history->delete();

        return redirect()
            ->route('prediction.history.page')
            ->with('success_message', 'Riwayat klasifikasi berhasil dihapus.');
    }

    public function reset(Request $request)
    {
        session()->forget(['result_data', 'error_message', 'show_history']);
        $request->session()->flash('_old_input', []);

        return redirect()->route('prediction.page');
    }

    private function ensureDefaultAdminUser(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => 'admin12345678',
            ],
        );
    }

    private function currentUserData(?User $user): array
    {
        if (!$user) {
            return [
                'name' => 'Guest User',
                'email' => 'guest@churnpredict.local',
                'role' => 'Viewer',
                'photo_url' => null,
            ];
        }

        return [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $this->roleForUser($user),
            'photo_url' => $this->profilePhotoUrl($user->id),
        ];
    }

    private function userSummary(User $user, string $currentEmail): array
    {
        return [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $this->roleForUser($user),
            'status' => $user->email === $currentEmail ? 'Login saat ini' : 'Aktif',
        ];
    }

    private function roleForUser(User $user): string
    {
        return $user->email === 'admin@gmail.com' ? 'Administrator' : 'Pengguna';
    }

    private function profilePhotoUrl(int $userId): ?string
    {
        $matches = glob(public_path(sprintf('profile-photos/user-%d.*', $userId))) ?: [];

        if ($matches === []) {
            return null;
        }

        return asset('profile-photos/'.basename($matches[0]));
    }

    private function deleteExistingProfilePhoto(int $userId): void
    {
        $matches = glob(public_path(sprintf('profile-photos/user-%d.*', $userId))) ?: [];

        foreach ($matches as $filePath) {
            if (is_file($filePath)) {
                @unlink($filePath);
            }
        }
    }
}
