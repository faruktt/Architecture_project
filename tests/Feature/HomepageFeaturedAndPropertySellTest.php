<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class HomepageFeaturedAndPropertySellTest extends TestCase
{
    use DatabaseTransactions;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::firstOrCreate(
            ['email' => 'admin@nook.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password123'),
                'role' => 'super_admin',
            ]
        );
    }

    public function test_project_of_week_exclusive_selection_via_set_feature_route(): void
    {
        $project1 = Project::create([
            'title' => 'Project Alpha',
            'slug' => 'project-alpha',
            'category' => 'Residential',
            'country' => 'Indonesia',
            'excerpt' => 'Excerpt 1',
            'content' => 'Content 1',
            'status' => 'approved',
            'featured_image' => 'https://example.com/p1.jpg',
            'is_featured' => true,
        ]);

        $project2 = Project::create([
            'title' => 'Project Beta',
            'slug' => 'project-beta',
            'category' => 'Commercial',
            'country' => 'Malaysia',
            'excerpt' => 'Excerpt 2',
            'content' => 'Content 2',
            'status' => 'approved',
            'featured_image' => 'https://example.com/p2.jpg',
            'is_featured' => false,
        ]);

        $this->assertTrue($project1->fresh()->is_featured);
        $this->assertFalse($project2->fresh()->is_featured);

        // Admin selects project2 as Project of the Week
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.projects.set-feature', [$project2->id, 'is_featured']));

        $response->assertRedirect();

        // Project 2 is now featured, Project 1 is automatically unset
        $this->assertTrue($project2->fresh()->is_featured);
        $this->assertFalse($project1->fresh()->is_featured);
        $this->assertEquals(1, Project::where('is_featured', true)->count());
    }

    public function test_nook_spotlight_exclusive_selection(): void
    {
        $project1 = Project::create([
            'title' => 'Project Gamma',
            'slug' => 'project-gamma',
            'category' => 'Interior',
            'country' => 'Thailand',
            'excerpt' => 'Excerpt Gamma',
            'content' => 'Content Gamma',
            'status' => 'approved',
            'featured_image' => 'https://example.com/p3.jpg',
            'is_spotlight' => true,
        ]);

        $project2 = Project::create([
            'title' => 'Project Delta',
            'slug' => 'project-delta',
            'category' => 'Interior',
            'country' => 'Vietnam',
            'excerpt' => 'Excerpt Delta',
            'content' => 'Content Delta',
            'status' => 'approved',
            'featured_image' => 'https://example.com/p4.jpg',
            'is_spotlight' => false,
        ]);

        $this->assertTrue($project1->fresh()->is_spotlight);
        $this->assertFalse($project2->fresh()->is_spotlight);

        // Admin selects project2 as Nook Spotlight
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.projects.set-feature', [$project2->id, 'is_spotlight']));

        $response->assertRedirect();

        // Project 2 is now spotlight, Project 1 is automatically unset
        $this->assertTrue($project2->fresh()->is_spotlight);
        $this->assertFalse($project1->fresh()->is_spotlight);
        $this->assertEquals(1, Project::where('is_spotlight', true)->count());
    }

    public function test_main_hero_story_exclusive_selection(): void
    {
        $project1 = Project::create([
            'title' => 'Project Epsilon',
            'slug' => 'project-epsilon',
            'category' => 'Interior',
            'country' => 'Malaysia',
            'excerpt' => 'Excerpt Epsilon',
            'content' => 'Content Epsilon',
            'status' => 'approved',
            'featured_image' => 'https://example.com/p5.jpg',
            'is_hero_story' => true,
        ]);

        $project2 = Project::create([
            'title' => 'Project Zeta',
            'slug' => 'project-zeta',
            'category' => 'Residential',
            'country' => 'Indonesia',
            'excerpt' => 'Excerpt Zeta',
            'content' => 'Content Zeta',
            'status' => 'approved',
            'featured_image' => 'https://example.com/p6.jpg',
            'is_hero_story' => false,
        ]);

        $this->assertTrue($project1->fresh()->is_hero_story);
        $this->assertFalse($project2->fresh()->is_hero_story);

        // Admin selects project2 as Main Hero Story
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.projects.set-feature', [$project2->id, 'is_hero_story']));

        $response->assertRedirect();

        // Project 2 is now hero story, Project 1 is automatically unset
        $this->assertTrue($project2->fresh()->is_hero_story);
        $this->assertFalse($project1->fresh()->is_hero_story);
        $this->assertEquals(1, Project::where('is_hero_story', true)->count());
    }

    public function test_property_sell_toggle_on_products(): void
    {
        $product = Product::create([
            'title' => 'Luxury Basin Faucet',
            'slug' => 'luxury-basin-faucet',
            'manufacturer' => 'Zurn Elkay',
            'category' => 'Kitchen & Bath',
            'short_description' => 'Architectural basin faucet',
            'status' => 'approved',
            'is_property_sell' => false,
            'price' => '$350',
            'featured_image' => 'https://example.com/prod1.jpg',
        ]);

        $this->assertFalse($product->fresh()->is_property_sell);

        // Toggle on
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.products.toggle-property-sell', $product->id));

        $response->assertRedirect();
        $this->assertTrue($product->fresh()->is_property_sell);

        // Toggle off
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.products.toggle-property-sell', $product->id));

        $response->assertRedirect();
        $this->assertFalse($product->fresh()->is_property_sell);
    }

    public function test_homepage_renders_hero_story_and_product_property_sell(): void
    {
        $featured = Project::create([
            'title' => 'Exclusive Week Project',
            'slug' => 'exclusive-week-project',
            'category' => 'Residential',
            'country' => 'Malaysia',
            'excerpt' => 'Week excerpt',
            'content' => 'Week content',
            'status' => 'approved',
            'featured_image' => 'https://example.com/p-week.jpg',
            'is_featured' => true,
        ]);

        $spotlight = Project::create([
            'title' => 'Exclusive Spotlight Project',
            'slug' => 'exclusive-spotlight-project',
            'category' => 'Interior',
            'country' => 'Indonesia',
            'excerpt' => 'Spotlight excerpt',
            'content' => 'Spotlight content',
            'status' => 'approved',
            'featured_image' => 'https://example.com/p-spotlight.jpg',
            'is_spotlight' => true,
        ]);

        $hero = Project::create([
            'title' => 'Exclusive Hero Story Article',
            'slug' => 'exclusive-hero-story-article',
            'category' => 'Interior',
            'country' => 'Malaysia',
            'excerpt' => 'Hero story excerpt',
            'content' => 'Hero story content',
            'status' => 'approved',
            'featured_image' => 'https://example.com/p-hero.jpg',
            'is_hero_story' => true,
        ]);

        $productForSale = Product::create([
            'title' => 'Exclusive Architectural Luminaire',
            'slug' => 'exclusive-architectural-luminaire',
            'manufacturer' => 'Lumicraft',
            'category' => 'Lighting & Electrical',
            'short_description' => 'Architectural lighting fixture',
            'status' => 'approved',
            'is_property_sell' => true,
            'price' => '$799',
            'featured_image' => 'https://example.com/prod-sale.jpg',
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Exclusive Week Project');
        $response->assertSee('Exclusive Spotlight Project');
        $response->assertSee('Exclusive Hero Story Article');
        $response->assertSee('PROPERTY SELL POST');
        $response->assertSee('Exclusive Architectural Luminaire');
        $response->assertSee(route('products.show', $productForSale->slug));
    }
}
