<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthorProfileEditTest extends TestCase
{
    use DatabaseTransactions;

    protected Author $author;

    protected function setUp(): void
    {
        parent::setUp();

        $this->author = Author::firstOrCreate(
            ['email' => 'sarah.jenkins@atelier.com'],
            [
                'name' => 'Ar. Sarah Jenkins',
                'username' => 'sarah-jenkins',
                'password' => Hash::make('password123'),
                'company' => 'Atelier Maritime',
                'title' => 'Principal Marine Architect',
                'country' => 'Malaysia',
                'status' => 'active',
                'avatar' => 'https://ui-avatars.com/api/?name=Sarah+Jenkins',
            ]
        );
    }

    public function test_guest_cannot_access_author_profile_edit_route(): void
    {
        $response = $this->get(route('author.profile.edit'));
        $response->assertRedirect(route('author.login'));
    }

    public function test_author_can_view_profile_edit_screen(): void
    {
        $response = $this->actingAs($this->author, 'author')->get(route('author.profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Edit Author Profile');
        $response->assertSee($this->author->name);
        $response->assertSee($this->author->email);
        $response->assertSee('New Password');
        $response->assertSee('Upload New Picture');
        $response->assertSee('Save Profile Changes');
    }

    public function test_author_can_update_profile_information(): void
    {
        $response = $this->actingAs($this->author, 'author')->put(route('author.profile.update'), [
            'name' => 'Ar. Sarah Jenkins Updated',
            'email' => 'sarah.jenkins@atelier.com',
            'company' => 'Maritime Architecture Studio',
            'title' => 'Lead Architectural Director',
            'country' => 'Malaysia',
            'phone' => '+60 3 2145 9999',
            'website' => 'https://maritimestudio.com',
            'bio' => 'Award-winning sustainable waterfront architecture practitioner.',
        ]);

        $response->assertRedirect(route('author.dashboard'));
        $response->assertSessionHas('success');

        $this->author->refresh();
        $this->assertEquals('Ar. Sarah Jenkins Updated', $this->author->name);
        $this->assertEquals('Maritime Architecture Studio', $this->author->company);
        $this->assertEquals('Lead Architectural Director', $this->author->title);
        $this->assertEquals('+60 3 2145 9999', $this->author->phone);
        $this->assertEquals('https://maritimestudio.com', $this->author->website);
        $this->assertEquals('Award-winning sustainable waterfront architecture practitioner.', $this->author->bio);
    }

    public function test_author_can_update_password(): void
    {
        $response = $this->actingAs($this->author, 'author')->put(route('author.profile.update'), [
            'name' => $this->author->name,
            'email' => $this->author->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('author.dashboard'));
        $response->assertSessionHas('success');

        $this->author->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->author->password));
    }

    public function test_author_can_update_avatar_image(): void
    {
        $fakeImage = UploadedFile::fake()->image('profile_avatar.jpg', 400, 400);

        $response = $this->actingAs($this->author, 'author')->put(route('author.profile.update'), [
            'name' => $this->author->name,
            'email' => $this->author->email,
            'avatar' => $fakeImage,
        ]);

        $response->assertRedirect(route('author.dashboard'));
        $response->assertSessionHas('success');

        $this->author->refresh();
        // Database contains the filename, accessor returns asset('uploads/' . basename(...))
        $rawAvatar = $this->author->getRawOriginal('avatar');
        $this->assertStringStartsWith('avatar_', $rawAvatar);

        // Clean up uploaded file
        $filePath = public_path('uploads/' . $rawAvatar);
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }

    public function test_author_layout_displays_admin_configured_logo(): void
    {
        Setting::set('site_logo_text', 'ARCHICRAFT');

        $response = $this->actingAs($this->author, 'author')->get(route('author.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('ARCHICRAFT');
        $response->assertSee('Author Studio');
        $response->assertSee(route('author.profile.edit'));
    }
}
