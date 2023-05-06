<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public function details()
    {
        return $this->hasMany(ServiceDetail::class);
    }
    public function breaks()
    {
        return $this->hasMany(ServiceBreak::class);
    }
    public function holidays()
    {
        return $this->hasMany(ServiceHoliday::class);
    }
    
}
