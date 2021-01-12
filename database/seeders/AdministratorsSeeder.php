<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Permission\Role;
use App\Models\Permission\Administrator;

class AdministratorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::updateOrCreate(['phone' => '17820720720']);
        $administrator = Administrator::updateOrCreate(['user_id' => $user->id]);
        $role = Role::updateOrCreate([ 'name' => 'admin']);
        $administrator->syncRoles([$role]);
    }
}
