<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
// Menyimpan pengaturan perusahaan
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;


class SettingController extends Controller
{
    // Menampilkan form pengaturan perusahaan
    public function index()
    {
        return view('pages.settings.index', [
            'companyName'     => Setting::where('key', 'company_name')->value('value') ?? '1112-Project',
            'appName'         => Setting::where('key', 'app_name')->value('value') ?? 'My App',
            'timezone'        => Setting::where('key', 'timezone')->value('value') ?? 'Asia/Jakarta',
            'country'         => Setting::where('key', 'country')->value('value') ?? 'Indonesia',
            'addressCompany'  => Setting::where('key', 'address_company')->value('value') ?? '',
            'slogan'          => Setting::where('key', 'slogan')->value('value') ?? '',
            'logo'            => Setting::where('key', 'logo')->value('value') ?? '',
            'favicon'         => Setting::where('key', 'favicon')->value('value') ?? '',

            // Tambahan logo custom
            'logoDarkSm'      => Setting::where('key', 'logo_dark_sm')->value('value') ?? '',
            'logoDarkLg'      => Setting::where('key', 'logo_dark_lg')->value('value') ?? '',
            'logoLightSm'     => Setting::where('key', 'logo_light_sm')->value('value') ?? '',
            'logoLightLg'     => Setting::where('key', 'logo_light_lg')->value('value') ?? '',
        ]);
    }



    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            // Validasi input dasar
            $request->validate([
                'company_name' => 'required|string|max:255',
                'app_name' => 'required|string|max:255',
                'timezone' => 'required|string',
                'country' => 'required|string',
                'address_company' => 'nullable|string|max:255',
                'slogan' => 'nullable|string|max:255',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
                'logo_dark_sm' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'logo_dark_lg' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'logo_light_sm' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'logo_light_lg' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Data input biasa
            $settings = [
                'company_name' => $request->input('company_name'),
                'app_name' => $request->input('app_name'),
                'timezone' => $request->input('timezone'),
                'country' => $request->input('country'),
                'address_company' => $request->input('address_company'),
                'slogan' => $request->input('slogan'),
            ];

            $logChanges = [];

            // Handle upload logo utama
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('setting', 'public');
                $settings['logo'] = $logoPath;
                $logChanges[] = "Logo diperbarui.";
            }

            // Handle favicon
            if ($request->hasFile('favicon')) {
                $faviconPath = $request->file('favicon')->store('setting', 'public');
                $settings['favicon'] = $faviconPath;
                $logChanges[] = "Favicon diperbarui.";
            }

            // Handle logo varian
            $logoVariants = ['logo_dark_sm', 'logo_dark_lg', 'logo_light_sm', 'logo_light_lg'];
            foreach ($logoVariants as $key) {
                if ($request->hasFile($key)) {
                    $path = $request->file($key)->store('setting', 'public');
                    $settings[$key] = $path;
                    $logChanges[] = ucfirst(str_replace('_', ' ', $key)) . " diperbarui.";
                }
            }

            // Simpan perubahan ke tabel settings
            foreach ($settings as $key => $value) {
                $existingValue = Setting::where('key', $key)->value('value');
                if ($existingValue !== $value) {
                    Setting::updateOrCreate(['key' => $key], ['value' => $value]);
                    Cache::forget("setting_{$key}");
                    if (!in_array("{$key} diperbarui.", $logChanges)) {
                        $logChanges[] = "Pengaturan '{$key}' diubah dari '{$existingValue}' ke '{$value}'.";
                    }
                }
            }

            // Simpan log jika ada perubahan
            if (!empty($logChanges)) {
                Log::create([
                    'user_id' => $user->id,
                    'action' => 'update_settings',
                    'category' => 'settings_update',
                    'description' => implode(" | ", $logChanges),
                    'user_agent' => $request->header('User-Agent'),
                    'external_id' => null,
                ]);
            }

            return redirect()->route('settings.index')->with('success', 'Pengaturan perusahaan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::create([
                'user_id' => $user->id ?? null,
                'action' => 'settings_update_failed',
                'category' => 'error',
                'description' => 'Gagal memperbarui pengaturan: ' . $e->getMessage(),
                'user_agent' => $request->header('User-Agent'),
                'external_id' => null,
            ]);

            return redirect()->route('settings.index')->with('error', 'Terjadi kesalahan saat memperbarui pengaturan.');
        }
    }


    public function uploadLogo(Request $request, $key)
    {
        $request->validate([
            'logo' => 'required|image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        $path = $request->file('logo')->store('setting', 'public');

        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $path]
        );

        return back()->with('success', 'Logo berhasil diperbarui.');
    }
}
