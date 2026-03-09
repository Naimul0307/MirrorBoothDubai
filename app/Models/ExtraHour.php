<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class ExtraHour extends Model
{
    use HasFactory, Sluggable;

    protected $table = 'extra_hours';

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_hours', 'hour_id', 'package_id');
    }

    public function sluggable(): array
    {
        return ['slug' => ['source' => 'name']];
    }
}
