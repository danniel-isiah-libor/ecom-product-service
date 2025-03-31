<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\StoreRequest;
use App\Http\Requests\Cart\UpdateRequest;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = request()->user();

        $cart = Cart::with('product')->whereUserId($user->id)->get();

        return response()->json($cart);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $validatedRequest = $request->validated();

        $cart = Cart::whereUserId($validatedRequest['user_id'])
            ->whereProductId($validatedRequest['product_id'])
            ->first();

        if ($cart) {
            $cart->update([
                'quantity' => $cart->quantity + $validatedRequest['quantity'],
            ]);
        } else {
            $cart = Cart::create($validatedRequest);
        }

        // $cart->product->update([
        //     'stock' => $cart->product->stock - $validatedRequest['quantity'],
        // ]);

        return response()->noContent();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Cart $cart)
    {
        $validatedRequest = $request->validated();

        $cart->update($validatedRequest);

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();

        return response()->noContent();
    }
}
