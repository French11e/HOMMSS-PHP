@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="mb-4">Terms of Service</h1>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2>1. Introduction</h2>
                        <p>Welcome to HOMMSS. These Terms of Service govern your use of our website and services.</p>

                        <h2>2. Acceptance of Terms</h2>
                        <p>By accessing or using our services, you agree to be bound by these Terms of Service.</p>

                        <h2>3. User Accounts</h2>
                        <p>When you create an account with us, you must provide accurate and complete information. You are responsible for maintaining the security of your account.</p>

                        <h2>4. Products and Services</h2>
                        <p>We strive to provide accurate descriptions of our products. However, we do not warrant that product descriptions or other content is accurate, complete, or error-free.</p>

                        <h2>5. Orders and Payments</h2>
                        <p>All orders are subject to acceptance and availability. We reserve the right to refuse or cancel any order for any reason.</p>

                        <h2>6. Shipping and Delivery</h2>
                        <p>Delivery times are estimates only. We are not responsible for delays caused by factors beyond our control.</p>

                        <h2>7. Returns and Refunds</h2>
                        <p>Please refer to our Refund Policy for information about returns and refunds.</p>

                        <h2>8. Intellectual Property</h2>
                        <p>All content on our website is the property of HOMMSS and is protected by copyright and other intellectual property laws.</p>

                        <h2>9. Limitation of Liability</h2>
                        <p>To the maximum extent permitted by law, we shall not be liable for any indirect, incidental, special, consequential, or punitive damages.</p>

                        <h2>10. Changes to Terms</h2>
                        <p>We reserve the right to modify these terms at any time. Your continued use of our services after such changes constitutes your acceptance of the new terms.</p>

                        <h2>11. Contact Information</h2>
                        <p>If you have any questions about these Terms, please contact us at support@hommss.com.</p>

                        <p class="mt-4 text-muted">Last updated: {{ date('F d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection