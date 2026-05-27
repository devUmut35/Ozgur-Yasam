<?php
require 'config/db.php';
// Kullanıcı oturum kontrolü [cite: 15, 58]
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');

// Toplam tasarruf verisini ilişkisel tablodan çek [cite: 19, 55, 63]
$stmt = $pdo->prepare("SELECT saved_money FROM savings WHERE user_id = ?");
$stmt->execute([$user_id]);
$savings = $stmt->fetchColumn() ?: 0;

// Bugünkü kayıtları listele [cite: 17, 49, 60]
$stmt_today = $pdo->prepare("SELECT * FROM habits WHERE user_id = ? AND date = ? ORDER BY id DESC");
$stmt_today->execute([$user_id, $today]);
$todays_habits = $stmt_today->fetchAll();

// Motivasyon Mesajları Dizisi [cite: 20]
$mesajlar = [
    ["baslik" => "İrade Gücü", "soz" => "Bağımlılık seni değil, sen bağımlılığı yönetiyorsun. Kontrol sende!", "icon" => "fa-brain", "color" => "#0dcaf0"],
    ["baslik" => "Finansal Özgürlük", "soz" => "Bugün tasarruf ettiğin her kuruş, yarınki özgürlüğünün temel taşıdır.", "icon" => "fa-piggy-bank", "color" => "#198754"],
    ["baslik" => "Yeni Bir Sayfa", "soz" => "Dün ne olduğu önemli değil, bugün her şeyi değiştirmek için harika bir gün.", "icon" => "fa-sun", "color" => "#ffc107"],
    ["baslik" => "Kararlılık", "soz" => "Zirveye çıkan yol, atılan o ilk ve kararlı adımla başlar. Durma!", "icon" => "fa-person-hiking", "color" => "#fd7e14"],
    ["baslik" => "Sağlık Yatırımı", "soz" => "Vücudun, içinde yaşamak zorunda olduğun tek yerdir. Ona iyi bak.", "icon" => "fa-heart-pulse", "color" => "#dc3545"],
    ["baslik" => "Küçük Zaferler", "soz" => "Her 'hayır' deyişin, özgürlüğüne atılmış dev bir adımdır.", "icon" => "fa-flag-checkered", "color" => "#6f42c1"],
    ["baslik" => "Gelecek İçin", "soz" => "Bugünün acısı, yarının gücüdür. Kendine yatırım yapmaktan vazgeçme.", "icon" => "fa-seedling", "color" => "#20c997"],
    ["baslik" => "Öz Saygı", "soz" => "Sözünü tuttuğun her gün, kendine olan saygını bir kat daha artırır.", "icon" => "fa-handshake", "color" => "#0d6efd"],
    ["baslik" => "Meydan Oku", "soz" => "Kolay olanı herkes yapar. Sen zoru başararak farkını ortaya koyuyorsun.", "icon" => "fa-fire", "color" => "#e83e8c"],
    ["baslik" => "Zamanın Değeri", "soz" => "Kaybettiğin parayı geri kazanabilirsin ama zamanı asla. Anı iyi değerlendir.", "icon" => "fa-hourglass-half", "color" => "#6c757d"]
];
$secilen = $mesajlar[array_rand($mesajlar)];

include 'includes/header.php';
?>

<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 mt-2">
        <div>
            <h1 class="fw-bold mb-1">Hoş geldin, <?= htmlspecialchars($_SESSION['user_name']) ?>! 👋</h1>
            <p class="text-muted">Gelişimini buradan takip edebilirsin.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="set_target.php" class="btn btn-primary btn-lg shadow-sm fw-bold rounded-pill px-4">
                <i class="fa-solid fa-plus-circle me-2"></i>Yeni Hedef Belirle
            </a>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); min-height: 180px;">
                <div class="card-body p-5 d-flex flex-column justify-content-center position-relative">
                    <i class="fa-solid fa-wallet position-absolute end-0 bottom-0 mb-n3 me-n3 opacity-25" style="font-size: 8rem; color: white;"></i>
                    <h6 class="text-uppercase fw-bold mb-2 text-white-50">Toplam Tasarruf</h6>
                    <h2 class="display-3 fw-bolder text-white m-0">₺<?= number_format($savings, 2) ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-2 border-start border-primary border-5">
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-2 rounded-3 me-3" style="background-color: <?= $secilen['color'] ?>20;">
                            <i class="fa-solid <?= $secilen['icon'] ?> fs-4" style="color: <?= $secilen['color'] ?>;"></i>
                        </div>
                        <span class="fw-bold text-uppercase small" style="color: <?= $secilen['color'] ?>; letter-spacing: 1px;">
                            <?= $secilen['baslik'] ?>
                        </span>
                    </div>
                    <div class="position-relative py-1">
                        <i class="fa-solid fa-quote-left text-primary opacity-25 position-absolute top-0 start-0 translate-middle" style="font-size: 2rem;"></i>
                        <h5 class="fw-normal lh-base fst-italic ps-3 mb-0">
                            "<?= $secilen['soz'] ?>"
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <h4 class="fw-bold mb-4 pb-2 border-bottom d-flex align-items-center">
            <i class="fa-solid fa-layer-group text-primary me-2"></i>Bugünkü Aktif Panellerin
        </h4>

        <div class="row g-4">
            <?php if(count($todays_habits) == 0): ?>
                <div class="col-12">
                    <div class="card border-2 border-dashed bg-transparent rounded-4 text-center p-5">
                        <i class="fa-solid fa-mug-hot text-muted fs-1 mb-3 opacity-50"></i>
                        <h5 class="text-muted">Henüz bir hedef girmedin.</h5>
                        <p class="small text-muted mb-4">Kontrolü eline almak için yukarıdaki butondan bir hedef belirle!</p>
                        <a href="set_target.php" class="btn btn-outline-primary btn-sm rounded-pill px-4">Şimdi Başla</a>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach($todays_habits as $habit): ?>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-transparent border-0 py-3 d-flex justify-content-between align-items-center px-4">
                                <h5 class="m-0 fw-bold"><?= htmlspecialchars($habit['type']) ?></h5>
                                <?php if($habit['status'] == 'pending'): ?>
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 shadow-sm"><i class="fa-solid fa-clock me-1"></i>Bekliyor</span>
                                <?php else: ?>
                                    <span class="badge bg-success rounded-pill px-3 py-2 shadow-sm"><i class="fa-solid fa-check me-1"></i>Tamamlandı</span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-4 pt-0">
                                <?php if($habit['status'] == 'pending'): ?>
                                    <div class="row text-center mb-4 bg-secondary bg-opacity-10 rounded-3 py-3 mx-0">
                                        <div class="col-6 border-end border-secondary border-opacity-25">
                                            <p class="text-muted small text-uppercase mb-1">Sabah Hedefi</p>
                                            <h3 class="fw-bold m-0"><?= $habit['target'] ?></h3>
                                        </div>
                                        <div class="col-6">
                                            <p class="text-muted small text-uppercase mb-1">Durum</p>
                                            <p class="fw-bold text-warning m-0">Kapanış Bekliyor</p>
                                        </div>
                                    </div>
                                    <a href="complete_target.php?id=<?= $habit['id'] ?>" class="btn btn-outline-primary w-100 py-3 rounded-3 fw-bold border-2">
                                        Günü Kapat ve Verileri Gir
                                    </a>
                                <?php else: ?>
                                    <div class="row text-center mb-4 bg-secondary bg-opacity-10 rounded-3 py-3 mx-0">
                                        <div class="col-6 border-end border-secondary border-opacity-25">
                                            <p class="text-muted small text-uppercase mb-1">Hedef</p>
                                            <h4 class="fw-bold text-muted text-decoration-line-through m-0"><?= $habit['target'] ?></h4>
                                        </div>
                                        <div class="col-6">
                                            <p class="text-muted small text-uppercase mb-1">Gerçekleşen</p>
                                            <h4 class="fw-bold m-0 <?= ($habit['amount'] <= $habit['target']) ? 'text-success' : 'text-danger' ?>">
                                                <?= $habit['amount'] ?>
                                            </h4>
                                        </div>
                                    </div>
                                    <?php if($habit['amount'] <= $habit['target']): ?>
                                        <div class="alert alert-success border-0 d-flex align-items-center mb-0 rounded-3">
                                            <i class="fa-solid fa-trophy fs-4 me-3"></i>
                                            <div><strong class="d-block">Sözünü Tuttun!</strong><small>İradenle kazandığın zafer bugün tasarrufuna yansıdı.</small></div>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-danger border-0 d-flex align-items-center mb-0 rounded-3">
                                            <i class="fa-solid fa-heart-pulse fs-4 me-3"></i>
                                            <div><strong class="d-block">Yarın Yeni Bir Şans</strong><small>Hedefini aşmış olabilirsin ama pes etmek yok!</small></div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>