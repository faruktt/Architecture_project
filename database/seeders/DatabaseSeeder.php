<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Author;
use App\Models\Project;
use App\Models\Product;
use App\Models\Article;
use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin Account
        $admin = Admin::updateOrCreate(
            ['email' => 'admin@nook.com'],
            [
                'name' => 'Chief Editor',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
            ]
        );

        // 2. Authors
        $authorSarah = Author::updateOrCreate(
            ['email' => 'sarah@nook.com'],
            [
                'name' => 'Ar. Sarah Jenkins',
                'username' => 'sarahjenkins',
                'password' => Hash::make('password123'),
                'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
                'bio' => 'Award-winning architect specializing in waterfront residences, floating pavilions, and sustainable maritime living spaces across Southeast Asia.',
                'company' => 'Atelier Maritime',
                'title' => 'Principal Marine Architect',
                'country' => 'Malaysia',
                'website' => 'https://ateliermaritime.example.com',
                'phone' => '+60 12-345 6789',
                'status' => 'active',
            ]
        );

        $authorTariq = Author::updateOrCreate(
            ['email' => 'tariq@nook.com'],
            [
                'name' => 'Ar. Tariq Ahmed',
                'username' => 'tariqahmed',
                'password' => Hash::make('password123'),
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
                'bio' => 'Passionate about tropical modernism, passive cooling, and bioclimatic architecture in Bali, Jakarta, and Dhaka.',
                'company' => 'Forma Habitat Studio',
                'title' => 'Lead Urban Architect',
                'country' => 'Indonesia',
                'website' => 'https://formahabitat.example.com',
                'phone' => '+62 812-3456-7890',
                'status' => 'active',
            ]
        );

        $authorGraphisoft = Author::updateOrCreate(
            ['email' => 'author@nook.com'],
            [
                'name' => 'GRAPHISOFT Official',
                'username' => 'graphisoft',
                'password' => Hash::make('password123'),
                'avatar' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=400&q=80',
                'bio' => 'Empowering architects and design teams to create great architecture with Archicad and BIMx ecosystem.',
                'company' => 'GRAPHISOFT SE',
                'title' => 'BIM Solution Architect',
                'country' => 'Global',
                'website' => 'https://graphisoft.com',
                'phone' => '+36 1 437 3000',
                'status' => 'active',
            ]
        );

        // Follow relation: Tariq follows Sarah, Sarah follows Tariq
        if (!$authorSarah->followers()->where('follower_id', $authorTariq->id)->exists()) {
            $authorSarah->followers()->attach($authorTariq->id);
        }
        if (!$authorTariq->followers()->where('follower_id', $authorSarah->id)->exists()) {
            $authorTariq->followers()->attach($authorSarah->id);
        }

        // 3. Projects (Matching Image 1 Exact Layout)
        // Main Editorial Hero Story
        Project::updateOrCreate(
            ['slug' => 'architecture-on-water-living-at-sea'],
            [
                'author_id' => $authorSarah->id,
                'title' => 'Architecture on Water: What Living at Sea Can Teach Us About Space and Materials',
                'subtitle' => '43 minutes ago | In Collaboration',
                'excerpt' => 'Unlike buildings, which are anchored to foundations and respond to climate from a fixed position, yachts are designed for constant movement and change. Interiors sway with the vessel, light changes by the minute, humidity is a permanent design condition, and every kilogram influences performance. Designing for the sea therefore requires architects, naval architects, engineers, and interior designers to solve questions that rarely exist on land: how can space remain calm while everything around it is in motion?',
                'content' => '<p>Unlike buildings, which are anchored to foundations and respond to climate from a fixed position, yachts are designed for constant movement and change. Interiors sway with the vessel, light changes by the minute, humidity is a permanent design condition, and every kilogram influences performance. Designing for the sea therefore requires architects, naval architects, engineers, and interior designers to solve questions that rarely exist on land: how can space remain calm while everything around it is in motion?</p>
                <p class="mt-4">In terrestrial architecture, we take inertia for granted. Floor slabs are leveled, plumbing follows predictable gravitational slopes, and exterior cladding deals with rain and wind from habitual prevailing angles. When floating on seawater, every material is put under relentless thermodynamic stress. Salt air corrodes steel within months if not specified with marine-grade 316 stainless alloys or titanium fasteners. Timber must breathe and swell without buckling against rigid bulkheads.</p>
                <p class="mt-4">Yet the spatial rewards are unparalleled. Floor-to-ceiling panoramic apertures reveal uninterrupted horizons, illuminated by soft amber indirect LED coves that mimic the setting sun. The master stateroom depicted features custom upholstered ribbed headboards with integrated diffused illumination, teak joinery, and acoustically isolated flooring that decouples mechanical vibration from the sleeping quarters.</p>',
                'country' => 'Malaysia',
                'city' => 'Langkawi & Malacca Strait',
                'category' => 'Interior',
                'year' => '2025',
                'area' => '480 m²',
                'collaboration' => 'In Collaboration with Studio Drift & Oceanica',
                'featured_image' => 'https://images.unsplash.com/photo-1540518614846-7ede433c4ef4?auto=format&fit=crop&w=1200&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1540518614846-7ede433c4ef4?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=1200&q=80',
                ],
                'status' => 'approved',
                'is_featured' => false,
                'is_spotlight' => false,
                'is_hero_story' => true,
                'views_count' => 4520,
            ]
        );

        // Top Card 1: "Project of the week"
        Project::updateOrCreate(
            ['slug' => 'project-of-the-week-illuminated-lattice-lounge'],
            [
                'author_id' => $authorSarah->id,
                'title' => 'The Illuminated Lattice Sanctuary: Craft & Contemporary Heritage',
                'subtitle' => 'Project of the week',
                'excerpt' => 'An intricately handcrafted geometric fretwork wall partition illuminated with backlight creates an opulent, contemplative gathering lounge.',
                'content' => '<p>Commissioned for a private collector in Kuala Lumpur, this residence brings traditional Nusantara geometric lattice craftsmanship into dialogue with state-of-the-art solid surface LED backlighting.</p>',
                'country' => 'Malaysia',
                'city' => 'Kuala Lumpur, Malaysia',
                'category' => 'Interior',
                'year' => '2024',
                'area' => '320 m²',
                'featured_image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=800&q=80'
                ],
                'status' => 'approved',
                'is_featured' => true,
                'is_spotlight' => false,
                'is_hero_story' => false,
                'views_count' => 3120,
            ]
        );

        // Top Card 2: "Nook Spotlight"
        Project::updateOrCreate(
            ['slug' => 'nook-spotlight-curved-cove-penthouse'],
            [
                'author_id' => $authorTariq->id,
                'title' => 'Curved Cove Lighting & Bookmatched Marble Penthouse',
                'subtitle' => 'Nook Spotlight',
                'excerpt' => 'Fluid architectural ceiling contours paired with Italian Statuario marble panels and smoked bronze mirrors create an ethereal sanctuary.',
                'content' => '<p>Fluid forms and soft indirect cove illumination define this high-rise sanctuary. The undulating ceiling curves sweep across the master suite, drawing eyes toward the skyline.</p>',
                'country' => 'Indonesia',
                'city' => 'Jakarta, Indonesia',
                'category' => 'Interior',
                'year' => '2025',
                'area' => '290 m²',
                'featured_image' => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=800&q=80'
                ],
                'status' => 'approved',
                'is_featured' => false,
                'is_spotlight' => true,
                'is_hero_story' => false,
                'views_count' => 2890,
            ]
        );

        // Project: Bali Modernist Villa
        Project::updateOrCreate(
            ['slug' => 'bali-indonesia-luxury-modern-villa'],
            [
                'author_id' => $authorTariq->id,
                'title' => 'Modern Biophilic Villa with Rooftop Reflection Pool',
                'subtitle' => 'Bali, Indonesia',
                'excerpt' => 'A breathtaking 4-bedroom modernist villa nestled in Canggu featuring basalt stone, cantilevered balconies, and seamless indoor-outdoor living.',
                'content' => '<p>A prime architectural residential estate in Bali, Indonesia. Features a double-height glass atrium, private infinity pool, solar photovoltaic integration, and lush landscaped courtyard gardens.</p>',
                'country' => 'Indonesia',
                'city' => 'Bali, Indonesia',
                'category' => 'Residential',
                'year' => '2024',
                'area' => '540 m²',
                'price' => '$1,650,000 USD',
                'featured_image' => 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=800&q=80'
                ],
                'status' => 'approved',
                'is_property_sell' => false,
                'is_hero_story' => false,
                'views_count' => 6100,
            ]
        );

        // Project: KL Triplex Courtyard
        Project::updateOrCreate(
            ['slug' => 'kl-malaysia-triplex-courtyard-home'],
            [
                'author_id' => $authorSarah->id,
                'title' => 'The Tiered Timber Courtyard: Luxury Triplex Residence',
                'subtitle' => 'KL, Malaysia',
                'excerpt' => 'Contemporary 3-storey urban sanctuary boasting timber slatted sun screens, landscaped private podiums, and automated smart climate control.',
                'content' => '<p>Located in Bukit Damansara, Kuala Lumpur. Designed with passive thermal stack ventilation, louvred timber screens that soften tropical midday glare, and spacious subterranean entertainment spaces.</p>',
                'country' => 'Malaysia',
                'city' => 'KL, Malaysia',
                'category' => 'Residential',
                'year' => '2024',
                'area' => '620 m²',
                'price' => '$2,450,000 USD',
                'featured_image' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=800&q=80'
                ],
                'status' => 'approved',
                'is_property_sell' => false,
                'is_hero_story' => false,
                'views_count' => 4890,
            ]
        );

        // Additional country projects for country tabs filter (Philippine, Thailand, Vietnam, China, Japan, India)
        $extraProjects = [
            [
                'title' => 'Bantayan Island Eco Pavilion: Bamboo Weaving & Rammed Earth',
                'slug' => 'bantayan-island-eco-pavilion-philippine',
                'author_id' => $authorTariq->id,
                'country' => 'Philippine',
                'city' => 'Cebu, Philippine',
                'category' => 'Hospitality',
                'featured_image' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80',
                'excerpt' => 'A resilient community and retreat pavilion utilizing local engineered bamboo and compressed earth blocks resistant to tropical storms.',
                'status' => 'approved',
            ],
            [
                'title' => 'Chiang Mai Mist Sanctuary: Floating Teak Platforms',
                'slug' => 'chiang-mai-mist-sanctuary-thailand',
                'author_id' => $authorSarah->id,
                'country' => 'Thailand',
                'city' => 'Chiang Mai, Thailand',
                'category' => 'Commercial',
                'featured_image' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80',
                'excerpt' => 'Cascading hillside resort seamlessly blended into the rainforest canopy with elevated walkways and natural thermal cooling.',
                'status' => 'approved',
            ],
            [
                'title' => 'Hanoi Brick Screen House: Microclimate Courtyards',
                'slug' => 'hanoi-brick-screen-house-vietnam',
                'author_id' => $authorTariq->id,
                'country' => 'Vietnam',
                'city' => 'Hanoi, Vietnam',
                'category' => 'Residential',
                'featured_image' => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=800&q=80',
                'excerpt' => 'Perforated terracotta masonry regulates ventilation and natural daylight throughout the dense historic urban fabric.',
                'status' => 'approved',
            ],
            [
                'title' => 'The Kyoto Courtyard House: Minimalist Hinoki & Paper Screens',
                'slug' => 'kyoto-courtyard-house-japan',
                'author_id' => $authorSarah->id,
                'country' => 'Japan',
                'city' => 'Kyoto, Japan',
                'category' => 'Interior',
                'featured_image' => 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?auto=format&fit=crop&w=800&q=80',
                'excerpt' => 'A serene residential intervention combining traditional Sukiya-zukuri carpentry with concealed radiant heating and acoustic glazing.',
                'status' => 'approved',
            ],
            [
                'title' => 'Bengal River House: Brick Terraces & Monsoon Patios',
                'slug' => 'bengal-river-house-india',
                'author_id' => $authorTariq->id,
                'country' => 'India',
                'city' => 'Kolkata, India',
                'category' => 'Residential',
                'featured_image' => 'https://images.unsplash.com/photo-1600565193348-f74bd3c7ccdf?auto=format&fit=crop&w=800&q=80',
                'excerpt' => 'Built along the riverbank with locally fired clay bricks, deep verandahs, and rainwater catchment reservoirs.',
                'status' => 'approved',
            ],
        ];

        foreach ($extraProjects as $item) {
            Project::updateOrCreate(['slug' => $item['slug']], array_merge($item, [
                'content' => '<p>' . $item['excerpt'] . '</p><p class="mt-4">Detailed architectural specifications, structural grid drawings, and carbon footprint assessments are included in this project documentation.</p>',
                'year' => '2025',
                'area' => '510 m²',
                'is_featured' => false,
                'is_spotlight' => false,
                'is_hero_story' => false,
                'is_property_sell' => false,
                'gallery' => [$item['featured_image'], 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80'],
            ]));
        }

        // Keep strictly these 10 projects with images and delete all others
        $seededProjectSlugs = [
            'architecture-on-water-living-at-sea',
            'project-of-the-week-illuminated-lattice-lounge',
            'nook-spotlight-curved-cove-penthouse',
            'bali-indonesia-luxury-modern-villa',
            'kl-malaysia-triplex-courtyard-home',
            'bantayan-island-eco-pavilion-philippine',
            'chiang-mai-mist-sanctuary-thailand',
            'hanoi-brick-screen-house-vietnam',
            'kyoto-courtyard-house-japan',
            'bengal-river-house-india',
        ];
        Project::whereNotIn('slug', $seededProjectSlugs)->delete();

        // 4. Products (Exactly 10 Architectural Products with Images)
        // Product 1: Hero BIMx
        Product::updateOrCreate(
            ['slug' => 'bim-presentation-and-communication-bimx'],
            [
                'author_id' => $authorGraphisoft->id,
                'title' => 'BIM Presentation and Communication - BIMx',
                'manufacturer' => 'GRAPHISOFT',
                'manufacturer_logo' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=120&q=80',
                'category' => 'Software / Courses',
                'country_region' => 'Global',
                'has_bim' => true,
                'website_url' => 'https://graphisoft.com/solutions/products/bimx',
                'phone' => '+36 1 437 3000',
                'email' => 'sales@graphisoft.com',
                'featured_image' => 'https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?auto=format&fit=crop&w=1000&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80',
                ],
                'short_description' => 'Award-winning presentation app for mobile and desktop to bridge the gap between design studio and construction site.',
                'use_description' => 'BIM presentation, project documentation review, design communication, on-site coordination, client and stakeholder collaboration',
                'applications' => 'Interactive exploration of BIM projects; review of linked 2D documentation and 3D models; access to building-element information; on-site project review and issue markup; sharing and publication of BIMx Hyper-models',
                'characteristics' => 'Multi-platform BIM viewer for desktop, mobile devices, web. Real-time walkthroughs, sunlight analysis, measuring tools, and cloud synchronization.',
                'specifications' => [
                    'Compatibility' => 'iOS, Android, macOS, Windows, Web Browser',
                    'File Formats' => 'BIMx Hyper-model (.bimx), IFC 4, BCF 2.1',
                    'Cloud Storage' => 'BIMx Model Transfer site & Graphisoft Cloud',
                    'Licensing' => 'Free viewer with BIMx PRO features for professional subscriptions',
                ],
                'status' => 'approved',
                'views_count' => 7691,
            ]
        );

        // Product 2: Filtration Faucet
        Product::updateOrCreate(
            ['slug' => 'filtration-faucet-avado-2-in-1'],
            [
                'author_id' => $authorSarah->id,
                'title' => 'Filtration Faucet - Avado® 2-in-1',
                'manufacturer' => 'ZURN ELKAY',
                'category' => 'Kitchen & Bath',
                'country_region' => 'USA',
                'has_bim' => true,
                'website_url' => 'https://www.zurn.com',
                'phone' => '+1 855-663-9876',
                'email' => 'commercial@zurn.com',
                'featured_image' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=800&q=80',
                ],
                'short_description' => 'Architectural filtration faucet featuring dual-flow technology for purified drinking water and high-pressure pull-down rinse.',
                'use_description' => 'Luxury residential kitchen islands, commercial breakrooms, and hospitality suites.',
                'applications' => 'Cold filtered drinking water on demand paired with multi-function spray head.',
                'characteristics' => 'Solid brass body, magnetic docking spray head, PVD scratch-resistant finish.',
                'status' => 'approved',
                'is_property_sell' => true,
                'price' => '$620 USD',
                'views_count' => 3420,
            ]
        );

        // Product 3: Archicad
        Product::updateOrCreate(
            ['slug' => 'bim-design-and-documentation-archicad'],
            [
                'author_id' => $authorGraphisoft->id,
                'title' => 'BIM Design and Documentation - Archicad',
                'manufacturer' => 'GRAPHISOFT',
                'category' => 'Software / Courses',
                'country_region' => 'Global',
                'has_bim' => true,
                'website_url' => 'https://graphisoft.com/archicad',
                'phone' => '+36 1 437 3000',
                'email' => 'archicad@graphisoft.com',
                'featured_image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80',
                ],
                'short_description' => 'The leading BIM design software created by architects for architects.',
                'use_description' => 'Architectural conceptual design, 3D visualization, clash detection, and construction documentation.',
                'applications' => 'Algorithmic design with Rhino-Grasshopper live connection, native IFC openBIM workflows.',
                'characteristics' => 'Multi-core processing, real-time rendering engine, automated drawing sheets.',
                'status' => 'approved',
                'views_count' => 5910,
            ]
        );

        // Product 4: Lumicraft Downlight
        Product::updateOrCreate(
            ['slug' => 'architectural-luminaire-glow-perfection'],
            [
                'author_id' => $authorTariq->id,
                'title' => 'Experience The Glow of Perfection - Aurora Recessed Downlight',
                'manufacturer' => 'LUMICRAFT LIGHTING',
                'category' => 'Lighting & Electrical',
                'country_region' => 'Japan',
                'has_bim' => true,
                'website_url' => 'https://lumicraft.example.com',
                'phone' => '+81 3 5555 0192',
                'email' => 'design@lumicraft.example.com',
                'featured_image' => 'https://images.unsplash.com/photo-1517991104123-1d56a6e81ed9?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1517991104123-1d56a6e81ed9?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80',
                ],
                'short_description' => 'Ultra-low glare precision recessed architectural downlight designed for mood elevation and high-end residential interiors.',
                'use_description' => 'Ambient ceiling lighting, art spotlighting, luxury hospitality living suites.',
                'applications' => 'Dim-to-warm 1800K-3000K smoothly mimicking halogen incandescent glow with 98 CRI fidelity.',
                'characteristics' => 'Die-cast aluminum heat sink, anti-glare honeycomb baffle, DALI-2 and 0-10V dimmable.',
                'status' => 'approved',
                'views_count' => 4180,
            ]
        );

        // Product 5: Nordic Acoustic Slat Panel System
        Product::updateOrCreate(
            ['slug' => 'acoustic-oak-wall-slat-system'],
            [
                'author_id' => $authorSarah->id,
                'title' => 'Nordic Acoustic Oak Wall Slat Panel System',
                'manufacturer' => 'NORDIC SOUNDSCAPES',
                'category' => 'Finishes',
                'country_region' => 'Sweden',
                'has_bim' => true,
                'website_url' => 'https://nordicsound.example.com',
                'phone' => '+46 8 123 4567',
                'email' => 'sales@nordicsound.example.com',
                'featured_image' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80',
                ],
                'short_description' => 'Sustainable recycled PET acoustic felt with natural oiled oak veneer battens for reverberation control.',
                'use_description' => 'Corporate auditoriums, conference rooms, recording studios, luxury dining spaces.',
                'applications' => 'Absorbs mid and high frequencies (NRC 0.85) while introducing Scandinavian warmth.',
                'characteristics' => 'Class A sound absorption, FSC certified oak, easy interlocking wall mounting.',
                'status' => 'approved',
                'is_property_sell' => true,
                'price' => '$145 / m²',
                'views_count' => 120,
            ]
        );

        // Product 6: Residential AC Mini Split Range - airHome™ (From Screenshot)
        Product::updateOrCreate(
            ['slug' => 'residential-ac-mini-split-airhome'],
            [
                'author_id' => $authorTariq->id,
                'title' => 'Residential AC Mini Split Range - airHome™',
                'manufacturer' => 'Hitachi Air Conditioning',
                'category' => 'HVAC & Climate Control',
                'country_region' => 'Japan',
                'has_bim' => true,
                'website_url' => 'https://hitachiaircon.com',
                'phone' => '+81 3 4567 8900',
                'email' => 'residential@hitachiaircon.com',
                'featured_image' => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80',
                ],
                'short_description' => 'Smart residential climate control combining FrostWash automatic self-cleaning with ultra-quiet 19dB operation.',
                'use_description' => 'High-end apartments, contemporary villas, boutique hotel suites requiring invisible air conditioning.',
                'applications' => 'Remote Wi-Fi smartphone app scheduling, Smart-Eco occupancy sensors, active electrostatic pathogen filtration.',
                'characteristics' => 'Inverter heat pump compressor, SEER 24 energy rating, sleek matte architectural white finish.',
                'status' => 'approved',
                'views_count' => 4820,
            ]
        );

        // Product 7: SiteSupervisor - Construction App (From Screenshot)
        Product::updateOrCreate(
            ['slug' => 'sitesupervisor-construction-app'],
            [
                'author_id' => $authorGraphisoft->id,
                'title' => 'SiteSupervisor - Construction App',
                'manufacturer' => 'SiteSupervisor',
                'category' => 'Software / Courses',
                'country_region' => 'USA',
                'has_bim' => true,
                'website_url' => 'https://sitesupervisor.example.com',
                'phone' => '+1 800 555 0144',
                'email' => 'support@sitesupervisor.example.com',
                'featured_image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80',
                ],
                'short_description' => 'Real-time construction jobsite management, defect tracking, and 2D/3D BIM drawing overlays for project superintendents.',
                'use_description' => 'Jobsite progress inspections, RFIs, punch lists, contractor coordination on commercial builds.',
                'applications' => 'Offline mobile tablet synchronization, automated PDF inspection exports, instant photo markups.',
                'characteristics' => 'Cloud collaborative ledger, multi-user role management, ISO 19650 compliant metadata.',
                'status' => 'approved',
                'views_count' => 3190,
            ]
        );

        // Product 8: Bathroom Equipment - Metallic Towel Rings (From Screenshot)
        Product::updateOrCreate(
            ['slug' => 'bathroom-equipment-metallic-towel-rings'],
            [
                'author_id' => $authorSarah->id,
                'title' => 'Bathroom Equipment - Metallic Towel Rings',
                'manufacturer' => 'Sanco',
                'category' => 'Kitchen & Bath',
                'country_region' => 'Germany',
                'has_bim' => true,
                'website_url' => 'https://sanco.example.com',
                'phone' => '+49 30 123456',
                'email' => 'bath@sanco.example.com',
                'featured_image' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=800&q=80',
                ],
                'short_description' => 'Brushed titanium and chrome solid brass architectural towel rings with concealed fixing brackets.',
                'use_description' => 'Luxury master bathrooms, powder rooms, five-star hotel hospitality washrooms.',
                'applications' => 'Wall-mounted towel hanging, anti-corrosion marine PVD treatment against moisture.',
                'characteristics' => 'Machined solid brass core, fingerprint-resistant micro-texture, concealed screw mounting plate.',
                'status' => 'approved',
                'views_count' => 2940,
            ]
        );

        // Product 9: Minimalist Frameless Sliding Glass System
        Product::updateOrCreate(
            ['slug' => 'minimalist-frameless-sliding-glass-system'],
            [
                'author_id' => $authorSarah->id,
                'title' => 'Minimalist Frameless Sliding Glass Facade System',
                'manufacturer' => 'Sky-Frame',
                'category' => 'Doors & Windows',
                'country_region' => 'Switzerland',
                'has_bim' => true,
                'website_url' => 'https://sky-frame.example.com',
                'phone' => '+41 52 724 94 94',
                'email' => 'info@sky-frame.example.com',
                'featured_image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80',
                ],
                'short_description' => 'Swiss precision-engineered frameless sliding doors with flush sill thresholds and triple-glazed thermal insulation.',
                'use_description' => 'Floor-to-ceiling glass pavilions, coastal villas, panoramic penthouse terraces.',
                'applications' => 'Seamless indoor-outdoor transitions, automated motorization with smart building integrations.',
                'characteristics' => 'Uw-value below 0.80 W/m²K, acoustic dampening up to 44 dB, concealed drainage tracks.',
                'status' => 'approved',
                'views_count' => 6120,
            ]
        );

        // Product 10: Ventilated Terracotta Rainscreen Facade Panels
        Product::updateOrCreate(
            ['slug' => 'terracotta-rainscreen-facade-panels'],
            [
                'author_id' => $authorTariq->id,
                'title' => 'Ventilated Terracotta Rainscreen Facade Panels',
                'manufacturer' => 'NBK Architectural Terracotta',
                'category' => 'Facades & Exterior',
                'country_region' => 'Germany',
                'has_bim' => true,
                'website_url' => 'https://nbkterracotta.example.com',
                'phone' => '+49 2822 929-0',
                'email' => 'sales@nbkterracotta.example.com',
                'featured_image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=800&q=80',
                ],
                'short_description' => 'Natural clay extruded hollow-core rainscreen tiles designed for high-performance thermal insulation and moisture resistance.',
                'use_description' => 'University campuses, museum envelopes, low-carbon municipal landmarks.',
                'applications' => 'Dry-hung ventilated facade cladding, thermal acoustic shielding, frost resistant.',
                'characteristics' => 'Class A1 non-combustible, 100% natural clay, custom glazed engobe hues.',
                'status' => 'approved',
                'views_count' => 3870,
            ]
        );

        // Keep strictly these 10 products with images and delete all others
        $seededProductSlugs = [
            'bim-presentation-and-communication-bimx',
            'filtration-faucet-avado-2-in-1',
            'bim-design-and-documentation-archicad',
            'architectural-luminaire-glow-perfection',
            'acoustic-oak-wall-slat-system',
            'residential-ac-mini-split-airhome',
            'sitesupervisor-construction-app',
            'bathroom-equipment-metallic-towel-rings',
            'minimalist-frameless-sliding-glass-system',
            'terracotta-rainscreen-facade-panels',
        ];
        Product::whereNotIn('slug', $seededProductSlugs)->delete();

        // 5. Articles (Matching Image 1) & Architecture News (Matching Image 2)
        // Article 1 (Exact Image 1)
        Article::updateOrCreate(
            ['slug' => 'from-garden-to-monument-liberation-landscapes-african-cities'],
            [
                'title' => 'From Garden to Monument: Liberation Landscapes in African Cities',
                'type' => 'article',
                'category' => 'Editorial',
                'author_name' => 'Nook Editorial Board',
                'image' => 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?auto=format&fit=crop&w=1200&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1479839672679-a46483c0e7c8?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=400&q=80',
                ],
                'has_audio' => true,
                'has_video' => false,
                'summary' => "Liberation does not always begin with a monument. It may begin in a garden, a yard, a clearing, a field; in the occupation of land and the possibility of gathering there. Across histories shaped by colonialism and dispossession, space has been more than the setting in which liberation takes place. It has been one of its practices: cultivated, occupied, hidden within, moved through and collectively remade.\n\nWolff Architects' research Liberation Gardens offers the garden as one way of reading these spatial practices, not simply as planted ground, but as a space through which survival, nourishment and emancipatory imagination can be sustained. The garden in this sense is not defined by landscape design so much as by relationships between people and land, cultivation and knowledge, occupation and autonomy. It can hold cultural production alongside subsistence, and forms of collective life that emerge under conditions of violence and dispossession. Liberation, understood spatially, becomes as much about the possibility of inhabiting differently as it is about arriving at a final condition of freedom.",
                'content' => "<p>Liberation does not always begin with a monument. It may begin in a garden, a yard, a clearing, a field; in the occupation of land and the possibility of gathering there. Across histories shaped by colonialism and dispossession, space has been more than the setting in which liberation takes place. It has been one of its practices: cultivated, occupied, hidden within, moved through and collectively remade.</p>
                <p>Wolff Architects' research <em>Liberation Gardens</em> offers the garden as one way of reading these spatial practices, not simply as planted ground, but as a space through which survival, nourishment and emancipatory imagination can be sustained. The garden in this sense is not defined by landscape design so much as by relationships between people and land, cultivation and knowledge, occupation and autonomy. It can hold cultural production alongside subsistence, and forms of collective life that emerge under conditions of violence and dispossession. Liberation, understood spatially, becomes as much about the possibility of inhabiting differently as it is about arriving at a final condition of freedom.</p>
                <h2>Spatial Sovereignty & Collective Memory</h2>
                <p>Through careful documentation of everyday clearings, urban allotments, and community nurseries, the project reveals how vernacular landscape strategies provide both psychological refuge and civic empowerment under persistent structural inequality.</p>",
                'status' => 'published',
                'published_at' => now()->subHours(1),
            ]
        );

        // Article 2 (Image 1 bottom)
        Article::updateOrCreate(
            ['slug' => 'escapism-from-the-streets-how-air-conditioning-reshaped-asian-city'],
            [
                'title' => 'Escapism from the Streets: How Air-Conditioning Reshaped the Asian City',
                'type' => 'article',
                'category' => 'Urbanism',
                'author_name' => 'Nook Architecture Staff',
                'image' => 'https://images.unsplash.com/photo-1506973035872-a4ec16b8e8d9?auto=format&fit=crop&w=1200&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1506973035872-a4ec16b8e8d9?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1479839672679-a46483c0e7c8?auto=format&fit=crop&w=400&q=80',
                ],
                'has_audio' => true,
                'has_video' => false,
                'summary' => "From high-density megacities to humid tropical archipelagos, interior climatization transformed modern public life, shopping culture, and domestic layouts across Southeast and East Asia, shifting civic socialization into sealed, multi-level atriums.",
                'content' => "<p>From high-density megacities to humid tropical archipelagos, interior climatization transformed modern public life, shopping culture, and domestic layouts across Southeast and East Asia, shifting civic socialization into sealed, multi-level atriums.</p>",
                'status' => 'published',
                'published_at' => now()->subHours(23),
            ]
        );

        // News 1 (Exact Image 2)
        Article::updateOrCreate(
            ['slug' => 'sharjah-architecture-triennial-presents-journey-into-architecture-archives'],
            [
                'title' => 'Sharjah Architecture Triennial Presents "A Journey into Architecture Archives" Focused on Baghdad, Damascus, and Tunis',
                'type' => 'news',
                'category' => 'Architecture News',
                'author_name' => 'Nook News Desk',
                'image' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1200&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1479839672679-a46483c0e7c8?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1512915922686-57c11dde9b6b?auto=format&fit=crop&w=400&q=80',
                ],
                'has_audio' => true,
                'has_video' => true,
                'badge_text' => '▷ Videos',
                'summary' => "The Sharjah Architecture Triennial (SAT) has released the full documentary films from A Journey into Architecture Archives: Baghdad, Damascus, Tunis online. Curated by George Arbid, the project is part of SAT's long-term research program focused on documenting and safeguarding architectural archives across the Arab world. The three films, filmed on location in Baghdad, Damascus, and Tunis, combine archival research with fieldwork and oral histories, examining how architectural histories are constructed, preserved, and revisited over time.",
                'content' => "<p>The Sharjah Architecture Triennial (SAT) has released the full documentary films from <em>A Journey into Architecture Archives: Baghdad, Damascus, Tunis</em> online.</p>
                <p>Curated by George Arbid, the project is part of SAT's long-term research program focused on documenting and safeguarding architectural archives across the Arab world. The three films, filmed on location in Baghdad, Damascus, and Tunis, combine archival research with fieldwork and oral histories, examining how architectural histories are constructed, preserved, and revisited over time.</p>
                <h2>A Tripartite Architectural Exploration</h2>
                <p>The films offer viewers rare glimpses into municipal repositories, private practices, and neglected blueprint vaults, capturing the mid-century modernism that emerged alongside post-colonial nation building across the region.</p>",
                'status' => 'published',
                'published_at' => now()->subHours(23),
            ]
        );

        // News 2 (Image 2 bottom)
        Article::updateOrCreate(
            ['slug' => 'citizen-led-campaign-seeks-unesco-status-select-pre-colonial-sites-lima-peru'],
            [
                'title' => 'Citizen-Led Campaign Seeks UNESCO Status for Select Pre-Colonial Sites in Lima, Peru',
                'type' => 'news',
                'category' => 'Heritage & Urban',
                'author_name' => 'Nook News Wire',
                'image' => 'https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?auto=format&fit=crop&w=1200&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1479839672679-a46483c0e7c8?auto=format&fit=crop&w=400&q=80',
                ],
                'has_audio' => true,
                'has_video' => false,
                'badge_text' => 'Preservation',
                'summary' => "Local community organizers and preservationists across Lima are petitioning international heritage organizations to recognize ancient adobe pyramids and pre-colonial canal paths threatened by rapid urban sprawl.",
                'content' => "<p>Local community organizers and preservationists across Lima are petitioning international heritage organizations to recognize ancient adobe pyramids and pre-colonial canal paths threatened by rapid urban sprawl.</p>",
                'status' => 'published',
                'published_at' => now()->subHours(24),
            ]
        );

        // 6. CMS Pages
        $this->call(PageSeeder::class);
        $this->call(CategoryAndCountrySeeder::class);
        $this->call(PartnerSeeder::class);
    }
}
