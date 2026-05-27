<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\Seo;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:120'],
            'site_email' => ['required', 'email', 'max:190'],
            'site_phone' => ['nullable', 'string', 'max:40'],
            'site_address' => ['nullable', 'string', 'max:300'],
            'admin_email' => ['required', 'email', 'max:190'],
            'footer_text' => ['nullable', 'string', 'max:500'],
            'tax_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'shipping_charge' => ['required', 'numeric', 'min:0'],
            'free_shipping_minimum' => ['required', 'numeric', 'min:0'],
            'facebook_url' => ['nullable', 'url', 'max:300'],
            'instagram_url' => ['nullable', 'url', 'max:300'],
            'x_url' => ['nullable', 'url', 'max:300'],
            'smtp_host' => ['nullable', 'string', 'max:190'],
            'smtp_port' => ['nullable', 'string', 'max:20'],
            'smtp_username' => ['nullable', 'string', 'max:190'],
            'smtp_encryption' => ['nullable', 'string', 'max:20'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'favicon' => ['nullable', 'image', 'max:1024'],
        ] + $this->seoRules());

        if ($request->hasFile('logo')) {
            $data['logo'] = Seo::storeImage($request->file('logo'), 'settings');
        }

        if ($request->hasFile('favicon')) {
            $data['favicon'] = Seo::storeImage($request->file('favicon'), 'settings');
        }

        foreach ($data as $key => $value) {
            if ($value !== null) {
                Setting::putValue($key, $value, in_array($key, ['logo', 'favicon'], true) ? 'image' : 'text');
            }
        }

        return back()->with('success', 'Website settings updated.');
    }

    private function seoRules(): array
    {
        $rules = [];

        foreach (array_keys(Setting::seoFields()) as $key) {
            $rules[$key.'_title'] = ['nullable', 'string', 'max:190'];
            $rules[$key.'_description'] = ['nullable', 'string', 'max:500'];
            $rules[$key.'_keywords'] = ['nullable', 'string', 'max:500'];
        }

        return $rules;
    }
}
