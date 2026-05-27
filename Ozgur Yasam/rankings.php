<?php
require 'config/db.php';

if(!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->query("
    SELECT u.id, u.name, s.saved_money, 
    (SELECT COUNT(*) FROM user_badges WHERE user_id = u.id) as badge_count 
    FROM users u 
    JOIN savings s ON u.id = s.user_id 
    ORDER BY s.saved_money DESC 
    LIMIT 20
");
$rankings = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bolder text-body-emphasis mb-2">
            <i class="fa-solid fa-ranking-star text-primary me-2"></i>İrade Şampiyonları
        </h2>
        <p class="text-body-secondary">En çok tasarruf eden ve rozet toplayan kullanıcılar.</p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-body-secondary">
                    <tr>
                        <th class="ps-4 py-3 border-0 text-secondary" style="width: 80px;">SIRA</th>
                        <th class="py-3 border-0 text-secondary">KULLANICI</th>
                        <th class="py-3 border-0 text-secondary text-center">ROZETLER</th>
                        <th class="pe-4 py-3 border-0 text-secondary text-end">TOPLAM TASARRUF</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1;
                    foreach($rankings as $r): 
                        $is_me = ($r['id'] == $user_id);
                    ?>
                    <tr class="<?= $is_me ? 'table-primary' : '' ?>">
                        <td class="ps-4">
                            <?php if($rank <= 3): ?>
                                <span class="badge <?= $rank==1 ? 'bg-warning text-dark' : ($rank==2 ? 'bg-secondary' : 'bg-danger') ?> rounded-circle p-2 shadow-sm">
                                    <i class="fa-solid <?= $rank==1 ? 'fa-crown' : 'fa-medal' ?>"></i>
                                </span>
                            <?php else: ?>
                                <span class="fw-bold text-secondary ms-2">#<?= $rank ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold me-3" style="width: 40px; height: 40px;">
                                    <?= mb_strtoupper(mb_substr($r['name'], 0, 1)) ?>
                                </div>
                                <span class="fw-bold text-body-emphasis">
                                    <?= htmlspecialchars($r['name']) ?>
                                    <?php if($is_me): ?> <small class="badge bg-primary ms-1 text-white">Siz</small> <?php endif; ?>
                                </span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                <i class="fa-solid fa-medal me-1"></i><?= $r['badge_count'] ?> Rozet
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <span class="fw-bolder text-success fs-5">₺ <?= number_format($r['saved_money'], 2) ?></span>
                        </td>
                    </tr>
                    <?php $rank++; endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>