<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\RideRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
            'pickup_location' => 'required|string|max:100', // Não vazio, máx 100 chars
            'destination_location' => 'required|string|max:100',
            'departure_date' => ['required', 'date', 'after_or_equal:today'], //
            'departure_time' => 'required|date_format:H:i', // HH:MM
            'total_seats' => 'required|integer|min:1|max:8' // 1-8 lugares
        ]);

        // CRIA Ride no banco (fillable no Model permite estes campos)
        Ride::create([
            'driver_id' => auth()->id(), // User logado
            'pickup_location' => $request->pickup_location,
            'destination_location' => $request->destination_location,
            'departure_date' => $request->departure_date,
            'departure_time' => $request->departure_time,
            'total_seats' => $request->total_seats,
            'available_seats' => $request->total_seats, // Mesma qtd inicial
            'observations' => $request->observations ?? null, // Opcional
            'status' => 'active' // 'active' = disponível
        ]);

        // Redireciona com mensagem flash (aparece 1x)
        return redirect()->route('rides.all')->with(
            'success',
            'Boleia criada!'
        );
    }

    // 3. LISTA BOLEIAS por ROLE
    public function allRides()
    {
        // with('driver') = carrega relação EAGER (iniciante: evita N+1 queries)
        if (auth()->user()->role == 'driver') {
            // Motorista: só SUAS boleias
            $rides = Ride::with('driver')->where(
                'driver_id',
                auth()->id()
            )->get();
        } elseif (auth()->user()->role == 'passenger') {

            $rides = Ride::with('driver')
                ->where('pickup_location', auth()->user()->pickup_location)
                ->get()
                ->filter(function ($ride) {

                    // Se a boleia está expirada → esconder
                    if ($ride->isExpired()) {
                        return false;
                    }

                    return true;
                });
        }


        // } elseif (auth()->user()->role == 'passenger') {
        //     // Passageiro: só do SEU pickup_location (perfil)
        //     $rides = Ride::with('driver')
        //             ->where(
        //                 'pickup_location',
        //                 auth()->user()->pickup_location
        //             )
        //             ->get();
        else {
            // Admin: TUDO (latest = mais recentes primeiro)
            $rides = Ride::with([
                'driver',
                'rideRequests'
            ])->get();
        }

        return view(
            'rides.all_rides',
            compact('rides')
        );
    }

    public function viewRide(Ride $ride)
{
    // Carregar relações
    $ride->load([
        'driver:id,name,email',
        'rideRequests.passenger:id,name,email'
    ]);

    $rejeitado = null;

    // Se for passageiro
    if (auth()->check() && auth()->user()->role === 'passenger') {

        // Verifica se o passageiro pediu esta boleia
        $pedido = RideRequest::where('ride_id', $ride->id)
            ->where('passenger_id', auth()->id())
            ->first();

        // 1. Se a boleia está expirada e o passageiro NÃO pediu → bloquear
        if ($ride->isExpired() && !$pedido) {
            return redirect()->route('rides.all')
                ->with('info', 'Esta boleia já não está disponível.');
        }

        // 2. Se pediu mas foi REJEITADO → bloquear também
        if ($ride->isExpired() && $pedido && $pedido->status === 'rejected') {
            return redirect()->route('rides.all')
                ->with('info', 'Esta boleia expirou e o seu pedido não foi aceite.');
        }

        // 3. Se pediu e foi ACEITO → pode ver até a hora da partida
        if ($pedido && $pedido->status === 'accepted') {
            // OK, segue normalmente
        }

        // 4. Se pediu mas ainda está pendente → pode ver até expirar
        if ($pedido && $pedido->status === 'pending') {
            // OK, segue normalmente
        }

        // Se o pedido foi rejeitado, enviar para a view (para mostrar botão de reversão)
        if ($pedido && $pedido->status === 'rejected') {
            $rejeitado = $pedido;
        }
    }

    // ADMIN: contar pedidos pendentes
    $pendingCount = 0;
    if (auth()->check() && auth()->user()->role === 'admin') {
        $pendingCount = \App\Models\RideReversalRequest::where('status', 'pending')->count();
    }

    return view('rides.view_ride', compact('ride', 'rejeitado', 'pendingCount'));
}



    // 5. MOTORISTA: Abre form EDITAR BOLEIA
    public function editRide(Ride $ride)
    {
        // Só o dono (driver) pode editar
        if (auth()->id() != $ride->driver_id) {
            abort(
                403,
                'Acesso negado'
            );
        }

        $pageTitle = "Editar Boleia #{$ride->id}";
        return view(
            'rides.edit_ride',
            compact(
                'ride',
                'pageTitle'
            )
        );
    }

    // 6. MOTORISTA: ATUALIZA boleia
    public function updateRide(Request $request, Ride $ride)
    {
        // Só o dono pode editar
        if (auth()->id() != $ride->driver_id) {
            abort(
                403,
                'Acesso negado'
            );
        }

        // Valida CAMPOS (iguais ao storeRide, nomes DB CESAE)
        $request->validate([
            'pickup_location' => 'required|string|max:100',
            'destination_location' => 'required|string|max:100',
            'departure_date' => ['required', 'date', 'after_or_equal:today'],
            'departure_time' => 'required|date_format:H:i',
            'available_seats' => 'required|integer|min:1|max:8'
        ]);

        // Atualiza NO BANCO (fillable permite)
        $ride->update([
            'pickup_location' => $request->pickup_location,
            'destination_location' => $request->destination_location,
            'departure_date' => $request->departure_date,
            'departure_time' => $request->departure_time,
            'available_seats' => $request->available_seats,
            'observations' => $request->observations ?? null,
        ]);

        // Volta para VIEW da boleia com sucesso
        return redirect()->route(
            'rides.view',
            $ride
        )
            ->with(
                'success',
                '✅ Boleia atualizada!'
            );
    }

    // 7. PASSAGEIRO: CRIAR PEDIDO DE BOLEIA
    public function requestRide(Request $request)
    {
        // Garantir que está autenticado e é passageiro
        if (
            !auth()->check() ||
            auth()->user()->role !== 'passenger'
        ) {
            abort(403);
        }

        // Validar dados mínimos (id da boleia)
        $data = $request->validate([
            'ride_id' => 'required|exists:rides,id',
        ]);

        // Buscar a boleia
        $ride = Ride::findOrFail($data['ride_id']);

        // Verificar se ainda está ativa e com lugares
        if (
            $ride->status !== 'active' ||
            $ride->available_seats <= 0
        ) {
            return redirect()
                ->route(
                    'rides.view',
                    $ride
                )
                ->with(
                    'error',
                    'Esta boleia já não está disponível.'
                );
        }

        // Verificar se passageiro já pediu esta boleia
        $existingRequest = RideRequest::where(
            'ride_id',
            $ride->id
        )
            ->where(
                'passenger_id',
                auth()->id()
            )
            ->first();

        if ($existingRequest) {
            // Se foi rejeitado, não pode pedir de novo
            if (
                $existingRequest->status === 'rejected'
            ) {
                return redirect()
                    ->route(
                        'rides.view',
                        $ride
                    )
                    ->with(
                        'error',
                        'O motorista recusou o teu pedido para esta boleia. Não podes pedir de novo.'
                    );
            }

            // Se tem outro pedido (pending ou accepted)
            return redirect()
                ->route(
                    'rides.view',
                    $ride
                )
                ->with(
                    'info',
                    'Já existe um pedido para esta boleia.'
                );
        }

        // Criar pedido de boleia
        RideRequest::create([
            'ride_id' => $ride->id,
            'passenger_id' => auth()->id(),
            'message' => null,
            'status' => 'pending',
        ]);

        return redirect()
            ->route(
                'rides.view',
                $ride
            )
            ->with(
                'success',
                'Pedido de boleia enviado. Aguarde aprovação do motorista.'
            );
    }

    // 8. LISTAR PEDIDOS DE BOLEIA (PASSAGEIRO OU MOTORISTA)
    public function myRequests()
    {
        $requests = collect();
        $weeklyRides = collect();

        // SEMANA: DOMINGO a SÁBADO (1x só)
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::SUNDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SATURDAY);

        // PASSAGEIRO: pedidos de boleias da semana
        if (auth()->user()->role === 'passenger') {
            $requests = RideRequest::with(['ride.driver'])
                ->where('passenger_id', auth()->id())
                ->whereHas('ride', function ($q) use ($startOfWeek, $endOfWeek) {
                    $q->whereBetween('departure_date', [
                        $startOfWeek->toDateString(),
                        $endOfWeek->toDateString()
                    ]);
                })

                ->orderBy('created_at')  // ← VOLTA ao original (funciona!)
                ->get();

            $pageTitle = 'Meus pedidos da semana';
        }
        // MOTORISTA: igual (já correto)
        elseif (auth()->user()->role === 'driver') {
            $weeklyRides = Ride::with('driver')
                ->where('driver_id', auth()->id())
                ->whereBetween('departure_date', [
                    $startOfWeek->toDateString(),
                    $endOfWeek->toDateString()
                ])

                ->orderBy('departure_date')           // ← MENOR → MAIOR
                ->orderBy('departure_time')           // ← HORA
                ->get();

            $pageTitle = 'Minhas boleias da semana';
        }
        // ADMIN (mantém igual)
        else {
            $requests = RideRequest::with(['ride', 'passenger', 'ride.driver'])
                ->orderByDesc('created_at')
                ->get();
            $pageTitle = 'Todos os pedidos de boleia';
        }

        return view('rides.my_requests', compact('requests', 'pageTitle', 'weeklyRides'));
    }

    // MOTORISTA: ACEITAR pedido
    public function acceptRequest($id)
    {
        // Garantir que está autenticado
        if (!auth()->check()) {
            abort(
                403,
                'Não autenticado'
            );
        }

        // 1. Buscar o pedido na tabela ride_requests
        $rideRequest = RideRequest::findOrFail($id);

        // 2. Buscar a boleia ligada a este pedido
        $ride = Ride::findOrFail($rideRequest->ride_id);

        // 3. Só o motorista dono da boleia pode aceitar
        if (
            auth()->id() !== $ride->driver_id
        ) {
            abort(
                403,
                'Apenas o motorista pode aceitar pedidos'
            );
        }

        // 4. Verificar se boleia ainda está disponível
        if (
            $ride->status !== 'active' ||
            $ride->available_seats <= 0
        ) {
            return back()->with(
                'error',
                'Boleia indisponível para aceitar pedido.'
            );
        }

        // 5. Atualizar pedido para accepted
        $rideRequest->status = 'accepted';
        $rideRequest->teams_link = 'https://teams.microsoft.com/l/meetup-join/XXXX';
        $rideRequest->save();

        // 6. Atualizar lugares disponíveis
        $ride->available_seats = $ride->available_seats - 1;
        if (
            $ride->available_seats <= 0
        ) {
            $ride->status = 'full';
        }
        $ride->save();

        return back()->with(
            'success',
            'Pedido aceito com sucesso.'
        );
    }

    // MOTORISTA: REJEITAR pedido
    public function rejectRequest($id)
    {
        // Garantir que está autenticado
        if (!auth()->check()) {
            abort(
                403,
                'Não autenticado'
            );
        }

        // 1. Buscar o pedido
        $rideRequest = RideRequest::findOrFail($id);

        // 2. Buscar a boleia
        $ride = Ride::findOrFail($rideRequest->ride_id);

        // 3. Só o motorista dono da boleia pode rejeitar
        if (
            auth()->id() !== $ride->driver_id
        ) {
            abort(
                403,
                'Apenas o motorista pode rejeitar pedidos'
            );
        }

        // 4. Atualizar pedido para rejected
        $rideRequest->status = 'rejected';
        $rideRequest->save();

        return back()->with(
            'info',
            'Pedido rejeitado.'
        );
    }

    // MOTORISTA: CANCELAR pedido (NOVO MÉTODO - CORREÇÃO DO ERRO)
    public function cancelRequest($id)
    {
        // Garantir que está autenticado
        if (!auth()->check()) {
            abort(
                403,
                'Não autenticado'
            );
        }

        // Buscar o pedido
        $rideRequest = RideRequest::findOrFail($id);

        // Só o passageiro dono do pedido OU motorista pode cancelar
        if (
            auth()->id() !== $rideRequest->passenger_id &&
            auth()->id() !== $rideRequest->ride->driver_id
        ) {
            abort(
                403,
                'Apenas o passageiro ou motorista podem cancelar'
            );
        }

        // Se já foi aceito, motorista pode cancelar (liberar lugar)
        if ($rideRequest->status === 'accepted') {
            $ride = $rideRequest->ride;
            $ride->available_seats = $ride->available_seats + 1;
            $ride->status = 'active';
            $ride->save();
        }

        // Cancelar o pedido
        $rideRequest->delete();

        return back()->with(
            'success',
            'Pedido cancelado com sucesso.'
        );
    }

    // MOTORISTA: APAGAR boleia
    public function deleteRide(Ride $ride)
    {
        // Garantir que está autenticado
        if (!auth()->check()) {
            abort(
                403,
                'Não autenticado'
            );
        }

        // Só o dono pode apagar
        if (
            auth()->id() !== $ride->driver_id
        ) {
            abort(
                403,
                'Apenas o motorista da boleia pode apagá-la'
            );
        }

        $ride->delete(); // apaga da tabela rides

        return redirect()
            ->route('rides.all')
            ->with(
                'success',
                'Boleia excluída com sucesso.'
            );
    }


    // PASSAGEIRO: solicitar reversão de pedido recusado
    public function requestReversal(Request $request)
    {
        // Validar ID do pedido recusado
        $request->validate([
            'ride_request_id' => 'required|exists:ride_requests,id'
        ]);

        $rideRequest = RideRequest::findOrFail($request->ride_request_id);

        // Garantir que o pedido pertence ao passageiro logado
        if ($rideRequest->passenger_id !== auth()->id()) {
            abort(403, 'Acesso negado.');
        }

        // Garantir que o pedido está realmente rejeitado
        if ($rideRequest->status !== 'rejected') {
            return back()->with('error', 'Este pedido não está rejeitado.');
        }

        // Evitar duplicação
        if (\App\Models\RideReversalRequest::where('ride_request_id', $rideRequest->id)
            ->where('status', 'pending')
            ->exists()
        ) {
            return back()->with('warning', 'Já existe um pedido de reversão pendente.');
        }

        // Criar pedido
        \App\Models\RideReversalRequest::create([
            'ride_request_id' => $rideRequest->id,
            'passenger_id' => auth()->id(),
            'status' => 'pending'
        ]);

        return back()->with('success', 'Pedido enviado ao suporte. Aguarde análise do administrador.');
    }


    // ADMIN: aprovar reversão
    public function approveReversal($id)
    {
        $reversal = \App\Models\RideReversalRequest::findOrFail($id);

        // Atualizar pedido original para pending
        $rideRequest = $reversal->rideRequest;
        $rideRequest->status = 'pending';
        $rideRequest->save();

        // Atualizar reversão
        $reversal->status = 'approved';
        $reversal->admin_notes = 'Reversão aprovada pelo administrador.';
        $reversal->save();

        return back()->with('success', 'Pedido revertido com sucesso. O passageiro pode pedir novamente.');
    }


    // ADMIN: rejeitar reversão
    public function rejectReversal($id)
    {
        $reversal = \App\Models\RideReversalRequest::findOrFail($id);

        $reversal->status = 'rejected';
        $reversal->admin_notes = 'Pedido de reversão rejeitado pelo administrador.';
        $reversal->save();

        return back()->with('info', 'Pedido de reversão rejeitado.');
    }


    // ADMIN: listar pedidos de reversão
    public function adminReversals()
    {
        // Apenas admin pode ver
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Apenas administradores podem aceder.');
        }

        // Buscar todos os pedidos de reversão
        $reversals = \App\Models\RideReversalRequest::with(['passenger', 'rideRequest'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reversals.index', compact('reversals'));
    }
}
