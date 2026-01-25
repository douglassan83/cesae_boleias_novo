<?php
// ========================================
// CONTROLLER BOLEIAS CESAE DIGITAL
// Motorista cria → Passageiro pede → Admin vê tudo
// ========================================

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
    // Laravel VALIDATE: para se dados errados (iniciante: @error blade mostra)
    public function storeRide(Request $request)
    {
        // Valida CAMPOS do form (required = obrigatório)
        $request->validate([
            'pickup_location' => 'required|string|max:100',      // Não vazio, máx 100 chars
            'destination_location' => 'required|string|max:100',
            'departure_date' => 'required|date|after:tomorrow',  // Amanhã+
            'departure_time' => 'required|date_format:H:i',      // HH:MM
            'total_seats' => 'required|integer|min:1|max:8'      // 1-8 lugares
        ]);

        // CRIA Ride no banco (fillable no Model permite estes campos)
        Ride::create([
            'driver_id' => auth()->id(),                        // User logado
            'pickup_location' => $request->pickup_location,
            'destination_location' => $request->destination_location,
            'departure_date' => $request->departure_date,
            'departure_time' => $request->departure_time,
            'total_seats' => $request->total_seats,
            'available_seats' => $request->total_seats,         // Mesma qtd inicial
            'observations' => $request->observations ?? null,   // Opcional (?? = ou null)
            'status' => 'active'                                // 'active' = disponível
        ]);

        // Redireciona com mensagem flash (aparece 1x)
        return redirect()->route('rides.all')->with('success', '✅ Boleia criada!');
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
            'pickup_location' => 'required|string|max:100',
            'destination_location' => 'required|string|max:100',
            'departure_date' => 'required|date|after:today',
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
        return redirect()->route('rides.view', $ride)
            ->with('success', '✅ Boleia atualizada!');
    }
}
