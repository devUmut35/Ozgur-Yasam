<?php
require 'config/db.php';

if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

include 'includes/header.php';
?>

<style>
    .hero-blob {
        position: absolute;
        width: 350px;
        height: 350px;
        background: linear-gradient(135deg, rgba(13, 110, 253, 0.15), rgba(25, 135, 84, 0.15));
        filter: blur(60px);
        border-radius: 50%;
        z-index: 0;
        top: 50%;
        right: 10%;
        transform: translateY(-50%);
    }
    .pro-card {
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        z-index: 1;
        position: relative;
    }
    .pro-card:hover {
        transform: translateY(-8px);
    }
    [data-bs-theme="light"] .pro-card:hover { box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important; }
    [data-bs-theme="dark"] .pro-card:hover { box-shadow: 0 1rem 3rem rgba(0,0,0,0.4) !important; }
    .icon-box {
        width: 60px;
        height: 60px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 1rem;
        margin-bottom: 1.5rem;
    }
    
    /* Rozet Vitrini CSS */
    .badge-card { transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.27); cursor: default; }
    .badge-card:hover { transform: scale(1.1); }
    .badge-icon-wrapper {
        width: 90px;
        height: 90px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 4px solid var(--bs-border-color);
        transition: all 0.3s ease;
        background-color: var(--bs-body-bg);
        position: relative;
    }
    .badge-icon-wrapper::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        box-shadow: 0 0 20px currentColor;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .badge-card:hover .badge-icon-wrapper { border-color: currentColor; }
    .badge-card:hover .badge-icon-wrapper::after { opacity: 0.2; }
</style>

<div class="row align-items-center py-5 mt-4 min-vh-75 position-relative">
    <div class="col-lg-6 mb-5 mb-lg-0 pe-lg-5" style="z-index: 2;">
        <div class="d-inline-flex align-items-center bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-4 border border-primary border-opacity-25">
            <span class="badge bg-primary rounded-pill me-2">Yeni</span>
            <span class="fw-bold fs-6">Bitirme Projesi 2026</span>
        </div>
        <h1 class="display-4 fw-bolder text-body-emphasis mb-4 lh-sm">
            Zararlı Alışkanlıklara Veda Et,<br>
            <span class="text-primary">Geleceğine Yatırım Yap.</span>
        </h1>
        <p class="lead text-body-secondary mb-5 fs-5">
            Sağlığını geri kazanırken cüzdanını da koru. Günlük tüketimini takip et, hedefler belirle ve her geçen gün ne kadar tasarruf ettiğini canlı olarak panelinden izle.
        </p>
        <div class="d-flex flex-column flex-sm-row gap-3">
            <a href="login.php" class="btn btn-primary btn-lg rounded-pill px-5 py-3 shadow-sm fw-bold">
                Hemen Başla <i class="fa-solid fa-arrow-right ms-2"></i>
            </a>
            <a href="#features" class="btn btn-outline-secondary btn-lg rounded-pill px-5 py-3 fw-bold">
                Nasıl Çalışır?
            </a>
        </div>
    </div>

    <div class="col-lg-6 position-relative">
        <div class="hero-blob d-none d-lg-block"></div>
        <div class="bg-body p-4 p-md-5 rounded-4 shadow-lg border pro-card" style="border-color: var(--bs-border-color-translucent);">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <div>
                    <p class="text-body-secondary mb-1 fw-bold fs-6">Toplam Tasarruf</p>
                    <h2 class="fw-bolder text-success mb-0">₺ 4.250,00</h2>
                </div>
                <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle">
                    <i class="fa-solid fa-wallet fa-2xl"></i>
                </div>
            </div>
            <div class="mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-body-emphasis fw-medium">Sigara (Aylık Hedef)</span>
                    <span class="text-primary fw-bold">%85</span>
                </div>
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 85%;"></div>
                </div>
            </div>
            <div class="d-flex align-items-center bg-body-tertiary p-3 rounded-3 border">
                <i class="fa-solid fa-bell text-warning fs-3 me-3"></i>
                <div>
                    <h6 class="mb-0 fw-bold text-body-emphasis">Harika gidiyorsun! 🎉</h6>
                    <small class="text-body-secondary">Bu hafta hedeflerinin %100'üne ulaştın.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="features" class="row py-5 mt-5 border-top">
    <div class="col-12 text-center mb-5">
        <span class="text-primary fw-bold text-uppercase tracking-wide">Özellikler</span>
        <h2 class="display-6 fw-bold text-body-emphasis mt-2">Neden Özgür Yaşam?</h2>
        <p class="text-body-secondary lead mx-auto" style="max-width: 600px;">Sistemin sana sunduğu araçlarla motivasyonunu asla kaybetme.</p>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100 border bg-body-tertiary p-4 rounded-4 pro-card shadow-sm">
            <div class="card-body p-0">
                <div class="icon-box bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-chart-pie fs-4"></i></div>
                <h4 class="fw-bold text-body-emphasis mb-3">Gelişmiş Analizler</h4>
                <p class="text-body-secondary mb-0">Harcamalarını ve tasarruflarını grafikler üzerinden detaylı olarak analiz et. Hangi gün ne kadar başardığını gör.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100 border bg-body-tertiary p-4 rounded-4 pro-card shadow-sm">
            <div class="card-body p-0">
                <div class="icon-box bg-success bg-opacity-10 text-success"><i class="fa-solid fa-bullseye fs-4"></i></div>
                <h4 class="fw-bold text-body-emphasis mb-3">Hedef Belirleme</h4>
                <p class="text-body-secondary mb-0">Kendine kısa ve uzun vadeli hedefler koy. Biriktirdiğin parayla almak istediğin şeyleri sisteme kaydet.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100 border bg-body-tertiary p-4 rounded-4 pro-card shadow-sm">
            <div class="card-body p-0">
                <div class="icon-box bg-warning bg-opacity-10 text-warning"><i class="fa-solid fa-medal fs-4"></i></div>
                <h4 class="fw-bold text-body-emphasis mb-3">Sürekli Motivasyon</h4>
                <p class="text-body-secondary mb-0">Sistem, ulaştığın her kilometre taşında sana başarı rozetleri verir. Gelişimini gördükçe vazgeçmeyeceksin.</p>
            </div>
        </div>
    </div>
</div>

<div id="badges" class="row py-5 mt-5 border-top">
    <div class="col-12 text-center mb-5">
        <span class="text-warning fw-bold text-uppercase tracking-wide"><i class="fa-solid fa-trophy me-2"></i>Oyunlaştırma</span>
        <h2 class="display-6 fw-bold text-body-emphasis mt-2">Rozet Koleksiyonunu Tamamla</h2>
        <p class="text-body-secondary lead mx-auto" style="max-width: 600px;">Sadece para biriktirme, başarılarını da biriktir. Hedeflerine ulaştıkça yeni rozetlerin kilidini aç!</p>
    </div>

    <div class="col-12">
        <div class="row g-4 justify-content-center">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card h-100 border-0 bg-transparent text-center badge-card text-success">
                    <div class="badge-icon-wrapper mx-auto mb-3 shadow-sm"><i class="fa-solid fa-seedling fa-2x"></i></div>
                    <h6 class="fw-bold text-body-emphasis mb-1">İlk Adım</h6>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card h-100 border-0 bg-transparent text-center badge-card text-danger">
                    <div class="badge-icon-wrapper mx-auto mb-3 shadow-sm"><i class="fa-solid fa-piggy-bank fa-2x"></i></div>
                    <h6 class="fw-bold text-body-emphasis mb-1">Bronz Kumbara</h6>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card h-100 border-0 bg-transparent text-center badge-card text-info">
                    <div class="badge-icon-wrapper mx-auto mb-3 shadow-sm"><i class="fa-solid fa-shield-halved fa-2x"></i></div>
                    <h6 class="fw-bold text-body-emphasis mb-1">Çelik İrade</h6>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card h-100 border-0 bg-transparent text-center badge-card text-warning">
                    <div class="badge-icon-wrapper mx-auto mb-3 shadow-sm"><i class="fa-solid fa-fire fa-2x"></i></div>
                    <h6 class="fw-bold text-body-emphasis mb-1">Ateşli Seri</h6>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card h-100 border-0 bg-transparent text-center badge-card text-primary">
                    <div class="badge-icon-wrapper mx-auto mb-3 shadow-sm"><i class="fa-solid fa-heart-pulse fa-2x"></i></div>
                    <h6 class="fw-bold text-body-emphasis mb-1">Sağlık Elçisi</h6>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card h-100 border-0 bg-transparent text-center badge-card" style="color: #ffd700;">
                    <div class="badge-icon-wrapper mx-auto mb-3 shadow-sm"><i class="fa-solid fa-crown fa-2x"></i></div>
                    <h6 class="fw-bold text-body-emphasis mb-1">Usta Tasarrufçu</h6>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row my-5">
    <div class="col-12">
        <div class="bg-primary text-white p-5 rounded-4 shadow-lg text-center position-relative overflow-hidden" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
            <div class="position-relative" style="z-index: 2;">
                <h2 class="fw-bold mb-3">Değişime Bugün Başla!</h2>
                <p class="fs-5 opacity-75 mb-4">Binlerce lira tasarruf eden ve sağlığını geri kazananlar arasına katıl.</p>
                <a href="login.php#register" class="btn btn-light btn-lg text-primary fw-bold rounded-pill px-5 shadow">Ücretsiz Hesap Oluştur</a>
            </div>
            <div class="position-absolute top-0 start-0 translate-middle rounded-circle bg-white" style="width: 200px; height: 200px; opacity: 0.1;"></div>
            <div class="position-absolute bottom-0 end-0 translate-middle rounded-circle bg-white" style="width: 300px; height: 300px; opacity: 0.1;"></div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>