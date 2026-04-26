@extends('layouts.app')

@section('title', 'Terms & Conditions')
@section('meta_description', 'Harris Cars Service Center Terms and Conditions — the rules and guidelines governing use of our website and services.')

@section('content')

{{-- Hero --}}
<div class="bg-brand-dark py-20 pt-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="font-display text-5xl sm:text-6xl text-white tracking-wider mb-4">TERMS &amp; <span class="text-brand-primary">CONDITIONS</span></h1>
        <p class="text-gray-400 max-w-xl mx-auto">The rules and guidelines governing your use of the Harris Cars Service Center website and services.</p>
    </div>
</div>

{{-- Body --}}
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-[220px_1fr] gap-10 items-start">

            {{-- Table of Contents --}}
            <aside class="hidden lg:block sticky top-28">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <h4 class="text-xs font-bold tracking-widest uppercase text-brand-dark mb-4">Contents</h4>
                    <ol class="space-y-1.5 list-none p-0 m-0">
                        @foreach([
                            ['#acceptance',            'Acceptance of Terms'],
                            ['#use-of-site',           'Use of the Website'],
                            ['#pricing',               'Pricing & Availability'],
                            ['#service-disclaimers',   'Service Disclaimers'],
                            ['#intellectual-property', 'Intellectual Property'],
                            ['#third-party-links',     'Third-Party Links'],
                            ['#disclaimer',            'Disclaimer of Warranties'],
                            ['#liability',             'Limitation of Liability'],
                            ['#governing-law',         'Governing Law'],
                            ['#changes',               'Changes to Terms'],
                            ['#contact-terms',         'Contact Us'],
                        ] as $i => $item)
                            <li class="flex items-start gap-2">
                                <span class="text-brand-primary font-bold text-xs flex-shrink-0 w-5 pt-0.5">{{ $i + 1 }}.</span>
                                <a href="{{ $item[0] }}" class="text-gray-500 hover:text-brand-dark text-xs leading-relaxed transition-colors">{{ $item[1] }}</a>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </aside>

            {{-- Main Content --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-8 lg:p-10">

                <p class="flex items-center gap-2 text-xs text-gray-400 mb-8">
                    <svg class="w-3.5 h-3.5 text-brand-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Effective Date: April 26, 2026
                </p>

                <p class="text-gray-600 text-sm leading-relaxed mb-10">
                    Please read these Terms and Conditions ("Terms") carefully before using the Harris Cars Service Center website and related subpages (collectively, the "Site"). By accessing or using the Site, you agree to be bound by these Terms. If you do not agree, please do not use the Site.
                </p>

                {{-- 1 --}}
                <section id="acceptance" class="mb-10 scroll-mt-28">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-brand-dark border-b border-gray-100 pb-3 mb-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-primary/10 text-brand-dark text-xs font-bold flex-shrink-0">1</span>
                        Acceptance of Terms
                    </h2>
                    <p class="text-gray-600 text-sm leading-relaxed mb-3">These Terms constitute a legally binding agreement between you and Harris Cars Service Center ("Harris Cars," "we," "our," or "us"), a business located at {{ site_address() }}. Your continued use of the Site constitutes your acceptance of these Terms and any updates.</p>
                    <p class="text-gray-600 text-sm leading-relaxed">If you are using the Site on behalf of an organization, you represent that you have the authority to bind that organization to these Terms.</p>
                </section>

                {{-- 2 --}}
                <section id="use-of-site" class="mb-10 scroll-mt-28">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-brand-dark border-b border-gray-100 pb-3 mb-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-primary/10 text-brand-dark text-xs font-bold flex-shrink-0">2</span>
                        Use of the Website
                    </h2>
                    <p class="text-gray-600 text-sm leading-relaxed mb-3">You agree to use the Site only for lawful purposes and in a manner that does not infringe the rights of others. You may not:</p>
                    <ul class="list-disc pl-5 space-y-1.5 text-gray-600 text-sm leading-relaxed mb-4">
                        <li>Use the Site in any way that violates applicable local, state, national, or international laws or regulations.</li>
                        <li>Transmit any unsolicited or unauthorized advertising or promotional material.</li>
                        <li>Attempt to gain unauthorized access to any part of the Site or its related systems.</li>
                        <li>Use automated tools (scrapers, bots, crawlers) to harvest data from the Site without our prior written consent.</li>
                        <li>Submit false, misleading, or fraudulent information through any form on the Site.</li>
                    </ul>
                    <p class="text-gray-600 text-sm leading-relaxed">We reserve the right to terminate or restrict access to the Site for any user who violates these Terms, without notice.</p>
                </section>

                {{-- 3 --}}
                <section id="pricing" class="mb-10 scroll-mt-28">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-brand-dark border-b border-gray-100 pb-3 mb-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-primary/10 text-brand-dark text-xs font-bold flex-shrink-0">3</span>
                        Service Pricing &amp; Availability
                    </h2>
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-5 text-sm text-gray-700 leading-relaxed mb-4">
                        All service prices, estimates, and special offers displayed on this Site are subject to change without notice. Prices shown are estimates and may not include taxes, shop supplies, disposal fees, or other applicable charges. While we make every effort to ensure accuracy, typographical errors may occur. Harris Cars Service Center reserves the right to correct pricing errors at any time and is not obligated to honor an incorrect price.
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">Specials and promotions listed on the Site are subject to expiration and availability. Please contact us to confirm current pricing and availability before scheduling service.</p>
                </section>

                {{-- 4 --}}
                <section id="service-disclaimers" class="mb-10 scroll-mt-28">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-brand-dark border-b border-gray-100 pb-3 mb-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-primary/10 text-brand-dark text-xs font-bold flex-shrink-0">4</span>
                        Service Disclaimers
                    </h2>
                    <p class="text-gray-600 text-sm leading-relaxed mb-3">Information, tips, and descriptions of automotive services provided on this Site are for general informational purposes only. Actual service recommendations are determined by our technicians after a physical inspection of your vehicle.</p>
                    <p class="text-gray-600 text-sm leading-relaxed mb-3">Submitting an appointment request through this Site does not constitute a confirmed appointment or a guarantee of service availability. We will contact you to confirm your appointment details.</p>
                    <p class="text-gray-600 text-sm leading-relaxed">Diagnostic assessments and repair recommendations are made in good faith based on the information available at the time of inspection. Additional issues may be identified once service work is underway; you will be notified and asked for authorization before any additional work is performed.</p>
                </section>

                {{-- 5 --}}
                <section id="intellectual-property" class="mb-10 scroll-mt-28">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-brand-dark border-b border-gray-100 pb-3 mb-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-primary/10 text-brand-dark text-xs font-bold flex-shrink-0">5</span>
                        Intellectual Property
                    </h2>
                    <p class="text-gray-600 text-sm leading-relaxed mb-3">All content on this Site — including but not limited to text, graphics, logos, photographs, service descriptions, and software — is the property of Harris Cars Service Center or its content licensors and is protected by United States and international copyright, trademark, and other intellectual property laws.</p>
                    <p class="text-gray-600 text-sm leading-relaxed">You may view, print, or download content from the Site for your personal, non-commercial use only. You may not reproduce, distribute, modify, create derivative works of, publicly display, or exploit any Site content for commercial purposes without our prior written permission.</p>
                </section>

                {{-- 6 --}}
                <section id="third-party-links" class="mb-10 scroll-mt-28">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-brand-dark border-b border-gray-100 pb-3 mb-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-primary/10 text-brand-dark text-xs font-bold flex-shrink-0">6</span>
                        Third-Party Links
                    </h2>
                    <p class="text-gray-600 text-sm leading-relaxed">The Site may contain links to third-party websites (including parts suppliers, service providers, and partners). These links are provided for your convenience only. Harris Cars Service Center has no control over the content of those sites and accepts no responsibility for them or for any loss or damage that may arise from your use of them. We encourage you to review the privacy policies and terms of any third-party site you visit.</p>
                </section>

                {{-- 7 --}}
                <section id="disclaimer" class="mb-10 scroll-mt-28">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-brand-dark border-b border-gray-100 pb-3 mb-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-primary/10 text-brand-dark text-xs font-bold flex-shrink-0">7</span>
                        Disclaimer of Warranties
                    </h2>
                    <p class="text-gray-600 text-sm leading-relaxed mb-3">THE SITE AND ITS CONTENT ARE PROVIDED "AS IS" AND "AS AVAILABLE" WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED. TO THE FULLEST EXTENT PERMITTED BY LAW, HARRIS CARS SERVICE CENTER DISCLAIMS ALL WARRANTIES, INCLUDING BUT NOT LIMITED TO IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT.</p>
                    <p class="text-gray-600 text-sm leading-relaxed">We do not warrant that the Site will be uninterrupted, error-free, or free of viruses or other harmful components.</p>
                </section>

                {{-- 8 --}}
                <section id="liability" class="mb-10 scroll-mt-28">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-brand-dark border-b border-gray-100 pb-3 mb-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-primary/10 text-brand-dark text-xs font-bold flex-shrink-0">8</span>
                        Limitation of Liability
                    </h2>
                    <p class="text-gray-600 text-sm leading-relaxed mb-3">TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW, HARRIS CARS SERVICE CENTER, ITS OFFICERS, DIRECTORS, EMPLOYEES, AND AGENTS SHALL NOT BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR PUNITIVE DAMAGES — INCLUDING LOSS OF PROFITS, DATA, OR GOODWILL — ARISING OUT OF OR IN CONNECTION WITH YOUR USE OF THE SITE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.</p>
                    <p class="text-gray-600 text-sm leading-relaxed">Our total liability to you for any claim arising out of or relating to these Terms or your use of the Site shall not exceed the amount you paid to Harris Cars Service Center in the twelve (12) months preceding the claim, or $100, whichever is greater.</p>
                </section>

                {{-- 9 --}}
                <section id="governing-law" class="mb-10 scroll-mt-28">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-brand-dark border-b border-gray-100 pb-3 mb-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-primary/10 text-brand-dark text-xs font-bold flex-shrink-0">9</span>
                        Governing Law
                    </h2>
                    <p class="text-gray-600 text-sm leading-relaxed">These Terms shall be governed by and construed in accordance with the laws of the State of North Carolina, without regard to its conflict-of-law provisions. Any dispute arising under these Terms shall be subject to the exclusive jurisdiction of the state and federal courts located in Mecklenburg County, North Carolina.</p>
                </section>

                {{-- 10 --}}
                <section id="changes" class="mb-10 scroll-mt-28">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-brand-dark border-b border-gray-100 pb-3 mb-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-primary/10 text-brand-dark text-xs font-bold flex-shrink-0">10</span>
                        Changes to Terms
                    </h2>
                    <p class="text-gray-600 text-sm leading-relaxed">Harris Cars Service Center reserves the right to modify these Terms at any time. When changes are made, we will update the "Effective Date" at the top of this page. Your continued use of the Site after any change constitutes your acceptance of the new Terms. If you do not agree to the modified Terms, you must stop using the Site.</p>
                </section>

                {{-- 11 --}}
                <section id="contact-terms" class="mb-0 scroll-mt-28">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-brand-dark border-b border-gray-100 pb-3 mb-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-primary/10 text-brand-dark text-xs font-bold flex-shrink-0">11</span>
                        Contact Us
                    </h2>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">For questions or concerns about these Terms, please contact us:</p>
                    <div class="bg-brand-primary/5 border border-brand-primary/20 rounded-lg p-6">
                        <p class="text-gray-800 font-semibold text-sm mb-2">Harris Cars Service Center</p>
                        <p class="text-gray-600 text-sm mb-1">{{ site_address() }}</p>
                        <p class="text-gray-600 text-sm mb-1">
                            Phone: <a href="tel:{{ format_phone(site_phone()) }}" class="text-brand-primary hover:underline">{{ site_phone() }}</a>
                        </p>
                        <p class="text-gray-600 text-sm">
                            Email: <a href="mailto:{{ site_email() }}" class="text-brand-primary hover:underline">{{ site_email() }}</a>
                        </p>
                    </div>
                </section>

            </div>{{-- /main content --}}
        </div>{{-- /grid --}}
    </div>{{-- /container --}}
</div>{{-- /body --}}

@endsection
