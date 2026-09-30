<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PartnerManagementTest extends TestCase
{
    protected function getAdmin()
    {
        return Admin::first() ?? Admin::create([
            'email' => 'admin@test.com',
            'name' => 'Super Admin',
            'password' => bcrypt('password123'),
        ]);
    }

    public function test_home_page_displays_partners_in_footer()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('OUR PARTNERS');
        $response->assertSee('HOLCIM FOUNDATION');
        $response->assertSee('https://www.holcimfoundation.org/');
    }

    public function test_home_page_renders_sliding_marquee_when_partners_exceed_six()
    {
        // Add 2 more partners so total is 8 (> 6)
        $p1 = Partner::create([
            'name' => 'Extra Partner One',
            'logo' => 'partner_holcim.svg',
            'url' => 'https://extra1.example.com',
            'order' => 7,
            'is_active' => true,
        ]);
        $p2 = Partner::create([
            'name' => 'Extra Partner Two',
            'logo' => 'partner_waf.svg',
            'url' => 'https://extra2.example.com',
            'order' => 8,
            'is_active' => true,
        ]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('partner-marquee-track');
        $response->assertSee('https://extra1.example.com');
        $response->assertSee('https://extra2.example.com');

        // Cleanup extras
        $p1->delete();
        $p2->delete();
    }

    public function test_admin_can_access_partners_index_page()
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.partners.index'));

        $response->assertStatus(200);
        $response->assertSee('Our Partners');
        $response->assertSee('Add New Partner');
        $response->assertSee('HOLCIM FOUNDATION');
    }

    public function test_admin_can_create_new_partner()
    {
        $admin = $this->getAdmin();
        $file = UploadedFile::fake()->image('test_partner_logo.png', 200, 50);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.partners.store'), [
            'name' => 'Brand New Architecture Partner',
            'url' => 'https://newpartner.org',
            'logo' => $file,
            'order' => 10,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.partners.index'));
        $this->assertDatabaseHas('partners', [
            'name' => 'Brand New Architecture Partner',
            'url' => 'https://newpartner.org',
        ]);

        // Cleanup
        Partner::where('name', 'Brand New Architecture Partner')->delete();
    }

    public function test_admin_can_toggle_partner_status()
    {
        $admin = $this->getAdmin();
        $partner = Partner::first();
        $initialStatus = $partner->is_active;

        $response = $this->actingAs($admin, 'admin')->post(route('admin.partners.toggle-status', $partner->id));

        $response->assertRedirect();
        $partner->refresh();
        $this->assertEquals(!$initialStatus, $partner->is_active);

        // Revert status
        $partner->is_active = $initialStatus;
        $partner->save();
    }

    public function test_admin_can_delete_partner()
    {
        $admin = $this->getAdmin();
        $partner = Partner::create([
            'name' => 'Temporary Partner to Delete',
            'logo' => 'partner_holcim.svg',
            'url' => 'https://temporary.org',
            'order' => 99,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin, 'admin')->delete(route('admin.partners.destroy', $partner->id));

        $response->assertRedirect(route('admin.partners.index'));
        $this->assertDatabaseMissing('partners', [
            'id' => $partner->id,
        ]);
    }
}
