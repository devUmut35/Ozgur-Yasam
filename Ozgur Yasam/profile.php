<?php
require 'config/db.php';

if(!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

$user_id = $_SESSION['user_id'];
$update_msg = ''; 
$update_err = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $newName = trim($_POST['name']); 
    $newEmail = trim($_POST['email']);
    try {
        if (!empty($_POST['password'])) {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
            $stmt->execute([$newName, $newEmail, password_hash($_POST['password'], PASSWORD_DEFAULT), $user_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $stmt->execute([$newName, $newEmail, $user_id]);
        }
        $_SESSION['user_name'] = $newName;
        $update_msg = "Bilgileriniz güncellendi!";
    } catch (Exception $e) { 
        $update_err = "Güncelleme sırasında bir hata oluştu."; 
    }
}

$allBadges = $pdo->query("SELECT * FROM badges ORDER BY requirement_value ASC")->fetchAll();
$stmt_ub = $pdo->prepare("SELECT badge_id FROM user_badges WHERE user_id = ?");
$stmt_ub->execute([$user_id]);
$userBadgeIds = $stmt_ub->fetchAll(PDO::FETCH_COLUMN);

$user = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
$user->execute([$user_id]);
$u = $user->fetch();

include 'includes/header.php';
?>

<style>
    .profile-header { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); border-radius: 1.5rem; padding: 3rem; color: white; margin-bottom: 2rem; }
    .user-avatar { width: 90px; height: 90px; background: white; color: #0d6efd; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: bold; box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .badge-box { background: var(--bs-body-tertiary); border: 1px solid var(--bs-border-color); border-radius: 1.2rem; padding: 1.5rem; text-align: center; height: 100%; transition: 0.3s; }
    .badge-locked { filter: grayscale(100%); opacity: 0.3; position: relative; }
    .badge-locked::after { content: '\f023'; font-family: "Font Awesome 6 Free"; font-weight: 900; position: absolute; top: 10px; right: 15px; background: var(--bs-body-bg); border-radius: 50%; width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; font-size: 12px; border: 1px solid var(--bs-border-color); }
    .badge-unlocked { transform: scale(1.05); filter: drop-shadow(0 5px 15px currentColor); }
</style>

<?php if($update_msg): ?> <div class="alert alert-success border-0 shadow-sm rounded-4"><?= $update_msg ?></div> <?php endif; ?>
<?php if($update_err): ?> <div class="alert alert-danger border-0 shadow-sm rounded-4"><?= $update_err ?></div> <?php endif; ?>

<div class="profile-header d-flex flex-column flex-md-row align-items-center shadow-lg">
    <div class="user-avatar me-md-4 mb-3 mb-md-0"><?= mb_strtoupper(mb_substr($u['name'], 0, 1)) ?></div>
    <div class="text-center text-md-start">
        <h2 class="fw-bold mb-1">Merhaba, <?= htmlspecialchars($u['name']) ?>!</h2>
        <p class="opacity-75 mb-0"><i class="fa-solid fa-envelope me-2"></i><?= htmlspecialchars($u['email']) ?></p>
    </div>
    <div class="ms-md-auto mt-4 mt-md-0">
        <span class="badge bg-light text-primary fs-6 px-4 py-2 rounded-pill shadow-sm">
            <i class="fa-solid fa-medal me-2"></i><?= count($userBadgeIds) ?> Rozet Kazanıldı
        </span>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-body">
            <h5 class="fw-bold mb-4 border-bottom pb-2"><i class="fa-solid fa-user-gear me-2"></i>Hesap Ayarları</h5>
            <form method="POST">
                <div class="mb-3">
                    <label class="small fw-bold text-secondary">Ad Soyad</label>
                    <input type="text" name="name" class="form-control bg-body-tertiary border-0 shadow-none" value="<?= htmlspecialchars($u['name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-secondary">E-posta</label>
                    <input type="email" name="email" class="form-control bg-body-tertiary border-0 shadow-none" value="<?= htmlspecialchars($u['email']) ?>" required>
                </div>
                <div class="mb-4">
                    <label class="small fw-bold text-secondary">Yeni Şifre (İsteğe bağlı)</label>
                    <input type="password" name="password" class="form-control bg-body-tertiary border-0 shadow-none">
                </div>
                <button type="submit" name="update_profile" class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-sm">Bilgileri Güncelle</button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-body">
            <h5 class="fw-bold mb-4 border-bottom pb-2"><i class="fa-solid fa-medal text-warning me-2"></i>Başarı Rozetleri</h5>
            <div class="row g-3">
                <?php foreach ($allBadges as $badge): 
                    $isUnlocked = in_array($badge['id'], $userBadgeIds);
                ?>
                <div class="col-6 col-md-4 col-xl-3">
                    <div class="badge-box <?= $isUnlocked ? $badge['color_class'] : 'badge-locked text-secondary' ?>" title="<?= htmlspecialchars($badge['description']) ?>">
                        <i class="fa-solid <?= $badge['icon'] ?> fa-3x mb-3 <?= $isUnlocked ? 'badge-unlocked' : '' ?>"></i>
                        <h6 class="fw-bold text-body-emphasis mb-1 small"><?= htmlspecialchars($badge['name']) ?></h6>
                        <p class="text-muted mb-0" style="font-size: 0.7rem;"><?= htmlspecialchars($badge['description']) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>