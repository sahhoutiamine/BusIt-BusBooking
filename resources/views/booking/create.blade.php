@extends('layouts.main')

@section('content')
<script src="//unpkg.com/alpinejs" defer></script>

<div class="bg-gray-50 min-h-screen py-8" x-data="bookingForm()">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between relative">
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-gray-200 -z-10"></div>
                <div class="flex items-center justify-center w-8 h-8 bg-blue-600 rounded-full text-white font-bold text-sm ring-4 ring-white">1</div>
                <div class="flex items-center justify-center w-8 h-8 bg-blue-600 rounded-full text-white font-bold text-sm ring-4 ring-white">2</div>
                <div class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-full text-gray-500 font-bold text-sm ring-4 ring-white">3</div>
            </div>
            <div class="flex justify-between mt-2 text-xs font-medium text-gray-500">
                <span>Search</span>
                <span class="text-blue-600">Passenger Details</span>
                <span>Payment</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Forms -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Trip Summary Request -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Trip Summary</h2>
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="text-lg font-semibold text-blue-600">
                                {{ $segment->startGare->ville->name }} &rarr; {{ $segment->endGare->ville->name }}
                            </div>
                            <div class="text-gray-500 mt-1">
                                {{ $date->format('l, d F Y') }}
                            </div>
                            <div class="text-gray-500 mt-1">
                                {{ \Carbon\Carbon::parse($segment->programme->heure_depart)->format('H:i') }}
                                <span class="mx-2">&bull;</span>
                                BusIt Express logic
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-gray-900">{{ number_format($segment->tarif, 2) }} <span class="text-sm font-normal text-gray-500">MAD</span></div>
                            <div class="text-sm text-green-600 mt-1">{{ $available }} seats remaining</div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('booking.store') }}" id="bookingForm">
                    @csrf
                    <input type="hidden" name="segment_id" value="{{ $segment->id }}">
                    <input type="hidden" name="date_reservation" value="{{ $date->format('Y-m-d') }}">

                    <!-- Passengers -->
                    <div class="space-y-4">
                        <template x-for="(passenger, index) in passengers" :key="passenger.id">
                            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 relative transition-all duration-300">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Passenger <span x-text="index + 1"></span></h3>
                                    <template x-if="passengers.length > 1">
                                        <button type="button" @click="removePassenger(index)" class="text-red-500 hover:text-red-700 text-sm font-medium">Remove</button>
                                    </template>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Full Name -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                        <input type="text" :name="`passengers[${index}][nom_complet]`" x-model="passenger.name" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2">
                                    </div>
                                    
                                    <!-- Date of Birth -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                        <input type="date" :name="`passengers[${index}][date_naissance]`" x-model="passenger.dob" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2">
                                    </div>

                                    <!-- Type -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                        <select :name="`passengers[${index}][type]`" x-model="passenger.type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2">
                                            <option value="adulte">Adult</option>
                                            <option value="enfant">Child (< 12y)</option>
                                        </select>
                                    </div>

                                    <!-- CIN (shown only if Adult) -->
                                    <div x-show="passenger.type === 'adulte'">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">CIN</label>
                                        <input type="text" :name="`passengers[${index}][cin]`" x-model="passenger.cin" :required="passenger.type === 'adulte'" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2">
                                    </div>
                                </div>

                                <!-- Extras -->
                                <div class="mt-6 pt-4 border-t border-gray-50">
                                    <p class="text-sm font-medium text-gray-900 mb-3">Extras</p>
                                    <div class="flex flex-col sm:flex-row gap-4">
                                        <label class="flex items-center space-x-3 cursor-pointer p-3 border rounded-lg hover:bg-gray-50 transition-colors" :class="{'border-blue-500 ring-1 ring-blue-500 bg-blue-50': passenger.insurance}">
                                            <input type="checkbox" :name="`passengers[${index}][has_insurance]`" x-model="passenger.insurance" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <div>
                                                <span class="text-sm font-medium text-gray-900">Cancellation Insurance</span>
                                                <span class="block text-xs text-gray-500">+25 MAD</span>
                                            </div>
                                        </label>

                                        <label class="flex items-center space-x-3 cursor-pointer p-3 border rounded-lg hover:bg-gray-50 transition-colors" :class="{'border-blue-500 ring-1 ring-blue-500 bg-blue-50': passenger.snack}">
                                            <input type="checkbox" :name="`passengers[${index}][has_snack_box]`" x-model="passenger.snack" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <div>
                                                <span class="text-sm font-medium text-gray-900">Snack Box</span>
                                                <span class="block text-xs text-gray-500">+15 MAD</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="mt-4">
                        <button type="button" @click="addPassenger()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" :disabled="passengers.length >= 10">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Add Passenger
                        </button>
                    </div>
                    
                    <div class="mt-8 flex justify-end">
                         <!-- Submit button managed by right column on mobile? or here -->
                    </div>
                </form>
            </div>

            <!-- Right Column: Price Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 sticky top-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Price Breakdown</h3>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Base Fare x <span x-text="passengers.length"></span></span>
                            <span class="font-medium" x-text="(passengers.length * basePrice).toFixed(2) + ' MAD'"></span>
                        </div>
                        
                        <template x-for="(p, i) in passengers" :key="p.id">
                            <div x-show="p.insurance || p.snack" class="text-xs text-gray-500 border-t border-gray-100 pt-2 mt-2">
                                <p class="font-semibold mb-1">Passenger <span x-text="i+1"></span> Extras:</p>
                                <div x-show="p.insurance" class="flex justify-between">
                                    <span>Insurance</span>
                                    <span>25.00 MAD</span>
                                </div>
                                <div x-show="p.snack" class="flex justify-between">
                                    <span>Snack Box</span>
                                    <span>15.00 MAD</span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="border-t border-gray-200 pt-4 flex justify-between items-center mb-6">
                        <span class="text-lg font-bold text-gray-900">Total</span>
                        <span class="text-2xl font-bold text-blue-600" x-text="totalPrice.toFixed(2) + ' MAD'"></span>
                    </div>

                    <button type="submit" form="bookingForm" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Confirm & Pay
                    </button>
                    
                    <p class="text-xs text-gray-400 text-center mt-4">
                        Secure Payment (Simulated). By confirm you agree to terms.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function bookingForm() {
    return {
        basePrice: {{ $segment->tarif }},
        passengers: [
            { id: Date.now(), name: '', cin: '', dob: '', type: 'adulte', insurance: false, snack: false }
        ],
        addPassenger() {
            if (this.passengers.length < 10) {
                this.passengers.push({ 
                    id: Date.now() + Math.random(), 
                    name: '', 
                    cin: '', 
                    dob: '', 
                    type: 'adulte', 
                    insurance: false, 
                    snack: false 
                });
            }
        },
        removePassenger(index) {
            if (this.passengers.length > 1) {
                this.passengers.splice(index, 1);
            }
        },
        get totalPrice() {
            return this.passengers.reduce((total, p) => {
                let cost = this.basePrice; 
                if (p.insurance) cost += 25;
                if (p.snack) cost += 15;
                return total + cost;
            }, 0);
        }
    }
}
</script>
@endsection
