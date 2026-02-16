<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real Time Voting System - Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: white;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: #1a7b3e;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            max-width: 380px;
            width: 100%;
            padding: 35px;
            position: relative;
            z-index: 1;
            border: 3px solid #ffd700;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 18px;
        }

        .logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(26, 123, 62, 0.4);
            background: white;
            padding: 5px;
        }

        .logo img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .title .real-time {
            color: white;
        }

        .title .voting {
            color: #ffd700;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        .title .system {
            color: white;
        }

        .subtitle {
            text-align: center;
            color: #e0e0e0;
            font-size: 13px;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 16px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: white;
            font-weight: 600;
            font-size: 12px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            width: 100%;
            padding: 11px 35px 11px 11px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 13px;
            transition: all 0.3s ease;
            background: white;
            color: #1a7b3e;
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: #ffd700;
            box-shadow: 0 0 0 4px rgba(255, 215, 0, 0.2);
        }

        .input-icon {
            position: absolute;
            right: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #1a7b3e;
            font-size: 14px;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            font-size: 11px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 6px;
            color: white;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #ffd700;
        }

        .forgot-password {
            color: #ffd700;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #ffed4e;
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            color: #1a7b3e;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
            border: 2px solid white;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 215, 0, 0.6);
            background: linear-gradient(135deg, #ffed4e 0%, #ffd700 100%);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .create-account {
            text-align: center;
            margin-top: 12px;
            color: white;
            font-size: 11px;
        }

        .create-account a {
            color: #ffd700;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            transition: color 0.3s ease;
        }

        .create-account a:hover {
            color: #ffed4e;
            text-decoration: underline;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 2px solid #ffd700;
            color: white;
            font-size: 10px;
            line-height: 1.5;
        }

        .footer .highlight {
            color: #ffd700;
            font-weight: 600;
        }

        /* CPSU Badge */
        .cpsu-badge {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            color: #1a7b3e;
            padding: 6px 14px;
            border-radius: 16px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 10px;
            border: 2px solid white;
            box-shadow: 0 4px 10px rgba(255, 215, 0, 0.3);
        }

        /* Error messages */
        .error-message {
            color: #ffd700;
            font-size: 12px;
            margin-top: 4px;
            font-weight: 500;
            background: rgba(255, 215, 0, 0.1);
            padding: 4px 8px;
            border-radius: 4px;
            border-left: 3px solid #ffd700;
        }

        .session-status {
            background: rgba(255, 215, 0, 0.2);
            color: #ffd700;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 13px;
            border: 1px solid rgba(255, 215, 0, 0.3);
            text-align: center;
        }

        @media (max-width: 480px) {
            .container {
                padding: 28px;
                max-width: 340px;
            }

            .title {
                font-size: 20px;
            }

            .logo {
                width: 70px;
                height: 70px;
            }

            .logo img {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <div class="logo">
                <img src="/images/cpsu-logo.jpg" alt="CPSU Logo">
            </div>
          
            <h1 class="title">
                <span class="real-time">REAL TIME</span>
                <span class="voting"> VOTING </span>
                <span class="system">SYSTEM</span>
            </h1>
            <p class="subtitle">Login to your account</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="session-status">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus autocomplete="username">
                    <span class="input-icon">📧</span>
                </div>
                @if ($errors->has('email'))
                    <div class="error-message">
                        {{ $errors->first('email') }}
                    </div>
                @endif
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                    <span class="input-icon">🔒</span>
                </div>
                @if ($errors->has('password'))
                    <div class="error-message">
                        {{ $errors->first('password') }}
                    </div>
                @endif
            </div>

            <div class="remember-forgot">
                <label class="remember-me">
                    <input type="checkbox" id="remember_me" name="remember">
                    <span>Remember Me</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="forgot-password" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <button type="submit" class="login-btn">Login</button>

            <div class="create-account">
                Don't have an account? <a href="{{ route('register') }}">Register here</a>
            </div>
        </form>

        <div class="footer">
            Real Time Voting System © 2026<br>
            Developed by <span class="highlight">Group 5</span>
        </div>
    </div>
</body>
</html>