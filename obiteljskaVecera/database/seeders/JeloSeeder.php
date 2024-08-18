<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jelo;

class JeloSeeder extends Seeder
{
    public function run()
    {
        $jela = [
            ['naziv' => 'Pizza'],
            ['naziv' => 'Pasta'],
            ['naziv' => 'Salata'],
            ['naziv' => 'Gulaš'],
        ];

        foreach ($jela as $jelo) {
            Jelo::create($jelo);
        }
    }
}
