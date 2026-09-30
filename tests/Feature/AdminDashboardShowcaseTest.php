<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Project;
use App\Models\Product;
use App\Models\Author;
use App\Models\Country;
use App\Models\ProjectCategory;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminDashboardShowcaseTest extends TestCase
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
                'password' => bcrypt('secretadmin123'),
                'role' => 'super_admin',
            ]
        );
    }

    public function test_admin_dashboard_displays_platform_totals_and_showcase(): void
    {
        // Create sample pending project
        $pendingProject = Project::create([
            'title' => 'Sample Showcase Project ' . uniqid(),
            'slug' => 'sample-showcase-project-' . uniqid(),
            'category' => 'Residential',
            'country' => 'Indonesia',
            'city' => 'Bali',
            'excerpt' => 'Showcase excerpt for pending project test.',
            'content' => 'Full architectural content',
            'status' => 'pending',
            'featured_image' => 'https://example.com/project.jpg',
        ]);

        // Create sample pending product
        $pendingProduct = Product::create([
            'title' => 'Sample Showcase Product ' . uniqid(),
            'slug' => 'sample-showcase-product-' . uniqid(),
            'manufacturer' => 'SHOWCASE CORP',
            'category' => 'Lighting',
            'short_description' => 'Architectural lighting system test.',
            'status' => 'pending',
            'website_url' => 'https://showcase.example.com',
            'featured_image' => 'https://example.com/product.jpg',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // Verify Platform Totals Card Group
        $response->assertSee('Platform Totals');
        $response->assertSee('Total Projects');
        $response->assertSee('Total Products');
        $response->assertSee('Total Countries');
        $response->assertSee('Total Categories');
        $response->assertSee('Articles');

        // Verify Pending Projects Showcase
        $response->assertSee('Pending Projects Awaiting Approval');
        $response->assertSee('Check & Edit', false);

        // Verify Pending Products Showcase
        $response->assertSee('Pending Products Awaiting Catalog Check');
    }
}
