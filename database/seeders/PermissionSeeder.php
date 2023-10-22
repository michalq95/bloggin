<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'writer']);
        Role::firstOrCreate(['name' => 'member']);

        Permission::firstOrCreate(['name' => 'create post']);
        Permission::firstOrCreate(['name' => 'read post']);
        Permission::firstOrCreate(['name' => 'update post']);
        Permission::firstOrCreate(['name' => 'delete post']);

        Permission::firstOrCreate(['name' => 'create comment']);
        Permission::firstOrCreate(['name' => 'read comment']);
        Permission::firstOrCreate(['name' => 'update comment']);
        Permission::firstOrCreate(['name' => 'update mycomment']);
        Permission::firstOrCreate(['name' => 'delete comment']);




        $admin = Role::where('name', 'admin')->first();
        $admin->givePermissionTo([
            'create post',
            'read post',
            'update post',
            'delete post',
            'create comment',
            'read comment',
            'update comment',
            'update mycomment',
            'delete comment'
        ]);

        $writer = Role::where('name', 'writer')->first();
        $writer->givePermissionTo([
            'create post',
            'read post',
            'update post',
            'delete post',
            'create comment',
            'read comment',
            'update comment',
            'update mycomment',
            'delete comment',
        ]);

        $member = Role::where('name', 'member')->first();
        $member->givePermissionTo([
            'create post',
            'read post',
            'update post',
            'delete post',
            'create comment',
            'read comment',
            'update mycomment',
        ]);
    }
}
