<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        //
        DB::table('users')->insert([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@xyz.com',
            'password' => bcrypt('123456'),
            'role' => '1',
        ]);
    }

}
