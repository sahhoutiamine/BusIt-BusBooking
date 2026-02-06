@extends('layouts.main')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-green-600 px-6 py-8 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h2 class="text-3xl font-extrabold text-white">Booking Confirmed!</h2>
                <p class="mt-2 text-green-100 text-lg">Your trip has been successfully scheduled.</p>
            </div>

            <div class="p-8">
                <div class="border-b border-gray-200 pb-8 mb-8">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wide font-semibold">Booking Reference</p>
                            <p class="text-2xl font-bold text-gray-900">#{{ $reservation->id }}</p>
                        </div>
                        <div class="mt-4 md:mt-0 text-center">
                             <!-- QR Code Placeholder -->
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=SATAS-{{ $reservation->id }}" alt="QR Code" class="h-24 w-24 border p-1 rounded">
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6 flex flex-col sm:flex-row justify-between items-center">
                        <div class="text-center sm:text-left mb-4 sm:mb-0">
                            <p class="text-sm text-gray-500">Departure</p>
                            <p class="text-lg font-bold text-gray-900">{{ $reservation->segment->startGare->ville->name }}</p>
                            <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($reservation->programme->heure_depart ?? '00:00')->format('H:i') }}</p>
                        </div>
                        <div class="hidden sm:block flex-1 border-t-2 border-dashed border-gray-300 mx-6 relative">
                            <div class="absolute -top-1.5 left-0 w-3 h-3 bg-gray-300 rounded-full"></div>
                            <div class="absolute -top-1.5 right-0 w-3 h-3 bg-gray-300 rounded-full"></div>
                        </div>
                        <div class="text-center sm:text-right">
                            <p class="text-sm text-gray-500">Arrival</p>
                            <p class="text-lg font-bold text-gray-900">{{ $reservation->segment->endGare->ville->name }}</p>
                            <p class="text-sm text-gray-600">--:--</p>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Passengers</h3>
                    <div class="space-y-3">
                        @foreach($reservation->passengers as $p)
                            <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $p->nom_complet }}</p>
                                    <p class="text-xs text-gray-500">{{ ucfirst($p->type) }} {{ $p->cin ? '- CIN: ' . $p->cin : '' }}</p>
                                </div>
                                <div class="text-sm font-bold text-blue-600">Seat: {{ $p->siege_numero }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <p class="text-lg font-medium text-gray-900">Total Paid</p>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($reservation->total_price, 2) }} MAD</p>
                </div>

                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('booking.ticket', $reservation) }}" class="flex-1 flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Print Official Tickets
                    </a>
                    <a href="{{ route('search.index') }}" class="flex-1 flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Book Another Trip
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
