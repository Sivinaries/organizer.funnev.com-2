<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TicketController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $tickets = $user->level === 'Admin'
            ? Ticket::latest()->get()
            : Ticket::whereHas('event', fn($query) => $query->where('user_id', $user->id))
                ->latest()
                ->get();

        return view('ticket', compact('tickets'));
    }

    public function create()
    {
        $events = Auth::user()->events;
        return view('addticket', compact('events'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'type' => 'required|string|max:255',
            'desc' => 'required|string|max:255',
            'price' => 'required|numeric',
            'pcs' => 'required|integer|min:1',
            'event_id' => 'required|exists:events,id',
        ]);

        $ticket = Ticket::create($data);

        Act::create([
            'user_id' => $user->id,
            'action' => 'create_ticket',
            'description' => "User {$user->name} membuat tiket: {$ticket->type} untuk event ID {$ticket->event_id}",
        ]);
        Cache::forget('acts');

        return redirect()->route('tickets')->with('success', 'Ticket successfully registered!');
    }

    public function edit($id, $type)
    {
        $ticket = Ticket::findOrFail($id);

        $events = Event::all();

        return view('editticket', compact('ticket', 'events'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $request->validate([
            'type' => 'required|string|max:255',
            'desc' => 'required|string|max:255',
            'price' => 'required|numeric',
            'pcs' => 'required|integer|min:1',
            'event_id' => 'required|exists:events,id',
        ]);

        $data = $request->only(['type', 'desc', 'price', 'pcs', 'event_id']);

        Ticket::where('id', $id)->update($data);

        Act::create([
            'user_id' => $user->id,
            'action' => 'update_ticket',
            'description' => "User {$user->name} mengubah tiket ID {$id} menjadi tipe {$data['type']}",
        ]);
        Cache::forget('acts');

        return redirect()->route('tickets')->with('success', 'Ticket updated!');
    }

    public function destroy($id)
    {
        $user = Auth::user();

        $ticket = Ticket::findOrFail($id);
        $ticketInfo = $ticket->type;
        $ticket->delete();

        Act::create([
            'user_id' => $user->id,
            'action' => 'delete_ticket',
            'description' => "User {$user->name} menghapus tiket: {$ticketInfo}",
        ]);
        Cache::forget('acts');

        return redirect()->route('tickets')->with('success', 'Ticket removed!');
    }
}
