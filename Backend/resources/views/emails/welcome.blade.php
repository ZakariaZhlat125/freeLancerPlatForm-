<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h1>
    {{ __('static.welcome_user') }} {{ $name }}
</h1>
<p>
    {{ __('static.welcome_user_message') }}
</p>
     <a href="{{ $activation_url }}">
        {{ __('static.press_activate') }}
    </a>

</body>
</html>
