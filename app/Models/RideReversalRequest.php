<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RideReversalRequest extends Model
{
    protected $fillable = [
        'ride_request_id',
        'passenger_id',
        'status',
        'admin_notes'
    ];

    // Relação com o pedido original
    public function rideRequest()
    {
        return $this->belongsTo(RideRequest::class);
    }

    // Relação com o passageiro
    public function passenger()
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }
}
