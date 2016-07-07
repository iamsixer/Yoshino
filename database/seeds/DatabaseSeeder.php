<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserTableSeeder::class);
    }
}

class CategoryTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('category')->delete();
        Category::create([
            'uri' => 'default',
            'name' => 'Default',
            'banner' => 'http://placehold.it/1600x680',
            'cover' => 'http://placehold.it/185x260'
        ]);
    }
}
