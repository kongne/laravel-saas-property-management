<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0d6efd">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/icons/icon-512x512.png">
    <title>{{ config('app.name') }} - Smart Property Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); }
        .feature-card { transition: transform 0.3s ease, box-shadow 0.3s ease; border: none; border-radius: 16px; }
        .feature-card:hover { transform: translateY(-5px); box-shadow: 0 12px 40px rgba(0,0,0,0.12); }
        .stat-card { border-radius: 12px; border: none; }
        .pricing-card { border-radius: 20px; transition: transform 0.3s ease; border: 1px solid #e9ecef; }
        .pricing-card:hover { transform: translateY(-8px); }
        .pricing-card.featured { border: 2px solid #0d6efd; transform: scale(1.02); }
        .testimonial-card { border: none; border-radius: 16px; background: #f8f9fa; }
        .btn-cta { padding: 14px 40px; border-radius: 50px; font-weight: 600; }
        .section-padding { padding: 100px 0; }
        @media (max-width: 768px) { .section-padding { padding: 60px 0; } }
        .navbar-blur { backdrop-filter: blur(10px); background: rgba(255,255,255,0.95) !important; border-bottom: 1px solid rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-blur">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="/">
                <i class="bi bi-building text-primary me-2"></i>{{ config('app.name') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#pricing">Pricing</a></li>
                    <li class="nav-item"><a class="nav-link" href="#testimonials">Testimonials</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    @guest
                        <li class="nav-item ms-lg-3"><a class="btn btn-outline-primary btn-sm px-4" href="{{ route('login') }}">Sign In</a></li>
                        <li class="nav-item"><a class="btn btn-primary btn-sm px-4" href="{{ route('register') }}">Get Started</a></li>
                    @else
                        <li class="nav-item ms-lg-3"><a class="btn btn-primary btn-sm px-4" href="{{ route('dashboard') }}">Dashboard</a></li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-gradient text-white position-relative overflow-hidden" style="min-height: 100vh; padding-top: 80px;">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center" style="min-height: calc(100vh - 80px);">
                <div class="col-lg-7">
                    <span class="badge bg-primary bg-opacity-25 text-primary-light px-3 py-2 mb-4 rounded-pill fs-6 fw-normal">
                        <i class="bi bi-shield-check me-1"></i> Trusted by 500+ Property Managers
                    </span>
                    <h1 class="display-3 fw-bold mb-4 lh-1">Smart Property<br>Management <span class="text-primary">Simplified</span></h1>
                    <p class="lead fs-5 mb-5 text-light opacity-75 col-lg-10">Track properties, manage tenants, collect payments, and handle maintenance requests — all from one powerful, secure platform.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg btn-cta px-5 py-3">
                            Start Free Trial <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="#features" class="btn btn-outline-light btn-lg btn-cta px-5 py-3">
                            Explore Features
                        </a>
                    </div>
                    <div class="d-flex gap-4 mt-5 pt-3">
                        <div><small class="text-light opacity-75">Trusted by</small>
                            <div class="d-flex gap-3 mt-2">
                                <span class="fw-bold fs-5">{{ number_format(500) }}+</span>
                                <span class="text-light opacity-50">|</span>
                                <span class="fw-bold fs-5">{{ number_format(10000) }}+</span>
                            </div>
                            <div class="d-flex gap-3"><small class="text-light opacity-75">Managers</small><small class="text-light opacity-75">Properties</small></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block text-center">
                    <div class="position-relative">
                        <div class="bg-primary bg-opacity-10 rounded-4 p-5 shadow-lg">
                            <i class="bi bi-building fs-1 text-primary"></i>
                            <i class="bi bi-people fs-1 text-success ms-3"></i>
                            <i class="bi bi-credit-card fs-1 text-warning ms-3"></i>
                            <i class="bi bi-tools fs-1 text-info ms-3"></i>
                            <div class="mt-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="position-absolute bottom-0 start-0 end-0" style="height: 150px; background: linear-gradient(transparent, #fff);"></div>
    </section>

    <section id="features" class="section-padding bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3 rounded-pill">Everything You Need</span>
                <h2 class="display-6 fw-bold">Powerful Features for Modern Management</h2>
                <p class="text-muted fs-5 mt-2">All the tools you need to manage your properties efficiently</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card p-4 h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-primary bg-opacity-10 rounded-3 d-inline-flex p-3 mb-3"><i class="bi bi-building fs-2 text-primary"></i></div>
                            <h5 class="fw-bold">Property Management</h5>
                            <p class="text-muted mb-0">Manage multiple properties with detailed info, images, and unit tracking. Organize by status, location, and type.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card p-4 h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-success bg-opacity-10 rounded-3 d-inline-flex p-3 mb-3"><i class="bi bi-people fs-2 text-success"></i></div>
                            <h5 class="fw-bold">Tenant Portal</h5>
                            <p class="text-muted mb-0">Tenants can view their lease, make payments, submit maintenance requests, and track their history.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card p-4 h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-warning bg-opacity-10 rounded-3 d-inline-flex p-3 mb-3"><i class="bi bi-credit-card fs-2 text-warning"></i></div>
                            <h5 class="fw-bold">Payment Tracking</h5>
                            <p class="text-muted mb-0">Track rent payments, late fees, and generate receipts. Get notified of overdue and pending payments.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card p-4 h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-danger bg-opacity-10 rounded-3 d-inline-flex p-3 mb-3"><i class="bi bi-tools fs-2 text-danger"></i></div>
                            <h5 class="fw-bold">Maintenance Tracking</h5>
                            <p class="text-muted mb-0">Submit, assign, and track maintenance requests with priority levels, images, and resolution tracking.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card p-4 h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-info bg-opacity-10 rounded-3 d-inline-flex p-3 mb-3"><i class="bi bi-file-text fs-2 text-info"></i></div>
                            <h5 class="fw-bold">Lease Management</h5>
                            <p class="text-muted mb-0">Create and manage leases with automatic rent reminders, renewals, and termination workflows.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card p-4 h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-purple bg-opacity-10 rounded-3 d-inline-flex p-3 mb-3" style="background: rgba(111,66,193,0.1) !important;"><i class="bi bi-shield-check fs-2" style="color: #6f42c1;"></i></div>
                            <h5 class="fw-bold">Role-Based Access</h5>
                            <p class="text-muted mb-0">Granular permissions for admins, landlords, and tenants. Each role sees exactly what they need.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 mb-3 rounded-pill">Real-time Dashboard</span>
                    <h2 class="display-6 fw-bold">See Your Business at a Glance</h2>
                    <p class="text-muted fs-5 mt-3">Get instant insights into your property portfolio with role-specific dashboards showing key metrics.</p>
                    <ul class="list-unstyled mt-4">
                        <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Real-time revenue tracking and forecasting</li>
                        <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Occupancy rates and vacancy alerts</li>
                        <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Overdue payment notifications</li>
                        <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Maintenance request summaries</li>
                    </ul>
                </div>
                <div class="col-lg-6 mt-4 mt-lg-0">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                        <div class="card-body p-5" style="background: linear-gradient(135deg, #1a1a2e, #0f3460); color: white;">
                            <div class="row g-3">
                                <div class="col-6"><div class="p-3 rounded-3" style="background: rgba(255,255,255,0.1);"><small>Revenue</small><h3 class="mb-0">$12,450</h3></div></div>
                                <div class="col-6"><div class="p-3 rounded-3" style="background: rgba(255,255,255,0.1);"><small>Properties</small><h3 class="mb-0">24</h3></div></div>
                                <div class="col-6"><div class="p-3 rounded-3" style="background: rgba(255,255,255,0.1);"><small>Occupancy</small><h3 class="mb-0">94%</h3></div></div>
                                <div class="col-6"><div class="p-3 rounded-3" style="background: rgba(255,255,255,0.1);"><small>Pending</small><h3 class="mb-0 text-warning">3</h3></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="pricing" class="section-padding bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3 rounded-pill">Simple Pricing</span>
                <h2 class="display-6 fw-bold">Plans That Scale With You</h2>
                <p class="text-muted fs-5 mt-2">Start free, upgrade when you grow</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <div class="card pricing-card p-4 h-100">
                        <div class="card-body">
                            <h5 class="fw-bold">Starter</h5>
                            <h2 class="fw-bold mb-1">$19<small class="fs-6 fw-normal text-muted">/mo</small></h2>
                            <p class="text-muted small">For individual landlords</p>
                            <hr>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Up to 10 properties</li>
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Basic tenant management</li>
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Payment tracking</li>
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Email support</li>
                            </ul>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 mt-3">Start Free Trial</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card pricing-card featured p-4 h-100">
                        <div class="card-body">
                            <span class="badge bg-primary mb-3">Most Popular</span>
                            <h5 class="fw-bold">Professional</h5>
                            <h2 class="fw-bold mb-1">$49<small class="fs-6 fw-normal text-muted">/mo</small></h2>
                            <p class="text-muted small">For professional managers</p>
                            <hr>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Up to 50 properties</li>
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Advanced tenant portal</li>
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Automated rent reminders</li>
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Maintenance workflows</li>
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Priority support</li>
                            </ul>
                            <a href="{{ route('register') }}" class="btn btn-primary w-100 mt-3">Start Free Trial</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card pricing-card p-4 h-100">
                        <div class="card-body">
                            <h5 class="fw-bold">Enterprise</h5>
                            <h2 class="fw-bold mb-1">$99<small class="fs-6 fw-normal text-muted">/mo</small></h2>
                            <p class="text-muted small">For large agencies</p>
                            <hr>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Unlimited properties</li>
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>API access</li>
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Custom branding</li>
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Advanced reporting</li>
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Dedicated account manager</li>
                            </ul>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 mt-3">Contact Sales</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="testimonials" class="section-padding bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3 rounded-pill">Testimonials</span>
                <h2 class="display-6 fw-bold">Trusted by Property Managers</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card testimonial-card p-4 h-100 shadow-sm">
                        <div class="card-body">
                            <div class="mb-3 text-warning">★★★★★</div>
                            <p class="mb-3">"This platform has completely transformed how I manage my 15 properties. The tenant portal alone saved me hours of communication."</p>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:40px;height:40px;">SM</div>
                                <div class="ms-3"><small class="fw-bold d-block">Sarah Mitchell</small><small class="text-muted">Property Manager, 15+ properties</small></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card testimonial-card p-4 h-100 shadow-sm">
                        <div class="card-body">
                            <div class="mb-3 text-warning">★★★★★</div>
                            <p class="mb-3">"The payment tracking and automated reminders reduced my late payments by 80%. Absolutely essential tool for any landlord."</p>
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:40px;height:40px;">JD</div>
                                <div class="ms-3"><small class="fw-bold d-block">James Donnelly</small><small class="text-muted">Landlord, 8 properties</small></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card testimonial-card p-4 h-100 shadow-sm">
                        <div class="card-body">
                            <div class="mb-3 text-warning">★★★★★</div>
                            <p class="mb-3">"As a tenant, being able to submit maintenance requests and track payments online is incredibly convenient. Great platform!"</p>
                            <div class="d-flex align-items-center">
                                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:40px;height:40px;">AK</div>
                                <div class="ms-3"><small class="fw-bold d-block">Alex Kim</small><small class="text-muted">Tenant</small></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="section-padding" style="background: linear-gradient(135deg, #1a1a2e, #0f3460);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center text-white">
                    <h2 class="display-6 fw-bold mb-4">Ready to Simplify Your Property Management?</h2>
                    <p class="lead mb-5 opacity-75">Join thousands of property managers who trust our platform. Start your free trial today.</p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg btn-cta px-5 py-3">
                            Get Started Free <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="mailto:support@propertymanager.com" class="btn btn-outline-light btn-lg btn-cta px-5 py-3">
                            Contact Sales
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-building text-primary me-2"></i>{{ config('app.name') }}</h5>
                    <p class="text-light opacity-75 small">Your all-in-one property management solution. Secure, reliable, and easy to use.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white opacity-50 hover-opacity-100"><i class="bi bi-twitter-x fs-5"></i></a>
                        <a href="#" class="text-white opacity-50 hover-opacity-100"><i class="bi bi-linkedin fs-5"></i></a>
                        <a href="#" class="text-white opacity-50 hover-opacity-100"><i class="bi bi-facebook fs-5"></i></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold mb-3">Product</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="#features" class="text-white opacity-75 text-decoration-none">Features</a></li>
                        <li class="mb-2"><a href="#pricing" class="text-white opacity-75 text-decoration-none">Pricing</a></li>
                        <li class="mb-2"><a href="{{ route('register') }}" class="text-white opacity-75 text-decoration-none">Sign Up</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold mb-3">Company</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="#" class="text-white opacity-75 text-decoration-none">About</a></li>
                        <li class="mb-2"><a href="#" class="text-white opacity-75 text-decoration-none">Blog</a></li>
                        <li class="mb-2"><a href="#contact" class="text-white opacity-75 text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold mb-3">Legal</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="#" class="text-white opacity-75 text-decoration-none">Privacy</a></li>
                        <li class="mb-2"><a href="#" class="text-white opacity-75 text-decoration-none">Terms</a></li>
                        <li class="mb-2"><a href="#" class="text-white opacity-75 text-decoration-none">Security</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold mb-3">Support</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="#" class="text-white opacity-75 text-decoration-none">Documentation</a></li>
                        <li class="mb-2"><a href="#" class="text-white opacity-75 text-decoration-none">FAQ</a></li>
                        <li class="mb-2"><a href="mailto:support@propertymanager.com" class="text-white opacity-75 text-decoration-none">Email</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 opacity-25">
            <div class="text-center small text-light opacity-50">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved. Built with <i class="bi bi-shield-check text-primary"></i> security first.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @if(session('success'))
    <script>document.addEventListener('DOMContentLoaded', function(){var t=document.createElement('div');t.className='position-fixed bottom-0 end-0 p-3';t.style.zIndex='9999';t.innerHTML='<div class="toast show align-items-center text-bg-success border-0" role="alert"><div class="d-flex"><div class="toast-body">'+'{{ session('success') }}'+'</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>';document.body.appendChild(t);});</script>
    @endif
    <script>if('serviceWorker'in navigator){window.addEventListener('load',function(){navigator.serviceWorker.register('/sw.js').catch(function(){})});}</script>
</body>
</html>
