<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }

    public function add_to_cart(Request $request)
    {
        Cart::instance('cart')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        return redirect()->back();
    }

    public function increase_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function decrease_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }

    public function empty_cart()
    {
        Cart::instance('cart')->destroy();
        return redirect()->back();
    }

    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $address = Address::where('user_id', Auth::user()->id)->where('isdefault')->first();
        return view('checkout', compact('address'));
    }

    public function place_an_order(Request $request)
    {
        $user_id = Auth::user()->id;
        
        // Validate payment method
        $request->validate([
            'mode' => 'required|in:card,gcash,cod',
        ], [
            'mode.required' => 'Please select a payment method',
            'mode.in' => 'Invalid payment method selected',
        ]);

        // Process address selection or creation
        if ($request->has('selected_address') && !empty($request->selected_address)) {
            $address = Address::where('id', $request->selected_address)
                              ->where('user_id', $user_id)
                              ->firstOrFail();
        } else {
            // Validate new address inputs
            $request->validate([
                'name' => 'required|max:100',
                'phone' => 'required|numeric|digits:11',
                'postal' => 'required|numeric|digits:4',
                'barangay' => 'required',
                'city' => 'required',
                'province' => 'required',
                'region' => 'required',
                'address' => 'required',
                'landmark' => 'required',
            ]);

            // Create new address
            $address = new Address();
            $address->name = $request->name;
            $address->phone = $request->phone;
            $address->postal = $request->postal;
            $address->barangay = $request->barangay;
            $address->city = $request->city;
            $address->province = $request->province;
            $address->region = $request->region;
            $address->address = $request->address;
            $address->landmark = $request->landmark;
            $address->country = 'Philippines';
            $address->user_id = $user_id;
            
            // Save as default if requested
            if ($request->has('save_as_default')) {
                // If setting as default, unset any existing default
                Address::where('user_id', $user_id)->update(['isdefault' => false]);
                $address->isdefault = true;
            }
            
            $address->save();
        }

        // Create order
        $order = new Order();
        $order->user_id = $user_id;
        $order->subtotal = str_replace(',', '', Cart::instance('cart')->subtotal());
        $order->discount = 0;
        $order->tax = str_replace(',', '', Cart::instance('cart')->tax());
        $order->total = str_replace(',', '', Cart::instance('cart')->total());
        $order->name = $address->name;
        $order->phone = $address->phone;
        $order->postal = $address->postal;
        $order->barangay = $address->barangay;
        $order->city = $address->city;
        $order->province = $address->province;
        $order->region = $address->region;
        $order->address = $address->address;
        $order->landmark = $address->landmark;
        $order->country = $address->country;
        $order->status = 'ordered';
        $order->save();

        // Create order items
        foreach (Cart::instance('cart')->content() as $item) {
            $orderItem = new OrderItem();
            $orderItem->product_id = $item->id;
            $orderItem->order_id = $order->id;
            $orderItem->price = $item->price;
            $orderItem->quantity = $item->qty;
            $orderItem->save();
        }

        // Create transaction record with payment method - ENSURE THIS RUNS
        $transaction = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->order_id = $order->id;
        $transaction->mode = $request->mode;
        
        // Set appropriate status based on payment method
        if ($request->mode == 'cod') {
            $transaction->status = 'pending';
        } else if ($request->mode == 'card') {
            $transaction->status = 'pending'; // Will be updated after payment processing
        } else if ($request->mode == 'gcash') {
            $transaction->status = 'pending'; // Will be updated after payment processing
        }
        
        $transaction->save();

        // Clear cart
        Cart::instance('cart')->destroy();

        // Store order ID in session
        Session::put('order_id', $order->id);
        
        // Redirect to order confirmation page with correct route name
        return redirect()->route('cart.order.confirmation');
    }

    public function setAmountforCheckout()
    {
        if (!Cart::instance('cart')->content()->count() > 0) {
            Session::forget('checkout');
            return;
        }

        if (Session::has('coupon')) {
            Session::put('checkout', [
                'discount' => str_replace(',', '', Session::get('discounts')['discount']),
                'subtotal' => str_replace(',', '', Session::get('discounts')['subtotal']),
                'tax' => str_replace(',', '', Session::get('discounts')['tax']),
                'total' => str_replace(',', '', Session::get('discounts')['total']),
            ]);
        } else {
            Session::put('checkout', [
                'discount' => 0,
                'subtotal' => str_replace(',', '', Cart::instance('cart')->subtotal()),
                'tax' => str_replace(',', '', Cart::instance('cart')->tax()),
                'total' => str_replace(',', '', Cart::instance('cart')->total()),
            ]);
        }
    }


    public function order_confirmation()
    {
        if (Session::has('order_id')) {
            $order = Order::find(Session::get('order_id'));
            return view('order-confirmation', compact('order'));
        }

        return redirect()->route('cart.index');
    }
}





