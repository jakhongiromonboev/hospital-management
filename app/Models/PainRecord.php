<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PainRecord extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'area', 'severity', 'description'];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
