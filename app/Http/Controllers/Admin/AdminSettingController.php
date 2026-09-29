<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSettingController extends Controller
{
    public function index()
    {
        $siteLogo = Setting::logoUrl();
        $siteLogoText = Setting::logoText();
        $siteLogoFilename = Setting::get('site_logo');
        $socialLinks = Setting::getSocialLinks();

        return view('admin.settings.index', compact('siteLogo', 'siteLogoText', 'siteLogoFilename', 'socialLinks'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_logo_text' => 'nullable|string|max:50',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'remove_logo_image' => 'nullable|boolean',
            'social_links' => 'nullable|array',
        ]);

        if ($request->boolean('remove_logo_image')) {
            Setting::set('site_logo', '');
        }

        if ($request->hasFile('site_logo')) {
            $file = $request->file('site_logo');
            $filename = 'logo_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            Setting::set('site_logo', $filename);
        }

        $logoText = $request->input('site_logo_text') ?? $request->input('logo_text');
        if ($logoText !== null) {
            Setting::set('site_logo_text', $logoText);
        }

        // Process Footer Social Media Links
        if ($request->has('social_links')) {
            $inputSocial = $request->input('social_links', []);
            $toStore = [];
            foreach (Setting::supportedSocialPlatforms() as $key => $meta) {
                $url = trim($inputSocial[$key]['url'] ?? '');
                $active = isset($inputSocial[$key]['active']) && ($inputSocial[$key]['active'] == '1' || $inputSocial[$key]['active'] === true);
                $toStore[$key] = [
                    'url' => $url,
                    'active' => $active && !empty($url),
                ];
            }
            Setting::set('footer_social_links', json_encode($toStore));
        }

        return redirect()->route('admin.settings.index')->with('success', 'Site branding, logo, and footer social links updated successfully.');
    }
}
