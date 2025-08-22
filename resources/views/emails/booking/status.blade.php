<x-mail::message>
    # Booking Status Updated

    Hello {{ $booking->user->name }},

    Your booking (ID: {{ $booking->id }}) status has been updated to **{{ ucfirst($booking->status) }}**.

    <x-mail::button :url="route('bookings.show', $booking->id)">
        View Booking
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
