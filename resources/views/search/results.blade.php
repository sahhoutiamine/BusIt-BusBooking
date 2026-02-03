@extends('layouts.main')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Search Results</h1>
                <p class="mt-2 text-gray-600">
                    Routing: <span class="font-semibold text-blue-600">{{ App\Models\Ville::find(request('ville_depart_id'))->name ?? 'Unknown' }}</span> 
                    &rarr; 
                    <span class="font-semibold text-blue-600">{{ App\Models\Ville::find(request('ville_arrivee_id'))->name ?? 'Unknown' }}</span>
                    <span class="ml-2 text-gray-400">|</span>
                    <span class="ml-2">{{ \Carbon\Carbon::parse(request('date_depart'))->format('D, d M Y') }}</span>
                </p>
            </div>
            <a href="{{ route('search.index') }}" class="text-blue-600 hover:text-blue-800 font-medium bg-blue-50 px-4 py-2 rounded-lg">Modify Search</a>
        </div>

        @if($directTrips->count() > 0)
            <div class="mb-10">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-0.5 rounded uppercase tracking-wide mr-2">Direct</span>
                    Available Trips
                </h2>
                <div class="space-y-4">
                    @foreach($directTrips as $segment)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                            <div class="p-6">
                                <div class="flex flex-col md:flex-row justify-between items-center">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            {{-- Assuming no company info available directly, using bus matricule or generic --}}
                                            <span class="text-sm font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded">BusIt Express</span>
                                            <span class="mx-2 text-gray-300">|</span>
                                            <span class="text-sm text-gray-500">{{ $searchDate ? $searchDate->format('D, d M Y') : 'Weekly Schedule' }}</span>
                                        </div>
                                        <div class="flex items-center text-gray-900">
                                            <div class="text-2xl font-bold">{{ \Carbon\Carbon::parse($segment->programme->heure_depart)->format('H:i') }}</div>
                                            <div class="mx-4 flex flex-col items-center w-24">
                                                <span class="text-xs text-gray-400 mb-1">{{ round($segment->distance_km) }} km</span>
                                                <div class="h-px w-full bg-gray-300 relative">
                                                    <div class="absolute w-2 h-2 bg-gray-400 rounded-full -top-1 left-0"></div>
                                                    <div class="absolute w-2 h-2 bg-gray-400 rounded-full -top-1 right-0"></div>
                                                </div>
                                                <span class="text-xs text-green-600 mt-1 font-medium">Direct</span>
                                            </div>
                                            {{-- Calculate arrival time roughly if not available --}}
                                            @php
                                                $duration = \Carbon\Carbon::parse($segment->duree_estimee);
                                                $depart = \Carbon\Carbon::parse($segment->programme->heure_depart);
                                                $arrival = $depart->copy()->addHours($duration->hour)->addMinutes($duration->minute);
                                            @endphp
                                            <div class="text-2xl font-bold">{{ $arrival->format('H:i') }}</div>
                                        </div>
                                        <div class="flex justify-between mt-2 text-sm text-gray-600">
                                            <span>{{ $segment->startGare->ville->name }} ({{ $segment->startGare->nom }})</span>
                                            <span>{{ $segment->endGare->ville->name }} ({{ $segment->endGare->nom }})</span>
                                        </div>
                                    </div>
                                    <div class="mt-4 md:mt-0 md:ml-8 text-center md:text-right">
                                        <div class="text-3xl font-bold text-gray-900 text-blue-600">{{ number_format($segment->tarif, 2) }} MAD</div>
                                        <button class="mt-2 w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Select
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if(count($indirectRoutes) > 0)
            {{-- Indirect routes section (Placeholder/Hidden if empty) --}}
        @endif

        @if($directTrips->count() == 0 && count($indirectRoutes) == 0)
            <div class="text-center py-16 bg-white rounded-xl shadow-sm">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No trips found</h3>
                <p class="mt-1 text-sm text-gray-500">Try searching for Monday (Lundi) or Wednesday (Mercredi) for demo data.</p>
                <div class="mt-6">
                    <a href="{{ route('search.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        New Search
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
