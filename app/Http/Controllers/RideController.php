<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\RideRequest;
use Illuminate\Http\Request;

class RideController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1. FORMULÁRIO PARA CRIAR BOLEIA (MOTORISTA)
    |--------------------------------------------------------------------------
    */
    public function addRide()
    {
        $pageTitle = 'Criar Boleia CESAE';
        return view('rides.add_ride', compact('pageTitle'));
    }


    /*
    |--------------------------------------------------------------------------
    | 2. SALVAR BOLEIA NO BANCO
    |--------------------------------------------------------------------------
    | Valida, cria e redireciona.
    */
    public function storeRide(Request $request)
    {
        $request->validate([
            'pickup_location'      => 'required|string|max:100',
            'destination_location' => 'required|string|max:100',
            'departure_date'       => 'required|date|after:tomorrow',
            'departure_time'       => 'required|date_format:H:i',
            'total_seats'          => 'required|integer|min:1|max:8'
        ]);

        Ride::create([
            'driver_id'           => auth()->id(),
            'pickup_location'     => $request->pickup_location,
            'destination_location'=> $request->destination_location,
            'departure_date'      => $request->departure_date,
            'departure_time'      => $request->departure_time,
            'total_seats'         => $request->total_seats,
            'available_seats'     => $request->total_seats,
            'observations'        => $request->observations ?? null,
            'status'              => 'active'
        ]);

        return redirect()->route('rides.all')
            ->with('success', 'Boleia criada com sucesso.');
    }


    /*
    |--------------------------------------------------------------------------
    | 3. LISTAR BOLEIAS (ROLE-BASED)
    |--------------------------------------------------------------------------
    */
    public function allRides()
    {
        if (auth()->user()->role === 'driver') {

            // Motorista vê apenas as próprias boleias
            $rides = Ride::with('driver')
                ->where('driver_id', auth()->id())
                ->get();

        } elseif (auth()->user()->role === 'passenger') {

            // Passageiro vê boleias do mesmo pickup_location
            $rides = Ride::with('driver')
                ->where('pickup_location', auth()->user()->pickup_location)
                ->get();

        } else {

            // Admin vê tudo
            $rides = Ride::with('driver')
                ->latest()
                ->get();
        }

        return view('rides.all_rides', compact('rides'));
    }


    /*
    |--------------------------------------------------------------------------
    | 4. VER DETALHES DE UMA BOLEIA
    |--------------------------------------------------------------------------
    */
    public function viewRide(Ride $ride)
    {
        $ride->load([
            'driver:id,name,email',
            'requests.passenger:id,name,email'
        ]);

        return view('rides.view_ride', compact('ride'));
    }


    /*
    |--------------------------------------------------------------------------
    | 5. FORMULÁRIO DE EDIÇÃO (MOTORISTA)
    |--------------------------------------------------------------------------
    */
    public function editRide(Ride $ride)
    {
        if (auth()->id() !== $ride->driver_id) {
            abort(403, 'Acesso negado');
        }

        $pageTitle = "Editar Boleia #{$ride->id}";
        return view('rides.edit_ride', compact('ride', 'pageTitle'));
    }


    /*
    |--------------------------------------------------------------------------
    | 6. ATUALIZAR BOLEIA
    |--------------------------------------------------------------------------
    */
    public function updateRide(Request $request, Ride $ride)
    {
        if (auth()->id() !== $ride->driver_id) {
            abort(403, 'Acesso negado');
        }

        $request->validate([
            'pickup_location'      => 'required|string|max:100',
            'destination_location' => 'required|string|max:100',
            'departure_date'       => 'required|date|after:today',
            'departure_time'       => 'required|date_format:H:i',
            'available_seats'      => 'required|integer|min:1|max:8'
        ]);

        $ride->update([
            'pickup_location'      => $request->pickup_location,
            'destination_location' => $request->destination_location,
            'departure_date'       => $request->departure_date,
            'departure_time'       => $request->departure_time,
            'available_seats'      => $request->available_seats,
            'observations'         => $request->observations ?? null,
        ]);

        return redirect()->route('rides.view', $ride)
            ->with('success', 'Boleia atualizada com sucesso.');
    }


    /*
    |--------------------------------------------------------------------------
    | 7. PASSAGEIRO: PEDIR BOLEIA
    |--------------------------------------------------------------------------
    */
    public function requestRide(Request $request)
    {
        if (!auth()->check() || auth()->user()->role !== 'passenger') {
            abort(403);
        }

        $data = $request->validate([
            'ride_id' => 'required|exists:rides,id',
        ]);

        $ride = Ride::findOrFail($data['ride_id']);

        if ($ride->status !== 'active' || $ride->available_seats <= 0) {
            return redirect()->route('rides.view', $ride)
                ->with('error', 'Esta boleia já não está disponível.');
        }

        $jaPediu = RideRequest::where('ride_id', $ride->id)
            ->where('passenger_id', auth()->id())
            ->exists();

        if ($jaPediu) {
            return redirect()->route('rides.view', $ride)
                ->with('info', 'Você já pediu esta boleia.');
        }

        RideRequest::create([
            'ride_id'      => $ride->id,
            'passenger_id' => auth()->id(),
            'message'      => null,
            'status'       => 'pending',
        ]);

        return redirect()->route('rides.view', $ride)
            ->with('success', 'Pedido enviado. Aguarde aprovação.');
    }


    /*
    |--------------------------------------------------------------------------
    | 8. LISTAR PEDIDOS (PASSAGEIRO, MOTORISTA, ADMIN)
    |--------------------------------------------------------------------------
    */
    public function myRequests()
    {
        if (auth()->user()->role === 'passenger') {

            $requests = RideRequest::with(['ride', 'ride.driver'])
                ->where('passenger_id', auth()->id())
                ->orderByDesc('created_at')
                ->get();

            $pageTitle = 'Minhas boleias pedidas';

        } elseif (auth()->user()->role === 'driver') {

            $requests = RideRequest::with(['ride', 'passenger'])
                ->whereHas('ride', fn($q) =>
                    $q->where('driver_id', auth()->id())
                )
                ->orderByDesc('created_at')
                ->get();

            $pageTitle = 'Pedidos recebidos';

        } else {

            $requests = RideRequest::with(['ride', 'passenger', 'ride.driver'])
                ->orderByDesc('created_at')
                ->get();

            $pageTitle = 'Todos os pedidos';
        }

        return view('rides.my_requests', compact('requests', 'pageTitle'));
    }


    /*
    |--------------------------------------------------------------------------
    | 9. PASSAGEIRO: CANCELAR PEDIDO
    |--------------------------------------------------------------------------
    */
    public function cancelRequest($id)
    {
        $request = RideRequest::findOrFail($id);

        if (auth()->id() !== $request->passenger_id) {
            abort(403);
        }

        $ride = Ride::findOrFail($request->ride_id);

        if ($request->status === 'accepted') {

            $ride->available_seats += 1;

            if ($ride->status === 'full') {
                $ride->status = 'active';
            }

            $ride->save();
        }

        $request->delete();

        return back()->with('success', 'Pedido cancelado.');
    }


    /*
    |--------------------------------------------------------------------------
    | 10. MOTORISTA: ACEITAR PEDIDO
    |--------------------------------------------------------------------------
    */
    public function acceptRequest($id)
    {
        $rideRequest = RideRequest::findOrFail($id);
        $ride = Ride::findOrFail($rideRequest->ride_id);

        if (auth()->id() !== $ride->driver_id) {
            abort(403);
        }

        if ($ride->status !== 'active' || $ride->available_seats <= 0) {
            return back()->with('error', 'Boleia indisponível.');
        }

        $rideRequest->update([
            'status'      => 'accepted',
            'teams_link'  => 'https://teams.microsoft.com/l/meetup-join/XXXX'
        ]);

        $ride->available_seats--;

        if ($ride->available_seats <= 0) {
            $ride->status = 'full';
        }

        $ride->save();

        return back()->with('success', 'Pedido aceito.');
    }


    /*
    |--------------------------------------------------------------------------
    | 11. MOTORISTA: REJEITAR PEDIDO
    |--------------------------------------------------------------------------
    */
    public function rejectRequest($id)
    {
        $rideRequest = RideRequest::findOrFail($id);
        $ride = Ride::findOrFail($rideRequest->ride_id);

        if (auth()->id() !== $ride->driver_id) {
            abort(403);
        }

        $rideRequest->update(['status' => 'rejected']);

        return back()->with('info', 'Pedido rejeitado.');
    }


    /*
    |--------------------------------------------------------------------------
    | 12. MOTORISTA: APAGAR BOLEIA
    |--------------------------------------------------------------------------
    */
    public function deleteRide(Ride $ride)
    {
        if (auth()->id() !== $ride->driver_id) {
            abort(403);
        }

        $ride->delete();

        return redirect()->route('rides.all')
            ->with('success', 'Boleia excluída.');
    }
}
