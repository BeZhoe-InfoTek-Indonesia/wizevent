<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f5f5f5; padding: 40px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h2 style="color: #7c3aed; margin: 0 0 20px 0;">{{ $title }}</h2>
        <p>Dear User,</p>
        <p>{{ $description }}</p>
        @if($link)
        <div style="margin-top: 30px;">
            <a href="{{ $link }}" style="display: inline-block; padding: 12px 24px; background-color: #7c3aed; color: white; text-decoration: none; border-radius: 4px;">Learn More</a>
        </div>
        @endif
        <p style="margin-top: 30px; font-size: 12px; color: #666;">
            If you no longer wish to receive promotional emails, you can update your notification preferences in your profile.
        </p>
    </div>
</body>
</html>
