<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;

    public function detail()
    {
        return $this->belongsTo(ServiceDetail::class, 'service_detail_id');
    }
}
