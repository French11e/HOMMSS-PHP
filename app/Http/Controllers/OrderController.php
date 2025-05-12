public function confirmation($order_id)
{
    $order = Order::with('transaction')->findOrFail($order_id);

    // If transaction doesn't exist for some reason, create a default one
    if (!$order->transaction) {
        $transaction = new Transaction();
        $transaction->user_id = $order->user_id;
        $transaction->order_id = $order->id;
        $transaction->mode = 'cod'; // Default to COD if missing
        $transaction->status = 'pending';
        $transaction->save();

        // Reload order with the new transaction
        $order = Order::with('transaction')->findOrFail($order_id);
    }

    return view('order-confirmation', compact('order'));
}

// Add a method to redirect to the correct route
public function redirectToConfirmation($order_id)
{
    Session::put('order_id', $order_id);
    return redirect()->route('cart.order.confirmation');
}
