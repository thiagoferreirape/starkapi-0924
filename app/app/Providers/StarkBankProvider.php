<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use StarkBank\Project;
use StarkBank\Settings;

class StarkBankProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $privKey = file_get_contents(base_path("/keys/privateKey.pem"));

        $project = new Project([
            "environment" => "sandbox",
            "id" => 4920155054276608,
            "privateKey" => $privKey
        ]);

        Settings::setUser($project);
    }
}
