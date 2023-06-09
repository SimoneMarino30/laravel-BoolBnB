<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Message;
use App\Models\Apartment;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // * Funzione per visualizzare lista messaggi inviati dai visitatori
    public function index(Request $request, Apartment $apartment, User $user)
    {
        $user = Auth::user();

        $apartment_id = $request->input('apartment_id');

        $messages = Message::whereHas('apartment', function ($query) use ($user) {
            $query->where('user_id', '=', $user->id);
        })->orderBy('created_at', 'desc');

        // SE esiste l'appartamento seleziona i messaggi di quell'appartamento
        if ($apartment_id) {
            $messages = $messages->where('apartment_id', $apartment_id);
        }

        $messages = $messages->paginate(5);

        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message, Apartment $apartment)
    {
        $user = Auth::user();
        $myUserId = $user->id; //id utente

        $myApartments = []; //gli appartamenti dell'utente

        $apartments = Apartment::where("user_id", $myUserId)->get()->toArray();

        foreach ($apartments as $apartment) {
            $myApartments[] = $apartment['id'];
        }

        if (in_array($message->apartment_id, $myApartments)) {

            return view('admin.messages.show', compact('message'));
        }
        abort(403, "accesso non autorizzato");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message $message
     * @return \Illuminate\Http\Response
     */
    // * Funzione per cestinare messaggio
    public function destroy(Message $message)
    {
        $message->delete();
        return to_route('admin.messages.index')
            ->with('message_type', 'danger')
            ->with('message_content', 'Messaggio da ' . $message->email . ' cestinato con successo.');
    }

    /**
     * Display a listing of the trashed message.
     *
     * @return \Illuminate\Http\Response
     */

    // * Funzione per visualizzare lista elementi DB nel cestino
    public function trash(Request $request)
    {
        $trashed_messages = Message::onlyTrashed()->orderBy('updated_at', 'ASC')->paginate(10);
        return view('admin.messages.trash', compact('trashed_messages'));
    }

    /**
     * Restores the specified resource from storage.
     *
     * @param  \App\Models\Int $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Int $id)
    {
        $message = Message::where('id', $id)->onlyTrashed()->first();
        $message->restore();
        return to_route('admin.messages.index')->with('message_content', 'Messaggio ripristinato!');
    }

    /**
     * Force deletes the specified message from storage.
     *
     * @param  \App\Models\Int $id
     * @return \Illuminate\Http\Response
     */
    public function forcedelete(Int $id)
    {
        $message = Message::where('id', $id)->onlyTrashed()->first();

        $message->forceDelete();
        return to_route('admin.messages.trash')->with('message_content', 'Messaggio eliminato definitivamente!')
            ->with('message_type', 'danger');
    }
}