<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [];

        for ($i = 1; $i <= 20; $i++) {
            $templates[] = [
                'name' => 'Template ' . $i,
                'path' => 'templates/template_' . $i . '.png', // Assuming you have placeholder images named like this
                'editable_regions' => json_encode([
                    'title' => ['x' => 50, 'y' => 50, 'width' => 500, 'height' => 100],
                    'description' => ['x' => 50, 'y' => 200, 'width' => 500, 'height' => 200],
                    'image' => ['x' => 50, 'y' => 450, 'width' => 500, 'height' => 400],
                    'url' => ['x' => 50, 'y' => 550, 'width' => 500, 'height' => 100]
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Template::insert($templates);
    }
}
