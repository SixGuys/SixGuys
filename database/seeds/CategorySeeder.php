<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);
        $time=$faker->dateTime;

        $categories=factory(Category::class)
            ->times(10)
            ->make()
            ->each(function($category,$index)
            use($faker,$time){
                $category->name=$faker->name;
                $category->desc=$faker->sentence;
                $category->created_at=$time;
                $category->updated_at=$time;
        });

        Category::insert($categories->toArray());

    }
}
