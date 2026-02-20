@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<!-- Page Header -->
<section class="page-header bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-2">About Us</h1>
                <p class="lead mb-0">Learn more about Milk Tea Shop</p>
            </div>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="https://images.unsplash.com/photo-1558160074-4d7d8bdf4256?w=600" 
                     alt="About Milk Tea Shop" class="img-fluid rounded shadow">
            </div>
            <div class="col-lg-6">
                <h2 class="mb-4">Our Story</h2>
                <p class="lead">
                    Milk Tea Shop was founded in 2020 with a simple mission: to bring the authentic taste 
                    of Taiwanese milk tea to our community.
                </p>
                <p>
                    What started as a small kiosk has grown into a beloved local destination for milk tea 
                    enthusiasts. We take pride in using only the finest tea leaves, fresh milk, and 
                    high-quality ingredients to create our signature drinks.
                </p>
                <p>
                    Every cup we serve is crafted with care and attention to detail. From brewing the 
                    perfect tea to cooking our tapioca pearls fresh daily, we ensure that every sip 
                    delivers an exceptional experience.
                </p>
                <div class="row mt-4">
                    <div class="col-4 text-center">
                        <h3 class="text-primary mb-0">50+</h3>
                        <small class="text-muted">Menu Items</small>
                    </div>
                    <div class="col-4 text-center">
                        <h3 class="text-primary mb-0">10K+</h3>
                        <small class="text-muted">Happy Customers</small>
                    </div>
                    <div class="col-4 text-center">
                        <h3 class="text-primary mb-0">4</h3>
                        <small class="text-muted">Years of Service</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Our Values</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-heart-fill fs-1 text-danger mb-3"></i>
                        <h4>Quality First</h4>
                        <p class="text-muted mb-0">
                            We never compromise on quality. Every ingredient is carefully selected 
                            to ensure the best taste and experience.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-leaf fs-1 text-success mb-3"></i>
                        <h4>Fresh Ingredients</h4>
                        <p class="text-muted mb-0">
                            We use fresh, natural ingredients sourced from trusted suppliers. 
                            No artificial preservatives or additives.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-people-fill fs-1 text-primary mb-3"></i>
                        <h4>Customer Focus</h4>
                        <p class="text-muted mb-0">
                            Your satisfaction is our priority. We're always here to listen 
                            and improve based on your feedback.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Meet Our Team</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-person-circle fs-1 text-primary"></i>
                        </div>
                        <h5 class="mb-1">Charlene Makilang</h5>
                        <p class="text-muted mb-0">Founder & CEO</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-person-circle fs-1 text-primary"></i>
                        </div>
                        <h5 class="mb-1">Christian Trimucha</h5>
                        <p class="text-muted mb-0">Head Barista</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-person-circle fs-1 text-primary"></i>
                        </div>
                        <h5 class="mb-1">Angel Serdan</h5>
                        <p class="text-muted mb-0">Operations Manager</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-person-circle fs-1 text-primary"></i>
                        </div>
                        <h5 class="mb-1">Justin Bieber</h5>
                        <p class="text-muted mb-0">Customer Service</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.page-header {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}
</style>
@endpush
