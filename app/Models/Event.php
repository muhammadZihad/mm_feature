<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'eventable_type', 'eventable_id'];

    public function eventable()
    {
        return $this->morphTo();
    }

    public function actor()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function scopeByMe($query)
    {
        return $query->where('user_id', auth()->id());
    }
}
