# Boz Tech çalışma alanı

Boz Tech ekibi için Laravel tabanlı mesai ve üretim raporlama uygulaması. Türkçe arayüz, SQLite ile hızlı başlangıç ve gerektiğinde MySQL/PostgreSQL yapılandırması sunar.

## Özellikler

- Yönetici için ilk kurulum ve oturum açma/kapama
- Yönetici ekranından ekip üyesi hesabı oluşturma
- Günlük mesai girişi ve çıkışı; açık mesai kaydını kapatmadan ikinci girişe izin verilmez
- Gün sonu, pazartesi–pazar hafta sonu ve takvim ayı raporları
- Taslak kaydetme ve raporu tamamlama
- Çalışanların yalnızca kendi kayıtlarını, yöneticinin ekip raporlarını görmesi
- Yönetici genel bakışında günlük mesai ve rapor özeti
- Türkiye saat dilimi ve küçük ekranlara uyumlu arayüz

## Yerelde çalıştırma

Gereksinimler: PHP 8.3+, Composer, Node.js ve npm.

```sh
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm install
npm run build
php artisan serve
```

Tarayıcıda `http://127.0.0.1:8000/kurulum` adresini açıp ilk yönetici hesabını oluştur. Bu kurulum ekranı yalnızca veritabanında kullanıcı yokken kullanılabilir. Sonrasında yönetici **Ekip** bölümünden çalışan hesaplarını açar.

Çalışma sırasında arayüz varlıklarını canlı derlemek için `npm run dev` kullanabilirsin.

## Dağıtım

Üretim ortamında `.env` içindeki `APP_ENV`, `APP_DEBUG`, `APP_URL`, veritabanı ve oturum ayarlarını sunucuya göre düzenle. `APP_DEBUG=false` kullan, HTTPS etkinleştir ve web kökünü Laravel projesinin `public` klasörüne yönlendir. `php artisan migrate --force` ile şemayı uygula. Veritabanı ve yükleme/oturum dosyaları için düzenli yedekleme ayarla.

Çalışan şifresi yönetici tarafından ilk hesap oluşturulurken belirlenir ve çalışanla güvenli biçimde paylaşılır. Uygulamada e-posta gönderimi veya parola sıfırlama ekranı yapılandırılmış değildir.
