<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ConfigurationClinique extends Model
{
    protected $table = 'configuration_clinique';
    protected $guarded = ['id'];

    public static function get(): self
    {
        return static::firstOrFail();
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }
}