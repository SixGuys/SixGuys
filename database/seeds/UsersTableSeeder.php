<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(Faker\Generator::class);
        $time=$faker->dateTime;
        User::insert([
            'name'=>'admin',
            'email'=>'admin@qiehe.net',
            'password'=>bcrypt('password'),
            'created_at'=>$time,
            'updated_at'=>$time,
        ]);
        $users=factory(User::class)->times(50)->make();
        User::insert($users->makeVisible(['password'])->toArray());
    }
}
