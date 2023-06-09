<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = ['slot_id', 'date'];
    public function details()
    {
        return $this->hasMany(BookingDetail::class, 'booking_id');
    }

    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }
}
