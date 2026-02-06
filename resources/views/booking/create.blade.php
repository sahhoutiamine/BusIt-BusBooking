@extends('layouts.main')

@section('content')

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
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Full Name -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                        <input type="text" :name="`passengers[${index}][nom_complet]`" x-model="passenger.name" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2">
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

                                    <!-- Seat Selection -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Seat Number</label>
                                        <div class="flex items-center space-x-2">
                                            <input type="hidden" :name="`passengers[${index}][siege_numero]`" x-model="passenger.seat">
                                            <button type="button" 
                                                @click="openSeatPicker(index)"
                                                class="flex-1 text-left px-4 py-2 border rounded-md shadow-sm text-sm"
                                                :class="passenger.seat ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-gray-50 border-gray-300 text-gray-500'">
                                                <span x-text="passenger.seat ? 'Seat ' + passenger.seat : 'Select a seat'"></span>
                                            </button>
                                            <button type="button" @click="openSeatPicker(index)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            </button>
                                        </div>
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

                    <!-- Seat Picker Modal -->
                    <div x-show="seatPickerOpen" 
                         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         @keydown.escape.window="closeSeatPicker()"
                         style="display: none;">
                        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-hidden flex flex-col border border-gray-100" @click.away="closeSeatPicker()">
                            <!-- Header -->
                            <div class="p-6 border-b flex justify-between items-center bg-gray-50/50">
                                <div>
                                    <h3 class="text-2xl font-black text-gray-900 tracking-tight">Choose Your Spot</h3>
                                    <p class="text-sm text-gray-500 font-medium">Passenger <span x-text="activePassengerIndex + 1" class="text-blue-600"></span> • Select an available seat</p>
                                </div>
                                <button type="button" @click="closeSeatPicker()" class="p-2 bg-white rounded-full shadow-sm border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-100 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <!-- Body -->
                            <div class="p-8 overflow-y-auto flex-1 bg-white relative">
                                <!-- Legend -->
                                <div class="flex justify-center flex-wrap gap-4 mb-10 text-[10px] uppercase font-bold tracking-widest text-gray-400">
                                    <div class="flex items-center group">
                                        <div class="w-5 h-5 bg-white border-2 border-gray-200 rounded-lg mr-2 group-hover:border-blue-400 transition-colors"></div> 
                                        <span>Available</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-5 h-5 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg mr-2 shadow-lg shadow-blue-200 ring-2 ring-blue-300"></div> 
                                        <span class="text-blue-600">Selected</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-5 h-5 bg-gray-100 border border-gray-200 rounded-lg mr-2 relative overflow-hidden">
                                            <div class="absolute inset-0 bg-[repeating-linear-gradient(45deg,transparent,transparent_2px,#e5e7eb_2px,#e5e7eb_4px)]"></div>
                                        </div> 
                                        <span>Booked</span>
                                    </div>
                                </div>

                                <!-- Bus Hull & Structure -->
                                <div class="relative mx-auto bg-slate-50 rounded-t-[5rem] rounded-b-[2rem] p-1 w-[24rem] shadow-2xl border-x-8 border-t-8 border-slate-200">
                                    <!-- Windshield / Nose -->
                                    <div class="h-16 bg-slate-800 rounded-t-[4.5rem] mb-2 flex items-center justify-center relative overflow-hidden ring-4 ring-slate-900/10">
                                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-slate-700/50"></div>
                                        <div class="w-24 h-1 bg-blue-400/30 rounded-full blur-sm animate-pulse"></div>
                                    </div>

                                    <div class="px-8 pb-10 pt-4 bg-white/80 backdrop-blur-md rounded-b-[1.5rem] shadow-inner">
                                        <!-- Cockpit & Driver Dash -->
                                        <div class="flex justify-between items-center mb-10 pb-6 border-b border-slate-100">
                                            <div class="w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center shadow-lg relative ring-2 ring-slate-800">
                                                <div class="absolute -top-1 left-1/2 -translate-x-1/2 w-4 h-1 bg-blue-500 rounded-full"></div>
                                                <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg>
                                            </div>
                                            <div class="text-[10px] font-black text-slate-300 uppercase tracking-[0.5em] pb-1">Main Deck</div>
                                            <div class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center text-slate-200">
                                                <svg class="w-5 h-5 opacity-20" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/></svg>
                                            </div>
                                        </div>

                                        <!-- 50 Seats Array (13 rows: 12 rows of 4 + 1 row of 2) -->
                                        <div class="grid grid-cols-5 gap-y-5 gap-x-2">
                                            <template x-for="row in 13" :key="row">
                                                <div class="contents">
                                                    <!-- Left Side Pairs -->
                                                    <template x-for="col in [1, 2]">
                                                        <template x-if="((row-1)*4 + col) <= 50">
                                                            <button type="button" 
                                                                @click="selectSeat((row-1)*4 + col)"
                                                                :disabled="isSeatOccupied((row-1)*4 + col)"
                                                                class="group relative w-12 h-12 rounded-lg flex flex-col items-center justify-center transition-all duration-300 transform"
                                                                :class="{
                                                                    'bg-slate-50 cursor-not-allowed grayscale opacity-30': isSeatOccupied((row-1)*4 + col),
                                                                    'bg-blue-600 shadow-xl shadow-blue-200 ring-4 ring-blue-100 -translate-y-1 z-10': isSeatSelectedByAny((row-1)*4 + col),
                                                                    'bg-white border-2 border-slate-100 hover:border-blue-400 hover:bg-slate-50 shadow-sm': !isSeatOccupied((row-1)*4 + col) && !isSeatSelectedByAny((row-1)*4 + col)
                                                                }">
                                                                
                                                                <!-- Luxury Seat Headrest -->
                                                                <div class="absolute -top-1 w-8 h-2.5 rounded-full border shadow-sm transition-colors"
                                                                     :class="isSeatSelectedByAny((row-1)*4 + col) ? 'bg-blue-500 border-blue-400' : 'bg-slate-100 border-slate-200 group-hover:bg-blue-100'"></div>
                                                                
                                                                <!-- Armrests -->
                                                                <div class="absolute -left-1 w-1.5 h-6 bg-inherit border-x-2 border-slate-100 rounded-full" :class="isSeatSelectedByAny((row-1)*4 + col) ? 'border-blue-400' : ''"></div>
                                                                <div class="absolute -right-1 w-1.5 h-6 bg-inherit border-x-2 border-slate-100 rounded-full" :class="isSeatSelectedByAny((row-1)*4 + col) ? 'border-blue-400' : ''"></div>
                                                                
                                                                <!-- Seat Stitching detail -->
                                                                <div class="w-full h-px bg-slate-50 absolute top-1/2 -translate-y-1/2 opacity-20" x-show="!isSeatSelectedByAny((row-1)*4 + col)"></div>

                                                                <span x-text="(row-1)*4 + col" class="text-[9px] font-black tracking-tighter relative z-10" :class="isSeatSelectedByAny((row-1)*4 + col) ? 'text-white' : 'text-slate-400'"></span>
                                                            </button>
                                                        </template>
                                                        <template x-if="((row-1)*4 + col) > 50">
                                                            <div class="w-12 h-12"></div>
                                                        </template>
                                                    </template>

                                                    <!-- Aisle -->
                                                    <div class="flex items-center justify-center">
                                                        <div class="w-px h-6 bg-slate-100 rounded-full"></div>
                                                    </div>

                                                    <!-- Right Side Pairs -->
                                                    <template x-for="col in [3, 4]">
                                                        <template x-if="((row-1)*4 + col) <= 50">
                                                            <button type="button" 
                                                                @click="selectSeat((row-1)*4 + col)"
                                                                :disabled="isSeatOccupied((row-1)*4 + col)"
                                                                class="group relative w-12 h-12 rounded-lg flex flex-col items-center justify-center transition-all duration-300 transform"
                                                                :class="{
                                                                    'bg-slate-50 cursor-not-allowed grayscale opacity-30': isSeatOccupied((row-1)*4 + col),
                                                                    'bg-blue-600 shadow-xl shadow-blue-200 ring-4 ring-blue-100 -translate-y-1 z-10': isSeatSelectedByAny((row-1)*4 + col),
                                                                    'bg-white border-2 border-slate-100 hover:border-blue-400 hover:bg-slate-50 shadow-sm': !isSeatOccupied((row-1)*4 + col) && !isSeatSelectedByAny((row-1)*4 + col)
                                                                }">
                                                                
                                                                <!-- Luxury Seat Headrest -->
                                                                <div class="absolute -top-1 w-8 h-2.5 rounded-full border shadow-sm transition-colors"
                                                                     :class="isSeatSelectedByAny((row-1)*4 + col) ? 'bg-blue-500 border-blue-400' : 'bg-slate-100 border-slate-200 group-hover:bg-blue-100'"></div>
                                                                
                                                                <!-- Armrests -->
                                                                <div class="absolute -left-1 w-1.5 h-6 bg-inherit border-x-2 border-slate-100 rounded-full" :class="isSeatSelectedByAny((row-1)*4 + col) ? 'border-blue-400' : ''"></div>
                                                                <div class="absolute -right-1 w-1.5 h-6 bg-inherit border-x-2 border-slate-100 rounded-full" :class="isSeatSelectedByAny((row-1)*4 + col) ? 'border-blue-400' : ''"></div>
                                                                
                                                                <span x-text="(row-1)*4 + col" class="text-[9px] font-black tracking-tighter relative z-10" :class="isSeatSelectedByAny((row-1)*4 + col) ? 'text-white' : 'text-slate-400'"></span>
                                                            </button>
                                                        </template>
                                                        <template x-if="((row-1)*4 + col) > 50">
                                                            <div class="w-12 h-12"></div>
                                                        </template>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    
                                    <!-- Tail / Rear Exit -->
                                    <div class="h-10 bg-slate-100 rounded-b-[1.5rem] border-t border-slate-200 flex items-center justify-center">
                                        <div class="w-12 h-1.5 bg-slate-300 rounded-full opacity-50"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="p-6 border-t bg-gray-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                                <div class="text-sm font-medium text-gray-500">
                                    Selected: <span x-text="passengers[activePassengerIndex]?.seat ? 'Seat ' + passengers[activePassengerIndex].seat : 'None'" class="font-bold text-blue-600"></span>
                                </div>
                                <div class="flex space-x-3 w-full sm:w-auto">
                                    <button type="button" @click="closeSeatPicker()" class="flex-1 sm:flex-none px-6 py-2.5 text-sm font-bold text-gray-600 hover:bg-gray-200 rounded-xl transition">Dismiss</button>
                                    <button type="button" @click="closeSeatPicker()" class="flex-1 sm:flex-none px-10 py-2.5 text-sm font-black bg-blue-600 text-white rounded-xl shadow-xl shadow-blue-200 hover:bg-blue-700 transform hover:-translate-y-0.5 transition-all">OK</button>
                                </div>
                            </div>
                        </div>
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
                            <div class="text-xs text-gray-500 border-t border-gray-100 pt-2 mt-2">
                                <p class="font-semibold mb-1">Passenger <span x-text="i+1"></span>: 
                                    <span x-show="p.seat" class="text-blue-600 uppercase font-bold">Seat <span x-text="p.seat"></span></span>
                                    <span x-show="!p.seat" class="text-red-500 italic">No seat selected</span>
                                </p>
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

                    <button type="submit" form="bookingForm" 
                        :disabled="passengers.some(p => !p.seat)"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Confirm & Pay
                    </button>
                    
                    <p class="text-xs text-gray-400 text-center mt-4 uppercase tracking-tighter">
                        Complete your selection to proceed
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function bookingForm() {
    const count = {{ $passengersCount }};
    const occupiedSeats = @json($occupiedSeats);
    const busCapacity = {{ $segment->bus->capacite }};
    
    const passengers = [];
    for(let i=0; i<count; i++) {
        passengers.push({ 
            id: Date.now() + i, 
            name: '', 
            cin: '', 
            type: 'adulte', 
            insurance: false, 
            snack: false,
            seat: null
        });
    }
    
    return {
        basePrice: {{ $segment->tarif }},
        passengers: passengers,
        occupiedSeats: occupiedSeats,
        totalSeats: busCapacity,
        seatPickerOpen: false,
        activePassengerIndex: null,
        
        openSeatPicker(index) {
            this.activePassengerIndex = index;
            this.seatPickerOpen = true;
        },
        
        closeSeatPicker() {
            this.seatPickerOpen = false;
        },
        
        selectSeat(n) {
            // Unselect if clicking the same seat
            if(this.passengers[this.activePassengerIndex].seat === n) {
                this.passengers[this.activePassengerIndex].seat = null;
                return;
            }
            
            // Check if another passenger already picked it
            if(this.isSeatSelectedByAny(n)) {
                alert("This seat is already selected by one of your other passengers.");
                return;
            }

            this.passengers[this.activePassengerIndex].seat = n;
        },
        
        isSeatOccupied(n) {
            return this.occupiedSeats.includes(n);
        },
        
        isSeatSelectedByAny(n) {
            return this.passengers.some(p => p.seat === n);
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
