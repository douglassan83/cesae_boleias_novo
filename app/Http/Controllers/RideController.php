<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\RideRequest;
use Illuminate\Http\Request;

class RideController extends Controller
{
    // 1. MOTORISTA: Abre form CRIAR BOLEIA
    // Passa título para blade (iniciante: compact() = variáveis para view)
    public function addRide()
    {

        $pageTitle = 'Criar Boleia CESAE';
        return view('rides.add_ride', compact('pageTitle'));
    }


    // 2. MOTORISTA: SALVA boleia (valida + cria Ride)
    // Laravel VALIDATE: para se dados errados ( @error blade mostra)
    public function storeRide(Request $request)
    {
        // Valida CAMPOS do form (required = obrigatório)
        $request->validate([
            'pickup_location'     => 'required|string|max:100',      // Não vazio, máx 100 chars
            'destination_location'=> 'required|string|max:100',
            'departure_date'      => 'required|date|after:tomorrow', // Amanhã+
            'departure_time'      => 'required|date_format:H:i',     // HH:MM
            'total_seats'         => 'required|integer|min:1|max:8'  // 1-8 lugares
        ]);

        // CRIA Ride no banco (fillable no Model permite estes campos)
        Ride::create([
            'driver_id'          => auth()->id(),                 // User logado
            'pickup_location'    => $request->pickup_location,
            'destination_location'=> $request->destination_location,
            'departure_date'     => $request->departure_date,
            'departure_time'     => $request->departure_time,
            'total_seats'        => $request->total_seats,
            'available_seats'    => $request->total_seats,        // Mesma qtd inicial
            'observations'       => $request->observations ?? null, // Opcional
            'status'             => 'active'                      // 'active' = disponível
        ]);

        // Redireciona com mensagem flash (aparece 1x)
        return redirect()->route('rides.all')->with('success', 'Boleia criada!');
    }

    // 3. LISTA BOLEIAS por ROLE
    public function allRides()
    {
        // with('driver') = carrega relação EAGER (iniciante: evita N+1 queries)
        if (auth()->user()->role == 'driver') {
            // Motorista: só SUAS boleias
            $rides = Ride::with('driver')->where('driver_id', auth()->id())->get();
        } elseif (auth()->user()->role == 'passenger') {
            // Passageiro: só do SEU pickup_location (perfil)
            $rides = Ride::with('driver')
                ->where('pickup_location', auth()->user()->pickup_location)
                ->get();
        } else {
            // Admin: TUDO (latest = mais recentes primeiro)
            $rides = Ride::with('driver')->latest()->get();
        }

        return view('rides.all_rides', compact('rides'));
    }

    // 4. VER 1 boleia específica (route model binding: Ride $ride = acha por ID)
    public function viewRide(Ride $ride)
    {
        // Carrega relações (iniciante: with() = JOIN otimizado)
        $ride->load('driver');
        $ride->load([
        'driver:id,name,email',
        'rideRequests.passenger:id,name,email'
    ]);

        return view('rides.view_ride', compact('ride'));
    }

    // 5. MOTORISTA: Abre form EDITAR BOLEIA
    public function editRide(Ride $ride)
    {
        // Só o dono (driver) pode editar
        if (auth()->id() != $ride->driver_id) {
            abort(403, 'Acesso negado');
        }

        $pageTitle = "Editar Boleia #{$ride->id}";
        return view('rides.edit_ride', compact('ride', 'pageTitle'));
    }

    // 6. MOTORISTA: ATUALIZA boleia
    public function updateRide(Request $request, Ride $ride)
    {
        // Só o dono pode editar
        if (auth()->id() != $ride->driver_id) {
            abort(403, 'Acesso negado');
        }

        // Valida CAMPOS (iguais ao storeRide, nomes DB CESAE)
        $request->validate([
            'pickup_location'     => 'required|string|max:100',
            'destination_location'=> 'required|string|max:100',
            'departure_date'      => 'required|date|after:today',
            'departure_time'      => 'required|date_format:H:i',
            'available_seats'     => 'required|integer|min:1|max:8'
        ]);

        // Atualiza NO BANCO (fillable permite)
        $ride->update([
            'pickup_location'    => $request->pickup_location,
            'destination_location'=> $request->destination_location,
            'departure_date'     => $request->departure_date,
            'departure_time'     => $request->departure_time,
            'available_seats'    => $request->available_seats,
            'observations'       => $request->observations ?? null,
        ]);

        // Volta para VIEW da boleia com sucesso
        return redirect()->route('rides.view', $ride)
            ->with('success', '✅ Boleia atualizada!');
    }

    // 7. PASSAGEIRO: CRIAR PEDIDO DE BOLEIA
    public function requestRide(Request $request)
    {
        // Garantir que está autenticado e é passageiro
        if (!auth()->check() || auth()->user()->role !== 'passenger') {
            abort(403);
        }

        // Validar dados mínimos (id da boleia)
        $data = $request->validate([
            'ride_id' => 'required|exists:rides,id',
        ]);

        // Buscar a boleia
        $ride = Ride::findOrFail($data['ride_id']);

        // Verificar se ainda está ativa e com lugares
        if ($ride->status !== 'active' || $ride->available_seats <= 0) {
            return redirect()
                ->route('rides.view', $ride)
                ->with('error', 'Esta boleia já não está disponível.');
        }

        // Verificar se passageiro já pediu esta boleia
        $jaPediu = RideRequest::where('ride_id', $ride->id)
            ->where('passenger_id', auth()->id())
            ->exists();

        if ($jaPediu) {
            return redirect()
                ->route('rides.view', $ride)
                ->with('info', 'Já existe um pedido para esta boleia.');
        }

        // Criar pedido (versão simples compatível com DB atual)
        RideRequest::create([
            'ride_id'      => $ride->id,
            'passenger_id' => auth()->id(),
            'message'      => null,
            'status'       => 'pending',
        ]);

        return redirect()
            ->route('rides.view', $ride)
            ->with('success', 'Pedido de boleia enviado. Aguarde aprovação do motorista.');
    }

    // 8. LISTAR PEDIDOS DE BOLEIA (PASSAGEIRO OU MOTORISTA)
    public function myRequests()
    {
        // PASSAGEIRO: pedidos que ele fez
        if (auth()->user()->role === 'passenger') {
            $requests = RideRequest::with(['ride', 'ride.driver'])
                ->where('passenger_id', auth()->id())
                ->orderByDesc('created_at')
                ->get();

            $pageTitle = 'Minhas boleias pedidas';


        }

            // Buscar o pedido
        // MOTORISTA: pedidos recebidos nas boleias dele
        elseif (auth()->user()->role === 'driver') {
            $requests = RideRequest::with(['ride', 'passenger'])
                ->whereHas('ride', function ($q) {
                    $q->where('driver_id', auth()->id());
                })
                ->orderByDesc('created_at')
                ->get();

            $pageTitle = 'Pedidos recebidos nas minhas boleias';
        }

        // ADMIN: vê todos
        else {
            $requests = RideRequest::with(['ride', 'passenger', 'ride.driver'])
                ->orderByDesc('created_at')
                ->get();

            $pageTitle = 'Todos os pedidos de boleia';
        }

        return view('rides.my_requests', compact('requests', 'pageTitle'));
    }

    // PASSAGEIRO: CANCELAR pedido de boleia
    public function cancelRequest($id)
{
    $request = RideRequest::findOrFail($id);

    // Só o passageiro dono pode cancelar
    if (auth()->id() !== $request->passenger_id) {
        abort(403);
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

    return back()->with('success', 'Pedido aceito com sucesso.');
}



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
    if (auth()->id() !== $ride->driver_id ) {
        abort(403);
    }

    $ride->delete();  // apaga da tabela rides

    return redirect()
        ->route('rides.all')
        ->with('success', 'Boleia excluída com sucesso.');
    }
}
