<?php

use Illuminate\Database\Seeder;

class BusinessActivityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\BusinessActivity::class, 25)->create();
    }
}
