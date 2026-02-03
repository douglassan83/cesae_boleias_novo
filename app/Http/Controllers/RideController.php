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
<<<<<<< HEAD
            'pickup_location'      => 'required|string|max:100',
            'destination_location' => 'required|string|max:100',
            'departure_date'       => 'required|date|after:tomorrow',
            'departure_time'       => 'required|date_format:H:i',
            'total_seats'          => 'required|integer|min:1|max:8'
=======
            'pickup_location' => 'required|string|max:100',      // Não vazio, máx 100 chars
            'destination_location' => 'required|string|max:100',
            'departure_date' => 'required|date|after:tomorrow', // Amanhã+
            'departure_time' => 'required|date_format:H:i',     // HH:MM
            'total_seats' => 'required|integer|min:1|max:8'  // 1-8 lugares
>>>>>>> 6825c959b3b7cef6d9f2b4f679a031f24e8c608b
        ]);

        Ride::create([
<<<<<<< HEAD
            'driver_id'           => auth()->id(),
            'pickup_location'     => $request->pickup_location,
            'destination_location'=> $request->destination_location,
            'departure_date'      => $request->departure_date,
            'departure_time'      => $request->departure_time,
            'total_seats'         => $request->total_seats,
            'available_seats'     => $request->total_seats,
            'observations'        => $request->observations ?? null,
            'status'              => 'active'
=======
            'driver_id' => auth()->id(),                 // User logado
            'pickup_location' => $request->pickup_location,
            'destination_location' => $request->destination_location,
            'departure_date' => $request->departure_date,
            'departure_time' => $request->departure_time,
            'total_seats' => $request->total_seats,
            'available_seats' => $request->total_seats,        // Mesma qtd inicial
            'observations' => $request->observations ?? null, // Opcional
            'status' => 'active'                      // 'active' = disponível
>>>>>>> 6825c959b3b7cef6d9f2b4f679a031f24e8c608b
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
<<<<<<< HEAD
            'requests.passenger:id,name,email'
=======
            'rideRequests.passenger:id,name,email'
>>>>>>> 6825c959b3b7cef6d9f2b4f679a031f24e8c608b
        ]);

        $pedido = RideRequest::where('ride_id', $ride->id)
            ->where('passenger_id', auth()->id())
            ->whereIn('status', ['pending', 'accepted'])
            ->first();




        return view('rides.view_ride', compact('ride', 'pedido'));
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
<<<<<<< HEAD
            'pickup_location'      => 'required|string|max:100',
            'destination_location' => 'required|string|max:100',
            'departure_date'       => 'required|date|after:today',
            'departure_time'       => 'required|date_format:H:i',
            'available_seats'      => 'required|integer|min:1|max:8'
=======
            'pickup_location' => 'required|string|max:100',
            'destination_location' => 'required|string|max:100',
            'departure_date' => 'required|date|after:today',
            'departure_time' => 'required|date_format:H:i',
            'available_seats' => 'required|integer|min:1|max:8'
>>>>>>> 6825c959b3b7cef6d9f2b4f679a031f24e8c608b
        ]);

        $ride->update([
<<<<<<< HEAD
            'pickup_location'      => $request->pickup_location,
            'destination_location' => $request->destination_location,
            'departure_date'       => $request->departure_date,
            'departure_time'       => $request->departure_time,
            'available_seats'      => $request->available_seats,
            'observations'         => $request->observations ?? null,
=======
            'pickup_location' => $request->pickup_location,
            'destination_location' => $request->destination_location,
            'departure_date' => $request->departure_date,
            'departure_time' => $request->departure_time,
            'available_seats' => $request->available_seats,
            'observations' => $request->observations ?? null,
>>>>>>> 6825c959b3b7cef6d9f2b4f679a031f24e8c608b
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
            'ride_id' => $ride->id,
            'passenger_id' => auth()->id(),
            'message' => null,
            'status' => 'pending',
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

<<<<<<< HEAD
=======
        }

        // Buscar o pedido
        // MOTORISTA: pedidos recebidos nas boleias dele
        elseif (auth()->user()->role === 'driver') {
>>>>>>> 6825c959b3b7cef6d9f2b4f679a031f24e8c608b
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

<<<<<<< HEAD

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
=======
    // PASSAGEIRO: CANCELAR pedido de boleia
    public function cancelRequest($id)
    {
        $request = RideRequest::findOrFail($id);

        // Só o passageiro dono pode cancelar
        if (auth()->id() !== $request->passenger_id) {
            abort(403);
>>>>>>> 6825c959b3b7cef6d9f2b4f679a031f24e8c608b
        }

        // Buscar a boleia
        $ride = Ride::findOrFail($request->ride_id);

        // 👉 SE O PEDIDO ESTAVA ACEITE, DEVOLVE O LUGAR
        if ($request->status === 'accepted') {
            $ride->available_seats += 1;

            // Se estava cheia, volta a ativa
            if ($ride->status === 'full') {
                $ride->status = 'active';
            }

            $ride->save();
        }

        // Apagar o pedido
        $request->delete();

        return back()->with('success', 'Pedido de boleia cancelado.');
    }


    // MOTORISTA: ACEITAR pedido
    public function acceptRequest($id)
    {
        // 1. Buscar o pedido na tabela ride_requests
        $rideRequest = RideRequest::findOrFail($id);

        // 2. Buscar a boleia ligada a este pedido
        $ride = Ride::findOrFail($rideRequest->ride_id);

        // 3. Só o motorista dono da boleia pode aceitar
        if (auth()->id() !== $ride->driver_id) {
            abort(403);
        }

        // 4. Verificar se boleia ainda está disponível
        if ($ride->status !== 'active' || $ride->available_seats <= 0) {
            return back()->with('error', 'Boleia indisponível para aceitar pedido.');
        }

        // 5. Atualizar pedido para accepted
        $rideRequest->status = 'accepted';
        $rideRequest->teams_link = 'https://teams.microsoft.com/l/meetup-join/XXXX';
        $rideRequest->save();

        // 6. Atualizar lugares disponíveis
        $ride->available_seats = $ride->available_seats - 1;
        if ($ride->available_seats <= 0) {
            $ride->status = 'full';
        }
        $ride->save();

<<<<<<< HEAD
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
=======
        return back()->with('success', 'Pedido aceito com sucesso.');
    }

>>>>>>> 6825c959b3b7cef6d9f2b4f679a031f24e8c608b

        if (auth()->id() !== $ride->driver_id) {
            abort(403);
        }

<<<<<<< HEAD
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
=======
    // MOTORISTA: REJEITAR pedido
    public function rejectRequest($id)
    {
        // 1. Buscar o pedido
        $rideRequest = RideRequest::findOrFail($id);

        // 2. Buscar a boleia
        $ride = Ride::findOrFail($rideRequest->ride_id);

        // 3. Só o motorista dono da boleia pode rejeitar
        if (auth()->id() !== $ride->driver_id) {
            abort(403);
        }

        // 4. Atualizar pedido para rejected
        $rideRequest->status = 'rejected';
        $rideRequest->save();
        return back()->with('info', 'Pedido rejeitado.');
    }

    // MOTORISTA: APAGAR boleia
    public function deleteRide(Ride $ride)
    {
        // Só o dono pode apagar
        if (auth()->id() !== $ride->driver_id) {
            abort(403);
        }

        $ride->delete();  // apaga da tabela rides

        return redirect()
            ->route('rides.all')
            ->with('success', 'Boleia excluída com sucesso.');
>>>>>>> 6825c959b3b7cef6d9f2b4f679a031f24e8c608b
    }
}
