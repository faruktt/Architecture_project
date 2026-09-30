<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminProfileAndHeaderTest extends TestCase
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

    public function test_guest_cannot_access_admin_profile_edit(): void
    {
        $response = $this->get(route('admin.profile.edit'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_profile_edit_screen(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Admin Profile Settings');
        $response->assertSee($this->admin->name);
        $response->assertSee($this->admin->email);
        $response->assertSee('Admin Avatar', false);
        $response->assertSee('Change Admin Password');
        $response->assertSee('Save Profile Changes');
    }

    public function test_admin_panel_renders_top_header_with_avatar_and_dropdown(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.dashboard'));

        $response->assertStatus(200);
        // Header elements
        $response->assertSee('Admin Control Room');
        $response->assertSee($this->admin->avatar);
        $response->assertSee('SUPER ADMIN');

        // Dropdown options
        $response->assertSee('Edit Profile');
        $response->assertSee(route('admin.profile.edit'));
        $response->assertSee('Settings');
        $response->assertSee(route('admin.settings.index'));
        $response->assertSee('Sign Out');
    }

    public function test_admin_can_update_name_and_email(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->put(route('admin.profile.update'), [
            'name' => 'Updated Chief Admin',
            'email' => 'chief.admin@arch.test',
        ]);

        $response->assertRedirect(route('admin.profile.edit'));
        $response->assertSessionHas('success');

        $this->admin->refresh();
        $this->assertEquals('Updated Chief Admin', $this->admin->name);
        $this->assertEquals('chief.admin@arch.test', $this->admin->email);
    }

    public function test_admin_can_update_password(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->put(route('admin.profile.update'), [
            'name' => $this->admin->name,
            'email' => $this->admin->email,
            'password' => 'newsecretpassword999',
            'password_confirmation' => 'newsecretpassword999',
        ]);

        $response->assertRedirect(route('admin.profile.edit'));
        $response->assertSessionHas('success');

        $this->admin->refresh();
        $this->assertTrue(Hash::check('newsecretpassword999', $this->admin->password));
    }

    public function test_admin_can_update_avatar_image(): void
    {
        $fakeImage = UploadedFile::fake()->image('test_admin_avatar.jpg', 300, 300);

        $response = $this->actingAs($this->admin, 'admin')->put(route('admin.profile.update'), [
            'name' => $this->admin->name,
            'email' => $this->admin->email,
            'avatar' => $fakeImage,
        ]);

        $response->assertRedirect(route('admin.profile.edit'));
        $response->assertSessionHas('success');

        $this->admin->refresh();
        $rawAvatar = $this->admin->getRawOriginal('avatar');
        $this->assertNotNull($rawAvatar);
        $this->assertStringStartsWith('admin_avatar_', $rawAvatar);

        // Check file exists in public uploads
        $this->assertFileExists(public_path('uploads/' . $rawAvatar));

        // Clean up created file
        if (file_exists(public_path('uploads/' . $rawAvatar))) {
            @unlink(public_path('uploads/' . $rawAvatar));
        }
    }

    public function test_admin_can_remove_custom_avatar(): void
    {
        // First set an avatar
        $this->admin->avatar = 'sample_avatar.jpg';
        $this->admin->save();

        $response = $this->actingAs($this->admin, 'admin')->put(route('admin.profile.update'), [
            'name' => $this->admin->name,
            'email' => $this->admin->email,
            'remove_avatar' => '1',
        ]);

        $response->assertRedirect(route('admin.profile.edit'));
        $response->assertSessionHas('success');

        $this->admin->refresh();
        $this->assertNull($this->admin->getRawOriginal('avatar'));
    }
}
