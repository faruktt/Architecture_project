<?php

namespace Tests\Feature;

use App\Models\Author;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthorHeaderBadgeTest extends TestCase
{
    use DatabaseTransactions;

    public function test_homepage_shows_author_avatar_name_and_company_when_logged_in(): void
    {
        $author = Author::create([
            'name' => 'MD FARUK HOSSAIN',
            'username' => 'mdfaruk',
            'email' => 'faruk@example.com',
            'password' => bcrypt('password'),
            'company' => 'Sarkarit',
            'avatar' => 'faruk_profile.jpg',
            'status' => 'active',
        ]);

        $response = $this->actingAs($author, 'author')->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('MD FARUK HOSSAIN');
        $response->assertSee('Sarkarit');
        $response->assertSee(route('author.dashboard'));
        $response->assertSee('faruk_profile.jpg');
    }

    public function test_homepage_shows_login_link_when_guest(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee(route('author.login'));
        $response->assertSee(route('author.register'));
    }
}
