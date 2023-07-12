<?php

namespace Modules\Authentication\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Modules\Authentication\Models\Role;
use Modules\Authentication\Models\User;

class AuthenticationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::query()
            ->create([
                'email' => 'jan@flyinc.de',
                'password' => Hash::make('test')
            ]);

        $role = Role::query()
            ->create([
                'name' => 'Admin'
            ]);

        $user->roles()->sync($role->id);
    }
}
