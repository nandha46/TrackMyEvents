<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Community!</title>
</head>
<body style="background-color: #f3f4f6; font-family: sans-serif;">

    <div style="max-width: 768px; margin: 40px auto; padding: 32px; background-color: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">

        <div style="text-align: center; margin-bottom: 32px;">
            <img src="{{ public_path('logo_tme.png') }}" alt="{{ config('app.name') }} Logo" style="display: block; margin: 0 auto 16px; height: 64px;">
            <h1 style="font-size: 1.875rem; font-weight: 600; color: #374151;">Welcome to Our Community, {{ $user->name }}!</h1>
            <p style="color: #6b7280; margin-top: 8px;">We're thrilled to have you join us.</p>
        </div>

        <div style="margin-bottom: 24px;">
            <p style="color: #4b5563; line-height: 1.625;">
                Thank you for signing up! We're excited to have you as a part of our growing community. 
                Whether you're here to learn, connect, or explore, we're confident you'll find something valuable.
            </p>
        </div>

        <div style="margin-bottom: 24px;">
            <h2 style="font-size: 1.25rem; font-weight: 600; color: #374151; margin-bottom: 12px;">Get Started:</h2>
            <ul style="list-style-type: disc; margin-left: 1.5rem; color: #4b5563; line-height: 1.625;">
                <li>Explore our features and resources.</li>
                <li>Connect with other members.</li>
                <li>Customize your profile.</li>
                <li>Stay updated with our latest news and events.</li>
            </ul>
        </div>

        <div style="text-align: center; margin-bottom: 32px;">
            <a href="{{ url('/dashboard') }}" style="display: inline-block; background-color: #6366f1; color: white; font-weight: 600; padding: 8px 24px; border-radius: 9999px; transition: background-color 0.3s ease-in-out; text-decoration: none;">
                Go to Dashboard
            </a>
        </div>

        <div style="text-align: center; color: #6b7280; margin-top: 24px;">
            <p>If you have any questions, feel free to reply to this email.</p>
            <p>We're here to help!</p>
        </div>

        <div style="text-align: center; margin-top: 32px;">
            <p style="font-size: 0.875rem; color: #9ca3af;">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>

    </div>

</body>
</html>