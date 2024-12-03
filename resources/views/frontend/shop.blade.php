@extends('frontend.layouts.master')
@section('content')
    <!-- Single Page Header End -->
       @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Fruits Shop Start-->
    <div class="container-fluid fruite py-5">
        <div class="container py-5">
            <h1 class="mb-4">Shop</h1>
            <div class="row g-4">
                <div class="col-lg-12">
                   
                    <div class="row g-4">
                        
                        <div class="col-lg-12">
                            <div class="row g-4 justify-content-center">
                                @foreach ($product as $item)
                                    <div class="col-md-6 col-lg-6 col-xl-4">
                                        <div class="rounded position-relative fruite-item">
                                            <a href="{{ url('/ProductDetails/' . $item['id']) }}">
                                                <div class="fruite-img">
                                                    <img src="{{ $item['photo'] }}"
                                                        class="img-fluid w-100 rounded-top" alt="">
                                                </div>
                                                <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                    style="top: 10px; left: 10px;">Fruits</div>
                                                <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                    <h4>{{ $item['title'] }}</h4>
                                                    <p>{{ $item['description'] }}</p>
                                            </a>
                                            <div class="d-flex justify-content-between flex-lg-wrap">
                                                <p class="text-dark fs-5 fw-bold mb-0">${{ $item['price'] }} /
                                                    kg
                                                </p>

                                                    <button
                                                        class="btn border border-secondary rounded-pill px-3 text-primary add-to-cart"
                                                        data-product-id="{{ $item->id }}" @if (auth()->check())
                                                        onclick="location.href='{{ url('AddtoCart/' . $item->id) }}'" @else data-product-id="{{ $item->id }}" @endif>
                                                        Add to Cart
                                                    </button>

                                            </div>
                                        </div>
                                    </div>
                            </div>
                            @endforeach

                            <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-0">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="loginModalLabel">Login to Continue</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Login Form Start -->
                                            <form id="loginForm" method="POST" action="{{ route('login') }}">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email address</label>
                                                    <input type="email" class="form-control" id="email"
                                                        name="email" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input type="password" class="form-control" id="password"
                                                        name="password" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary w-100">Login</button>
                                            </form>
                                            <hr>
                                            <a href="{{ url('Register') }}"
                                                class="btn btn-primary w-100">Register</a>

                                            <!-- Login Form End -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-12">
                                <div class="pagination d-flex justify-content-center mt-5">
                                    <a href="#" class="rounded">&laquo;</a>
                                    <a href="#" class="active rounded">1</a>
                                    <a href="#" class="rounded">2</a>
                                    <a href="#" class="rounded">3</a>
                                    <a href="#" class="rounded">4</a>
                                    <a href="#" class="rounded">5</a>
                                    <a href="#" class="rounded">6</a>
                                    <a href="#" class="rounded">&raquo;</a>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Fruits Shop End-->
@endsection
