<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        //
    }


    public function AddtoCart($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to add items to the cart.');
        }
        $product = Product::find($id);
        if (!$product) {
            return redirect()->route('shop')->with('error', 'Product not found.');
        }
        $cart = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();
        if ($cart) {
            $cart->quantity += 1;
            $cart->save();
        } else {
            $cart = Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'title' => $product->title,
                'price' => $product->price,
                'quantity' => 1,
            ]);
        }
        $cartCount = Cart::where('user_id', auth()->id())->count();
        return redirect()->back()->with('cartCount', $cartCount)->with('success', 'Product added to cart!');
    }

    public function Cart()
    {

        $cart = Cart::where('user_id', auth()->id())->with('products')->get();
        $total = collect($cart)->sum(function ($cart) {
            return $cart['price'] * $cart['quantity'];
        });
        return view('frontend.cart', compact('cart', 'total'));
    }

    public function RemoveItem($id)
    {
        // Find and delete the item
        $cartItem = Cart::where('id', $id)->where('user_id', auth()->id())->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return redirect()->back()->with('success', 'Product remove to cart!'); // Redirect back to the cart view
    }

    public function UpdateCart(Request $request, $id)
    {
        // Validate the quantity input
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Find the cart item for the authenticated user
        $cartItem = Cart::where('id', $id)->where('user_id', auth()->id())->first();

        // If the cart item doesn't exist, return an error response
        if (!$cartItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'Item not found.',
            ], 404);
        }

        // Update the quantity of the cart item
        $cartItem->quantity = $validatedData['quantity'];
        $cartItem->save();

        // Calculate the total price for this cart item
        $totalPrice = $cartItem->quantity * $cartItem->price;

        // Calculate the grand total of all items in the user's cart
        // Sum the price*quantity directly using DB
        $grandTotal = Cart::where('user_id', auth()->id())
            ->selectRaw('SUM(quantity * price) as total')
            ->value('total');  // Retrieves the computed total

        // Return the updated data
        return response()->json([
            'status' => 'success',
            'new_quantity' => $cartItem->quantity,
            'total_price' => number_format($totalPrice, 2),
            'grandtotal' => number_format($grandTotal, 2),
        ]);
    }



    public function CheckOut()
    {
        $cart = Cart::where('user_id', auth()->id())->with('products')->get();
        $total = collect($cart)->sum(function ($cart) {
            return $cart['price'] * $cart['quantity'];
        });

        return view('frontend.checkout', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'total_amount' => 'required',
                'address' => 'required',
                'payment_method' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()], 500);
            }

            $orderid = 'ORD-' . strtoupper(\Str::random(10));


            // return $request->total_amount;
            if ($request->payment_method == 'stripe') {
                $token = $request->input('stripeToken');
                Stripe\Charge::create([
                    "amount" => $request->total_amount * 100,
                    "currency" => "usd",
                    "source" => $request->stripeToken,
                    "description" => "This is a Dr Peter Checkout transaction"
                ]);
            }
            $products = Cart::where('user_id', auth()->id())->get();
            foreach ($products as $product) {
                OrderDetail::create([
                    'order_no' => $orderid,
                    'product_id' => $product['product_id'], // Use array syntax
                    'price' => $product['price'],
                    'quantity' => $product['quantity'],
                ]);
            }

            $order = new Order();
            $order_data = $request->all();
            $order->order_number =  $orderid;
            $order->user_id =  auth()->id();
            $order->name = $request->name;
            $order->email = $request->email;
            $order->country = $request->country;
            $order->payment_status = ($request->payment_method == 'cod') ? 'unpaid' : 'paid';
            $order->post_code = $request->post_code;
            $order->phone = $request->phone;
            $order->total_amount = $request->total_amount;
            $order->address = $request->address;
            $order->status = 'new';
            $order->payment_method = $request->payment_method;
            // $order->role = Auth::user()->current_role;
            $order->save();

            Cart::where('user_id', auth()->id())->delete();

            return redirect()->to('/')->with([
                // 'success' => true,
                'success' => 'Order Placed Successfully',
                'data' => $order
            ]);
        } catch (\Eception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
