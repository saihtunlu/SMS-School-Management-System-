<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /***Role Permissions***/

        Gate::define('isAdmin', function ($user) {
            return $user->user_role->role->name === 'Administrator';
        });

        /***CURD Permissions***/

        //Permission_employee
        Gate::define('isEmployee_Read', function ($user) {
            return $user->permission_employee->read === 1;
        });
        Gate::define('isEmployee_Create', function ($user) {
            return $user->permission_employee->create === 1;
        });
        Gate::define('isEmployee_Update', function ($user) {
            return $user->permission_employee->update  === 1;
        });
        Gate::define('isEmployee_Delete', function ($user) {
            return $user->permission_employee->delete  === 1;
        });

        //Permission_user
        Gate::define('isUser_Read', function ($user) {
            return $user->permission_user->read  === 1;
        });
        Gate::define('isUser_Create', function ($user) {
            return $user->permission_user->create  === 1;
        });
        Gate::define('isUser_Update', function ($user) {
            return $user->permission_user->update  === 1;
        });
        Gate::define('isUser_Delete', function ($user) {
            return $user->permission_user->delete === 1;
        });

        //Permission_student
        Gate::define('isStudent_Read', function ($user) {
            return $user->permission_student->read  === 1;
        });
        Gate::define('isStudent_Create', function ($user) {
            return $user->permission_student->create  === 1;
        });
        Gate::define('isStudent_Update', function ($user) {
            return $user->permission_student->update  === 1;
        });
        Gate::define('isStudent_Delete', function ($user) {
            return $user->permission_student->delete === 1;
        });
    }
}
