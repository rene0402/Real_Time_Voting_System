<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Real-Time Voting System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles -->
        <style>
            /* Minimal CSS Reset */
            *, *::before, *::after {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            body {
                font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
                color: #333;
            }

            .container {
                width: 100%;
                max-width: 450px;
            }

            .card {
                background: white;
                border-radius: 16px;
                padding: 3rem 2rem;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
                text-align: center;
            }

            .logo {
                font-size: 2.25rem;
                font-weight: 700;
                color: #2c3e50;
                margin-bottom: 1.5rem;
                letter-spacing: -0.5px;
            }

            .logo-highlight {
                color: #3498db;
            }

            .tagline {
                color: #7f8c8d;
                font-size: 1rem;
                margin-bottom: 1.5rem;
                font-weight: 500;
            }

            .welcome-text {
                color: #666;
                font-size: 1.125rem;
                line-height: 1.6;
                margin-bottom: 2.5rem;
            }

            .button-group {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .btn {
                display: block;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                text-decoration: none;
                font-weight: 500;
                font-size: 1rem;
                transition: all 0.2s ease;
                text-align: center;
            }

            .btn-primary {
                background: #3498db;
                color: white;
                border: 2px solid #3498db;
            }

            .btn-primary:hover {
                background: #2980b9;
                border-color: #2980b9;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
            }

            .btn-secondary {
                background: transparent;
                color: #666;
                border: 2px solid #e0e0e0;
            }

            .btn-secondary:hover {
                border-color: #3498db;
                color: #3498db;
                background: #f8f9fa;
                transform: translateY(-2px);
            }

            @media (min-width: 640px) {
                .card {
                    padding: 4rem 3rem;
                }
                
                .button-group {
                    flex-direction: row;
                }
                
                .btn {
                    flex: 1;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="card">
                <div class="logo">
                    Real-Time <span class="logo-highlight">Voting</span> System
                </div>
                
                <div class="tagline">
                    Secure • Instant • Reliable
                </div>
                
                <p class="welcome-text">
                    Welcome to our secure voting platform. Please log in or register to participate in real-time polls and elections.
                </p>

                <div class="button-group">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                Log In
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-secondary">
                                    Create Account
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </body>
</html>