# Flowchart untuk Draw.io (diagrams.net)

## Cara Menggunakan:

### Metode 1: Import Mermaid (Paling Mudah)
1. Buka **https://app.diagrams.net/**
2. Klik **Extras** → **Edit Diagram** (atau tekan Ctrl+Shift+X)
3. Ganti isi dengan XML di bawah
4. Klik **OK** → Diagram otomatis muncul

### Metode 2: Mermaid Live Editor → Export
1. Buka **https://mermaid.live/**
2. Paste kode Mermaid di bawah
3. Klik **Actions** → **Export as PNG/SVG**
4. Atau copy kode ke draw.io via: **Insert** → **Advanced** → **Mermaid**

---

## A. Flowchart Login

```mermaid
flowchart TD
    A([MULAI]) --> B[Tampilkan Halaman Form Login]
    B --> C[/Input: Email & Password/]
    C --> D{Format input valid?\nemail & password terisi?}
    D -->|TIDAK| E[Tampilkan pesan error validasi]
    E --> B
    D -->|YA| F[Cari pengguna di DB\nberdasarkan email]
    F --> G{Email ditemukan &\npassword cocok\nbcrypt?}
    G -->|TIDAK| H[Tampilkan pesan:\nKredensial tidak cocok]
    H --> B
    G -->|YA| I[Regenerasi sesi\nsession regenerate]
    I --> J[Baca kolom peran\ndari data pengguna]
    J --> K{peran?}
    K -->|admin| L[Redirect ke\n/admin/dashboard]
    K -->|petugas| M[Redirect ke\n/petugas/dashboard]
    K -->|peminjam| N[Redirect ke\n/peminjam/dashboard]
    L --> O([SELESAI])
    M --> O
    N --> O
```

---

## B. Flowchart Peminjaman Alat

```mermaid
flowchart TD
    A([MULAI]) --> B

    subgraph PEMINJAM["👤 PEMINJAM"]
        B[Buka Halaman Katalog Alat] --> C["Query: SELECT alat\nWHERE stok > 0\nfilter by kategori"]
        C --> D[Tampilkan daftar alat\nyang tersedia]
        D --> E[Pilih alat yang\ningin dipinjam]
        E --> F[/Input:\nTanggal pinjam\nDurasi 1-14 hari/]
        F --> G{Validasi:\nalat_id valid?\ntanggal >= hari ini?\ndurasi 1-14?}
        G -->|TIDAK| H[Tampilkan pesan\nerror validasi]
        G -->|YA| I{Stok alat >= 1?}
        I -->|TIDAK| J[Stok alat habis]
        I -->|YA| K["Hitung:\ntgl_wajib_kembali =\ntgl_pinjam + durasi"]
        K --> L["INSERT ke tabel peminjaman:\npengguna_id, alat_id\ntanggal_pinjam\ntgl_wajib_kembali\nstatus = diajukan"]
        L --> M[Tampilkan pesan:\nPeminjaman berhasil diajukan]
    end

    subgraph PETUGAS["🔧 PETUGAS / ADMIN"]
        N[Buka halaman\ndaftar peminjaman] --> O["Pilih peminjaman\nstatus = diajukan"]
        O --> P{Aksi?}
        P -->|SETUJUI| Q["CALL proses_persetujuan\n_peminjaman\nid, petugas_id"]
        P -->|TOLAK| R["UPDATE status\n= ditolak"]
        Q --> S["Di dalam Procedure:\nSTART TRANSACTION\nUPDATE status = disetujui\nINSERT log_aktivitas\nCOMMIT"]
        S --> T["TRIGGER otomatis:\nkurangi_stok_setelah\n_disetujui\nstok = stok - jumlah"]
        T --> U[Tampilkan pesan sukses]
        R --> U
    end

    M --> N
    H --> F
    J --> D
    U --> V([SELESAI])
```

---

## C. Flowchart Pengembalian Alat & Denda

```mermaid
flowchart TD
    A([MULAI]) --> B

    subgraph PEMINJAM["👤 PEMINJAM"]
        B["Buka halaman\nPeminjaman Saya"] --> C["Pilih peminjaman\nstatus = disetujui\nKlik Ajukan Pengembalian"]
        C --> D{pengguna_id\n= user login?}
        D -->|TIDAK| E["TOLAK 403\nAkses ditolak"]
        D -->|YA| F{status =\ndisetujui?}
        F -->|TIDAK| G["Error: Hanya yang\ndisetujui bisa\ndikembalikan"]
        F -->|YA| H["UPDATE peminjaman\nSET status =\nsedang_dikembalikan"]
        H --> I["Tampilkan pesan:\nSilakan serahkan\nalat ke petugas"]
    end

    subgraph PETUGAS["🔧 PETUGAS / ADMIN"]
        J["Petugas menerima\nalat secara fisik"] --> K["Klik tombol\nKembalikan"]
        K --> L{status = disetujui\nATAU\nsedang_dikembalikan?}
        L -->|TIDAK| M["Error: Status tidak\nbisa dikembalikan"]
        L -->|YA| N

        subgraph SP["📦 STORED PROCEDURE: proses_pengembalian"]
            N["START TRANSACTION"] --> O["Langkah 1:\nAmbil tanggal_wajib_kembali"]
            O --> P

            subgraph FN["⚙️ FUNCTION: hitung_denda"]
                P{"tanggal_kembali >\ntanggal_wajib?"} -->|YA| Q["hari_terlambat =\nselisih hari\ndenda = hari × 5000"]
                P -->|TIDAK| R["denda = 0"]
            end

            Q --> S["Langkah 3:\nUPDATE peminjaman\nstatus = dikembalikan\ntanggal_kembali = HARI INI\ndenda = nominal_denda"]
            R --> S
            S --> T["Langkah 4:\nINSERT log_aktivitas\naksi = Konfirmasi Pengembalian"]
            T --> U["COMMIT"]
        end

        U --> V["TRIGGER otomatis:\ntambah_stok_setelah\n_dikembalikan\nstok = stok + jumlah"]
        V --> W["Tampilkan pesan:\nAlat dikembalikan\nDenda: Rp XXX"]
    end

    I --> J
    W --> X([SELESAI])
```

---

## Tips di Draw.io:

1. **Import via Mermaid**: Klik menu **Insert** → **Advanced** → **Mermaid...**
2. Paste salah satu kode `mermaid` di atas (tanpa tanda ``` )
3. Klik **Insert** → Diagram otomatis ter-render
4. Bisa di-edit visual setelahnya (drag, resize, warna)
5. Export sebagai **PNG**, **SVG**, atau **PDF**
