<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            [
                'name' => 'HOLCIM FOUNDATION',
                'logo' => 'partner_holcim.svg',
                'url' => 'https://www.holcimfoundation.org/',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'World Architecture Festival',
                'logo' => 'partner_waf.svg',
                'url' => 'https://www.worldarchitecturefestival.com/',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'UIA International Union of Architects',
                'logo' => 'partner_uia.svg',
                'url' => 'https://www.uia-architectes.org/',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'UN-HABITAT',
                'logo' => 'partner_unhabitat.svg',
                'url' => 'https://unhabitat.org/',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'OBEL AWARD',
                'logo' => 'partner_obel.svg',
                'url' => 'https://obelaward.org/',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'EUROPEAN CULTURAL CENTRE',
                'logo' => 'partner_ecc.svg',
                'url' => 'https://europeanculturalcentre.eu/',
                'order' => 6,
                'is_active' => true,
            ],
        ];

        // Clear existing initial partners and reinsert to match exact names
        Partner::truncate();

        foreach ($partners as $partner) {
            Partner::create($partner);
        }
    }
}
