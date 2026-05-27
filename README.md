# Bağımlılık Azaltma ve Tasarruf Takip Sistemi

Bağımlılık Azaltma ve Tasarruf Takip Sistemi; sigara, alkol, şans oyunları, fast food, abur cubur ve benzeri alışkanlıklarını azaltmak ya da bırakmak isteyen kullanıcılar için geliştirilmiş web tabanlı bir takip uygulamasıdır.

Uygulama, kullanıcıların günlük alışkanlık verilerini kaydetmesini, hedef belirlemesini, zaman içindeki tasarruf miktarını takip etmesini ve başarılarına göre rozet kazanmasını sağlar.

## Özellikler

- Kullanıcı kayıt ve giriş sistemi
- PHP session tabanlı oturum yönetimi
- Alışkanlık ekleme, düzenleme ve silme işlemleri
- Günlük alışkanlık takibi
- Hedef miktar ve birim maliyet belirleme
- Otomatik tasarruf hesaplama
- Toplam tasarruf görüntüleme
- Rozet / başarı sistemi
- Kullanıcı dashboard paneli
- Geçmiş kayıtları görüntüleme
- Profil bilgilerini güncelleme
- Telegram bot bildirim altyapısı
- Responsive arayüz tasarımı
- Bootstrap tabanlı kullanıcı arayüzü

## Kullanılan Teknolojiler

- PHP
- MySQL / MariaDB
- HTML5
- CSS3
- JavaScript
- Bootstrap 5
- Font Awesome
- Telegram Bot API
- XAMPP / Apache
- phpMyAdmin

## Proje Yapısı

```txt
bagimlilik-takip/
├── config/
│   └── db.php
│
├── includes/
│   ├── header.php
│   └── footer.php
│
├── complete_target.php
├── dashboard.php
├── database.sql
├── history.php
├── index.php
├── login.php
├── logout.php
├── profile.php
├── rankings.php
└── set_target.php
```

## Veritabanı Yapısı

Proje `addiction_tracking` isimli MySQL veritabanı üzerinde çalışmaktadır.

Temel tablolar:

- `users`  
  Kullanıcı bilgilerini tutar.

- `habits`  
  Kullanıcıların alışkanlık kayıtlarını, hedeflerini, birim maliyetlerini ve tarih bilgilerini tutar.

- `savings`  
  Kullanıcıların toplam tasarruf miktarlarını saklar.

- `badges`  
  Sistemdeki rozet tanımlarını içerir.

- `user_badges`  
  Kullanıcıların kazandığı rozetleri saklar.

## Kurulum

Projeyi yerel ortamda çalıştırmak için XAMPP, Laragon veya benzeri bir PHP/MySQL geliştirme ortamı kullanılabilir.

### 1. Projeyi Klonlayın

```bash
git clone https://github.com/kullanici-adi/repo-adi.git
```

### 2. Proje Klasörünü Sunucu Dizinine Taşıyın

XAMPP kullanıyorsanız proje klasörünü aşağıdaki dizine taşıyın:

```txt
C:/xampp/htdocs/
```

Örnek dizin:

```txt
C:/xampp/htdocs/bagimlilik-takip/
```

### 3. Veritabanını Oluşturun

phpMyAdmin üzerinden yeni bir veritabanı oluşturun:

```sql
CREATE DATABASE addiction_tracking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Ardından proje içerisindeki SQL dosyasını içe aktarın:

```txt
database.sql
```

veya elinizdeki güncel SQL dump dosyasını import edin.

### 4. Veritabanı Bağlantısını Ayarlayın

`config/db.php` dosyasında veritabanı bilgilerini kendi ortamınıza göre düzenleyin:

```php
$host = "localhost";
$dbname = "addiction_tracking";
$username = "root";
$password = "";
```

### 5. Apache ve MySQL Servislerini Başlatın

XAMPP üzerinden:

- Apache
- MySQL

servislerini başlatın.

### 6. Projeyi Tarayıcıda Açın

```txt
http://localhost/bagimlilik-takip/
```

## Kullanım

1. Kullanıcı sisteme kayıt olur.
2. Giriş yaptıktan sonra dashboard ekranına yönlendirilir.
3. Alışkanlık türü, tüketim miktarı, hedef miktar ve birim maliyet bilgileri girilir.
4. Sistem hedef ve gerçek tüketim farkına göre tasarruf miktarını hesaplar.
5. Kullanıcı toplam tasarrufunu ve kazandığı rozetleri dashboard üzerinden takip eder.
6. Geçmiş kayıtlar sayfasından önceki alışkanlık verileri görüntülenebilir.

## Rozet Sistemi

Uygulamada kullanıcı motivasyonunu artırmak için rozet sistemi bulunmaktadır.

Rozetler, kullanıcının toplam tasarruf miktarına göre otomatik olarak kazanılır. Örnek rozetler:

- İlk Adım
- Kararlı
- Bronz Kumbara
- Akıllı Bildirim
- Gümüş Kasa
- Altın Külçe
- Elmas Kasa
- Çelik İrade
- Sağlık Elçisi
- Usta Tasarrufçu

## Telegram Bot Entegrasyonu

Projede Telegram Bot API altyapısı kullanılarak bildirim sistemi desteklenmiştir. Belirli işlemler veya başarı durumlarında kullanıcıya Telegram üzerinden bildirim gönderilmesi hedeflenmiştir.

Telegram entegrasyonunu kullanmak için bot token ve chat ID bilgilerinin ilgili PHP dosyalarında veya yapılandırma alanında tanımlanması gerekir.

## Güvenlik

Projede kullanıcı parolaları düz metin olarak saklanmaz. Parolalar PHP tarafında hashlenerek veritabanına kaydedilir.

Ayrıca oturum kontrolü ile yetkisiz kullanıcıların korumalı sayfalara erişmesi engellenir.

## Responsive Tasarım

Arayüz Bootstrap 5 kullanılarak geliştirilmiştir. Bu sayede uygulama masaüstü, tablet ve mobil cihazlarda kullanılabilir yapıdadır.

## Geliştirici Notu

Bu proje eğitim ve geliştirme amacıyla hazırlanmış bir web uygulamasıdır. Sistem gerçek kullanıcı verileriyle yayınlanmadan önce güvenlik, veri doğrulama ve hata yönetimi tarafında ek iyileştirmeler yapılmalıdır.

## Lisans

Bu proje eğitim amaçlı geliştirilmiştir.
