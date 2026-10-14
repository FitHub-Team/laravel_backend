<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SuperFit | الصفحة الرئيسية</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;700&display=swap" rel="stylesheet">
<style>
  :root{--sf-green:#5faa5f;--sf-green-soft:#a9eb9a;--sf-bg:#d8f5cf;--sf-ink:#111;}
  body{font-family:'IBM Plex Sans Arabic',sans-serif;min-height:100vh;margin:0;padding:0;background:#fff;color:var(--sf-ink);}
  .sf-frame{background:#fff;position:relative;overflow:hidden;width:100%;min-height:100vh;}
  .sf-frame::before{content:"";position:absolute;inset:auto auto 0 0;width:70%;height:45%;
            background:radial-gradient(ellipse at 20% 80%,rgba(120,210,110,.45),transparent 65%);pointer-events:none;}
  .sf-frame::after{content:"";position:absolute;top:-40px;right:-40px;width:260px;height:260px;
            background:radial-gradient(circle,rgba(140,220,120,.5),transparent 70%);pointer-events:none;}
  .navbar{position:relative;z-index:2;}
  .navbar-brand img{height:64px;}
  .nav-link{color:var(--sf-ink);font-weight:500;}
  .nav-link.active{color:var(--sf-green)!important;}
  .btn-sf{border:1px solid #333;border-radius:50px;padding:.55rem 1.5rem;background:#fff;color:var(--sf-ink);font-weight:500;}
  .btn-sf:hover{background:var(--sf-green);border-color:var(--sf-green);color:#fff;}
  .hero{position:relative;z-index:1;}
  .hero h1{font-weight:700;font-size:clamp(2.4rem,5.5vw,4.4rem);line-height:1.2;}
  .hero h1 .g{color:var(--sf-green);}
  .hero p.lead{max-width:30rem;font-size:1.05rem;font-weight:500;line-height:1.8;}
  .hero-img{width:100%;max-width:440px;display:block;margin-inline:auto 0;}
  .feature{background:rgba(255,255,255,.75);backdrop-filter:blur(6px);border:1px solid #aaa;border-radius:18px;padding:1rem 1.25rem;height:100%;}
  .feature img{width:96px;height:96px;object-fit:cover;border-radius:50%;}
  .feature .tag{color:var(--sf-green);font-size:.8rem;font-weight:700;}
  .feature h5{font-weight:700;margin:0;}
  .feature p{font-size:.85rem;margin:0;line-height:1.6;}
  @media(max-width:991px){.hero-img{margin:2rem auto 0}}
</style>
</head>
<body>
<div class="sf-frame">
  <nav class="navbar navbar-expand-lg px-4 pt-4">
    <a class="navbar-brand" href="#">  <img src="{{ asset('front/imag/logo.png') }}" alt="SuperFit Logo">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-label="القائمة"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3 mt-3 mt-lg-0">
        <li class="nav-item"><a class="nav-link active" href="#">الرئيسية</a></li>
        <li class="nav-item"><a class="nav-link" href="#">اتصل بنا</a></li>
        <li class="nav-item"><a class="btn btn-sf" href="#">دخول المسؤول</a></li>
        <li class="nav-item"><a class="btn btn-sf" href="#">دخول المدرب</a></li>
      </ul>
    </div>
  </nav>

  <main class="hero container-fluid px-4 px-lg-5 pb-5">
    <div class="row align-items-center">
      <div class="col-lg-7 py-4">
        <h1 class="mb-4"><span class="g">صحة</span> الجسم،<br><span class="g">صفاء</span> الروح،<br><span class="g">قوة</span> العقل.</h1>
        <p class="lead mb-5">غذِّ جسمك، واهدئ روحك، واشحذ ذهنك. خيارات صحية يومية بسيطة تقودك إلى غد أقوى وأكثر سعادة.</p>
        <div class="row g-3">
          <div class="col-sm-6">
            <div class="feature d-flex align-items-center gap-3">
             <img src="{{ asset('front/imag/dumbbells.jpg') }}" alt="dumbbells">
              <div><span class="tag">صحي</span><h5>تدريب القوة</h5><p>بناء العضلات وتنشيط حرق السعرات.</p></div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="feature d-flex align-items-center gap-3">
              <img src="{{ asset('front/imag/salad.jpg') }}" alt="salad">
              <div><span class="tag">صحي</span><h5>سلطة خضراء</h5><p>طازجة وخفيفة وغنية بالعناصر الغذائية.</p></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <img src="{{ asset('front/imag/hero.jpg') }}" alt="hero">
      </div>
    </div>
  </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
