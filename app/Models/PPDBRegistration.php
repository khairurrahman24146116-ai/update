<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PPDBRegistration extends Model
{
    protected $table = 'p_p_d_b_registrations';

    protected $fillable = ['wave_id', 'name', 'nisn', 'phone', 'email', 'address', 'status', 'notes'];

    public function wave(): BelongsTo
    {
        return $this->belongsTo(PPDBWave::class, 'wave_id');
    }
}
