<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About',
                'slug' => 'about-us',
                'meta_description' => 'About Nook Magazine - Architectural and Interior Curation',
                'content' => '<h2>About Nook Magazine</h2>
<p class="mt-3">Nook Magazine is a premier international architectural showcase dedicated to inspiring spaces, contemporary materials, and visionary architectural practices across Southeast Asia and the world.</p>
<p class="mt-3">We connect architects, spatial designers, and material innovators, celebrating the beauty of built design from floating sanctuaries to sustainable urban pavilions.</p>',
                'status' => 'published',
                'order' => 1,
            ],
            [
                'title' => 'Imprint',
                'slug' => 'imprint',
                'meta_description' => 'Legal Imprint, Editorial Board and Publisher Details',
                'content' => '<h2>Legal Imprint & Editorial Board</h2>
<p class="mt-3"><strong>Publisher:</strong> DAaily platforms AG & Nook Media International Ltd.</p>
<p class="mt-3"><strong>Editor-in-Chief:</strong> Editorial Director & Curation Committee</p>
<p class="mt-3"><strong>Address:</strong> Architecture & Design Quarter, Innovation Boulevard</p>
<p class="mt-3"><strong>ISSN:</strong> 0719-8884 &bull; Registered International Architectural Periodical</p>',
                'status' => 'published',
                'order' => 2,
            ],
            [
                'title' => 'Terms of Use',
                'slug' => 'terms-of-use',
                'meta_description' => 'Terms and Conditions for accessing Nook Magazine',
                'content' => '<h2>Terms of Use</h2>
<p class="mt-3">By accessing and using this architectural platform, you agree to comply with our editorial and intellectual property standards.</p>
<p class="mt-3">All project photographs, plans, drawings, and 3D models remain the copyright of their respective authors, architects, and photographers as explicitly attributed.</p>',
                'status' => 'published',
                'order' => 3,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'meta_description' => 'Privacy Policy and Data Protection Information',
                'content' => '<h2>Privacy Policy</h2>
<p class="mt-3">We respect your privacy and are committed to safeguarding personal data submitted via architect registration, project inquiries, and product catalog requests.</p>
<p class="mt-3">We do not sell personal data to third parties. Data is used exclusively for editorial curation, authentication, and manufacturer inquiry routing.</p>',
                'status' => 'published',
                'order' => 4,
            ],
            [
                'title' => 'Cookie Policy',
                'slug' => 'cookie-policy',
                'meta_description' => 'How we use cookies to improve your magazine reading experience',
                'content' => '<h2>Cookie Policy</h2>
<p class="mt-3">Nook Magazine uses essential and performance cookies to maintain secure sessions for architects, preserve regional filtering preferences, and track editorial readership analytics.</p>
<p class="mt-3">You can manage cookie preferences directly through your browser settings at any time.</p>',
                'status' => 'published',
                'order' => 5,
            ],
            [
                'title' => 'Contributors Policy',
                'slug' => 'contributors-policy',
                'meta_description' => 'Editorial standards and submission policy for architecture contributors',
                'content' => '<h2>Contributors & Editorial Policy</h2>
<p class="mt-3">We invite licensed architecture practices, interior designers, urban planners, and architectural photographers to submit high-resolution project documentation.</p>
<p class="mt-3">All submissions undergo rigorous review by our curation team to ensure architectural integrity, photography excellence, and factual accuracy.</p>',
                'status' => 'published',
                'order' => 6,
            ],
        ];

        foreach ($pages as $data) {
            Page::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
