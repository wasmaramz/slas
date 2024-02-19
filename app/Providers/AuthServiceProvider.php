<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
		Gate::define('SYST', function (User $user){
			return $user->level_id === "SYST";
		});

		Gate::define('ADMN', function (User $user){
			return ($user->level_id === "SYST" OR $user->level_id === "ADMN");
		});

		Gate::define('KEPR', function (User $user){
			return (/*$user->level_id === "SYST" OR */$user->level_id === "KEPR");
		});

		Gate::define('TIPE', function (User $user){
			return (/*$user->level_id === "SYST" OR */$user->level_id === "TIPE");
		});

		Gate::define('PEAK', function (User $user){
			return (/*$user->level_id === "SYST" OR */$user->level_id === "PEAK");
		});

		Gate::define('STUD', function (User $user){
			return (/*$user->level_id === "SYST" OR */$user->level_id === "STUD");
		});

		Gate::define('STAFF', function (User $user){
			return (
				$user->level_id === "SYST" OR $user->level_id === "ADMN" OR 
				$user->level_id === "KEPR" OR $user->level_id === "TIPE" OR 
				$user->level_id === "PEAK"
			);
		});
    }
}
