<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
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
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div>
                <label for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Enter your name">
            </div>
            <div>
                <label for="login">Login</label>
                <input type="text" name="login" id="login" placeholder="Enter your login">
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your mail">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
            </div>
            <button type="submit">
                Register
            </button>
    
        </form>
        <div>
            you already have an account <a href="{{route('login')}}">login ?</a>
        </div>
    </div>
</body>
</html>

