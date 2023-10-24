<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    use CanLoadRelationships;

    private array $relations = ['user'];

    public function __construct()
    {
       $this-> middleware('auth:sanctum')->except('index', 'show', 'update');
       $this->authorizeResource(Attendee::class, 'attendee');
    }

    public function index(Event $event)
    {


        $attendees = $this->loadRelationships(
            $event->attendees()->latest()
        );
        //$attendees = $event->attendees()->latest();

        return AttendeeResource::collection(
            $attendees->paginate()
        );
    }

    
    // It creates a new attendee and this will also automatically set the event_id 
    public function store(Request $request, Event $event)
    {
        $attendee = $this->loadRelationships(
            $event->attendees()->create([
                'user_id' => 1
            ])
        );

        return new AttendeeResource($attendee);
    }

    //This attendee will be scoped to an avent
    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeeResource(
            $this->loadRelationships($attendee)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */

     //Parameters in the same order as defined inside the route
    public function destroy(Event $event, Attendee $attendee)
    {

        //$this->authorize('delete-attendee', [$event, $attendee]);
        $attendee->delete();

        return response(status:204);
    }
}
