<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CPSU Hinoba-an - Real-time Voting System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --cpsu-blue: #004d00;
            --cpsu-gold: #FFD700;
            --cpsu-green: #28a745;
            --cpsu-red: #dc3545;
            --cpsu-purple: #6f42c1;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        /* Preloader */
        .js-preloader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--cpsu-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .preloader-inner {
            text-align: center;
        }

        .dots {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .dots span {
            width: 20px;
            height: 20px;
            background: var(--cpsu-gold);
            border-radius: 50%;
            animation: bounce 1.4s infinite ease-in-out both;
        }

        .dots span:nth-child(1) { animation-delay: -0.32s; }
        .dots span:nth-child(2) { animation-delay: -0.16s; }

        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }

        .sub-header {
            background: green;
            color: white;
            padding: 12px 0;
        }

        .sub-header .info {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .sub-header .info li {
            display: flex;
            align-items: center;
        }

        .sub-header .info i {
            color: var(--cpsu-gold);
            margin-right: 8px;
        }

        .social-links {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .social-links a {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s;
            text-decoration: none;
        }

        .social-links a:hover {
            background: var(--cpsu-gold);
            color: green;
            transform: translateY(-3px);
        }

        /* Header */
        .header-area {
            background: white;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid #eee;
        }

        .logo {
            text-decoration: none;
        }

        .logo h1 {
            color: #004d00;
            font-weight: 700;
            font-size: 28px;
            margin: 0;
        }

        .logo span {
            color: #FFD700;
        }

        .main-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .main-nav .nav {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 5px;
        }

        .main-nav .nav li a {
            color: #004d00;
            font-weight: 500;
            padding: 8px 20px !important;
            border-radius: 30px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .main-nav .nav li a:hover,
        .main-nav .nav li a.active {
            background: #FFD700;
            color: #004d00;
        }

        .main-nav .nav li a i {
            margin-right: 8px;
        }

        .menu-trigger {
            display: none;
            cursor: pointer;
            padding: 10px;
        }

        /* Main Banner */
        .main-banner {
            position: relative;
            overflow: hidden;
            margin-top: 0;
        }

        .item-1 {
            background: linear-gradient(rgba(0, 51, 102, 0.3), rgba(0, 51, 102, 0.3)), url('/images/cpsu2.jpg') !important;
            background-size: cover !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
        }

        .item-2 {
            background: linear-gradient(rgba(0, 51, 102, 0.3), rgba(0, 51, 102, 0.3)), url('/images/cpsu3.jpg') !important;
            background-size: contain !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
        }

        .item-3 {
            background: linear-gradient(rgba(0, 51, 102, 0.3), rgba(0, 51, 102, 0.3)), url('/images/cpsu1.jpg') !important;
            background-size: cover !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
        }

        .owl-carousel .item {
            height: 80vh;
            background-size: cover !important;
            background-position: center !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header-text {
            max-width: 800px;
            padding: 0 20px;
            text-align: center;
            color: white;
        }

        .header-text .category {
            background: var(--cpsu-gold);
            color: var(--cpsu-blue);
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 18px;
            display: inline-block;
            margin-bottom: 30px;
        }

        .header-text h2 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 30px;
            line-height: 1.2;
        }

        /* Sections */
        .section {
            padding: 100px 0;
        }

        .section-heading {
            margin-bottom: 50px;
        }

        .section-heading h6 {
            color: var(--cpsu-blue);
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
            padding-left: 40px;
        }

        .section-heading h6:before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 30px;
            height: 3px;
            background: var(--cpsu-gold);
        }

        .section-heading h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--cpsu-blue);
        }

        /* Featured Section */
        .left-image {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        .left-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            min-height: 500px;
        }

        .vote-overlay-link {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-decoration: none;
        }

        /* Accordion */
        .accordion-button {
            font-weight: 600;
            padding: 20px;
            background: #f8f9fa;
            border: none;
            margin-bottom: 10px;
            border-radius: 10px !important;
        }

        .accordion-button:not(.collapsed) {
            background: var(--cpsu-blue);
            color: white;
        }

        .accordion-body {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 0 0 10px 10px;
        }

        /* Info Table */
        .info-table {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        .info-table ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-table ul li {
            display: flex;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }

        .info-table ul li:last-child {
            border-bottom: none;
        }

        .info-table ul li i {
            margin-right: 20px;
        }

        .info-table ul li h4 {
            font-size: 18px;
            font-weight: 700;
            color: var(--cpsu-blue);
            margin: 0;
        }

        .info-table ul li span {
            color: #666;
            font-weight: 400;
            font-size: 14px;
        }

        /* Video Section */
        .video-frame {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .video-frame img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }

        .video-frame a {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80px;
            height: 80px;
            background: var(--cpsu-gold);
            color: var(--cpsu-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            transition: all 0.3s;
            text-decoration: none;
        }

        .video-frame a:hover {
            transform: translate(-50%, -50%) scale(1.1);
        }

        /* Fun Facts */
        .fun-facts {
            background: var(--cpsu-blue);
            color: white;
            padding: 80px 0;
        }

        .counter {
            text-align: center;
            padding: 30px;
        }

        .counter h2 {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--cpsu-gold);
            margin-bottom: 10px;
        }

        .count-text {
            font-size: 18px;
            font-weight: 500;
        }

        /* Tabs */
        .tabs-content {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .nav-tabs {
            border-bottom: 2px solid #eee;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #666;
            font-weight: 600;
            padding: 15px 30px;
            margin: 0 10px;
            border-radius: 10px 10px 0 0;
            transition: all 0.3s;
        }

        .nav-tabs .nav-link.active {
            background: var(--cpsu-blue);
            color: white;
        }

        .tab-content {
            padding: 30px 0;
        }

        /* Candidates */
        .candidate-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: all 0.3s;
            height: 100%;
        }

        .candidate-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .candidate-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .candidate-card .category {
            display: inline-block;
            background: var(--cpsu-gold);
            color: var(--cpsu-blue);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin: 20px 20px 10px;
        }

        .candidate-card h6 {
            color: var(--cpsu-blue);
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 20px 10px;
        }

        .candidate-card h4 {
            font-size: 1.2rem;
            margin: 0 20px 20px;
        }

        .candidate-card h4 a {
            color: #333;
            text-decoration: none;
        }

        .candidate-card ul {
            list-style: none;
            padding: 0 20px;
            margin: 0 0 20px;
            display: flex;
            flex-wrap: wrap;
        }

        .candidate-card ul li {
            width: 50%;
            margin-bottom: 10px;
            color: #666;
        }

        .candidate-card ul li span {
            float: right;
            color: var(--cpsu-blue);
            font-weight: 600;
        }

        .main-button {
            padding: 20px;
            border-top: 1px solid #eee;
            margin-top: auto;
        }

        .main-button a {
            display: block;
            background: var(--cpsu-blue);
            color: white;
            text-align: center;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }

        .main-button a:hover {
            background: var(--cpsu-gold);
            color: var(--cpsu-blue);
        }

        /* Contact */
        .contact-content {
            padding: 50px 0 100px;
        }

        #map iframe {
            border-radius: 15px;
            width: 100%;
            height: 500px;
        }

        .phone, .email {
            display: flex;
            align-items: center;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            height: 100%;
        }

        .phone i, .email i {
            margin-right: 20px;
        }

        .phone h6, .email h6 {
            font-size: 18px;
            font-weight: 700;
            color: var(--cpsu-blue);
            margin: 0;
        }

        .phone span, .email span {
            color: #666;
            font-weight: 400;
            font-size: 14px;
        }

        #contact-form {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        fieldset {
            margin-bottom: 25px;
            border: none;
            padding: 0;
        }

        fieldset label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--cpsu-blue);
        }

        fieldset input, fieldset textarea, fieldset select {
            width: 100%;
            padding: 15px;
            border: 2px solid #eee;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
        }

        fieldset input:focus, fieldset textarea:focus, fieldset select:focus {
            border-color: var(--cpsu-blue);
            outline: none;
        }

        .orange-button {
            background: var(--cpsu-gold);
            color: var(--cpsu-blue);
            border: none;
            padding: 15px 40px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
        }

        .orange-button:hover {
            background: var(--cpsu-blue);
            color: white;
        }

        /* Footer */
        footer {
            background: var(--cpsu-blue);
            color: white;
            padding: 30px 0;
            text-align: center;
        }

        /* Real-time voting specific styles */
        .vote-count {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--cpsu-green);
        }

        .vote-progress {
            height: 10px;
            border-radius: 5px;
            background: #eee;
            margin: 20px 0;
            overflow: hidden;
        }

        .vote-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--cpsu-blue), var(--cpsu-purple));
            transition: width 0.5s ease;
        }

        .live-badge {
            background: var(--cpsu-red);
            color: white;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            animation: pulse 2s infinite;
            float: right;
            margin: 20px;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }

        .vote-btn {
            background: var(--cpsu-green);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%;
        }

        .vote-btn:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        .results-chart {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .position-badge {
            background: var(--cpsu-purple);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .main-nav .nav {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                padding: 20px;
            }

            .menu-trigger {
                display: block;
            }

            .main-nav .nav.active {
                display: flex;
            }

            .header-text h2 {
                font-size: 2.5rem;
            }

            .section-heading h2 {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            .sub-header .info {
                justify-content: center;
                margin-bottom: 10px;
            }

            .social-links {
                justify-content: center;
            }

            .header-text h2 {
                font-size: 2rem;
            }

            .counter h2 {
                font-size: 2.5rem;
            }
        }
    </style>
</head>

<body>

  <!-- Preloader -->
  <div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>

  <!-- Sub Header -->
  <div class="sub-header">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-8 col-md-8">
          <ul class="info">
            <li><i class="fa fa-envelope"></i> voting@cpsu-hinobaan.edu.ph</li>
            <li><i class="fa fa-map"></i> CPSU Hinoba-an Campus, Negros Occidental, Philippines</li>
          </ul>
        </div>
        <div class="col-lg-4 col-md-4">
          <ul class="social-links">
            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
            <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Header -->
  <header class="header-area header-sticky">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav">
            <a href="index.html" class="logo">
              <img src="/logo/Central_Philippines_State_University_Logo.jpg" alt="CPSU Logo" style="height: 50px; margin-right: 10px; background: white; padding: 5px; border-radius: 5px;">
              <h1>CPSU<span>VOTE</span></h1>
            </a>

            <ul class="nav">
              <li><a href="{{ url('/') }}" class="active">Home</a></li>
              <li><a href="#candidates">Candidates</a></li>
              <li><a href="#results">Live Results</a></li>
              <li><a href="{{ route('login') }}">Cast Vote</a></li>
              <li><a href="#contact">Contact</a></li>
              <li><a href="{{ route('login') }}"><i class="fa fa-user"></i> Login</a></li>
            </ul>

            <a class='menu-trigger'>
              <span>Menu</span>
            </a>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Banner -->
  <div class="main-banner">
    <div class="owl-carousel owl-banner">
      <div class="item item-1">
        <div class="header-text">
          <span class="category">CPSU <em>Hinoba-an</em></span>
          <h2>REAL-TIME<br>VOTING SYSTEM</h2>
        </div>
      </div>
      <div class="item item-2">
        <div class="header-text">
          <span class="category"><em>Elections 2026</em></span>
          <h2>Cast Your Vote<br>Shape Our Campus</h2>
        </div>
      </div>
      <div class="item item-3">
        <div class="header-text">
          <span class="category">Real-time <em>Results</em></span>
          <h2>Watch Results<br>Live as Votes Come In</h2>
        </div>
      </div>
    </div>
  </div>

  <!-- Featured Section -->
  <div class="featured section">
    <div class="container">
      <div class="row">
        <div class="col-lg-4">
          <div class="left-image">
            <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="CPSU Campus Voting">
            <a href="{{ route('login') }}" class="vote-overlay-link">
              <i class="fas fa-vote-yea" style="font-size: 60px; color: var(--cpsu-gold); background: rgba(255,255,255,0.9); padding: 20px; border-radius: 50%;"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="section-heading">
            <h6>| Featured Candidates</h6>
            <h2>Student Council Presidential Race</h2>
          </div>
          <div class="accordion" id="accordionExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  How to Vote?
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  <strong>Simple 3-step voting process:</strong> 1) Login with your student credentials, 2) Select your preferred candidates, 3) Submit your vote securely. Each student can vote only once. Voting period: October 15-20, 2024.
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  Real-time Results?
                </button>
              </h2>
              <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  Our system provides <strong>live updates</strong> as votes are cast. You can watch the results dashboard for instant updates on candidate standings. All results are encrypted and securely stored. Final results will be announced after voting closes.
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  Why Digital Voting?
                </button>
              </h2>
              <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  Digital voting ensures <strong>transparency, security, and accessibility</strong> for all students. It eliminates manual counting errors, provides instant results, and allows students to vote from anywhere on campus. Our system uses bank-level encryption to protect your vote.
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="info-table">
            <ul>
              <li>
                <i class="fas fa-users" style="font-size: 52px; color: var(--cpsu-blue);"></i>
                <h4>2,500<br><span>Registered Voters</span></h4>
              </li>
              <li>
                <i class="fas fa-vote-yea" style="font-size: 52px; color: var(--cpsu-green);"></i>
                <h4>1,892<br><span>Active Voters</span></h4>
              </li>
              <li>
                <i class="fas fa-clock" style="font-size: 52px; color: var(--cpsu-gold);"></i>
                <h4>2 Days Left<br><span>Election Status</span></h4>
              </li>
              <li>
                <i class="fas fa-shield-alt" style="font-size: 52px; color: var(--cpsu-purple);"></i>
                <h4>100% Secure<br><span>Encrypted System</span></h4>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Video Section -->
  <div class="video section">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 offset-lg-4">
          <div class="section-heading text-center">
            <h6>| System Demo</h6>
            <h2>How Our Voting System Works</h2>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="video-content">
    <div class="container">
      <div class="row">
        <div class="col-lg-10 offset-lg-1">
          <div class="video-frame">
            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Voting System Demo">
            <a href="https://youtube.com" target="_blank"><i class="fa fa-play"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Fun Facts -->
  <div class="fun-facts">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="wrapper">
            <div class="row">
              <div class="col-lg-3 col-md-6">
                <div class="counter">
                  <h2 class="timer count-title count-number" data-to="2500" data-speed="1000">2500</h2>
                  <p class="count-text">Total<br>Students Eligible</p>
                </div>
              </div>
              <div class="col-lg-3 col-md-6">
                <div class="counter">
                  <h2 class="timer count-title count-number" data-to="1892" data-speed="1000">1892</h2>
                  <p class="count-text">Active<br>Voters</p>
                </div>
              </div>
              <div class="col-lg-3 col-md-6">
                <div class="counter">
                  <h2 class="timer count-title count-number" data-to="1" data-speed="1000">1</h2>
                  <p class="count-text">Active<br>Elections</p>
                </div>
              </div>
              <div class="col-lg-3 col-md-6">
                <div class="counter">
                  <h2 class="timer count-title count-number" data-to="15" data-speed="1000">15</h2>
                  <p class="count-text">Election<br>Officials</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Live Results -->
  <div class="section best-deal" id="results">
    <div class="container">
      <div class="row">
        <div class="col-lg-4">
          <div class="section-heading">
            <h6>| Live Results</h6>
            <h2>Current Election Standings</h2>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="tabs-content">
            <div class="row">
              <div class="nav-wrapper">
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="president-tab" data-bs-toggle="tab" data-bs-target="#president" type="button" role="tab" aria-controls="president" aria-selected="true">President</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="vice-president-tab" data-bs-toggle="tab" data-bs-target="#vice-president" type="button" role="tab" aria-controls="vice-president" aria-selected="false">Vice President</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="secretary-tab" data-bs-toggle="tab" data-bs-target="#secretary" type="button" role="tab" aria-controls="secretary" aria-selected="false">Secretary</button>
                  </li>
                </ul>
              </div>

              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="president" role="tabpanel" aria-labelledby="president-tab">
                  <div class="row">
                    <div class="col-lg-3">
                      <div class="info-table">
                        <ul>
                          <li>Total Votes Cast <span class="vote-count">892</span></li>
                          <li>Leading Candidate <span>Juan Dela Cruz</span></li>
                          <li>Votes Needed <span>1227</span></li>
                          <li>Voting Progress <span>72.7%</span></li>
                          <li>Time Remaining <span>2 Days</span></li>
                        </ul>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="results-chart">
                        <h4 class="mb-4">Presidential Candidates Results</h4>
                        <div class="mb-3">
                          <div class="d-flex justify-content-between">
                            <span>Juan Dela Cruz</span>
                            <span>487 votes (54.6%)</span>
                          </div>
                          <div class="vote-progress">
                            <div class="vote-progress-bar" style="width: 54.6%"></div>
                          </div>
                        </div>
                        <div class="mb-3">
                          <div class="d-flex justify-content-between">
                            <span>Maria Santos</span>
                            <span>325 votes (36.4%)</span>
                          </div>
                          <div class="vote-progress">
                            <div class="vote-progress-bar" style="width: 36.4%"></div>
                          </div>
                        </div>
                        <div class="mb-3">
                          <div class="d-flex justify-content-between">
                            <span>Pedro Reyes</span>
                            <span>80 votes (9.0%)</span>
                          </div>
                          <div class="vote-progress">
                            <div class="vote-progress-bar" style="width: 9%"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <h4>Election Updates</h4>
                      <p><strong>Live Update:</strong> Juan Dela Cruz maintains lead with 54.6% of votes.</p>
                      <p><strong>Next Update:</strong> Results refresh every 30 seconds.</p>
                      <p><strong>Reminder:</strong> Voting closes on October 20, 5:00 PM.</p>
                      <div class="main-button mt-4">
                        <a href="results.html"><i class="fa fa-chart-bar"></i> View Full Results</a>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="tab-pane fade" id="vice-president" role="tabpanel" aria-labelledby="vice-president-tab">
                  <div class="row">
                    <div class="col-lg-3">
                      <div class="info-table">
                        <ul>
                          <li>Total Votes Cast <span class="vote-count">892</span></li>
                          <li>Leading Candidate <span>Ana Lopez</span></li>
                          <li>Votes Needed <span>1227</span></li>
                          <li>Voting Progress <span>72.7%</span></li>
                          <li>Time Remaining <span>2 Days</span></li>
                        </ul>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="results-chart">
                        <h4 class="mb-4">Vice Presidential Candidates Results</h4>
                        <div class="mb-3">
                          <div class="d-flex justify-content-between">
                            <span>Ana Lopez</span>
                            <span>512 votes (57.4%)</span>
                          </div>
                          <div class="vote-progress">
                            <div class="vote-progress-bar" style="width: 57.4%"></div>
                          </div>
                        </div>
                        <div class="mb-3">
                          <div class="d-flex justify-content-between">
                            <span>Carlos Ramirez</span>
                            <span>280 votes (31.4%)</span>
                          </div>
                          <div class="vote-progress">
                            <div class="vote-progress-bar" style="width: 31.4%"></div>
                          </div>
                        </div>
                        <div class="mb-3">
                          <div class="d-flex justify-content-between">
                            <span>Elena Torres</span>
                            <span>100 votes (11.2%)</span>
                          </div>
                          <div class="vote-progress">
                            <div class="vote-progress-bar" style="width: 11.2%"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <h4>Election Updates</h4>
                      <p><strong>Live Update:</strong> Ana Lopez leads with 57.4% of votes.</p>
                      <p><strong>Next Update:</strong> Results refresh every 30 seconds.</p>
                      <p><strong>Reminder:</strong> Voting closes on October 20, 5:00 PM.</p>
                      <div class="main-button mt-4">
                        <a href="results.html"><i class="fa fa-chart-bar"></i> View Full Results</a>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="tab-pane fade" id="secretary" role="tabpanel" aria-labelledby="secretary-tab">
                  <div class="row">
                    <div class="col-lg-3">
                      <div class="info-table">
                        <ul>
                          <li>Total Votes Cast <span class="vote-count">892</span></li>
                          <li>Leading Candidate <span>Miguel Garcia</span></li>
                          <li>Votes Needed <span>1227</span></li>
                          <li>Voting Progress <span>72.7%</span></li>
                          <li>Time Remaining <span>2 Days</span></li>
                        </ul>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="results-chart">
                        <h4 class="mb-4">Secretarial Candidates Results</h4>
                        <div class="mb-3">
                          <div class="d-flex justify-content-between">
                            <span>Miguel Garcia</span>
                            <span>420 votes (47.1%)</span>
                          </div>
                          <div class="vote-progress">
                            <div class="vote-progress-bar" style="width: 47.1%"></div>
                          </div>
                        </div>
                        <div class="mb-3">
                          <div class="d-flex justify-content-between">
                            <span>Sofia Mendoza</span>
                            <span>372 votes (41.7%)</span>
                          </div>
                          <div class="vote-progress">
                            <div class="vote-progress-bar" style="width: 41.7%"></div>
                          </div>
                        </div>
                        <div class="mb-3">
                          <div class="d-flex justify-content-between">
                            <span>Luis Fernandez</span>
                            <span>100 votes (11.2%)</span>
                          </div>
                          <div class="vote-progress">
                            <div class="vote-progress-bar" style="width: 11.2%"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <h4>Election Updates</h4>
                      <p><strong>Live Update:</strong> Miguel Garcia leads with 47.1% of votes.</p>
                      <p><strong>Next Update:</strong> Results refresh every 30 seconds.</p>
                      <p><strong>Reminder:</strong> Voting closes on October 20, 5:00 PM.</p>
                      <div class="main-button mt-4">
                        <a href="results.html"><i class="fa fa-chart-bar"></i> View Full Results</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Candidates Section -->
  <div class="properties section" id="candidates">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 offset-lg-4">
          <div class="section-heading text-center">
            <h6>| Candidates</h6>
            <h2>Meet Your Student Leaders</h2>
          </div>
        </div>
      </div>
      <div class="row">
        <!-- Candidate 1 -->
        <div class="col-lg-4 col-md-6">
          <div class="candidate-card">
            <a href="candidate-details.html">
              <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Juan Dela Cruz">
            </a>
            <span class="position-badge">President</span>
            <span class="live-badge">LIVE</span>
            <h6>Juan Dela Cruz</h6>
            <h4><a href="candidate-details.html">BS Computer Science - 4th Year</a></h4>
            <ul>
              <li>Votes: <span class="vote-count">487</span></li>
              <li>Percentage: <span>54.6%</span></li>
              <li>Status: <span>Leading</span></li>
            </ul>
            <div class="main-button">
              <a href="{{ route('login') }}" class="vote-btn">Vote Now</a>
            </div>
          </div>
        </div>

        <!-- Candidate 2 -->
        <div class="col-lg-4 col-md-6">
          <div class="candidate-card">
            <a href="candidate-details.html">
              <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Maria Santos">
            </a>
            <span class="position-badge">President</span>
            <h6>Maria Santos</h6>
            <h4><a href="candidate-details.html">BS Education - 3rd Year</a></h4>
            <ul>
              <li>Votes: <span class="vote-count">325</span></li>
              <li>Percentage: <span>36.4%</span></li>
              <li>Status: <span>Contending</span></li>
            </ul>
            <div class="main-button">
              <a href="{{ route('login') }}" class="vote-btn">Vote Now</a>
            </div>
          </div>
        </div>

        <!-- Candidate 3 -->
        <div class="col-lg-4 col-md-6">
          <div class="candidate-card">
            <a href="candidate-details.html">
              <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Ana Lopez">
            </a>
            <span class="position-badge">Vice President</span>
            <span class="live-badge">LIVE</span>
            <h6>Ana Lopez</h6>
            <h4><a href="candidate-details.html">BS Business Admin - 4th Year</a></h4>
            <ul>
              <li>Votes: <span class="vote-count">512</span></li>
              <li>Percentage: <span>57.4%</span></li>
              <li>Status: <span>Leading</span></li>
            </ul>
            <div class="main-button">
              <a href="{{ route('login') }}" class="vote-btn">Vote Now</a>
            </div>
          </div>
        </div>

        <!-- Candidate 4 -->
        <div class="col-lg-4 col-md-6">
          <div class="candidate-card">
            <a href="candidate-details.html">
              <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Carlos Ramirez">
            </a>
            <span class="position-badge">Vice President</span>
            <h6>Carlos Ramirez</h6>
            <h4><a href="candidate-details.html">BS Engineering - 3rd Year</a></h4>
            <ul>
              <li>Votes: <span class="vote-count">280</span></li>
              <li>Percentage: <span>31.4%</span></li>
              <li>Status: <span>Contending</span></li>
            </ul>
            <div class="main-button">
              <a href="{{ route('login') }}" class="vote-btn">Vote Now</a>
            </div>
          </div>
        </div>

        <!-- Candidate 5 -->
        <div class="col-lg-4 col-md-6">
          <div class="candidate-card">
            <a href="candidate-details.html">
              <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Miguel Garcia">
            </a>
            <span class="position-badge">Secretary</span>
            <span class="live-badge">LIVE</span>
            <h6>Miguel Garcia</h6>
            <h4><a href="candidate-details.html">BS Agriculture - 4th Year</a></h4>
            <ul>
              <li>Votes: <span class="vote-count">420</span></li>
              <li>Percentage: <span>47.1%</span></li>
              <li>Status: <span>Leading</span></li>
            </ul>
            <div class="main-button">
              <a href="{{ route('login') }}" class="vote-btn">Vote Now</a>
            </div>
          </div>
        </div>

        <!-- Candidate 6 -->
        <div class="col-lg-4 col-md-6">
          <div class="candidate-card">
            <a href="candidate-details.html">
              <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Sofia Mendoza">
            </a>
            <span class="position-badge">Treasurer</span>
            <h6>Sofia Mendoza</h6>
            <h4><a href="candidate-details.html">BS Accountancy - 3rd Year</a></h4>
            <ul>
              <li>Votes: <span class="vote-count">372</span></li>
              <li>Percentage: <span>41.7%</span></li>
              <li>Status: <span>Contending</span></li>
            </ul>
            <div class="main-button">
              <a href="{{ route('login') }}" class="vote-btn">Vote Now</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Contact Section -->
  <div class="contact section" id="contact">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 offset-lg-4">
          <div class="section-heading text-center">
            <h6>| Contact Us</h6>
            <h2>Need Help? Contact Election Committee</h2>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;">
      <i class="fas fa-check-circle"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="contact-content">
    <div class="container">
      <div class="row">
        <div class="col-lg-7">
          <div id="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d125442.87817696705!2d122.44459785386746!3d9.621486993041368!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33b251c8da94987d%3A0x5d9615f0a1ff3045!2sCentral%20Philippines%20State%20University%20-%20Hinoba-an%20Campus!5e0!3m2!1sen!2sph!4v1697219234567!5m2!1sen!2sph" width="100%" height="500px" frameborder="0" style="border:0; border-radius: 10px; box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.15);" allowfullscreen=""></iframe>
          </div>
          <div class="row">
            <div class="col-lg-6 col-md-6">
              <div class="phone">
                <i class="fas fa-phone" style="font-size: 52px; color: var(--cpsu-blue);"></i>
                <h6>(034) 123-4567<br><span>Election Hotline</span></h6>
              </div>
            </div>
            <div class="col-lg-6 col-md-6">
              <div class="email">
                <i class="fas fa-envelope" style="font-size: 52px; color: var(--cpsu-blue);"></i>
                <h6>election@cpsu-hinobaan.edu.ph<br><span>Official Email</span></h6>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5">
          <form id="contact-form" action="{{ route('contact.submit') }}" method="post" class="needs-validation" novalidate>
            @csrf
            <div class="row">
              <div class="col-lg-12">
                <fieldset>
                  <label for="name">Full Name</label>
                  <input type="text" name="name" id="name" placeholder="Your Name..." autocomplete="on" required class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                  @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <label for="email">Student Email</label>
                  <input type="email" name="email" id="email" placeholder="your.email@cpsu.edu.ph" required class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                  @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <label for="student_id">Student ID</label>
                  <input type="text" name="student_id" id="student_id" placeholder="2024-XXXXX" required class="form-control @error('student_id') is-invalid @enderror" value="{{ old('student_id') }}">
                  @error('student_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <label for="issue_type">Issue / Concern</label>
                  <select name="issue_type" class="form-control @error('issue_type') is-invalid @enderror" style="padding: 15px; border: 2px solid #eee; border-radius: 10px;" required>
                    <option value="">Select issue type</option>
                    <option value="voting" {{ old('issue_type') == 'voting' ? 'selected' : '' }}>Voting Problem</option>
                    <option value="login" {{ old('issue_type') == 'login' ? 'selected' : '' }}>Login Issue</option>
                    <option value="candidate" {{ old('issue_type') == 'candidate' ? 'selected' : '' }}>Candidate Information</option>
                    <option value="other" {{ old('issue_type') == 'other' ? 'selected' : '' }}>Other Concern</option>
                  </select>
                  @error('issue_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <label for="message">Message</label>
                  <textarea name="message" id="message" placeholder="Describe your issue..." required class="form-control @error('message') is-invalid @enderror" rows="5">{{ old('message') }}</textarea>
                  @error('message')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <button type="submit" id="form-submit" class="orange-button">Submit Inquiry</button>
                </fieldset>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <div class="container">
      <div class="row">
        <div class="col-12 text-center">
          <p style="margin: 0; color: white;">Copyright © 2024 Central Philippines State University - Hinoba-an Campus. All rights reserved.
          <br> Elections 2026 | Real-time Voting System </p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>

  <script>
    $(document).ready(function(){
      // Preloader
      $(window).on('load', function(){
        $('.js-preloader').fadeOut();
      });

      // Counter Animation
      $('.count-number').counterUp({
        delay: 10,
        time: 1000
      });

      // Owl Carousel
      $('.owl-banner').owlCarousel({
        items: 1,
        loop: true,
        dots: true,
        nav: true,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        smartSpeed: 1000,
        responsive:{
          0:{
            items:1
          }
        }
      });

      // Mobile menu toggle
      $('.menu-trigger').click(function(){
        $('.main-nav .nav').toggleClass('active');
      });

      // Close menu when clicking outside
      $(document).click(function(event) {
        if (!$(event.target).closest('.main-nav').length) {
          $('.main-nav .nav').removeClass('active');
        }
      });

      // Live vote update simulation
      function updateLiveVotes() {
        $('.vote-count').each(function() {
          let current = parseInt($(this).text());
          let randomIncrement = Math.floor(Math.random() * 3);
          $(this).text(current + randomIncrement);
        });

        updatePercentages();
      }

      function updatePercentages() {
        $('.vote-progress-bar').each(function() {
          let currentWidth = parseFloat($(this).attr('data-width') || 0);
          let randomChange = (Math.random() * 2 - 1) * 0.5;
          let newWidth = Math.max(0, Math.min(100, currentWidth + randomChange));
          $(this).css('width', newWidth + '%').attr('data-width', newWidth);

          // Update percentage text
          let span = $(this).closest('.mb-3').find('span').last();
          let currentText = span.text();
          let votesMatch = currentText.match(/(\d+)\s+votes/);
          if (votesMatch) {
            let votes = parseInt(votesMatch[1]) + Math.floor(randomChange * 10);
            span.text(votes + ' votes (' + newWidth.toFixed(1) + '%)');
          }
        });
      }

      // Initialize percentages
      $('.vote-progress-bar').each(function() {
        let style = $(this).attr('style');
        if (style) {
          let match = style.match(/width:\s*([\d.]+)%/);
          if (match) {
            $(this).attr('data-width', parseFloat(match[1]));
          }
        }
      });

      // Update votes every 30 seconds
      setInterval(updateLiveVotes, 30000);

      // Smooth scrolling for anchor links
      $('a[href^="#"]').on('click', function(event) {
        if (this.hash !== "") {
          event.preventDefault();
          const hash = this.hash;
          $('html, body').animate({
            scrollTop: $(hash).offset().top - 100
          }, 800);
        }
      });
    });
  </script>
</body>
</html>
