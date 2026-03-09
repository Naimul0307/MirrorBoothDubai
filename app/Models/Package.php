<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Package extends Model
{
    use HasFactory, Sluggable;

    protected $table = 'packages';

    protected $fillable = [
        'name',
        'price',
        'included_hours',
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'package_branding');
    }

    public function hours()
    {
        return $this->belongsToMany(ExtraHour::class, 'package_hours', 'package_id', 'hour_id');
    }

    public function sluggable(): array
    {
        return [
            'slug' => ['source' => 'name']
        ];
    }
}


