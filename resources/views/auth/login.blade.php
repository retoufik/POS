<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
    <div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div> 
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your mail">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
            </div>
            <button type="submit">
                Login
            </button>
    
        </form>
        <div>
            don't have an account <a href="{{route('register')}}">create one ?</a>
        </div>
    </div>
</body>
</html>
