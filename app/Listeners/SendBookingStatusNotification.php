<?php

namespace App\Listeners;

use App\Events\BookingStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingStatusChangedMail;

class SendBookingStatusNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookingStatusChanged $event): void
    {
        switch ($event->newStatus) {
            case 'confirmed':
                Mail::to($event->booking->customer->email)
                    ->queue(new BookingStatusChangedMail($event->booking));
                //\Log::info("Booking #{$event->booking->id} confirmed.");
                // send confirmation email
                // Notification::send($event->booking->user, new BookingConfirmedNotification($event->booking));
                break;

            case 'cancelled':
                Mail::to($event->booking->customer->email)
                    ->queue(new BookingStatusChangedMail($event->booking));
                //\Log::warning("Booking #{$event->booking->id} cancelled.");
                // send cancellation email
                break;

            case 'completed':
                Mail::to($event->booking->customer->email)
                    ->queue(new BookingStatusChangedMail($event->booking));
                //\Log::info("Booking #{$event->booking->id} completed.");
                // generate invoice
                break;

            case 'pending':
                //\Log::info("Booking #{$event->booking->id} created, status pending.");
                // notify provider
                break;
        }
    }
}
