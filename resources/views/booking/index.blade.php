@extends('layouts.main')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">My Reservations</h1>
            <a href="{{ route('search.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-bold shadow-lg hover:bg-blue-700 transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Booking
            </a>
        </div>

        @if($reservations->isEmpty())
            <div class="bg-white rounded-3xl shadow-xl p-12 text-center border border-gray-100">
                <div class="mx-auto w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-blue-500 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No bookings found</h3>
                <p class="text-gray-500 mb-8 max-w-sm mx-auto">You haven't made any reservations yet. Start your journey by searching for available trips.</p>
                <a href="{{ route('search.index') }}" class="text-blue-600 font-black uppercase text-xs tracking-widest hover:text-blue-700">Explore Trips &rarr;</a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6">
                @foreach($reservations as $res)
                    <div class="bg-white rounded-3xl shadow-md border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 group">
                        <div class="flex flex-col md:flex-row">
                            <!-- Left: Trip Info -->
                            <div class="flex-grow p-8">
                                <div class="flex justify-between items-start mb-6">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase tracking-widest rounded-full">
                                            {{ $res->statut }}
                                        </span>
                                        <span class="text-xs text-gray-400 font-medium tracking-tighter">#{{ $res->id }} • {{ \Carbon\Carbon::parse($res->date_reservation)->format('d M, Y') }}</span>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-black text-gray-900">{{ number_format($res->total_price, 2) }} <span class="text-xs font-normal text-gray-400">MAD</span></p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between mb-8 relative">
                                    <div class="z-10 bg-white">
                                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-1">Departure</p>
                                        <p class="text-xl font-bold text-gray-900">{{ $res->segment->startGare->ville->name }}</p>
                                        <p class="text-sm text-gray-400 font-medium">{{ \Carbon\Carbon::parse($res->programme->heure_depart ?? '00:00')->format('H:i') }}</p>
                                    </div>

                                    <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 h-px border-t-2 border-dashed border-gray-100 -z-0 mx-24"></div>
                                    <div class="flex-1 flex justify-center z-10">
                                        <div class="p-2 bg-blue-50 rounded-full group-hover:bg-blue-600 group-hover:text-white transition-colors duration-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                                        </div>
                                    </div>

                                    <div class="text-right z-10 bg-white">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 text-right">Arrival</p>
                                        <p class="text-xl font-bold text-gray-900">{{ $res->segment->endGare->ville->name }}</p>
                                        <p class="text-sm text-gray-400 font-medium italic">Express Delivery</p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-6 text-sm">
                                    <div class="flex -space-x-2">
                                        @foreach($res->passengers->take(3) as $p)
                                            <div class="w-8 h-8 rounded-full bg-slate-200 border-2 border-white flex items-center justify-center text-[10px] font-bold text-slate-600 uppercase" title="{{ $p->nom_complet }}">
                                                {{ substr($p->nom_complet, 0, 1) }}
                                            </div>
                                        @endforeach
                                        @if($res->passengers->count() > 3)
                                            <div class="w-8 h-8 rounded-full bg-blue-50 border-2 border-white flex items-center justify-center text-[10px] font-bold text-blue-600">
                                                +{{ $res->passengers->count() - 3 }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-gray-500 font-medium">
                                        {{ $res->passengers->count() }} Passenger{{ $res->passengers->count() > 1 ? 's' : '' }} • 
                                        <span class="text-blue-600 font-bold">Seats: {{ $res->passengers->pluck('siege_numero')->implode(', ') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Actions -->
                            <div class="bg-gray-50/50 border-l border-gray-100 p-8 flex flex-col justify-center space-y-3 min-w-[200px]">
                                <a href="{{ route('booking.ticket', $res) }}" class="flex items-center justify-center w-full px-6 py-3 bg-white border border-slate-200 rounded-xl text-sm font-black text-slate-700 shadow-sm hover:border-blue-400 hover:text-blue-600 transition group-hover:shadow-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    Print Tickets
                                </a>
                                <a href="{{ route('booking.confirmation', $res) }}" class="flex items-center justify-center w-full px-6 py-3 bg-blue-600 border border-transparent rounded-xl text-sm font-black text-white shadow-lg shadow-blue-200 hover:bg-blue-700 transition">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
