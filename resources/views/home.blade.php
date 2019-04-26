@extends('layouts.app')

@section('header-left')
@include('home.nav')
@endsection

@section('content')
<header class="masthead">
  <div class="container h-100">
    <div class="row h-100">
      <div class="col-md-7 my-auto">
        <div class="header-content mx-auto">
          <h1 class="mb-5">{{ __('home.header') }}</h1>
          <a href="{{ route('register') }}" class="btn btn-outline btn-xl js-scroll-trigger">{{ __('home.header_button') }}</a>
        </div>
      </div>
      <div class="col-md-5 my-auto">
        <div class="device-container">
          <div class="device-mockup macbook portrait white">
            <div class="device">
              <div class="screen">
                
              </div>
              <div class="button"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<section class="download bg-primary text-center" id="wat">
  <div class="container">
    <div class="row">
      <div class="col-md-9 mx-auto">
        <h2 class="section-heading">{{ __('Uw financiën overzichtelijk verwerkt in hapklare grafiekjes en tabelletjes') }}</h2>
        <p>Wil je weten hoe het werkt? Maak dan nu gratis een account aan!</p>
        <a class="btn btn-outline btn-xl js-scroll-trigger" href="{{ route('register') }}"><img src="images/mammon-ee-large.png" height="50" alt="Mammon-ee"></a>
      </div>
    </div>
  </div>
</section>

<section class="features" id="features">
  <div class="container">
    <div class="section-heading text-center">
      <h2>{{ __('Best wel mooi, best wel handig') }}</h2>
      <p class="text-muted">Bekijk eigenschappen van deze app!</p>
      <hr>
    </div>
    <div class="row">
      <div class="col-lg-4 my-auto">
        <div class="device-container">
          <div class="device-mockup macbook portrait white">
            <div class="device">
              <div class="screen">

              </div>
              <div class="button"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-8 my-auto">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-6">
              <div class="feature-item">
                <i class="icon-screen-smartphone text-primary"></i>
                <h3>Mobiel</h3>
                <p class="text-muted">Gewoon in je browser, dus ook op tablet en mobiel!</p>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="feature-item">
                <i class="icon-pie-chart text-primary"></i>
                <h3>Simpel</h3>
                <p class="text-muted">Simpel, duidelijk, niet te veel poe-ha!</p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="feature-item">
                <i class="icon-present text-primary"></i>
                <h3>Gratis</h3>
                <p class="text-muted">Kost niks, qua financieel geld!</p>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="feature-item">
                <i class="icon-lock-open text-primary"></i>
                <h3>Open Source</h3>
                <p class="text-muted">MIT-licensed, dus doe ermee wat je wilt!</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="cta">
  <div class="cta-content">
    <div class="container">
      <h2>Gewoon eens proberen?<br>Begin met registreren.</h2>
      <a href="{{ route('register') }}" class="btn btn-outline btn-xl js-scroll-trigger">Laten we beginnen!</a>
    </div>
  </div>
  <div class="overlay"></div>
</section>

<section class="contact bg-primary" id="code">
  <div class="container">
    <h2>W<span><img src="favicon.png" height="64" class="inline-ee" alt=""></span>ten hoe het gemaakt is?</h2>
    <p>Bekijk op Github</p>
    <ul class="list-inline list-social">
      <li class="list-inline-item social-github">
        <a href="https://github.com/mvune/mammon-ee" target="_blank" rel="noopener noreferrer" class="js-scroll-trigger">
          <i class="fa fa-github"></i>
        </a>
      </li>
    </ul>
  </div>
</section>

<footer class="home-footer">
  <div class="container">
    <p>&copy; Mammon-ee 2019</p>
    <ul class="list-inline">
      <li class="list-inline-item">
        <a href="#">Privacy</a>
      </li>
      <li class="list-inline-item">
        <a href="#">Voorwaarden</a>
      </li>
      <li class="list-inline-item">
        <a href="#">FAQ</a>
      </li>
    </ul>
  </div>
</footer>
@endsection
