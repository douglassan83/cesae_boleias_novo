<?php
// ========================================
// MODEL RIDE - CESAE BOLEIAS ✅
// ========================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    use HasFactory;

    /**
     * CAMPOS QUE ACEITA do Controller/Form
     * SEM ISSO = mass assignment bloqueado = NULL no banco!
     */
    protected $fillable = [
        'driver_id',
        'pickup_location',
        'destination_location',
        'departure_date',
        'departure_time',
        'total_seats',
        'available_seats',
        'observations',
        'status'
    ];

    /**
     * Formata datas automaticamente
     * $ride->departure_date = Carbon object
     */
    protected $casts = [
        'departure_date' => 'date',
        'departure_time' => 'datetime:H:i'
    ];

    /**
     * Relação MOTORISTA (1 ride = 1 driver)
     * Laravel carrega com $ride->driver->name
     */
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * Relação PEDIDOS (1 ride = N pedidos)
     * Futuro: $ride->requests
     */
    public function requests()
    {
    return $this->hasMany(RideRequest::class, 'ride_id');
}

}
