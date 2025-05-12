<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Show payment form
     */
    public function showPaymentForm($order_id)
    {
        $order = Order::findOrFail($order_id);

        // Check if order belongs to authenticated user
        if ($order->user_id != Auth::id()) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        return view('payment.form', compact('order'));
    }

    /**
     * Process payment
     */
    public function processPayment(Request $request)
    {
        // Validate payment data
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|in:card,paypal',
            // Add other validation rules based on payment method
        ]);

        $order_id = $request->order_id;
        $order = Order::findOrFail($order_id);

        // Check if order belongs to authenticated user
        if ($order->user_id != Auth::id()) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        try {
            // Process payment based on method
            if ($request->payment_method == 'card') {
                // Process card payment
                // This would integrate with your payment gateway
                $success = $this->processCardPayment($request, $order);
            } else if ($request->payment_method == 'paypal') {
                // Process PayPal payment
                $success = $this->processPayPalPayment($request, $order);
            }

            if ($success) {
                // Update transaction status
                $transaction = Transaction::where('order_id', $order_id)->first();
                if ($transaction) {
                    $transaction->status = 'approved';
                    $transaction->save();
                }

                // Store order ID in session for confirmation page
                Session::put('order_id', $order_id);

                // After successful payment, redirect to the correct route
                return redirect()->route('cart.order.confirmation');
            } else {
                // Payment failed
                return redirect()->back()->with('error', 'Payment processing failed. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('Payment processing error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred during payment processing. Please try again later.');
        }
    }

    /**
     * Process card payment
     */
    private function processCardPayment(Request $request, Order $order)
    {
        // Implement card payment processing logic
        // This would integrate with your payment gateway like Stripe, PayStack, etc.

        // For demonstration purposes, we'll return true
        return true;
    }

    /**
     * Process PayPal payment
     */
    private function processPayPalPayment(Request $request, Order $order)
    {
        // Implement PayPal payment processing logic

        // For demonstration purposes, we'll return true
        return true;
    }

    /**
     * Handle payment callback from payment gateway
     */
    public function handlePaymentCallback(Request $request)
    {
        // Process callback data from payment gateway
        $paymentSuccessful = true; // Determine this based on callback data

        if ($paymentSuccessful) {
            // Update order and transaction status
            $order_id = $request->input('order_id');
            $transaction = Transaction::where('order_id', $order_id)->first();

            if ($transaction) {
                $transaction->status = 'approved';
                $transaction->save();
            }

            // Store order ID in session for confirmation page
            Session::put('order_id', $order_id);

            return redirect()->route('cart.order.confirmation');
        } else {
            return redirect()->route('payment.failed');
        }
    }

    /**
     * Show payment failed page
     */
    public function paymentFailed()
    {
        return view('payment.failed');
    }
}
