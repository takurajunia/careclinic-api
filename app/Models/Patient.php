<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'full_name', 'date_of_birth', 'gender', 'national_id',
        'phone_number', 'address', 'medical_aid_provider',
        'medical_aid_number', 'allergies', 'chronic_conditions', 'archived_at'
    ];

    protected $casts = [
        'archived_at' => 'datetime',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }
}