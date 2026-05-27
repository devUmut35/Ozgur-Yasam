<!DOCTYPE html>
<html lang="tr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Özgür Yaşam | Bağımlılık Azaltma</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .modern-nav {
            background-color: var(--bs-body-bg);
            border-bottom: 1px solid var(--bs-border-color-translucent);
        }
        .nav-link.custom-pill {
            color: var(--bs-secondary-color);
            font-weight: 600;
            padding: 0.6rem 1.2rem !important;
            border-radius: 50rem;
            transition: all 0.3s ease;
            margin: 0 0.1rem;
        }
        .nav-link.custom-pill:hover {
            background-color: var(--bs-secondary-bg);
            color: var(--bs-body-color);
        }
        .nav-link.custom-pill.active {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd !important;
        }
        [data-bs-theme="dark"] .nav-link.custom-pill.active {
            background-color: rgba(13, 110, 253, 0.15);
            color: #6ea8fe !important;
        }
        /* Tema Değiştirici Buton Stili */
        #themeSwitcher {
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: 0.3s;
        }
        #themeSwitcher:hover {
            background-color: var(--bs-secondary-bg);
        }
    </style>

    <script>
        // Sayfayı En Üste Atan Kod
        if ('scrollRestoration' in history) { history.scrollRestoration = 'manual'; }
        window.addEventListener('load', function() { window.scrollTo(0, 0); });

        // Gece/Gündüz Modu Fonksiyonu
        function toggleTheme() {
            const htmlElement = document.documentElement;
            const currentTheme = htmlElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            htmlElement.setAttribute('data-bs-theme', newTheme);
            
            // İkonu Değiştir
            const icon = document.querySelector('#themeSwitcher i');
            icon.className = newTheme === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
            
            // Tercihi Kaydet (Sayfa yenilenince gitmesin)
            localStorage.setItem('theme', newTheme);
        }

        // Sayfa açıldığında kaydedilen temayı yükle
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            const icon = document.querySelector('#themeSwitcher i');
            if(icon) icon.className = savedTheme === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
        });
    </script>
</head>
<body class="bg-body-tertiary d-flex flex-column min-vh-100">

<?php $current_page = basename($_SERVER['PHP_SELF']); ?>

<nav class="navbar navbar-expand-lg modern-nav sticky-top py-3 shadow-sm mb-4">
    <div class="container border-0">
        
        <a class="navbar-brand fw-bolder text-primary d-flex align-items-center fs-4" href="index.php">
            <i class="fa-solid fa-leaf me-2 text-success"></i>Özgür Yaşam
        </a>
        
        <div class="d-flex align-items-center order-lg-3">
            <div id="themeSwitcher" class="me-2" onclick="toggleTheme()">
                <i class="fa-solid fa-moon fs-5 text-secondary"></i>
            </div>

            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="btn btn-outline-danger rounded-pill fw-bold btn-sm px-3 ms-2 d-none d-sm-inline-block">
                    Çıkış <i class="fa-solid fa-right-from-bracket ms-1"></i>
                </a>
            <?php else: ?>
                <div class="d-flex gap-2 ms-2">
                    <a href="login.php" class="btn btn-light rounded-pill fw-bold btn-sm px-3 border shadow-sm">Giriş</a>
                    <a href="login.php" class="btn btn-primary rounded-pill fw-bold btn-sm px-3 shadow-sm">Kayıt Ol</a>
                </div>
            <?php endif; ?>

            <button class="navbar-toggler border-0 shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#topMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse order-lg-2" id="topMenu">
            <?php if(isset($_SESSION['user_id'])): ?>
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link custom-pill <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>" href="dashboard.php">
                        <i class="fa-solid fa-chart-pie me-2"></i>Panel
                    </a>
                </li>
                <li class="nav-item">
    <a class="nav-link custom-pill <?= ($current_page == 'rankings.php') ? 'active' : '' ?>" href="rankings.php">
        <i class="fa-solid fa-ranking-star me-2"></i>Sıralama
    </a>
</li>
                <li class="nav-item">
                    <a class="nav-link custom-pill <?= ($current_page == 'profile.php') ? 'active' : '' ?>" href="profile.php">
                        <i class="fa-solid fa-user me-2"></i>Profilim
                    </a>
                </li>
            </ul>
            <?php endif; ?>
        </div>
        
    </div>
</nav>

<div class="container flex-grow-1">