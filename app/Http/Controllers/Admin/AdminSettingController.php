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
        $siteTitle = Setting::siteTitle();
        $siteFavicon = Setting::faviconUrl();
        $siteFaviconFilename = Setting::get('site_favicon');
        $socialLinks = Setting::getSocialLinks();

        return view('admin.settings.index', compact(
            'siteLogo',
            'siteLogoText',
            'siteLogoFilename',
            'siteTitle',
            'siteFavicon',
            'siteFaviconFilename',
            'socialLinks'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_title' => 'nullable|string|max:150',
            'site_name' => 'nullable|string|max:150',
            'site_favicon' => 'nullable|file|mimes:ico,png,jpg,jpeg,svg,webp|max:2048',
            'remove_favicon' => 'nullable|boolean',
            'site_logo_text' => 'nullable|string|max:50',
            'logo_text' => 'nullable|string|max:50',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'remove_logo_image' => 'nullable|boolean',
            'social_links' => 'nullable|array',
        ]);

        // 1. Browser Title
        $siteTitle = $request->input('site_title') ?? $request->input('site_name');
        if ($siteTitle !== null) {
            Setting::set('site_title', trim($siteTitle));
        }

        // 2. Browser Favicon
        if ($request->boolean('remove_favicon')) {
            $oldFavicon = Setting::get('site_favicon');
            if ($oldFavicon && file_exists(public_path('uploads/' . basename($oldFavicon)))) {
                @unlink(public_path('uploads/' . basename($oldFavicon)));
            }
            Setting::set('site_favicon', '');
        }

        if ($request->hasFile('site_favicon')) {
            $file = $request->file('site_favicon');
            $oldFavicon = Setting::get('site_favicon');
            if ($oldFavicon && file_exists(public_path('uploads/' . basename($oldFavicon)))) {
                @unlink(public_path('uploads/' . basename($oldFavicon)));
            }
            $filename = 'favicon_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            Setting::set('site_favicon', $filename);
        }

        // 3. Brand Logo Image & Text
        if ($request->boolean('remove_logo_image')) {
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo && file_exists(public_path('uploads/' . basename($oldLogo)))) {
                @unlink(public_path('uploads/' . basename($oldLogo)));
            }
            Setting::set('site_logo', '');
        }

        if ($request->hasFile('site_logo')) {
            $file = $request->file('site_logo');
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo && file_exists(public_path('uploads/' . basename($oldLogo)))) {
                @unlink(public_path('uploads/' . basename($oldLogo)));
            }
            $filename = 'logo_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            Setting::set('site_logo', $filename);
        }

        $logoText = $request->input('site_logo_text') ?? $request->input('logo_text');
        if ($logoText !== null) {
            Setting::set('site_logo_text', $logoText);
        }

        // 4. Process Footer Social Media Links
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

        return redirect()->route('admin.settings.index')->with('success', 'Site browser title, favicon, branding, and footer settings updated successfully.');
    }
}
