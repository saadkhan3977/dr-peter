@extends('frontend.layouts.master')
@section('content')
    <!-- Single Page Header End -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    <!-- Cart Page Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="table-responsive">
                {{-- if(count($cart)> 0) --}}
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Products</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $item)
                            <tr>
                                <th scope="row">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->products->photo }}" class="img-fluid me-5 rounded-circle"
                                            style="width: 80px; height: 80px;" alt="">
                                    </div>
                                </th>
                                <td>
                                    <p class="mb-0 mt-4">{{ $item['title'] }}</p>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">{{ $item['price'] }} $</p>
                                </td>
                                <td>
                                    <form action="{{ route('cart.update', $item['id']) }}" method="POST"
                                        id="cart-update-form-{{ $item['id'] }}">
                                        @csrf
                                        {{-- @method('post') --}}
                                        <div class="input-group quantity mt-4" style="width: 100px;">
                                            <div class="input-group-btn">
                                                <button type="button"
                                                    class="btn btn-sm btn-minus rounded-circle bg-light border"
                                                    data-id="{{ $item['id'] }}">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="text" name="quantity"
                                                class="form-control form-control-sm text-center border-0"
                                                value="{{ $item['quantity'] }}" readonly>
                                            <div class="input-group-btn">
                                                <button type="button"
                                                    class="btn btn-sm btn-plus rounded-circle bg-light border"
                                                    data-id="{{ $item['id'] }}">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    {{-- <p class="mb-0 mt-4 total-price">{{ $item['total'] }} $</p> --}}
                                    {{-- <p class="mb-0 mt-4 total-price" id="total-price-{{ $item['id'] }}">
                                        $ {{ $total }} </p> --}}
                                <td id="total-price-{{ $item['id'] }}">
                                    ${{ number_format($item->quantity * $item->price, 2) }}
                                </td>
                                </td>
                                <td>
                                    <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-md rounded-circle bg-light border mt-4">
                                            <i class="fa fa-times text-danger"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-5">
                <input type="text" class="border-0 border-bottom rounded me-5 py-3 mb-4"
                    placeholder="Coupon Code">
                <button class="btn border-secondary rounded-pill px-4 py-3 text-primary" type="button">Apply
                    Coupon</button>
            </div>
            <div class="row g-4 justify-content-end">
                <div class="col-8"></div>
                <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                    <div class="bg-light rounded">
                        <div class="p-4">
                            <h1 class="display-6 mb-4">Cart <span class="fw-normal">Total</span></h1>
                            <div class="d-flex justify-content-between mb-4">
                                <h5 class="mb-0 me-4">Subtotal:</h5>
                                {{-- <p class="mb-0"><strong><span
                                            id="grandtotal">${{ session('grandtotal', 0) }}</span></strong></p> --}}
                                <p class="mb-0"><strong><span id="grandtotal">
                                            @if ($cart->isEmpty())
                                                $0.00
                                            @else
                                                ${{ number_format($total, 2) }}
                                            @endif
                                        </span></strong></p>
                            </div>
                        </div>
                        <a href="{{ url('CheckOut') }}"
                            class="btn border-secondary rounded-pill px-4 py-3 text-primary text-uppercase mb-4 ms-4"
                            type="button">Proceed Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart Page End -->

@endsection