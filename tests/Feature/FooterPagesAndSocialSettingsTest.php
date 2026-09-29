<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Page;
use App\Models\Setting;

class FooterPagesAndSocialSettingsTest extends TestCase
{
    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::first();
        $this->assertNotNull($this->admin);
    }

    /**
     * Test pages created by admin appear in footer, and draft/deleted pages do not.
     */
    public function test_admin_created_pages_appear_in_footer_and_render_content(): void
    {
        $uniqueTitle = 'Special Curation FAQ ' . rand(100, 999);
        $uniqueSlug = 'curation-faq-' . rand(100, 999);
        $uniqueContent = '<p>Exclusive curation questions answered here by chief curator.</p>';

        // 1. Admin creates a new page
        $createResponse = $this->actingAs($this->admin, 'admin')->post('/admin/pages', [
            'title' => $uniqueTitle,
            'slug' => $uniqueSlug,
            'meta_description' => 'FAQ regarding project submissions',
            'content' => $uniqueContent,
            'status' => 'published',
            'order' => 10,
        ]);
        $createResponse->assertRedirect('/admin/pages');

        // 2. Check that the page title appears on the Homepage footer
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee($uniqueTitle);
        $homeResponse->assertSee('/page/' . $uniqueSlug);

        // 3. Click the page link and verify title and content render
        $pageResponse = $this->get('/page/' . $uniqueSlug);
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee($uniqueTitle);
        $pageResponse->assertSee('Exclusive curation questions answered here');

        // 4. Admin edits the page to draft status
        $page = Page::where('slug', $uniqueSlug)->first();
        $this->assertNotNull($page);

        $updateResponse = $this->actingAs($this->admin, 'admin')->put('/admin/pages/' . $page->id, [
            'title' => $uniqueTitle,
            'slug' => $uniqueSlug,
            'content' => $uniqueContent,
            'status' => 'draft',
            'order' => 10,
        ]);
        $updateResponse->assertRedirect('/admin/pages');

        // 5. Check that draft page does NOT appear in footer
        $homeResponseAfterDraft = $this->get('/');
        $homeResponseAfterDraft->assertStatus(200);
        $homeResponseAfterDraft->assertDontSee('/page/' . $uniqueSlug);

        // 6. Clean up
        $page->delete();
    }

    /**
     * Test admin can manage social links and active ones appear with icons on footer
     */
    public function test_admin_can_manage_footer_social_icons(): void
    {
        $testLinkedinUrl = 'https://linkedin.com/company/nook-magazine-test';
        $testFacebookUrl = 'https://facebook.com/nook-official-test';

        // 1. Admin updates social links: activates LinkedIn & Facebook, deactivates Pinterest
        $response = $this->actingAs($this->admin, 'admin')->post('/admin/settings', [
            'social_links' => [
                'facebook' => [
                    'url' => $testFacebookUrl,
                    'active' => '1',
                ],
                'twitter' => [
                    'url' => 'https://x.com/nookmag',
                    'active' => '1',
                ],
                'pinterest' => [
                    'url' => 'https://pinterest.com/nook',
                    'active' => '0', // Deactivated!
                ],
                'instagram' => [
                    'url' => 'https://instagram.com/nookmag',
                    'active' => '1',
                ],
                'youtube' => [
                    'url' => 'https://youtube.com/@nook',
                    'active' => '1',
                ],
                'linkedin' => [
                    'url' => $testLinkedinUrl,
                    'active' => '1', // Newly activated!
                ],
                'tiktok' => [
                    'url' => '',
                    'active' => '0',
                ],
            ],
        ]);

        $response->assertRedirect('/admin/settings');

        // 2. Homepage footer renders active links
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);

        // LinkedIn is active and should be present with its URL
        $homeResponse->assertSee($testLinkedinUrl);
        $homeResponse->assertSee($testFacebookUrl);

        // Pinterest was deactivated, so its link should not be rendered
        $homeResponse->assertDontSee('https://pinterest.com/nook');
    }
}
