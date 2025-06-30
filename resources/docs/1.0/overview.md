# Sistem SIMRS - KASIR

Aplikasi web berbasis Laravel yang dirancang untuk mengelola operasional klinik secara efisien, mulai dari pendaftaran pasien, manajemen data, hingga proses pembayaran yang terintegrasi. Yang merupakan ruang lingkup kasir sebuah rumah sakit

---

## 📜 Ringkasan Proyek

Sistem Informasi Klinik ini berfungsi sebagai platform terpusat untuk administrasi klinik. Aplikasi ini mengatasi tantangan dalam mengelola alur data yang kompleks dengan mengintegrasikan data pasien dan pendaftaran dari API eksternal, sementara mengelola data finansial seperti harga dan transaksi secara lokal. Tujuannya adalah menyediakan antarmuka yang bersih dan fungsional bagi admin untuk memonitor dan mengelola kegiatan klinik sehari-hari.

---

## ✨ Fitur Inti

**Dashboard Admin:** Menampilkan data agregat penting seperti total pemasukan, jumlah transaksi, dan jumlah pasien hariwan.

**Manajemen Pendaftaran:** Menampilkan daftar pendaftaran pasien dari API dengan fitur pencarian dan filter server-side.

**Manajemen Pasien (CRUD):** Fungsionalitas lengkap untuk menambah, melihat, mengedit, dan menghapus data pasien melalui integrasi API.

**Manajemen Harga Dinamis:** Modul terpisah untuk menetapkan harga layanan untuk setiap **Poli** dan **Dokter** yang datanya disimpan secara lokal.

**Sistem Tagihan & Pembayaran:** Alur kerja kompleks untuk menghasilkan rincian tagihan berdasarkan Nomor Invoice, menggabungkan data dari berbagai API dan database lokal.

**Autentikasi Berbasis Role:** Sistem membedakan hak akses untuk beberapa jenis pengguna (misal: Admin, Pasien, API).

**Dokumentasi Interaktif:** Dokumentasi proyek yang dibangun menggunakan VitePress.

---

## 🌊 Alur Kerja Utama (Contoh: Pembuatan Tagihan)

Alur pembuatan tagihan menunjukkan bagaimana sistem mengagregasi data dari berbagai sumber:

1.  **Input:** Admin memasukkan **Nomor Invoice** di halaman pencarian tagihan.
2.  **Identifikasi:** Sistem mengekstrak `unicode` dari invoice untuk menemukan `no_registrasi` yang sesuai dari database lokal.
3.  **Agregasi Data:** :
    -   Mengambil detail pendaftaran dari **API Pendaftaran** (untuk info poli & dokter).
    -   Mengambil daftar obat dari **API E-Resep**.
    -   Mengambil harga master obat dari **API Obat**.
    -   Mengambil data harga dari model lokal: `PricePoli`, `PriceDokter`, dan `PriceAdditional`.
    -   Mengambil data diskon dari model lokal `PaymentType`.
4.  **Kalkulasi:** Service menghitung total biaya, menerapkan diskon, dan menghasilkan _grand total_.
5.  **Presentasi:** Controller menampilkan hasil kalkulasi pada halaman detail tagihan.
6.  **Pembayaran:** Admin memproses pembayaran, yang kemudian meng-update record `Transaction` di database lokal.

---
