<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Request</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-top: 5px solid #c90b73;
        }
        .header {
            text-align: center;
            background-color: #c90b73;
            color: #ffffff;
            padding: 20px 0;
        }
        .header img {
            max-width: 120px;
            margin-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }
        .content {
            padding: 25px 30px;
            color: #333333;
        }
        .content h2 {
            color: #c90b73;
            font-size: 20px;
            margin-top: 0;
        }
        .content p {
            line-height: 1.6;
            margin: 10px 0;
        }
        .content .label {
            font-weight: 600;
            color: #555555;
        }
        .footer {
            background-color: #f1f1f1;
            text-align: center;
            font-size: 12px;
            padding: 15px 20px;
            color: #777777;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #c90b73;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">

            <h1>New Contact Request</h1>
        </div>

        <div class="content">
            <h2>Hello Team,</h2>
            <p>You have received a new message from your website contact form. Here are the details:</p>

            <p><span class="label">Name:</span> {{ $mailData['name'] }}</p>
            <p><span class="label">Email:</span> {{ $mailData['email'] }}</p>
            <p><span class="label">Phone:</span> {{ $mailData['phone'] }}</p>
            <p><span class="label">Message:</span></p>
            <p style="background-color:#f8f8f8; padding:15px; border-radius:5px;">{{ $mailData['message'] }}</p>

            <a href="mailto:{{ $mailData['email'] }}" class="button">Reply to Sender</a>
        </div>

        <div class="footer">
            Mirror Booth Dubai &copy; {{ date('Y') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
