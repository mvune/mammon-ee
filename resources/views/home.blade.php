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
          <a href="{{ route('register') }}" class="btn btn-outline btn-xl">{{ __('home.header_button') }}</a>
        </div>
      </div>
      <div class="col-md-5 my-auto">
        <div class="device-container">
          <div class="device-mockup macbook portrait white">
            <div class="device">
              <div class="screen">
                <img src="/images/donuts.png" class="img-fluid" alt="Dashboardpagina met donutdiagrammen">
              </div>
              <div class="button"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<section class="download bg-primary text-center" id="what">
  <div class="container">
    <div class="row">
      <div class="col-md-9 mx-auto">
        <h2 class="section-heading">{{ __('Uw financiën overzichtelijk verwerkt in hapklare grafiekjes en tabelletjes') }}</h2>
        <p>Wil je weten hoe het werkt? Maak dan nu gratis een account aan!</p>
        <a class="btn btn-outline btn-primary btn-xl" href="{{ route('register') }}"><img src="images/mammon-ee-large.png" height="50" alt="Mammon-ee"></a>
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
                <img src="/images/sheet.png" class="img-fluid" alt="Dashboardpagina met overzichtstabel">
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

<section class="cta" id="cta">
  <div class="cta-content">
    <div class="container">
      <h2>Gewoon eens proberen?<br>Begin met registreren.</h2>
      <a href="{{ route('register') }}" class="btn btn-outline btn-xl btn-start">Laten we beginnen!</a>
    </div>
  </div>
  <div class="overlay"></div>
</section>
@endsection
