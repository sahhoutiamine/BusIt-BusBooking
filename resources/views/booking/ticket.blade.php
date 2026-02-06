<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $reservation->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; }
            .ticket-container { border: 2px solid #e5e7eb; box-shadow: none; }
        }
    </style>
</head>
<body class="bg-gray-100 py-10">
    <div class="max-w-3xl mx-auto px-4">
        <div class="no-print mb-6 flex justify-between items-center">
            <a href="{{ route('booking.confirmation', $reservation) }}" class="text-blue-600 font-medium flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                Back to Confirmation
            </a>
            <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold shadow-md hover:bg-blue-700">
                Print Official Ticket
            </button>
        </div>

        @foreach($reservation->passengers as $passenger)
            <div class="ticket-container bg-white rounded-xl shadow-lg overflow-hidden mb-8 border border-gray-100">
                <div class="bg-blue-600 px-6 py-4 flex justify-between items-center text-white">
                    <div class="flex items-center space-x-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span class="text-xl font-bold tracking-tight">BusIt Express Official Ticket</span>
                    </div>
                    <div class="text-right">
                        <p class="text-xs opacity-75 uppercase tracking-widest">Ticket Ref</p>
                        <p class="font-mono font-bold">#{{ $reservation->id }}-{{ $loop->iteration }}</p>
                    </div>
                </div>

                <div class="p-8">
                    <div class="flex flex-col md:flex-row justify-between mb-8 pb-8 border-b border-dashed border-gray-200">
                        <div class="flex-1">
                            <p class="text-xs text-gray-400 uppercase font-bold mb-1">Passenger</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $passenger->nom_complet }}</p>
                            <p class="text-sm text-gray-500">{{ ucfirst($passenger->type) }} {{ $passenger->cin ? '- '.$passenger->cin : '' }}</p>
                        </div>
                        <div class="mt-4 md:mt-0 md:ml-8 flex items-start space-x-6">
                            <div class="text-center">
                                <p class="text-xs text-gray-400 font-bold uppercase mb-1">Date</p>
                                <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($reservation->date_reservation)->format('d M Y') }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-400 font-bold uppercase mb-1">Seat</p>
                                <p class="font-bold text-blue-600">SEAT {{ $passenger->siege_numero }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div class="space-y-6">
                            <div class="flex items-start space-x-4">
                                <div class="w-1 bg-blue-600 h-full rounded-full self-stretch"></div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase">Departure</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $reservation->segment->startGare->ville->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $reservation->segment->startGare->nom }}</p>
                                    <p class="text-xl font-black text-blue-700 mt-1">{{ \Carbon\Carbon::parse($reservation->programme->heure_depart ?? '00:00')->format('H:i') }}</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="w-1 bg-gray-300 h-full rounded-full self-stretch"></div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase">Arrival</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $reservation->segment->endGare->ville->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $reservation->segment->endGare->nom }}</p>
                                    <p class="text-sm text-gray-500 italic">Estimated duration: {{ \Carbon\Carbon::parse($reservation->segment->duree_estimee)->format('H\hi') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-center justify-center p-6 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=TICKET-{{ $reservation->id }}-{{ $passenger->id }}" alt="Validation QR" class="w-32 h-32 mb-2">
                            <p class="text-[10px] text-gray-400 font-mono">SCAN AT BOARDING</p>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between items-center italic text-xs text-gray-400">
                        <p>Bus Plate: {{ $reservation->segment->bus->matricule }}</p>
                        <p>Powered by SATAS Express Transit Systems</p>
                    </div>
                </div>
                
                <div class="bg-gray-100 py-2 px-8 flex justify-between text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">
                    <span>Terms & Conditions Apply</span>
                    <span>Valid for Date shown only</span>
                    <span>Non-Transferable</span>
                </div>
            </div>
            
            <div class="print-cut no-print border-t-2 border-dashed border-gray-300 my-4 h-0 w-full"></div>
        @endforeach
    </div>
</body>
</html>
