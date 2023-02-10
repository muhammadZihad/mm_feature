<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['event' => function ($query) {
            return $query->with('actor:id,name');
        }])->get();
        return view('home', compact('users'));
    }

    public function edit(User $user)
    {
        $event = $user->getEvent();
        if (!is_null($event) && $event->user_id != auth()->id()) {
            return back()->with([
                'actor' => $event->actor->name
            ]);
        }
        if (is_null($event) || $event->user_id != auth()->id()) {
            $user->event()->create([
                'user_id' => auth()->id()
            ]);
        }
        return view('users.edit', [
            'user' => $user
        ]);
    }


    public function updateEventStatus(Event $event, Request $req)
    {
        if ($req->clear == 1) {
            $event->delete();
            return response()->json([], 200);
        }
        if ($event->updated_at < Carbon::now()->subSeconds(15)) {
            $newEvent = Event::whereNot('id', $event->id)
                ->with('actor')
                ->where('eventable_id', $event->eventable_id)
                ->where('eventable_type', $event->eventable_type)
                ->where('updated_at', '>', Carbon::now()->subSeconds(15))
                ->first();
            if ($newEvent) {
                return response()->json([
                    'error' => "This record is currently being edited by {$event->actor->name}"
                ], 500);
            }
            $event->touch();
            return response()->json([
                'event' => $event
            ], 200);
        }

        return response()->json([
            'event' => $event
        ], 200);
    }
}
