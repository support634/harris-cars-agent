@extends('layouts.app')

@section('title', 'Auto Repair Services')
@section('meta_description', 'Complete auto repair and maintenance services in Stallings, NC. Oil changes, brake service, engine diagnostics, AC repair, transmission, tires, and more.')

@section('content')

{{-- Page Header --}}
<div class="bg-brand-dark py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <p class="text-brand-primary font-semibold uppercase tracking-widest text-sm mb-2">What We Offer</p>
            <h1 class="font-display text-5xl sm:text-6xl text-white tracking-wider mb-4">OUR SERVICES</h1>
            <p class="text-gray-400 max-w-xl mx-auto">Complete automotive care for all domestic and foreign vehicles — serviced by ASE Certified technicians.</p>
        </div>
    </div>
</div>

{{-- Services by Category --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($categories->isNotEmpty())
            @foreach($categories as $category)
                <div id="{{ $category->slug }}" class="mb-16 last:mb-0">
                    <div class="flex items-center mb-8">
                        <div class="h-px bg-gray-200 flex-1"></div>
                        <h2 class="font-display text-3xl text-brand-dark tracking-wider px-6">{{ strtoupper($category->name) }}</h2>
                        <div class="h-px bg-gray-200 flex-1"></div>
                    </div>
                    @if($category->description)
                        <p class="text-gray-500 text-center mb-8 -mt-4">{{ $category->description }}</p>
                    @endif

                    @if($category->activeServices->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            @foreach($category->activeServices as $service)
                                <a href="{{ route('services.show', $service->slug) }}"
                                   class="group border border-gray-200 hover:border-brand-primary rounded-lg p-6 transition-all hover:shadow-md border-l-4 border-l-brand-primary">
                                    <h3 class="font-display text-xl text-brand-dark tracking-wide mb-2 group-hover:text-brand-primary transition-colors">
                                        {{ strtoupper($service->title) }}
                                    </h3>
                                    <p class="text-gray-500 text-sm leading-relaxed mb-4">{{ $service->short_description }}</p>
                                    @if($service->duration)
                                        <span class="text-gray-400 text-xs">{{ $service->duration }}</span>
                                    @endif
                                    <span class="inline-flex items-center text-sm text-brand-primary font-medium mt-3">
                                        Learn More
                                        <svg class="ml-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 text-center py-8">No services listed in this category yet.</p>
                    @endif
                </div>
            @endforeach
        @else
            {{-- Fallback grid when DB is empty --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach([
                    ['title' => 'Oil Change & Lube', 'desc' => 'Conventional, synthetic blend, or full synthetic oil changes with multi-point vehicle inspection and fluid top-off.'],
                    ['title' => 'Brake Service', 'desc' => 'Complete brake inspection, pad and shoe replacement, rotor resurfacing or replacement, and brake fluid service.'],
                    ['title' => 'Engine Diagnostics', 'desc' => 'Advanced computer diagnostics to read and clear fault codes. We pinpoint engine issues accurately to save you time and money.'],
                    ['title' => 'AC & Heating Repair', 'desc' => 'Full HVAC diagnosis, refrigerant recharge, compressor replacement, heater core service, and blower motor repair.'],
                    ['title' => 'Transmission Service', 'desc' => 'Transmission fluid flush and filter replacement. Full transmission diagnosis, repair, and rebuild services available.'],
                    ['title' => 'Exhaust System Service', 'desc' => 'Exhaust inspection, muffler replacement, catalytic converter service, and complete exhaust system repair.'],
                    ['title' => 'Suspension & Steering', 'desc' => 'Wheel alignment, shocks and struts, ball joints, tie rods, and complete suspension system inspection and repair.'],
                    ['title' => 'Tire Services', 'desc' => 'Tire rotation, balancing, flat repair, TPMS service, and new tire installation for all makes and models.'],
                    ['title' => 'Battery & Electrical', 'desc' => 'Battery test and replacement, alternator and starter service, electrical diagnosis, and lighting repairs.'],
                    ['title' => 'Cooling System', 'desc' => 'Coolant flush, thermostat replacement, radiator repair, water pump service, and cooling fan diagnosis.'],
                    ['title' => 'Preventive Maintenance', 'desc' => 'Scheduled maintenance services including air filters, cabin filters, spark plugs, belts, hoses, and fluid services.'],
                    ['title' => 'Wheel Alignment', 'desc' => 'Precision wheel alignment to improve handling, reduce tire wear, and keep your vehicle tracking straight on the road.'],
                    ['title' => 'ADAS Calibration', 'desc' => 'Advanced Driver Assistance System calibration for cameras, radar, and sensors — required after windshield replacement, alignment, suspension, or collision repair.'],
                    ['title' => 'Pre-Purchase Inspection', 'desc' => 'Comprehensive vehicle inspection before you buy a used car. Get the full picture and negotiate with confidence.'],
                ] as $svc)
                    <div class="border border-gray-200 hover:border-brand-primary rounded-lg p-6 transition-all hover:shadow-md border-l-4 border-l-brand-primary">
                        <h3 class="font-display text-xl text-brand-dark tracking-wide mb-2">{{ strtoupper($svc['title']) }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ $svc['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>

{{-- CTA --}}
<section class="bg-brand-dark py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-display text-4xl text-white tracking-wider mb-4">DIDN'T SEE YOUR SERVICE?</h2>
        <p class="text-gray-400 mb-8">We service virtually all makes and models. Give us a call and we'll let you know if we can help.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="tel:{{ format_phone(site_phone()) }}"
               class="inline-flex items-center justify-center bg-brand-primary hover:bg-[#003370] text-white px-8 py-4 font-display text-xl tracking-wider transition-colors">
                CALL {{ site_phone() }}
            </a>
            <a href="{{ route('appointments.create') }}"
               class="inline-flex items-center justify-center border-2 border-gray-500 hover:border-white text-white px-8 py-4 font-display text-xl tracking-wider transition-colors">
                BOOK APPOINTMENT
            </a>
        </div>
    </div>
</section>

@endsection
