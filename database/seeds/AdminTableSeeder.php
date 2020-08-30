<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'saihtunlu14996@gmail.com',
            'type' => 'Employee',
            'active' => 1,
            'email_verified_at' => now(),
            'password' =>  Hash::make('zaihtunlu'),
        ]);
        DB::table('roles')->insert([
            'name' => 'Administrator',
        ]);
        DB::table('roles')->insert([
            'name' => 'Manager',
        ]);
        DB::table('roles')->insert([
            'name' => 'Teacher',
        ]);
        DB::table('roles')->insert([
            'name' => 'Parent',
        ]);
        DB::table('roles')->insert([
            'name' => 'Student',
        ]);
        DB::table('roles')->insert([
            'name' => 'Guest',
        ]);
        DB::table('user_roles')->insert([
            'role_id' => 1,
            'user_id' => 1,
        ]);
        DB::table('employees')->insert([
            'first_name' => 'Sai Htun',
            'last_name' => 'Lu',
            'nick_name' => 'Sai',
            'gender' => 'Male',
            'phone' => '09692082635',
            'position' => 'Administrator',
            'email' => 'saihtunlu14996@gmail.com',
            'NRC' => 'NRC/123',
            'user_id' => 1,
        ]);
        DB::table('permission_employees')->insert([
            'name' => "Employee",
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'read' => 1,
            'user_id' => 1,
        ]);
        DB::table('permission_users')->insert([
            'name' => "Student",
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'read' => 1,
            'user_id' => 1,
        ]);
        DB::table('permission_students')->insert([
            'name' => "User",
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'read' => 1,
            'user_id' => 1,
        ]);
        DB::table('permission_invoices')->insert([
            'name' => "Invoice",
            'create' => 1,
            'update' => 1,
            'delete' => 1,
            'read' => 1,
            'user_id' => 1,
        ]);

        DB::table('generals')->insert([
            'logo' => "/storage/image/logo.gif",
            'transition' => 'zoom-in-out',
            'title' => 'Laravel & Vue',
            'primary' => "rgb(180, 122, 243)",
            'light_primary' => "rgb(130, 122, 243)",
            'success' => "rgb(40, 199, 111)",
            'warning' => "rgb(255, 159, 67)",
            'danger' => "rgb(242, 19, 93)",
        ]);
        DB::table('themes')->insert([
            'dark' => "rgb(65,65,65)",
            'gray' => "rgb(239, 242, 247)",
            'gray2' => "rgb(239, 242, 247)",
            'input' => "rgb(255, 255, 255)",
            'main_bg' => "rgb(239, 238, 253)",
            'mode' => "light",
            'semi_dark' => "rgb(65,65,65)",
            'semi_theme' => "rgb(255,255,255)",
            'theme_1' => "rgb(255,255,255)",
            'theme_2' => "rgb(255,255,255)",
            'theme_3' => "rgb(255,255,255)"
        ]);
        DB::table('themes')->insert([
            'dark' => "rgb(225,225,225)",
            'gray' => "rgb(41, 41, 41)",
            'gray2' => "rgb(50, 50, 50)",
            'input' => "rgb(41, 41, 41)",
            'main_bg' => "rgb(18, 18, 18)",
            'mode' => "dark",
            'semi_dark' => "rgb(225,225,225)",
            'semi_theme' => "rgb(30, 30, 30)",
            'theme_1' => "rgb(30, 30, 30)",
            'theme_2' => "rgb(41, 41, 41)",
            'theme_3' => "rgb(41, 41, 41)"
        ]);
        DB::table('themes')->insert([
            'dark' => "rgb(65,65,65)",
            'gray' => "rgb(239, 242, 247)",
            'gray2' => "rgb(195, 195, 195)",
            'input' => "rgb(255, 255, 255)",
            'main_bg' => "rgb(239, 238, 253)",
            'mode' => "semi_dark",
            'semi_dark' => "rgb(225,225,225)",
            'semi_theme' => "rgb(30, 30, 30)",
            'theme_1' => "rgb(255,255,255)",
            'theme_2' => "rgb(245, 245, 245)",
            'theme_3' => "rgb(255, 255, 255)"
        ]);
        DB::table('academic_years')->insert([
            'title' => "First Year",
            'from' => "01/06/2020 00:00:00",
            'to' => "01/04/2021 00:00:00",
        ]);
    }
}
