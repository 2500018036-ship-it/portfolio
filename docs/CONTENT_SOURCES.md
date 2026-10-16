# Sumber konten dan topologi

Konten ditinjau pada 16 Oktober 2026. Tidak ada konfigurasi perangkat jaringan
yang diubah atau dipulihkan selama pembaruan website ini.

Pembaruan gaya tulisan menghapus catatan proses editorial dari halaman publik.
Parameter teknis tetap sama; sumber dan batas pemeriksaan tetap dicatat di file
ini. Blog dan dokumentasi memakai sampul logo, sedangkan topologi hanya muncul
di bagian Home Lab. Topologi unggahan pemilik dari admin dipertahankan. Sumber
logo ada di `public/media/portfolio/brands/SOURCES.md`; komposisi cover dibuat
dengan `python tools/render_article_covers.py`, lalu diraster ke PNG.

| Informasi | Sumber yang diperiksa |
| --- | --- |
| Profil, pendidikan, pengalaman | CV dan sertifikat pengguna di `data_konten/`; profil produksi |
| Masa prakerin | Sertifikat PT Adhi Persada Properti: 22 Juli–20 Desember 2024 |
| Pelatihan CCNA | Sertifikat pelatihan IDN.ID; tidak diklaim sebagai kelulusan ujian Cisco |
| Asseting | Repository `2500018036-ship-it/asseting`, commit `311a42fb9a213f6134d3444080b678fb1d7b3e5d`; controller, model, struktur tabel, screenshot pengguna |
| MoneyGe | Repository `2500018036-ship-it/moneyge`, commit `7efe6484c8976befb5a86f1801da0d80a88951bd`; `lib/`, `pubspec.yaml`, ikon aplikasi |
| Ticketing | Screenshot System Ticketing Adhi Persada Properti dari pengguna; source dan URL deployment tidak tersedia |
| Hubungan fisik | Sketsa topologi pengguna: ONT, MikroTik, Cisco, PC, server, TP-Link AP |
| Cisco SF200-24 | Screenshot Port Settings dan VLAN Membership dari pengguna |
| MikroTik | `BU.backup` milik pengguna, dibaca offline; interface, VLAN, alamat, pool, DHCP, endpoint tunnel, dan sasaran rule firewall |
| Debian | SSH ke `azril@192.168.20.195`: interface, route, lease DHCP, resolver, sshd, Docker, dan proxy host |
| Technitium | Resolver Debian, respons DNS UDP/53, identitas halaman HTTP/5380 |
| Proxmox | Endpoint `192.168.20.200:8006`, header pve-api-daemon dan judul halaman |
| Jalur Azure | `docs/DEPLOYMENT.md`, endpoint WireGuard dalam backup, hasil DNS, dan HTTP GET domain publik |
| Tailscale | Pernyataan penggunaan dari pengguna; node/ACL/rute belum diperiksa; tailscaled tidak aktif di Debian saat audit |

## Batas verifikasi

Backup MikroTik adalah snapshot konfigurasi, bukan pembacaan keadaan router
secara langsung. Detail CLI dalam artikel merupakan rekonstruksi nilai yang
dapat dipahami, bukan export lengkap atau skrip restore. Firewall dideskripsikan
melalui sumber, sasaran, port, dan komentar rule; efektivitas isolasi tidak
diklaim tanpa pemeriksaan urutan/counter dan tes dari tiap VLAN.

Sketsa awal menyebut static IP via Winbox. Debian aktual menggunakan DHCP
(`ens18`, lease 1800 detik), sehingga dokumentasi tidak mengklaim IP statis pada
Debian atau reservasi router yang belum dibuktikan. VM ID, bridge, CPU/RAM,
model AP/router, konfigurasi Nginx Azure lengkap, dan tailnet tidak diasumsikan.

Isi backup asli, private key, token, password, dan konfigurasi runtime rahasia
tidak disertakan dalam repository maupun aset publik.

## Pengelolaan konten

Bundle ada di `app/Content/portfolio.json`; isi artikel ada di
`app/Content/articles/`. Gambar asli yang relevan dan diagram ada di
`public/media/portfolio/`. Diagram dapat dibuat ulang dengan:

```sh
python tools/render_topology.py
```

SVG adalah sumber vektor. PNG topologi dan service-path disertakan untuk
thumbnail dan metadata media sosial. Jika SVG berubah, render ulang PNG dengan
renderer SVG yang tersedia dan periksa hasilnya secara visual.

Migrasi `PublishVerifiedPortfolio` memanggil seeder yang menjaga data nyata,
menambahkan bundle yang ditinjau, dan mengarsipkan record `is_dummy=1` sebagai
draft/inactive. Record dan file lama tidak dihapus. Semua query publik juga
menyaring `is_dummy=0`, termasuk detail, related content, kategori, dan unduhan.
Deployment normal menyimpan dump database sebelum migrasi. `down()` tidak
menghapus konten; pemulihan data memakai backup pradeploy bila diperlukan.

Seeder idempotent berdasarkan slug/identitas. Pemanggilan ulang disengaja akan
menyelaraskan record bundle, sehingga setelah pemilik mengedit konten di admin,
jangan menjalankan seeder ulang tanpa meninjau perbedaan terlebih dahulu.
