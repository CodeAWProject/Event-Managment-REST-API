<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notifications to all event attendees that event starts soon';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        //Loading events with attendees and pointing to the users
        $events = Event::with('attendees.user')
            ->whereBetween('start_time', [now(), now()->addDay()])
            ->get();

        //Count methot just returns how many items there are in this collection    
        $eventCount = $events->count();  
        $eventLabel = Str::plural('event', $eventCount);

        $this->info("Found {$eventCount} ${eventLabel}.");

        $events->each(
            fn ($event) => $event->attendees->each(
                fn ($attendee) => $this->info("Notifing the user {$attendee->user->id}")));
        
        $this->info('Reminder notifications sent successfully');
    }
}
