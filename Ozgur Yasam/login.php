<?php
require 'config/db.php';

// Kullanıcı zaten giriş yapmışsa direkt dashboard'a at
if(isset($_SESSION['user_id'])) { header("Location: dashboard.php"); exit; }
$error = ''; $success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        $name = $_POST['name']; 
        $email = $_POST['email'];
        
        // GÜVENLİK AKTİF: Şifreler artık kriptolanarak kaydedilecek
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $password]);
            
            $user_id = $pdo->lastInsertId();
            // Yeni kullanıcı için tasarruf tablosunda sıfır bakiyeli bir satır aç
            $pdo->query("INSERT INTO savings (user_id, saved_money) VALUES ($user_id, 0)");
            
            $success = "Harika! Kaydın oluşturuldu. Şimdi giriş yapabilirsin.";
        } catch(Exception $e) { 
            $error = "Bu e-posta adresi zaten kullanımda."; 
        }
        
    } elseif (isset($_POST['login'])) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$_POST['email']]);
        $user = $stmt->fetch();
        
        // GÜVENLİK AKTİF: Girilen şifre ile kriptolu şifre karşılaştırılıyor
        if ($user && password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['user_name'] = $user['name'];
            header("Location: dashboard.php"); 
            exit;
        } else { 
            $error = "E-posta veya şifre hatalı. Lütfen tekrar dene."; 
        }
    }
}
include 'includes/header.php';
?>

<style>
    .nav-pills .nav-link.active { color: #ffffff !important; }
</style>

<div class="row justify-content-center align-items-center mt-5 mb-5">
    <div class="col-xl-10 col-lg-12">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-transparent">
            <div class="row g-0">
                <div class="col-md-5 bg-primary text-white d-none d-md-flex flex-column justify-content-center align-items-center p-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                    <div style="z-index: 2;" class="text-center">
                        <i class="fa-solid fa-leaf text-success mb-3" style="font-size: 5rem; filter: drop-shadow(0px 4px 6px rgba(0,0,0,0.3));"></i>
                        <h2 class="fw-bolder mb-3">Özgür Yaşam'a<br>Hoş Geldin</h2>
                        <p class="opacity-75 fs-6 mb-0">Sağlığını geri kazanırken, cüzdanını korumaya başlamak için tek yapman gereken adım atmak.</p>
                    </div>
                    <div class="position-absolute top-0 start-0 translate-middle rounded-circle bg-white" style="width: 150px; height: 150px; opacity: 0.1; z-index: 0;"></div>
                    <div class="position-absolute bottom-0 end-0 translate-middle rounded-circle bg-white" style="width: 250px; height: 250px; opacity: 0.1; z-index: 0;"></div>
                </div>

                <div class="col-md-7 bg-body p-4 p-md-5">
                    <?php if($error): ?><div class="alert alert-danger shadow-sm border-0 fw-bold"><i class="fa-solid fa-triangle-exclamation me-2"></i><?= $error ?></div><?php endif; ?>
                    <?php if($success): ?><div class="alert alert-success shadow-sm border-0 fw-bold"><i class="fa-solid fa-check-circle me-2"></i><?= $success ?></div><?php endif; ?>

                    <ul class="nav nav-pills nav-justified mb-4 pb-2 border-bottom" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation"><button class="nav-link active fw-bold fs-5 rounded-pill" id="pills-login-tab" data-bs-toggle="pill" data-bs-target="#pills-login" type="button" role="tab">Giriş Yap</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link fw-bold fs-5 rounded-pill" id="pills-register-tab" data-bs-toggle="pill" data-bs-target="#pills-register" type="button" role="tab">Kayıt Ol</button></li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-login" role="tabpanel">
                            <div class="text-center mb-4">
                                <h4 class="fw-bold text-body-emphasis">Tekrar Merhaba!</h4>
                                <p class="text-body-secondary">Kaldığın yerden devam etmek için giriş yap.</p>
                            </div>
                            <form method="POST">
                                <div class="form-floating mb-3">
                                    <input type="email" name="email" class="form-control bg-body-tertiary border-0" id="loginEmail" placeholder="name@example.com" required>
                                    <label for="loginEmail" class="text-body-secondary"><i class="fa-solid fa-envelope me-2"></i>E-posta Adresi</label>
                                </div>
                                <div class="form-floating mb-4">
                                    <input type="password" name="password" class="form-control bg-body-tertiary border-0" id="loginPass" placeholder="Şifre" required>
                                    <label for="loginPass" class="text-body-secondary"><i class="fa-solid fa-lock me-2"></i>Şifre</label>
                                </div>
                                <button type="submit" name="login" class="btn btn-primary btn-lg w-100 fw-bold shadow rounded-pill py-3">Sisteme Gir <i class="fa-solid fa-arrow-right ms-2"></i></button>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="pills-register" role="tabpanel">
                            <div class="text-center mb-4">
                                <h4 class="fw-bold text-body-emphasis">Aramıza Katıl</h4>
                                <p class="text-body-secondary">Sadece birkaç saniye içinde yeni bir sayfa aç.</p>
                            </div>
                            <form method="POST">
                                <div class="form-floating mb-3">
                                    <input type="text" name="name" class="form-control bg-body-tertiary border-0" id="regName" placeholder="Ad Soyad" required>
                                    <label for="regName" class="text-body-secondary"><i class="fa-solid fa-user me-2"></i>Ad Soyad</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="email" name="email" class="form-control bg-body-tertiary border-0" id="regEmail" placeholder="name@example.com" required>
                                    <label for="regEmail" class="text-body-secondary"><i class="fa-solid fa-envelope me-2"></i>E-posta Adresi</label>
                                </div>
                                <div class="form-floating mb-4">
                                    <input type="password" name="password" class="form-control bg-body-tertiary border-0" id="regPass" placeholder="Şifre" required>
                                    <label for="regPass" class="text-body-secondary"><i class="fa-solid fa-lock me-2"></i>Şifre Belirle</label>
                                </div>
                                <button type="submit" name="register" class="btn btn-success btn-lg w-100 fw-bold shadow rounded-pill py-3">Ücretsiz Kayıt Ol <i class="fa-solid fa-user-plus ms-2"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>