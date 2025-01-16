<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Due Reminder</title>
</head>
<body>
    <h1>Dear {{ $user->name }},</h1>
    <p>This is a friendly reminder that your payment is due on <strong>{{ $dueDate->format('F j, Y') }}</strong>.</p>
    <p>Please make the payment before the due date to avoid any penalties.</p>
    <p>Thank you for your attention!</p>
    <p>Best regards,</p>
    <p>Your Company Name</p>
</body>
</html>
