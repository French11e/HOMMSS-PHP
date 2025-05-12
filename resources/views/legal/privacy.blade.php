@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="mb-4">Privacy Policy</h1>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2>1. Information We Collect</h2>
                        <p>We collect personal information that you provide to us, such as your name, email address, shipping address, and payment information.</p>

                        <h2>2. How We Use Your Information</h2>
                        <p>We use your information to process orders, provide customer service, and improve our services.</p>

                        <h2>3. Information Sharing</h2>
                        <p>We do not sell or rent your personal information to third parties. We may share your information with service providers who help us operate our business.</p>

                        <h2>4. Cookies and Tracking Technologies</h2>
                        <p>We use cookies and similar technologies to enhance your experience on our website.</p>

                        <h2>5. Data Security</h2>
                        <p>We implement appropriate security measures to protect your personal information.</p>

                        <h2>6. Your Rights</h2>
                        <p>You have the right to access, correct, or delete your personal information.</p>

                        <h2>7. Changes to This Policy</h2>
                        <p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new policy on this page.</p>

                        <h2>8. Contact Us</h2>
                        <p>If you have any questions about this Privacy Policy, please contact us at privacy@hommss.com.</p>

                        <p class="mt-4 text-muted">Last updated: {{ date('F d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection