<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f4f6f9; margin: 0; padding: 40px 20px;">
    <div style="max-width: 480px; margin: 0 auto; background: white; border-radius: 16px; padding: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
        <div style="text-align: center; margin-bottom: 32px;">
            <div style="width: 48px; height: 48px; background: #0d6efd; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                <span style="color: white; font-size: 24px; font-weight: bold;">P</span>
            </div>
            <h1 style="font-size: 24px; font-weight: 700; margin: 0; color: #1a1a2e;">Two-Factor Authentication</h1>
            <p style="color: #6c757d; margin-top: 8px;">{{ config('app.name') }}</p>
        </div>

        <p style="color: #333; font-size: 16px; line-height: 1.5;">Hello <strong>{{ $user->name }}</strong>,</p>
        <p style="color: #333; font-size: 16px; line-height: 1.5;">Please use the following code to complete your login:</p>

        <div style="text-align: center; margin: 32px 0; padding: 24px; background: #f8f9fa; border-radius: 12px; letter-spacing: 8px;">
            <span style="font-size: 36px; font-weight: 800; color: #0d6efd; font-family: 'Courier New', monospace;">{{ $code }}</span>
        </div>

        <p style="color: #6c757d; font-size: 14px;">This code expires in <strong>10 minutes</strong>.</p>
        <p style="color: #6c757d; font-size: 14px;">If you didn't attempt to log in, please ignore this email.</p>

        <hr style="border: none; border-top: 1px solid #e9ecef; margin: 32px 0;">
        <p style="color: #adb5bd; font-size: 12px; text-align: center;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
