@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0">
                <i class="fas fa-user me-2"></i> Customer Details
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Left Column -->
                <div class="col-md-6">
                    <p><strong><i class="fas fa-user me-1"></i> Name:</strong> {{ $customer->name }}</p>
                    <p><strong><i class="fas fa-envelope me-1"></i> Email:</strong> {{ $customer->email }}</p>
                    <p><strong><i class="fas fa-phone me-1"></i> Phone:</strong> {{ $customer->phone }}</p>
                    <p><strong><i class="fas fa-map-marker-alt me-1"></i> Address:</strong> {{ $customer->address }}</p>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <p><strong><i class="fas fa-building me-1"></i> Company:</strong> {{ $customer->company }}</p>
                    <p><strong><i class="fas fa-credit-card me-1"></i> Credit Limit:</strong> {{ $customer->credit_limit }}</p>
                    <p><strong><i class="fas fa-map-pin me-1"></i> Area:</strong> {{ $customer->area }}</p>
                    <p><strong><i class="fas fa-cube me-1"></i> Brick:</strong> {{ $customer->brick }}</p>
                    <p><strong><i class="fas fa-user-tie me-1"></i> Salesman:</strong> {{ $customer->salesman }}</p>
                    <p><strong><i class="fas fa-money-bill-wave me-1"></i> Currencies:</strong>
                        @if($customer->usd) USD @endif
                        @if($customer->afn) AFN @endif
                        @if($customer->pkr) PKR @endif
                    </p>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>
@endsection