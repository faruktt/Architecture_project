<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Author;
use App\Models\ProjectCategory;
use App\Models\Country;
use App\Models\ProductCategory;

class CategoryAndCountryManagementTest extends TestCase
{
    /**
     * Test Admin can manage Project Categories and they populate project dropdowns
     */
    public function test_admin_can_manage_project_categories(): void
    {
        $admin = Admin::first();
        $this->assertNotNull($admin);

        $author = Author::first();
        $this->assertNotNull($author);

        $uniqueName = 'Futuristic Megastructure ' . rand(100, 999);

        // 1. Admin accesses index
        $indexResponse = $this->actingAs($admin, 'admin')->get('/admin/project-categories');
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Project Categories');

        // 2. Admin creates category
        $createResponse = $this->actingAs($admin, 'admin')->post('/admin/project-categories', [
            'name' => $uniqueName,
            'description' => 'Extreme engineering marvels',
            'order' => 1,
            'is_active' => 1,
        ]);
        $createResponse->assertRedirect(route('admin.project-categories.index'));
        $this->assertDatabaseHas('project_categories', ['name' => $uniqueName]);

        // 3. Admin Project create form displays this category
        $adminProjectCreateResponse = $this->actingAs($admin, 'admin')->get('/admin/projects/create');
        $adminProjectCreateResponse->assertStatus(200);
        $adminProjectCreateResponse->assertSee($uniqueName);

        // 4. Author Project create form displays this category
        $authorProjectCreateResponse = $this->actingAs($author, 'author')->get('/author/projects/create');
        $authorProjectCreateResponse->assertStatus(200);
        $authorProjectCreateResponse->assertSee($uniqueName);

        // Clean up
        ProjectCategory::where('name', $uniqueName)->delete();
    }

    /**
     * Test Admin can manage Countries, they appear in dropdowns and on Homepage
     */
    public function test_admin_can_manage_countries_and_they_appear_on_homepage(): void
    {
        $admin = Admin::first();
        $this->assertNotNull($admin);

        $uniqueCountry = 'Greenland ' . rand(100, 999);
        $code = 'GL';

        // 1. Admin accesses countries page
        $indexResponse = $this->actingAs($admin, 'admin')->get('/admin/countries');
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Countries Management');

        // 2. Admin creates country
        $createResponse = $this->actingAs($admin, 'admin')->post('/admin/countries', [
            'name' => $uniqueCountry,
            'code' => $code,
            'order' => 99,
            'is_active' => 1,
        ]);
        $createResponse->assertRedirect(route('admin.countries.index'));
        $this->assertDatabaseHas('countries', ['name' => $uniqueCountry]);

        // 3. Appears in Admin project create dropdown
        $adminProjectCreateResponse = $this->actingAs($admin, 'admin')->get('/admin/projects/create');
        $adminProjectCreateResponse->assertStatus(200);
        $adminProjectCreateResponse->assertSee($uniqueCountry);

        // 4. Appears on the Homepage
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee($uniqueCountry);

        // 5. Homepage can filter by this country
        $filterResponse = $this->get('/?country=' . urlencode($uniqueCountry));
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee($uniqueCountry);

        // Clean up
        Country::where('name', $uniqueCountry)->delete();
    }

    /**
     * Test Admin can manage Product Categories and they populate product dropdowns
     */
    public function test_admin_can_manage_product_categories(): void
    {
        $admin = Admin::first();
        $this->assertNotNull($admin);

        $author = Author::first();
        $this->assertNotNull($author);

        $uniqueCat = 'Smart Solar Glazing ' . rand(100, 999);

        // 1. Admin accesses product categories index
        $indexResponse = $this->actingAs($admin, 'admin')->get('/admin/product-categories');
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Product Categories');

        // 2. Admin creates product category
        $createResponse = $this->actingAs($admin, 'admin')->post('/admin/product-categories', [
            'name' => $uniqueCat,
            'description' => 'Photovoltaic architectural glass panels',
            'order' => 1,
            'is_active' => 1,
        ]);
        $createResponse->assertRedirect(route('admin.product-categories.index'));
        $this->assertDatabaseHas('product_categories', ['name' => $uniqueCat]);

        // 3. Admin Product create form displays this category
        $adminProductCreateResponse = $this->actingAs($admin, 'admin')->get('/admin/products/create');
        $adminProductCreateResponse->assertStatus(200);
        $adminProductCreateResponse->assertSee($uniqueCat);

        // 4. Author Product create form displays this category
        $authorProductCreateResponse = $this->actingAs($author, 'author')->get('/author/products/create');
        $authorProductCreateResponse->assertStatus(200);
        $authorProductCreateResponse->assertSee($uniqueCat);

        // Clean up
        ProductCategory::where('name', $uniqueCat)->delete();
    }
}
