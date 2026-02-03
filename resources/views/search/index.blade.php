@extends('layouts.main')

@section('content')
<div class="relative bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">Travel across Morocco</span>
                        <span class="block text-blue-600 xl:inline">with comfort</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        Book your tickets easily with SATAS. Reliable service, extensive network, and best prices guaranteed.
                    </p>
                    
                    <div class="mt-8 bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
                        <form action="{{ route('search.results') }}" method="GET" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="relative">
                                    <label for="ville_depart_id" class="block text-sm font-medium text-gray-700 mb-1">From</label>
                                    <select name="ville_depart_id" id="ville_depart_id" class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg bg-gray-50">
                                        <option value="" disabled selected>Select City</option>
                                        @foreach($villes as $ville)
                                            <option value="{{ $ville->id }}">{{ $ville->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="relative">
                                    <label for="ville_arrivee_id" class="block text-sm font-medium text-gray-700 mb-1">To</label>
                                    <select name="ville_arrivee_id" id="ville_arrivee_id" class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg bg-gray-50">
                                        <option value="" disabled selected>Select City</option>
                                        @foreach($villes as $ville)
                                            <option value="{{ $ville->id }}">{{ $ville->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="relative">
                                    <label for="date_depart" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                    <input type="date" name="date_depart" id="date_depart" class="block w-full pl-3 pr-3 py-3 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg bg-gray-50" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            
                            <div class="pt-2">
                                <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                                    Search Trips
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-gray-50">
        <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full opacity-90" src="https://miro.medium.com/v2/resize:fit:1100/format:webp/1*dt92NJHdpAYpAiCb874CoQ.jpeg" alt="Bus travel in Morocco">
    </div>
</div>
@endsection
