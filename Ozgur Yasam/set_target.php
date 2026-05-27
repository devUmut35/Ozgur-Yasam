<?php
require 'config/db.php';
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $type = $_POST['type'];
    $target = $_POST['target'];
    $unit_cost = $_POST['unit_cost'];
    $date = date('Y-m-d');

    $check = $pdo->prepare("SELECT id FROM habits WHERE user_id = ? AND type = ? AND date = ? AND status = 'pending'");
    $check->execute([$user_id, $type, $date]);
    
    if($check->rowCount() > 0) {
        $error = "Bugün için bu kategoride zaten bekleyen bir hedefin var! Lütfen önce onu tamamla.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO habits (user_id, type, amount, target, unit_cost, date, status) VALUES (?, ?, 0, ?, ?, ?, 'pending')");
        $stmt->execute([$user_id, $type, $target, $unit_cost, $date]);
        
        // TELEGRAM BİLDİRİMİ GÖNDER
        if (function_exists('sendTelegram')) {
            $msg = "🚀 <b>Yeni Bir Söz Verildi!</b>\n\nBugünkü hedefin: <b>$target $type</b>\nBirim Maliyeti: ₺$unit_cost\n\n<i>Unutma, irade kas gibidir; kullandıkça güçlenir!</i>";
            sendTelegram($msg);
        }

        header("Location: dashboard.php");
        exit;
    }
}

$aliskanliklar = [
    ['id' => 'sigara', 'isim' => 'Sigara', 'ikon' => 'fa-smoking', 'birim' => 'Dal'],
    ['id' => 'alkol', 'isim' => 'Alkol', 'ikon' => 'fa-wine-glass', 'birim' => 'Kadeh/Şişe'],
    ['id' => 'kahve', 'isim' => 'Kahve', 'ikon' => 'fa-mug-hot', 'birim' => 'Bardak'],
    ['id' => 'fastfood', 'isim' => 'Fast Food', 'ikon' => 'fa-burger', 'birim' => 'Porsiyon'],
    ['id' => 'aburcubur', 'isim' => 'Abur Cubur', 'ikon' => 'fa-cookie-bite', 'birim' => 'Paket'],
    ['id' => 'bahis', 'isim' => 'Şans Oyunları', 'ikon' => 'fa-dice', 'birim' => 'Kupon/Oyun']
];

include 'includes/header.php';
?>

<style>
    .habit-card-label {
        transition: all 0.2s ease-in-out;
        border: 2px solid var(--bs-border-color);
        background-color: var(--bs-body-bg);
        color: var(--bs-body-color);
        cursor: pointer;
    }
    .habit-card-label:hover {
        border-color: var(--bs-primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .btn-check:checked + .habit-card-label {
        border-color: var(--bs-primary);
        background-color: rgba(var(--bs-primary-rgb), 0.1);
        color: var(--bs-primary);
    }
    .btn-check:checked + .habit-card-label i { transform: scale(1.15); }
    
    .input-group-text {
        background-color: rgba(var(--bs-primary-rgb), 0.1);
        border: 1px solid var(--bs-border-color);
        border-right: none;
        color: var(--bs-primary);
    }
    .input-group .form-control { border-left: none; }
    .input-group:focus-within .input-group-text { border-color: #86b7fe; }
    [data-bs-theme="dark"] .input-group:focus-within .input-group-text { border-color: #0a58ca; }
</style>

<div class="row justify-content-center mt-4 mb-5">
    <div class="col-md-10 col-lg-6">
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger fw-bold shadow-sm border-0 rounded-4 mb-4 d-flex align-items-center">
                <i class="fa-solid fa-circle-exclamation fs-4 me-3"></i>
                <div><?= $error ?></div>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-body p-4 p-md-5 text-center">
                
                <div class="bg-primary text-white d-inline-flex justify-content-center align-items-center rounded-circle mb-4 shadow-sm" style="width: 80px; height: 80px;">
                    <i class="fa-solid fa-bullseye fs-1"></i>
                </div>
                
                <h3 class="fw-bold mb-2">Güne Başlarken</h3>
                <p class="text-muted mb-4 small">Bugün neyi kontrol altına almak istiyorsun? Hedefini seç ve iradeni göster.</p>

                <form method="POST" class="text-start">
                    
                    <label class="form-label fw-bold text-body mb-3">Neyi takip ediyoruz?</label>
                    <div class="row g-3 mb-4">
                        <?php foreach($aliskanliklar as $index => $item): ?>
                            <div class="col-6 col-sm-4">
                                <input type="radio" class="btn-check" name="type" id="type_<?= $item['id'] ?>" value="<?= $item['isim'] ?>" <?= $index === 0 ? 'required' : '' ?>>
                                <label class="habit-card-label h-100 w-100 p-3 rounded-4 text-center d-flex flex-column align-items-center justify-content-center" for="type_<?= $item['id'] ?>">
                                    <i class="fa-solid <?= $item['ikon'] ?> fs-2 mb-2" style="transition: transform 0.2s;"></i>
                                    <span class="fw-bold d-block"><?= $item['isim'] ?></span>
                                    <small class="text-muted mt-1" style="font-size: 0.75rem;">(<?= $item['birim'] ?>)</small>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="row g-3 mb-5">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-body">Bugünkü Maksimum Hedefin</label>
                            <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                                <span class="input-group-text"><i class="fa-solid fa-crosshairs"></i></span>
                                <input type="number" step="0.1" name="target" class="form-control" placeholder="Örn: 10" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-body">Birim Maliyeti</label>
                            <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                                <span class="input-group-text"><i class="fa-solid fa-turkish-lira-sign"></i></span>
                                <input type="number" step="0.01" name="unit_cost" class="form-control" placeholder="Örn: 4.50" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold py-3 shadow-sm rounded-pill">
                        Kendime Söz Veriyorum <i class="fa-solid fa-handshake ms-2"></i>
                    </button>
                    
                </form>
            </div>
        </div>
        
    </div>
</div>

<?php include 'includes/footer.php'; ?>