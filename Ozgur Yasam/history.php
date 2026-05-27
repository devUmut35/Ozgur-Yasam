<?php
require 'config/db.php';
// Kullanıcı giriş yapmamışsa login'e at
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$user_id = $_SESSION['user_id'];
$message = '';

// Veri Silme İşlemi (Güvenlik için sadece kendi kaydını silebilsin)
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM habits WHERE id = ? AND user_id = ?");
    if ($stmt->execute([$delete_id, $user_id])) {
        $message = '<div class="alert alert-success alert-dismissible fade show"><i class="fa-solid fa-check-circle"></i> Kayıt başarıyla silindi. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
}

// Kullanıcının tüm geçmiş verilerini tarihe göre yeniden eskiye sıralı çek [cite: 60]
$stmt = $pdo->prepare("SELECT * FROM habits WHERE user_id = ? ORDER BY date DESC");
$stmt->execute([$user_id]);
$records = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center border-bottom pb-3">
        <h2 class="fw-bold text-dark"><i class="fa-solid fa-clock-rotate-left text-primary"></i> Geçmiş Kayıtlar</h2>
        <a href="add_habit.php" class="btn btn-outline-primary fw-bold"><i class="fa-solid fa-plus"></i> Yeni Ekle</a>
    </div>
</div>

<?= $message ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="py-3 px-4">Tarih</th>
                        <th class="py-3">Alışkanlık Türü</th>
                        <th class="py-3">Tüketim Miktarı</th>
                        <th class="py-3 text-end px-4">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($records) > 0): ?>
                        <?php foreach($records as $row): ?>
                        <tr>
                            <td class="px-4 fw-bold text-secondary"><?= date('d.m.Y', strtotime($row['date'])) ?></td>
                            <td>
                                <?php 
                                    // İkon belirleme
                                    $icon = 'fa-tag';
                                    if(strpos(strtolower($row['type']), 'sigara') !== false) $icon = 'fa-smoking';
                                    if(strpos(strtolower($row['type']), 'alkol') !== false) $icon = 'fa-wine-glass';
                                    if(strpos(strtolower($row['type']), 'kahve') !== false) $icon = 'fa-mug-hot';
                                ?>
                                <i class="fa-solid <?= $icon ?> text-muted me-2"></i> 
                                <?= htmlspecialchars($row['type']) ?>
                            </td>
                            <td><span class="badge bg-primary fs-6 px-3 py-2 rounded-pill"><?= $row['amount'] ?></span></td>
                            <td class="text-end px-4">
                                <a href="history.php?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger fw-bold" onclick="return confirm('Bu kaydı silmek istediğinize emin misiniz?');">
                                    <i class="fa-solid fa-trash"></i> Sil
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-folder-open fs-1 mb-3 text-light"></i><br>
                                Henüz hiçbir geçmiş kaydın bulunmuyor.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>