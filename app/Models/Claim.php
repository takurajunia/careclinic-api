<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = [
        'consultation_id', 'patient_id', 'claim_number',
        'medical_aid_provider', 'medical_aid_number',
        'amount_claimed', 'status', 'date_of_service'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}