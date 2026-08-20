<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h2>Verify Your Email</h2>

    <p>Hello {{ $user->full_name }},</p>

    <p>Your verification code is:</p>

    <h1>{{ $verificationCode }}</h1>

    <p>This code will expire in 10 minutes.</p>
</body>

</html>
