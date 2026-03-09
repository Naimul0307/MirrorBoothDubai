<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Brand extends Model
{
    use HasFactory, Sluggable;

    protected $table = 'brands';

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_branding');
    }

    public function sluggable(): array
    {
        return ['slug' => ['source' => 'name']];
    }
}
