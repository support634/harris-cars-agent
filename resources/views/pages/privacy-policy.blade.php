@extends('layouts.app')

@section('title', 'Privacy Policy')
@section('meta_description', 'Harris Cars Service Center Privacy Policy — how we collect, use, and protect your personal information.')

@section('content')

{{-- Hero --}}
<div class="bg-brand-dark py-20 pt-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="font-display text-5xl sm:text-6xl text-white tracking-wider mb-4">PRIVACY <span class="text-brand-primary">POLICY</span></h1>
        <p class="text-gray-400 max-w-xl mx-auto">How Harris Cars Service Center collects, uses, and protects your personal information.</p>
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
                    <ol class="space-y-1.5 list-none p-0 m-0" style="counter-reset:toc">
                        @foreach([
                            ['#information-collected', 'Information We Collect'],
                            ['#how-we-use',            'How We Use Your Information'],
                            ['#information-sharing',   'Information Sharing'],
                            ['#cookies',               'Cookies & Tracking'],
                            ['#data-security',         'Data Security'],
                            ['#your-rights',           'Your Rights'],
                            ['#childrens-privacy',     "Children's Privacy"],
                            ['#policy-changes',        'Changes to This Policy'],
                            ['#contact-privacy',       'Contact Us'],
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
                    Harris Cars Service Center ("Harris Cars," "we," "our," or "us") is committed to protecting your privacy. This Privacy Policy describes how we collect, use, disclose, and safeguard personal information when you visit our website, submit an inquiry, schedule an appointment, or otherwise interact with us. By using our website, you agree to the practices described in this policy.
                </p>

                {{-- Section helper macro via Blade component-style reuse --}}

                {{-- 1 --}}
                <section id="information-collected" class="mb-10 scroll-mt-28">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-brand-dark border-b border-gray-100 pb-3 mb-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-primary/10 text-brand-dark text-xs font-bold flex-shrink-0">1</span>
                        Information We Collect
                    </h2>
                    <p class="text-gray-600 text-sm leading-relaxed mb-3">We collect information you provide directly to us and information collected automatically through your use of our website.</p>
                    <p class="text-gray-700 text-sm font-semibold mb-2">Information you provide:</p>
                    <ul class="list-disc pl-5 space-y-1.5 text-gray-600 text-sm leading-relaxed mb-4">
                        <li>Name, mailing address, email address, and phone number</li>
                        <li>Vehicle information (year, make, model, VIN, mileage, condition)</li>
                        <li>Service appointment details and preferences</li>
                        <li>Photos or files you voluntarily upload</li>
                        <li>Communications and messages sent through contact forms or email</li>
                    </ul>
                    <p class="text-gray-700 text-sm font-semibold mb-2">Information collected automatically:</p>
                    <ul class="list-disc pl-5 space-y-1.5 text-gray-600 text-sm leading-relaxed">
                        <li>IP address, browser type, and device information</li>
                        <li>Pages visited, time spent on pages, and referring URLs</li>
                        <li>Cookie identifiers and similar tracking technologies (see Section 4)</li>
                    </ul>
                </section>

                {{-- 2 --}}
                <section id="how-we-use" class="mb-10 scroll-mt-28">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-brand-dark border-b border-gray-100 pb-3 mb-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-primary/10 text-brand-dark text-xs font-bold flex-shrink-0">2</span>
                        How We Use Your Information
                    </h2>
                    <p class="text-gray-600 text-sm leading-relaxed mb-3">We use the information we collect to:</p>
                    <ul class="list-disc pl-5 space-y-1.5 text-gray-600 text-sm leading-relaxed">
                        <li>Respond to inquiries and provide customer service</li>
                        <li>Schedule service appointments and send appointment reminders</li>
                        <li>Process service requests and communicate job status</li>
                        <li>Send promotional communications about services, offers, and events (with your consent where required)</li>
                        <li>Comply with legal obligations and resolve disputes</li>
                        <li>Improve our website, services, and customer experience through analytics</li>
                        <li>Detect and prevent fraudulent activity</li>
                    </ul>
                </section>

                {{-- 3 --}}
                <section id="information-sharing" class="mb-10 scroll-mt-28">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-brand-dark border-b border-gray-100 pb-3 mb-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-primary/10 text-brand-dark text-xs font-bold flex-shrink-0">3</span>
                        Information Sharing
                    </h2>
                    <p class="text-gray-600 text-sm leading-relaxed mb-3">We do not sell your personal information. We may share your information in the following circumstances:</p>
                    <ul class="list-disc pl-5 space-y-1.5 text-gray-600 text-sm leading-relaxed">
                        <li><strong class="text-gray-700">Service providers:</strong> Third-party vendors who assist us with website hosting, CRM, marketing, and analytics under appropriate data-protection agreements.</li>
                        <li><strong class="text-gray-700">Parts and supply partners:</strong> As needed to fulfill parts orders or service requests.</li>
                        <li><strong class="text-gray-700">Legal requirements:</strong> When required by law, court order, or governmental authority, or to protect the rights and safety of Harris Cars Service Center or others.</li>
                        <li><strong class="text-gray-700">Business transfers:</strong> In connection with a merger, acquisition, or sale of assets, subject to standard confidentiality obligations.</li>
                    </ul>
                </section>

                {{-- 4 --}}
                <section id="cookies" class="mb-10 scroll-mt-28">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-brand-dark border-b border-gray-100 pb-3 mb-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-primary/10 text-brand-dark text-xs font-bold flex-shrink-0">4</span>
                        Cookies &amp; Tracking
                    </h2>
                    <p class="text-gray-600 text-sm leading-relaxed mb-3">Our website uses cookies and similar technologies to enhance your browsing experience and analyze site traffic. Cookies are small data files stored on your device.</p>
                    <p class="text-gray-700 text-sm font-semibold mb-2">We use:</p>
                    <ul class="list-disc pl-5 space-y-1.5 text-gray-600 text-sm leading-relaxed mb-4">
                        <li><strong class="text-gray-700">Essential cookies</strong> required for the website to function correctly (e.g., session management).</li>
                        <li><strong class="text-gray-700">Analytics cookies</strong> to understand how visitors interact with our site (e.g., Google Analytics).</li>
                        <li><strong class="text-gray-700">Marketing cookies</strong> to deliver relevant advertisements on third-party platforms.</li>
                    </ul>
                    <p class="text-gray-600 text-sm leading-relaxed">You can control or disable cookies through your browser settings. Note that disabling certain cookies may affect website functionality. For more information, visit <a href="https://www.allaboutcookies.org" target="_blank" rel="noreferrer" class="text-brand-primary underline hover:text-brand-dark transition-colors">allaboutcookies.org</a>.</p>
                </section>

                {{-- 5 --}}
                <section id="data-security" class="mb-10 scroll-mt-28">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-brand-dark border-b border-gray-100 pb-3 mb-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-primary/10 text-brand-dark text-xs font-bold flex-shrink-0">5</span>
                        Data Security
                    </h2>
                    <p class="text-gray-600 text-sm leading-relaxed mb-3">We implement reasonable administrative, technical, and physical safeguards to protect your personal information from unauthorized access, disclosure, alteration, or destruction. Forms on this website are transmitted via SSL/TLS encryption.</p>
                    <p class="text-gray-600 text-sm leading-relaxed">No method of transmission over the internet or electronic storage is 100% secure. While we strive to protect your information, we cannot guarantee absolute security.</p>
                </section>

                {{-- 6 --}}
                <section id="your-rights" class="mb-10 scroll-mt-28">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-brand-dark border-b border-gray-100 pb-3 mb-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-primary/10 text-brand-dark text-xs font-bold flex-shrink-0">6</span>
                        Your Rights
                    </h2>
                    <p class="text-gray-600 text-sm leading-relaxed mb-3">Depending on your state of residence, you may have the right to:</p>
                    <ul class="list-disc pl-5 space-y-1.5 text-gray-600 text-sm leading-relaxed mb-4">
                        <li><strong class="text-gray-700">Access</strong> the personal information we hold about you.</li>
                        <li><strong class="text-gray-700">Correct</strong> inaccurate or incomplete information.</li>
                        <li><strong class="text-gray-700">Delete</strong> your personal information, subject to legal retention requirements.</li>
                        <li><strong class="text-gray-700">Opt out</strong> of marketing communications at any time by clicking "unsubscribe" in any email or contacting us directly.</li>
                        <li><strong class="text-gray-700">Data portability</strong> — receive a copy of your data in a machine-readable format where technically feasible.</li>
                    </ul>
                    <p class="text-gray-600 text-sm leading-relaxed mb-3">To exercise any of these rights, please contact us using the information in Section 9. We will respond within 30 days.</p>
                    <p class="text-gray-600 text-sm leading-relaxed">California residents may have additional rights under the California Consumer Privacy Act (CCPA). For CCPA-specific requests, please indicate this when contacting us.</p>
                </section>

                {{-- 7 --}}
                <section id="childrens-privacy" class="mb-10 scroll-mt-28">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-brand-dark border-b border-gray-100 pb-3 mb-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-primary/10 text-brand-dark text-xs font-bold flex-shrink-0">7</span>
                        Children's Privacy
                    </h2>
                    <p class="text-gray-600 text-sm leading-relaxed">Our website is not directed to children under 13 years of age. We do not knowingly collect personal information from children under 13. If you believe we have inadvertently collected such information, please contact us immediately and we will delete it.</p>
                </section>

                {{-- 8 --}}
                <section id="policy-changes" class="mb-10 scroll-mt-28">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-brand-dark border-b border-gray-100 pb-3 mb-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-primary/10 text-brand-dark text-xs font-bold flex-shrink-0">8</span>
                        Changes to This Policy
                    </h2>
                    <p class="text-gray-600 text-sm leading-relaxed">We may update this Privacy Policy from time to time. When we make material changes, we will update the "Effective Date" at the top of this page. We encourage you to review this policy periodically. Your continued use of our website after any changes constitutes acceptance of the updated policy.</p>
                </section>

                {{-- 9 --}}
                <section id="contact-privacy" class="mb-0 scroll-mt-28">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-brand-dark border-b border-gray-100 pb-3 mb-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-primary/10 text-brand-dark text-xs font-bold flex-shrink-0">9</span>
                        Contact Us
                    </h2>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">For privacy-related questions, requests, or concerns, please contact us:</p>
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
