<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Get the formatted site logo URL or null if text logo is used.
     */
    public static function logoUrl(): ?string
    {
        $logo = static::get('site_logo');
        if (!$logo) {
            return null;
        }

        if (str_starts_with($logo, 'http://') || str_starts_with($logo, 'https://')) {
            return $logo;
        }

        return asset('uploads/' . basename($logo));
    }

    /**
     * Get the brand text logo name (default: 'nook').
     */
    public static function logoText(): string
    {
        return static::get('site_logo_text', 'nook');
    }

    /**
     * Get the configured browser site title.
     */
    public static function siteTitle(): string
    {
        return static::get('site_title') ?: (static::logoText() . ' MAGAZINE | Interior • Architecture • Lifestyle');
    }

    /**
     * Get the formatted site favicon URL or null if not set.
     */
    public static function faviconUrl(): ?string
    {
        $favicon = static::get('site_favicon');
        if (!$favicon) {
            return null;
        }

        if (str_starts_with($favicon, 'http://') || str_starts_with($favicon, 'https://')) {
            return $favicon;
        }

        return asset('uploads/' . basename($favicon));
    }

    /**
     * Supported social media platforms with their display names, placeholders, and SVGs.
     */
    public static function supportedSocialPlatforms(): array
    {
        return [
            'facebook' => [
                'name' => 'Facebook',
                'placeholder' => 'https://facebook.com/yourbrand',
                'default_url' => 'https://facebook.com',
                'svg' => '<path d="M9 8H6v4h3v12h5V12h3.642L18 8h-4V6.333C14 5.374 14.5 5 15.6 5H18V0h-3.808C10.595 0 9 1.583 9 4.615V8z"/>',
            ],
            'twitter' => [
                'name' => 'X (Twitter)',
                'placeholder' => 'https://x.com/yourbrand',
                'default_url' => 'https://x.com',
                'svg' => '<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>',
            ],
            'pinterest' => [
                'name' => 'Pinterest',
                'placeholder' => 'https://pinterest.com/yourbrand',
                'default_url' => 'https://pinterest.com',
                'svg' => '<path d="M12 0a12 12 0 0 0-4.37 23.18c-.06-.94-.12-2.39.02-3.42l1-4.24s-.25-.5-.25-1.24c0-1.16.67-2.03 1.51-2.03.71 0 1.05.53 1.05 1.17 0 .71-.46 1.78-.69 2.77-.2.83.42 1.5 1.24 1.5 1.48 0 2.62-1.56 2.62-3.82 0-2-1.44-3.4-3.49-3.4-2.38 0-3.77 1.78-3.77 3.63 0 .72.28 1.49.62 1.9.07.09.08.17.06.26l-.23.95c-.04.15-.13.18-.3.11-1.12-.52-1.82-2.15-1.82-3.46 0-2.82 2.05-5.41 5.91-5.41 3.1 0 5.51 2.21 5.51 5.17 0 3.09-1.94 5.57-4.64 5.57-.91 0-1.76-.47-2.05-1.03l-.56 2.13c-.2.78-.75 1.76-1.12 2.36A12 12 0 1 0 12 0z"/>',
            ],
            'instagram' => [
                'name' => 'Instagram',
                'placeholder' => 'https://instagram.com/yourbrand',
                'default_url' => 'https://instagram.com',
                'svg' => '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>',
            ],
            'youtube' => [
                'name' => 'YouTube',
                'placeholder' => 'https://youtube.com/@yourbrand',
                'default_url' => 'https://youtube.com',
                'svg' => '<path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>',
            ],
            'linkedin' => [
                'name' => 'LinkedIn',
                'placeholder' => 'https://linkedin.com/company/yourbrand',
                'default_url' => '',
                'svg' => '<path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>',
            ],
            'tiktok' => [
                'name' => 'TikTok',
                'placeholder' => 'https://tiktok.com/@yourbrand',
                'default_url' => '',
                'svg' => '<path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-1.01-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>',
            ],
        ];
    }

    /**
     * Get all social platform configurations for admin editing.
     */
    public static function getSocialLinks(): array
    {
        $platforms = static::supportedSocialPlatforms();
        $storedJson = static::get('footer_social_links');
        $stored = $storedJson ? json_decode($storedJson, true) : null;

        $results = [];
        foreach ($platforms as $key => $meta) {
            if ($stored !== null) {
                $url = $stored[$key]['url'] ?? '';
                $active = isset($stored[$key]['active']) ? (bool)$stored[$key]['active'] : false;
            } else {
                // Initial defaults
                $url = $meta['default_url'];
                $active = !empty($url);
            }

            $results[$key] = [
                'key' => $key,
                'name' => $meta['name'],
                'url' => $url,
                'active' => $active,
                'placeholder' => $meta['placeholder'],
                'svg' => $meta['svg'],
            ];
        }

        return $results;
    }

    /**
     * Get only active social platforms with valid URLs for frontend rendering.
     */
    public static function getActiveSocialLinks(): array
    {
        $all = static::getSocialLinks();
        $active = [];
        foreach ($all as $key => $item) {
            if (!empty($item['active']) && !empty($item['url'])) {
                $active[$key] = $item;
            }
        }
        return $active;
    }
}
