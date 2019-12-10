<?php

use Illuminate\Database\Seeder;

class UnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Unit::class,3)->create()->each(function ($u) {
            $u->admins()->save((factory(App\Admin::class))->make());
            $u->Boxes()->saveMany(factory(App\Box::class,3)->make());
        });
    }
}
