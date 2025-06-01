<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'company_id', 'title', 'slug', 'type', 'description',
        'location', 'price', 'policy_cancellation', 'capacity',
        'duration', 'status', 'published_at'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function media()
    {
        return $this->hasMany(ServiceMedia::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
