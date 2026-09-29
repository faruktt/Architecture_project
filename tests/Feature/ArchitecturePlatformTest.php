<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Author;
use App\Models\Project;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArchitecturePlatformTest extends TestCase
{
    /**
     * Test Homepage loads with Image 1 elements
     */
    public function test_homepage_loads_matching_image_1(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('nook');
        $response->assertSee('MAGAZINE');
        $response->assertSee('Submit Your Project');
        $response->assertSee('PROPERTY SELL POST');
        $response->assertSee('Products Catalog');
        $response->assertSee('Malaysia');
        $response->assertSee('Indonesia');
    }

    /**
     * Test Products catalog page loads matching Image 2
     */
    public function test_products_catalog_loads_matching_image_2(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
        $response->assertSee('Architecture Products');
        $response->assertSee('Categories');
        $response->assertSee('Show Only BIM Files');
        $response->assertSee('GRAPHISOFT');
    }

    /**
     * Test Product details page loads matching Image 3 with website link and manufacturer
     */
    public function test_product_detail_page_loads_matching_image_3(): void
    {
        $product = Product::where('slug', 'bim-presentation-and-communication-bimx')->first();
        $this->assertNotNull($product);

        $response = $this->get('/products/' . $product->slug);
        $response->assertStatus(200);
        $response->assertSee('GRAPHISOFT');
        $response->assertSee('Contact Manufacturer');
        $response->assertSee('Website');
        $response->assertSee($product->website_url); // Website link added!
        $response->assertSee('SEND MESSAGE');
        $response->assertSee('This product page is available in Bangladesh');
    }

    /**
     * Test dual guards authentication separation
     */
    public function test_dual_guards_admin_and_author_separation(): void
    {
        // Unauthenticated access to protected routes
        $adminDashboard = $this->get('/admin/dashboard');
        $adminDashboard->assertRedirect('/admin/login');

        $authorDashboard = $this->get('/author/dashboard');
        $authorDashboard->assertRedirect('/author/login');
    }

    /**
     * Test Author project submission starts with status 'pending'
     */
    public function test_author_project_submission_workflow(): void
    {
        $author = Author::first();
        $this->assertNotNull($author);

        $response = $this->actingAs($author, 'author')->post('/author/projects', [
            'title' => 'Test Green Pavilion In Dhaka',
            'subtitle' => 'Biophilic sustainable bamboo structure',
            'category' => 'Architecture',
            'country' => 'Others',
            'city' => 'Dhaka',
            'excerpt' => 'Test excerpt for architectural publication.',
            'content' => 'Full architectural documentation and passive ventilation details.',
        ]);

        $project = Project::where('title', 'Test Green Pavilion In Dhaka')->first();
        $this->assertNotNull($project);
        $this->assertEquals('pending', $project->status); // Status is pending!
        $this->assertEquals($author->id, $project->author_id);
    }

    /**
     * Test Admin can review, edit all fields, and approve pending project
     */
    public function test_admin_can_edit_all_and_approve_project(): void
    {
        $admin = Admin::first();
        $this->assertNotNull($admin);

        $project = Project::create([
            'title' => 'Pending Villa Review ' . uniqid(),
            'slug' => 'pending-villa-review-' . uniqid(),
            'category' => 'Residential',
            'country' => 'Indonesia',
            'excerpt' => 'Awaiting admin review',
            'content' => 'Full architectural content',
            'status' => 'pending',
            'featured_image' => 'https://example.com/test.jpg',
        ]);

        // Admin updates all fields and marks status as approved
        $response = $this->actingAs($admin, 'admin')->put('/admin/projects/' . $project->id, [
            'title' => 'Pending Villa Review (Edited by Chief Editor)',
            'slug' => $project->slug,
            'subtitle' => 'Approved luxury beachfront property',
            'category' => 'Residential',
            'country' => 'Indonesia',
            'city' => 'Bali, Indonesia',
            'excerpt' => 'Updated lead summary by editor.',
            'content' => 'Updated narrative by editor.',
            'status' => 'approved', // Admin approves!
            'is_property_sell' => true,
        ]);
        $response->assertSessionHasNoErrors();

        $project->refresh();
        $this->assertEquals('approved', $project->status);
        $this->assertEquals('Pending Villa Review (Edited by Chief Editor)', $project->title);
    }

    /**
     * Test Author follow/unfollow system
     */
    public function test_author_follow_system(): void
    {
        $authors = Author::take(2)->get();
        if ($authors->count() >= 2) {
            $follower = $authors[0];
            $target = $authors[1];

            // Toggle follow
            $response = $this->actingAs($follower, 'author')->postJson('/architect/' . $target->id . '/follow');
            $response->assertStatus(200);
            $response->assertJsonStructure(['success', 'is_following', 'followers_count']);
        }
    }

    /**
     * Test Author product submission starts with status 'pending' and includes website link
     */
    public function test_author_product_submission_workflow(): void
    {
        $author = Author::first();
        $this->assertNotNull($author);

        $response = $this->actingAs($author, 'author')->post('/author/products', [
            'title' => 'Parametric Acoustic Ceiling Tiles ' . uniqid(),
            'manufacturer' => 'ACOUSTIC LABS',
            'category' => 'Finishes',
            'country_region' => 'Japan',
            'has_bim' => 1,
            'website_url' => 'https://acousticlabs.example.com',
            'phone' => '+81 3 1234 5678',
            'email' => 'contact@acousticlabs.example.com',
            'short_description' => 'Architectural acoustic absorption tiles with parametric 3D origami patterns.',
            'use_description' => 'Concert halls and contemporary open-plan offices.',
            'applications' => 'Ceiling suspension systems and feature wall accents.',
            'characteristics' => 'NRC 0.90 sound absorption, Class A fire rated.',
        ]);

        $response->assertSessionHasNoErrors();
        $product = Product::where('manufacturer', 'ACOUSTIC LABS')->latest()->first();
        $this->assertNotNull($product);
        $this->assertEquals('pending', $product->status); // Status is pending!
        $this->assertEquals('https://acousticlabs.example.com', $product->website_url); // Website link added!
    }

    /**
     * Test Admin can review, edit all fields, and approve product
     */
    public function test_admin_can_edit_and_approve_product(): void
    {
        $admin = Admin::first();
        $this->assertNotNull($admin);

        $product = Product::create([
            'title' => 'Pending Solar Facade ' . uniqid(),
            'slug' => 'pending-solar-facade-' . uniqid(),
            'manufacturer' => 'HELIOS GLASS',
            'category' => 'Facade & Exterior',
            'status' => 'pending',
            'short_description' => 'BIPV photovoltaic facade glass panels',
            'website_url' => 'https://helios.example.com',
        ]);

        $response = $this->actingAs($admin, 'admin')->put('/admin/products/' . $product->id, [
            'title' => 'Solar Facade Glass BIPV (Approved by Editor)',
            'slug' => $product->slug,
            'manufacturer' => 'HELIOS GLASS ARCHITECTURE',
            'category' => 'Facade & Exterior',
            'country_region' => 'Germany',
            'has_bim' => 1,
            'website_url' => 'https://helios-architecture.example.com',
            'short_description' => 'High-efficiency building integrated photovoltaic glass.',
            'status' => 'approved', // Admin approves!
        ]);

        $response->assertSessionHasNoErrors();
        $product->refresh();
        $this->assertEquals('approved', $product->status);
        $this->assertEquals('Solar Facade Glass BIPV (Approved by Editor)', $product->title);
    }

    /**
     * Test Admin Page Create feature
     */
    public function test_admin_page_create_feature(): void
    {
        $admin = Admin::first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin, 'admin')->post('/admin/pages', [
            'title' => 'Submission & Copyright Standards',
            'slug' => 'submission-copyright-' . uniqid(),
            'meta_description' => 'Guidelines on architectural copyright and image licensing.',
            'content' => '<h2>Copyright Policy</h2><p>All projects submitted remain the intellectual property of the author.</p>',
            'status' => 'published',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('pages', ['title' => 'Submission & Copyright Standards']);
    }

    /**
     * Test Product contact inquiry submission
     */
    public function test_product_inquiry_submission(): void
    {
        $product = Product::first();
        $this->assertNotNull($product);

        $response = $this->post('/products/' . $product->id . '/inquiry', [
            'name' => 'Ar. Kamrul Hasan',
            'email' => 'kamrul@habitat.com',
            'phone' => '+880 1812 345678',
            'company' => 'Habitat Atelier',
            'message' => 'Please provide BIM objects and regional distribution contacts for Bangladesh.',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('product_inquiries', [
            'product_id' => $product->id,
            'email' => 'kamrul@habitat.com',
            'status' => 'new',
        ]);
    }

    /**
     * Test Admin can change logo text and upload logo
     */
     public function test_admin_can_update_logo_and_brand_settings(): void
     {
         $admin = Admin::first();
         $this->assertNotNull($admin);

         $response = $this->actingAs($admin, 'admin')->post('/admin/settings', [
             'site_name' => 'nook ARCHITECTURE',
             'logo_text' => 'nook studio',
             'contact_email' => 'admin@nook.test',
         ]);

         $response->assertRedirect('/admin/settings');
         $this->assertEquals('nook studio', Setting::logoText());

         // Check that frontend reflects updated logo text
         $home = $this->get('/');
         $home->assertStatus(200);
         $home->assertSee('nook studio');

         // Reset logo text back to default
         Setting::set('site_logo_text', 'nook');
     }

    /**
     * Test global search returns matching projects and products
     */
    public function test_global_search_returns_projects_and_products(): void
    {
        $response = $this->get('/search?q=Residence');
        $response->assertStatus(200);
        $response->assertSee('Search Results');
    }

    /**
     * Test image uploads store filename only in database and file in public/uploads/
     */
    public function test_uploaded_image_saves_to_public_uploads_and_stores_filename_only(): void
    {
        $author = Author::first();
        $this->assertNotNull($author);

        $fakeFile = UploadedFile::fake()->image('test_project.jpg', 800, 600);

        $response = $this->actingAs($author, 'author')->post('/author/projects', [
            'title' => 'Test Filename Storage Project ' . uniqid(),
            'category' => 'Residential',
            'country' => 'Malaysia',
            'excerpt' => 'Testing that only the filename is stored in DB.',
            'content' => 'Full architectural content for file storage test.',
            'featured_image' => $fakeFile,
        ]);

        $response->assertRedirect('/author/projects');

        $latestProject = Project::latest('id')->first();
        $this->assertNotNull($latestProject);

        $rawFilename = $latestProject->getRawOriginal('featured_image');
        // Ensure only filename is stored (e.g., no 'uploads/' or 'http' in raw value)
        $this->assertStringNotContainsString('uploads/', $rawFilename);
        $this->assertStringNotContainsString('/', $rawFilename);
        $this->assertStringNotContainsString('\\', $rawFilename);
        $this->assertFileExists(public_path('uploads/' . $rawFilename));

        // Ensure accessor returns accessible URL
        $this->assertStringContainsString('uploads/' . $rawFilename, $latestProject->featured_image);

        // Clean up test file
        if (file_exists(public_path('uploads/' . $rawFilename))) {
            @unlink(public_path('uploads/' . $rawFilename));
        }
    }

    /**
     * Test footer renders partners and legal links matching ArchDaily image
     */
    public function test_footer_renders_partners_and_legal_links(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('OUR PARTNERS');
        $response->assertSee('HOLCIM');
        $response->assertSee('World Architecture');
        $response->assertSee('UIA');
        $response->assertSee('UN-HABITAT');
        $response->assertSee('OBEL AWARD');
        $response->assertSee('EUROPEAN CULTURAL CENTRE');
        $response->assertSee('Terms of Use');
        $response->assertSee('Privacy Policy');
        $response->assertSee('Cookie Policy');
    }

    /**
     * Test Admin can create a new project with either 'content' or 'description'
     */
    public function test_admin_can_create_new_project_successfully(): void
    {
        $admin = Admin::first();
        $this->assertNotNull($admin);

        // Test 1: Submitting with 'content'
        $title1 = 'Admin Modern Pavilion ' . uniqid();
        $response1 = $this->actingAs($admin, 'admin')->post('/admin/projects', [
            'title' => $title1,
            'category' => 'Residential',
            'country' => 'Malaysia',
            'status' => 'approved',
            'excerpt' => 'Lead summary for admin pavilion.',
            'content' => 'Full architectural content body written by administrator.',
        ]);
        $response1->assertRedirect('/admin/projects');
        $response1->assertSessionHasNoErrors();
        $this->assertDatabaseHas('projects', ['title' => $title1]);

        // Test 2: Submitting with 'description' (which previously caused "The content field is required")
        $title2 = 'Admin Cantilever House ' . uniqid();
        $response2 = $this->actingAs($admin, 'admin')->post('/admin/projects', [
            'title' => $title2,
            'category' => 'Architecture',
            'country' => 'Indonesia',
            'status' => 'approved',
            'excerpt' => 'Lead summary for cantilever house.',
            'description' => 'Full architectural story passed via description field.',
        ]);
        $response2->assertRedirect('/admin/projects');
        $response2->assertSessionHasNoErrors();
        $this->assertDatabaseHas('projects', ['title' => $title2]);
    }

    /**
     * Test Admin can create a new product with either field naming convention
     */
    public function test_admin_can_create_new_product_successfully(): void
    {
        $admin = Admin::first();
        $this->assertNotNull($admin);

        $productTitle = 'Acoustic Ceiling Grid ' . uniqid();
        $response = $this->actingAs($admin, 'admin')->post('/admin/products', [
            'title' => $productTitle,
            'manufacturer' => 'Armstrong Ceiling Systems',
            'category' => 'Finishes',
            'status' => 'approved',
            'excerpt' => 'High performance acoustic ceiling tile system.',
            'description' => 'Detailed technical specifications and acoustic absorption data.',
            'contact_phone' => '+1 800 555 1234',
            'contact_email' => 'tech@armstrong.com',
        ]);

        $response->assertRedirect('/admin/products');
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('products', ['title' => $productTitle]);
    }

    /**
     * Test Rich text editor image upload endpoint for inserting photos into narrative
     */
    public function test_editor_image_upload_endpoint(): void
    {
        $admin = Admin::first();
        $this->assertNotNull($admin);

        // Guest cannot upload
        $guestRes = $this->postJson('/editor/upload-image', []);
        $guestRes->assertStatus(401);

        // Authenticated admin can upload 4-5 photos directly
        $fakeImages = [
            UploadedFile::fake()->image('narrative_shot1.jpg', 800, 600),
            UploadedFile::fake()->image('narrative_shot2.jpg', 800, 600),
            UploadedFile::fake()->image('narrative_shot3.jpg', 800, 600),
            UploadedFile::fake()->image('narrative_shot4.jpg', 800, 600),
        ];

        $res = $this->actingAs($admin, 'admin')->postJson('/editor/upload-image', [
            'images' => $fakeImages,
        ]);

        $res->assertStatus(200);
        $res->assertJsonStructure([
            'success',
            'url',
            'urls',
            'filename',
            'filenames',
            'count'
        ]);

        $this->assertEquals(4, $res->json('count'));
        $this->assertCount(4, $res->json('urls'));

        // Verify files exist in public/uploads/
        foreach ($res->json('filenames') as $fn) {
            $this->assertFileExists(public_path('uploads/' . $fn));
            // cleanup
            @unlink(public_path('uploads/' . $fn));
        }
    }

    /**
     * Test Direct image and gallery multi-file uploads for projects and products
     */
    public function test_direct_image_and_multi_gallery_file_uploads(): void
    {
        $admin = Admin::first();
        $this->assertNotNull($admin);

        $featured = UploadedFile::fake()->image('cover_direct.jpg', 1200, 800);
        $gallery = [
            UploadedFile::fake()->image('gallery1.jpg', 1000, 700),
            UploadedFile::fake()->image('gallery2.jpg', 1000, 700),
            UploadedFile::fake()->image('gallery3.jpg', 1000, 700),
        ];

        $title = 'Direct Upload Villa ' . uniqid();
        $res = $this->actingAs($admin, 'admin')->post('/admin/projects', [
            'title' => $title,
            'category' => 'Residential',
            'country' => 'Japan',
            'status' => 'approved',
            'excerpt' => 'Project with direct cover and 3 gallery uploads.',
            'content' => '<p>First paragraph of narrative.</p><p><img src="/uploads/fake.jpg"></p><p>Second paragraph after photos.</p>',
            'featured_image' => $featured,
            'gallery' => $gallery,
        ]);

        $res->assertRedirect('/admin/projects');
        $res->assertSessionHasNoErrors();

        $project = Project::where('title', $title)->first();
        $this->assertNotNull($project);

        // Featured image should be saved in uploads
        $rawFeatured = $project->getRawOriginal('featured_image');
        $this->assertStringNotContainsString('http', $rawFeatured);
        $this->assertFileExists(public_path('uploads/' . $rawFeatured));
        @unlink(public_path('uploads/' . $rawFeatured));

        // Gallery should contain 3 uploaded images
        $rawGallery = $project->getRawOriginal('gallery');
        $galleryArray = is_string($rawGallery) ? json_decode($rawGallery, true) : $rawGallery;
        $this->assertCount(3, $galleryArray);

        foreach ($galleryArray as $fn) {
            $this->assertFileExists(public_path('uploads/' . $fn));
            @unlink(public_path('uploads/' . $fn));
        }
    }
}


