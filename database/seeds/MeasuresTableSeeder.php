<?php

use App\Measure;
use Illuminate\Database\Seeder;

class MeasuresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Measure::truncate();

        Measure::create([
            'name' => 'грамм',
        ]);
        Measure::create([
            'name' => 'миллилитров',
        ]);
        Measure::create([
            'name' => 'штук',
        ]);
        Measure::create([
            'name' => 'чайные ложки',
        ]);
        Measure::create([
            'name' => 'столовые ложки',
        ]);
        Measure::create([
            'name' => 'щипотки',
        ]);
    }
}
