<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BrowserFaviconAndTitleSettingsTest extends TestCase
{
    use DatabaseTransactions;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::firstOrCreate(
            ['email' => 'admin@arch.test'],
            [
                'name' => 'Architecture Administrator',
                'password' => Hash::make('secretadmin123'),
                'role' => 'super_admin',
            ]
        );
    }

    public function test_settings_screen_renders_browser_title_and_favicon_controls(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.settings.index'));

        $response->assertStatus(200);
        $response->assertSee('Browser Title');
        $response->assertSee('Favicon Control');
        $response->assertSee('Browser Tab Title');
        $response->assertSee('name="site_title"', false);
        $response->assertSee('Upload Favicon Icon');
        $response->assertSee('name="site_favicon"', false);
        $response->assertSee('Live Browser Tab Preview');
    }

    public function test_admin_can_update_browser_site_title(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.settings.update'), [
            'site_title' => 'Custom Architecture Magazine | Global Showcase',
        ]);

        $response->assertRedirect(route('admin.settings.index'));
        $response->assertSessionHas('success');

        $this->assertEquals('Custom Architecture Magazine | Global Showcase', Setting::siteTitle());

        // Verify title renders on frontend
        $frontend = $this->get('/');
        $frontend->assertStatus(200);
        $frontend->assertSee('Custom Architecture Magazine | Global Showcase');

        // Verify title renders on admin dashboard
        $adminDashboard = $this->actingAs($this->admin, 'admin')->get(route('admin.dashboard'));
        $adminDashboard->assertStatus(200);
        $adminDashboard->assertSee('Admin Dashboard | Custom Architecture Magazine | Global Showcase');
    }

    public function test_admin_can_upload_and_render_favicon(): void
    {
        $fakeFavicon = UploadedFile::fake()->image('custom_favicon.png', 64, 64);

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.settings.update'), [
            'site_title' => 'Favicon Test Title',
            'site_favicon' => $fakeFavicon,
        ]);

        $response->assertRedirect(route('admin.settings.index'));
        $response->assertSessionHas('success');

        $faviconFilename = Setting::get('site_favicon');
        $this->assertNotNull($faviconFilename);
        $this->assertStringStartsWith('favicon_', $faviconFilename);
        $this->assertFileExists(public_path('uploads/' . $faviconFilename));

        $faviconUrl = Setting::faviconUrl();
        $this->assertNotNull($faviconUrl);
        $this->assertStringContainsString($faviconFilename, $faviconUrl);

        // Verify frontend page includes the favicon link
        $frontend = $this->get('/');
        $frontend->assertStatus(200);
        $frontend->assertSee('<link rel="icon"', false);
        $frontend->assertSee($faviconFilename);

        // Verify admin dashboard includes the favicon link
        $adminDash = $this->actingAs($this->admin, 'admin')->get(route('admin.dashboard'));
        $adminDash->assertStatus(200);
        $adminDash->assertSee('<link rel="icon"', false);
        $adminDash->assertSee($faviconFilename);

        // Clean up created file
        if (file_exists(public_path('uploads/' . $faviconFilename))) {
            @unlink(public_path('uploads/' . $faviconFilename));
        }
    }

    public function test_admin_can_remove_favicon(): void
    {
        Setting::set('site_favicon', 'existing_test_favicon.png');

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.settings.update'), [
            'remove_favicon' => '1',
        ]);

        $response->assertRedirect(route('admin.settings.index'));
        $response->assertSessionHas('success');

        $this->assertEmpty(Setting::get('site_favicon'));
        $this->assertNull(Setting::faviconUrl());
    }
}
