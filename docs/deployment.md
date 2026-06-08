# Panduan Deployment SIMADES

Dokumen ini merangkum deployment SIMADES untuk shared hosting dan VPS. Sesuaikan perintah dengan panel hosting, versi PHP, dan akses shell yang tersedia.

## Checklist Umum Production

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_KEY` sudah dibuat dan tidak berubah setelah go-live.
- `APP_URL` sesuai domain HTTPS.
- Database production sudah dibuat.
- `DEFAULT_ADMIN_PASSWORD` diisi kuat sebelum seeding pertama.
- `FONNTE_TOKEN` diisi jika WhatsApp aktif.
- Folder `storage/` dan `bootstrap/cache/` writable.
- Queue worker atau cron sudah disiapkan.
- Backup database dilakukan sebelum deploy perubahan besar.

## Shared Hosting

Shared hosting biasanya punya batasan akses shell dan document root. Target paling aman adalah mengarahkan domain ke folder `public/`.

### Struktur Direkomendasikan

```text
home/user/simades/              # source Laravel
home/user/public_html/          # document root domain
```

Opsi terbaik:

- Set document root domain/subdomain ke `simades/public`.
- Jika panel tidak mendukung ubah document root, pindahkan isi `public/` ke `public_html/`, lalu sesuaikan path `../vendor/autoload.php` dan `../bootstrap/app.php` agar mengarah ke folder source.

### Upload File

Upload source aplikasi, lalu jalankan jika shell tersedia:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate --force
php artisan migrate --force
php artisan simades:sync-permissions
php artisan storage:link
php artisan optimize
```

Jika tidak ada shell:

- Build vendor dan asset dari lokal dengan versi PHP yang sama.
- Upload folder `vendor/`, `public/build/`, source aplikasi, dan `.env` production.
- Jalankan migration dan seeder melalui fitur terminal hosting, scheduled task, atau minta bantuan provider.

### Queue di Shared Hosting

Jika tidak tersedia process manager, gunakan cron:

```bash
* * * * * cd /home/user/simades && php artisan schedule:run >> /dev/null 2>&1
```

Untuk queue tanpa supervisor, cron dapat menjalankan worker pendek:

```bash
* * * * * cd /home/user/simades && php artisan queue:work --stop-when-empty --tries=3 --timeout=60 >> /dev/null 2>&1
```

## VPS

VPS memberi kontrol penuh. Gunakan Nginx/Apache, PHP-FPM, MySQL/MariaDB, dan Supervisor.

### Deploy Awal

```bash
git clone https://github.com/hndko/app_suratdesa_laravel12.git /var/www/simades
cd /var/www/simades
composer install --no-dev --optimize-autoloader
npm ci
npm run build
cp .env.example .env
php artisan key:generate --force
php artisan migrate --force
php artisan simades:sync-permissions
php artisan storage:link
php artisan optimize
```

Atur permission:

```bash
sudo chown -R www-data:www-data /var/www/simades
sudo chmod -R ug+rwx storage bootstrap/cache
```

### Contoh Nginx

```nginx
server {
    listen 80;
    server_name simades.example.com;
    root /var/www/simades/public;

    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
    }

    location ~ /\. {
        deny all;
    }
}
```

### Supervisor Queue

```ini
[program:simades-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/simades/artisan queue:work --sleep=3 --tries=3 --timeout=60
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/simades/storage/logs/queue.log
stopwaitsecs=3600
```

Aktifkan:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl restart simades-queue:*
```

## Update Production

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan simades:sync-permissions
php artisan optimize
php artisan queue:restart
```

Jika hanya ada perubahan permission:

```bash
php artisan simades:sync-permissions
```

## Rollback Singkat

- Kembalikan commit aplikasi ke versi sebelumnya.
- Restore database dari backup jika migration sudah mengubah struktur/data.
- Jalankan:

```bash
php artisan optimize:clear
php artisan queue:restart
```
