<?php

use App\Continent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContinentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      /*DB::table('continents')->insert(
        array_map(function ($value){
          return [
            'name' => $value, 'created_at' => now(), 'updated_at' => now(),
          ];
        }, Continent::continents())
      );*/

      foreach (Continent::seed() as $continent){
        factory(Continent::class)->state('seeding')->create(['name' => $continent]);
      }
    }
}
