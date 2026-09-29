<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectCategory;
use App\Models\Country;
use App\Models\ProductCategory;
use Illuminate\Support\Str;

class CategoryAndCountrySeeder extends Seeder
{
    public function run(): void
    {
        $projectCats = ['Architecture', 'Interior', 'Residential', 'Commercial', 'Hospitality', 'Landscape', 'Cultural', 'Industrial'];
        foreach ($projectCats as $i => $name) {
            ProjectCategory::firstOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name), 'order' => $i, 'is_active' => true]
            );
        }

        $countries = ['Malaysia', 'Indonesia', 'Philippine', 'Thailand', 'Vietnam', 'China', 'Japan', 'India', 'Brazil', 'Chile', 'Mexico', 'Others'];
        foreach ($countries as $i => $name) {
            Country::firstOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name), 'code' => strtoupper(substr($name, 0, 2)), 'order' => $i, 'is_active' => true]
            );
        }

        $productCats = ['Software / Courses', 'Kitchen & Bath', 'Lighting & Electrical', 'Finishes', 'Facade & Exterior', 'Furniture', 'Doors & Windows', 'Acoustics & Insulation', 'Structural Materials'];
        foreach ($productCats as $i => $name) {
            ProductCategory::firstOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name), 'order' => $i, 'is_active' => true]
            );
        }
    }
}
