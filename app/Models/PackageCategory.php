<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class PackageCategory extends Model
{
    use HasFactory, Sluggable;

    protected $table = 'package_categories';

    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    public function packages()
    {
        return $this->hasMany(Package::class, 'category_id');
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}