<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Your Email Address</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7fafc; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <h2 style="color: #2d3748;">Hello, {{ $user->name }}!</h2>

        <p style="color: #4a5568;">Thank you for registering. Please click the button below to verify your email address:</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $verification_url }}"
               style="background-color: #3182ce; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Verify Email
            </a>
        </div>

        <p style="color: #718096;">If you’re having trouble clicking the "Verify Email" button, copy and paste the URL below into your web browser:</p>
        <p style="color: #2d3748; word-break: break-all;">{{ $verification_url }}</p>

        <p style="color: #a0aec0; font-size: 12px;">If you did not create an account, no further action is required.</p>
    </div>
</body>
</html>
