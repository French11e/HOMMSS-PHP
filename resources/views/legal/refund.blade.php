@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="mb-4">Refund Policy</h1>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2>1. Return Eligibility</h2>
                        <p>You may return most new, unopened items within 30 days of delivery for a full refund.</p>

                        <h2>2. Return Process</h2>
                        <p>To initiate a return, please contact our customer service team at returns@hommss.com with your order number and reason for return.</p>

                        <h2>3. Refunds</h2>
                        <p>Once we receive and inspect your return, we will notify you of the approval or rejection of your refund. If approved, your refund will be processed within 5-7 business days.</p>

                        <h2>4. Late or Missing Refunds</h2>
                        <p>If you haven't received a refund yet, first check your bank account again. Then contact your credit card company, it may take some time before your refund is officially posted.</p>

                        <h2>5. Damaged or Defective Items</h2>
                        <p>If you receive a damaged or defective item, please contact us immediately at support@hommss.com with photos of the damaged item.</p>

                        <h2>6. Exchanges</h2>
                        <p>We only replace items if they are defective or damaged. If you need to exchange it for the same item, send us an email at returns@hommss.com.</p>

                        <h2>7. Shipping</h2>
                        <p>You will be responsible for paying for your own shipping costs for returning your item. Shipping costs are non-refundable.</p>

                        <p class="mt-4 text-muted">Last updated: {{ date('F d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection