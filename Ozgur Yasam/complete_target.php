<?php
require 'config/db.php';

if(!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$success_msg = '';
$error_msg = '';

$habit_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $pdo->prepare("SELECT * FROM habits WHERE id = ? AND user_id = ?");
$stmt->execute([$habit_id, $user_id]);
$habit = $stmt->fetch();

if (!$habit || $habit['status'] == 'completed') {
    die("Hata: Kayıt bulunamadı veya zaten tamamlanmış.");
}

function sendTelegramNotification($msg) {
    $token = "BOT_TOKEN"; 
    $chatId = "CHAT_ID";
    $url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chatId&text=" . urlencode($msg);
    @file_get_contents($url);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['gunu_kapat'])) {
    $gerceklesen = floatval($_POST['gerceklesen']);
    
    if ($gerceklesen < 0) {
        $error_msg = "Miktar negatif olamaz.";
    } else {
        $kurtarilan_adet = $habit['target'] - $gerceklesen;
        $tasarruf = ($kurtarilan_adet > 0) ? ($kurtarilan_adet * $habit['unit_cost']) : 0;
        
        try {
            $pdo->beginTransaction();

            $stmt1 = $pdo->prepare("UPDATE savings SET saved_money = saved_money + ? WHERE user_id = ?");
            $stmt1->execute([$tasarruf, $user_id]);
            
            $stmt2 = $pdo->prepare("UPDATE habits SET status = 'completed', amount = ? WHERE id = ?");
            $stmt2->execute([$gerceklesen, $habit_id]);
            
            $stmt_bal = $pdo->prepare("SELECT saved_money FROM savings WHERE user_id = ?");
            $stmt_bal->execute([$user_id]);
            $new_balance = $stmt_bal->fetchColumn();

            $stmt_badge = $pdo->prepare("
                INSERT IGNORE INTO user_badges (user_id, badge_id)
                SELECT ?, id FROM badges 
                WHERE requirement_type = 'total_savings' AND requirement_value <= ?
            ");
            $stmt_badge->execute([$user_id, $new_balance]);

            $pdo->commit();

            $tele_msg = "✅ Gün Sonu: $user_name\nSöz: {$habit['type']}\nTasarruf: " . number_format($tasarruf, 2) . " ₺\nToplam Bakiye: " . number_format($new_balance, 2) . " ₺";
            sendTelegramNotification($tele_msg);

            $success_msg = "Başarıyla kaydedildi! Yönlendiriliyorsunuz...";
            header("refresh:2;url=dashboard.php");

        } catch (Exception $e) {
            $pdo->rollBack();
            $error_msg = "Hata: " . $e->getMessage();
        }
    }
}

include 'includes/header.php';
?>

<div class="container d-flex justify-content-center align-items-center min-vh-75 mt-5">
    <div class="col-md-6 col-lg-5">
        <?php if($success_msg) echo "<div class='alert alert-success shadow rounded-4'>$success_msg</div>"; ?>
        <?php if($error_msg) echo "<div class='alert alert-danger shadow rounded-4'>$error_msg</div>"; ?>

        <div class="card border-0 shadow-lg rounded-4 bg-body p-4 text-center">
            <div class="mx-auto bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                <i class="fa-solid fa-scale-balanced fa-2xl"></i>
            </div>
            <h3 class="fw-bold mb-3">Gün Sonu Yüzleşmesi</h3>
            <p class="text-secondary small mb-4">Söz verilen miktar: <b><?= $habit['target'] ?> Birim <?= $habit['type'] ?></b></p>

            <form method="POST">
                <div class="mb-4">
                    <label class="form-label fw-bold">Gerçekleşen Miktar</label>
                    <div class="input-group input-group-lg shadow-sm rounded-4 overflow-hidden">
                        <input type="number" step="0.01" name="gerceklesen" class="form-control bg-body border-0 text-center fs-3 fw-bold" placeholder="0.00" required autofocus>
                        <span class="input-group-text bg-body border-0 fw-bold px-3">Birim</span>
                    </div>
                </div>
                <button type="submit" name="gunu_kapat" class="btn btn-success btn-lg w-100 rounded-pill fw-bold py-3 shadow-sm">
                    Kaydet ve Bitir <i class="fa-solid fa-check-double ms-2"></i>
                </button>
                <a href="dashboard.php" class="btn btn-link text-secondary mt-3 text-decoration-none small">Vazgeç, Panele Dön</a>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>