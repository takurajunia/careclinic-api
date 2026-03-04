<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'patient_id', 'appointment_id', 'blood_pressure', 'temperature',
        'weight', 'pulse', 'diagnosis', 'prescribed_treatment',
        'notes', 'consultation_fee'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function claim()
    {
        return $this->hasOne(Claim::class);
    }
}