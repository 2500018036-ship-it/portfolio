# Deployment portfolio

## Arsitektur

`azrilachdi.web.id` → Nginx HTTPS di Azure → WireGuard → Nginx Proxy Manager
di Debian → `portfolio-web:80` di Docker network `web-proxy`.

Port aplikasi juga tersedia hanya pada loopback Debian `127.0.0.1:18081`.
MariaDB tidak membuka port ke host. Aplikasi cloud storage menggunakan layanan
dan database tersendiri.

## Deploy perubahan

```sh
git add .
git commit -m "Update portfolio"
git push origin main
```

GitHub Actions memvalidasi Compose, script shell, Composer, dan sintaks PHP;
membangun image produksi; menjalankan migrasi pada database kosong; lalu mengecek
halaman utama, blog, dokumentasi, galeri, login, dan proteksi halaman admin.

Timer Debian memeriksa `main` setiap dua menit. Deploy hanya berjalan setelah
workflow `ci.yml` untuk commit yang sama selesai dengan status `success`, berasal
dari repository ini, dan dipicu oleh push ke `main`. Pull request tidak dideploy.
Repository ini publik sehingga server dapat membaca commit dan hasil CI tanpa
menyimpan token GitHub atau membuka SSH untuk GitHub Actions. Bila repository
diubah menjadi privat, akses baca server perlu dikonfigurasi ulang.

## Lokasi di Debian

| Data | Lokasi |
| --- | --- |
| Source | `/mnt/data/docker-compose/portfolio/repository` |
| Konfigurasi rahasia | `/mnt/data/docker-compose/portfolio/runtime.env` |
| Commit aktif | `/mnt/data/docker-compose/portfolio/state/deployed-sha` |
| Backup database | `/mnt/data/docker-compose/portfolio/backups/` |
| Database MariaDB | `/mnt/data/storage/portfolio/mysql/` |
| File unggahan publik | `/mnt/data/storage/portfolio/uploads/` |
| Log, session, dokumen privat | `/mnt/data/storage/portfolio/writable/` |
| Konfigurasi deploy | `/etc/portfolio-deploy.conf` |

`.env`, dump SQL, dokumen sumber `data_konten`, dan unggahan pengguna tidak
dimasukkan ke Git atau image Docker. File tersebut dipindahkan langsung ke
server melalui SSH pada instalasi awal. Akun admin dan konten awal berasal
dari database lokal; deployment berikutnya tidak menjalankan seeder konten
atau mengganti akun admin.

## Perintah operasi

```sh
sudo systemctl status portfolio-deploy.timer
sudo journalctl -u portfolio-deploy.service -n 100 --no-pager
sudo systemctl start portfolio-deploy.service

cd /mnt/data/docker-compose/portfolio/repository
sudo docker compose --env-file ../runtime.env ps
sudo docker compose --env-file ../runtime.env logs --tail=100 app
```

Setiap deploy menyimpan dump database konsisten sebelum migrasi, lalu membangun
dan menjalankan image berlabel SHA commit. Jika migrasi atau health check gagal,
script mengembalikan aplikasi ke image sebelumnya dan mempertahankan dump
database untuk pemulihan. Migrasi database tidak dibalik otomatis karena dapat
menghilangkan data. Buat migrasi yang tetap kompatibel dengan versi sebelumnya.

## Rollback manual

Hentikan timer terlebih dahulu supaya commit `main` tidak dipasang kembali:

```sh
sudo systemctl stop portfolio-deploy.timer
cd /mnt/data/docker-compose/portfolio/repository
sudo git checkout --detach COMMIT_SEBELUMNYA
sudo env RELEASE_SHA=COMMIT_SEBELUMNYA docker compose --env-file ../runtime.env \
  up -d --no-deps --no-build --wait app
```

Periksa kompatibilitas schema sebelum memulihkan database dari backup. Untuk
melanjutkan deployment otomatis, perbaiki atau revert perubahan di `main`, lalu
aktifkan kembali `sudo systemctl start portfolio-deploy.timer`.

## Instalasi ulang

Gunakan `.env.production.example` sebagai contoh untuk `runtime.env`, buat
password acak baru dan `ENCRYPTION_KEY` 64 digit hex, atur izin file menjadi 600,
dan salin `deploy/portfolio-deploy.conf.example` ke `/etc/portfolio-deploy.conf`.
Simpan source dan data pada lokasi di tabel di atas. Pastikan network
`web-proxy` tersedia. Pulihkan database serta direktori unggahan dari backup;
untuk instalasi kosong gunakan `php spark migrate --all` dan seeder secara manual.

Salin `deploy/deploy.sh` dan `deploy/poll.sh` ke
`/usr/local/sbin/portfolio-deploy` dan `/usr/local/sbin/portfolio-poll` dengan
izin 750. Pasang unit service/timer ke `/etc/systemd/system/`, lalu jalankan:

```sh
sudo systemctl daemon-reload
sudo systemctl enable --now portfolio-deploy.timer
```

Konfigurasikan proxy host untuk `azrilachdi.web.id` menuju `portfolio-web:80`.
Nginx Azure harus meneruskan `Host` dan `X-Forwarded-Proto: https` melalui
WireGuard. Sertifikat TLS dikelola di Azure.

Cookie sesi produksi menggunakan `Secure` dan `HttpOnly`. Pemeriksaan HTTP
internal harus menyertakan header `X-Forwarded-Proto: https`, seperti health
check dan workflow CI, untuk mencerminkan terminasi HTTPS di proxy. Domain
`www` diarahkan langsung ke `https://azrilachdi.web.id/` di Azure. Proxy Azure
juga mengubah redirect HTTP dari origin menjadi HTTPS.
