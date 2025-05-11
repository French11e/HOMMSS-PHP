<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

class OrderService
{
    public function createOrder($addressData, $paymentMethod)
    {
        $user_id = Auth::id();
        
        // Create order
        $order = new Order();
        $order->user_id = $user_id;
        $order->subtotal = str_replace(',', '', Cart::instance('cart')->subtotal());
        $order->discount = 0;
        $order->tax = str_replace(',', '', Cart::instance('cart')->tax());
        $order->total = str_replace(',', '', Cart::instance('cart')->total());
        
        // Set address data
        $this->setOrderAddress($order, $addressData);
        $order->status = 'ordered';
        $order->save();
        
        // Create order items
        $this->createOrderItems($order->id);
        
        // Create transaction
        $this->createTransaction($order->id, $user_id, $paymentMethod);
        
        // Clear cart
        Cart::instance('cart')->destroy();
        
        return $order;
    }
    
    private function setOrderAddress($order, $address)
    {
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
        
        return $order;
    }
    
    private function createOrderItems($orderId)
    {
        foreach (Cart::instance('cart')->content() as $item) {
            $orderItem = new OrderItem();
            $orderItem->product_id = $item->id;
            $orderItem->order_id = $orderId;
            $orderItem->price = $item->price;
            $orderItem->quantity = $item->qty;
            $orderItem->save();
        }
    }
    
    private function createTransaction($orderId, $userId, $paymentMethod)
    {
        $transaction = new Transaction();
        $transaction->user_id = $userId;
        $transaction->order_id = $orderId;
        $transaction->mode = $paymentMethod;
        $transaction->status = 'pending';
        $transaction->save();
        
        return $transaction;
    }
}

