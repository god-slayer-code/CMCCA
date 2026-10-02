<?php

use App\Models\ConfigurationClinique;
use Illuminate\Support\Facades\Cache;

if (!function_exists('clinique')) {
    function clinique(): ConfigurationClinique
    {
        return Cache::rememberForever('config_clinique', function () {
            return ConfigurationClinique::get();
        });
    }
} 
