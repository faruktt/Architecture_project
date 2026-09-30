<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Admin;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProjectSpecificationFieldsTest extends TestCase
{
    protected function getAuthor()
    {
        return Author::first() ?? Author::create([
            'email' => 'architect@test.com',
            'username' => 'architect_test',
            'name' => 'Ar. Testing User',
            'password' => bcrypt('password123'),
            'status' => 'active',
        ]);
    }

    protected function getAdmin()
    {
        return Admin::first() ?? Admin::create([
            'email' => 'admin@test.com',
            'name' => 'Super Admin',
            'password' => bcrypt('password123'),
        ]);
    }

    public function test_author_can_submit_project_with_all_specification_fields()
    {
        $author = $this->getAuthor();

        $imageFile = UploadedFile::fake()->image('villa_main.jpg', 1200, 800);

        $payload = [
            'title' => 'The Rainforest Pavilion ' . uniqid(),
            'subtitle' => 'Parametric Timber & Tropical Ventilation',
            'lead_architects' => 'Ar. Tariq Ahmed, Atelier Studio',
            'associate' => 'Associate Partner Studio',
            'area' => '650 m²',
            'build_year' => '2024',
            'photographer' => 'Iwan Baan Architecture Photography',
            'category' => 'Residential',
            'illustrations' => 'Illustrations by Studio Design',
            'city' => 'Kuala Lumpur',
            'country' => 'Malaysia',
            'phone_number' => '+60 12-345 6789',
            'web_address' => 'https://www.architecturedemo.com',
            'excerpt' => 'A tropical sustainable pavilion designed for passive cooling and rain harvest.',
            'content' => '<h2>Concept and Materiality</h2><p>Constructed utilizing recycled mass timber and locally quarried volcanic stone.</p>',
            'featured_image' => $imageFile,
        ];

        $response = $this->actingAs($author, 'author')->post(route('author.projects.store'), $payload);

        $response->assertRedirect(route('author.projects.index'));

        $this->assertDatabaseHas('projects', [
            'title' => $payload['title'],
            'lead_architects' => 'Ar. Tariq Ahmed, Atelier Studio',
            'associate' => 'Associate Partner Studio',
            'area' => '650 m²',
            'build_year' => '2024',
            'photographer' => 'Iwan Baan Architecture Photography',
            'illustrations' => 'Illustrations by Studio Design',
            'phone_number' => '+60 12-345 6789',
            'web_address' => 'https://www.architecturedemo.com',
            'status' => 'pending',
        ]);

        $project = Project::where('title', $payload['title'])->first();
        $this->assertNotNull($project);

        // Verify that the public project page shows these specifications
        $showResponse = $this->get(route('projects.show', $project->slug));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Project Specifications');
        $showResponse->assertSee('Ar. Tariq Ahmed, Atelier Studio');
        $showResponse->assertSee('Associate Partner Studio');
        $showResponse->assertSee('650 m²');
        $showResponse->assertSee('2024');
        $showResponse->assertSee('Iwan Baan Architecture Photography');
        $showResponse->assertSee('Illustrations by Studio Design');
        $showResponse->assertSee('+60 12-345 6789');
        $showResponse->assertSee('https://www.architecturedemo.com');

        // Clean up
        $project->delete();
    }

    public function test_submit_project_create_screen_displays_all_inputs()
    {
        $author = $this->getAuthor();

        $response = $this->actingAs($author, 'author')->get(route('author.projects.create'));

        $response->assertStatus(200);
        $response->assertSee('Lead Architects');
        $response->assertSee('Associate');
        $response->assertSee('Area');
        $response->assertSee('000 m²');
        $response->assertSee('Build Year');
        $response->assertSee('Photographer');
        $response->assertSee('Category');
        $response->assertSee('Illustrations');
        $response->assertSee('City');
        $response->assertSee('Country');
        $response->assertSee('Phone Number');
        $response->assertSee('Web address');
    }
}
