<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category')->insert([
            'uri' => 'default',
            'name' => '默认分类',
            'banner' => '//s-img.niconico.in/large/a15b4afegw1f4dqgd7m6vj218g0iwwkb.jpg',
            'cover' => '//s-img.niconico.in/large/a15b4afegw1f4klsm1xtjj207k0ak74y.jpg',
            'on_index' => 1
        ]);
    }
}
