<?php
session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION["username"] ?? "Pengguna";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peta Kebudayaan Indonesia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font lebih menarik -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&family=Cinzel+Decorative:wght@600;700&display=swap" rel="stylesheet">

    <style>
        :root {
    --text: #e5e7eb;
    --muted: #9ca3af;
    --card: rgba(15, 23, 42, 0.9);
    --border: rgba(148, 163, 184, 0.6);

    /* palet dari gambar: navy → ungu → magenta */
    --stripe-1: #020617;
    --stripe-2: #111827;
    --stripe-3: #1d236a;
    --stripe-4: #4c1d95;
    --stripe-5: #be2a92;
}

* { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: "Quicksand", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    min-height: 100vh;
    color: var(--text);

    /* FOTO BACKGROUND UTAMA */
    background: #020617 url("bgpastel.jpg") center/cover fixed no-repeat;
    position: relative;
}

/* overlay gelap tipis supaya teks tetap kebaca */
body::before {
    content: "";
    position: fixed;
    inset: 0;
    background:
        radial-gradient(circle at top, rgba(59, 130, 246, 0.2), transparent 55%),
        radial-gradient(circle at bottom, rgba(147, 51, 234, 0.25), transparent 65%),
        linear-gradient(to bottom, rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.85));
    mix-blend-mode: soft-light;
    pointer-events: none;
    z-index: -2;
}

/* ornamen batik di kiri & kanan layar */
.page-wrap {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    position: relative;
    z-index: 0;
}

.page-wrap::before,
.page-wrap::after {
    content: "";
    position: fixed;
    top: 0;
    bottom: 0;
    width: 120px;
    pointer-events: none;
    opacity: 0.45;
    mix-blend-mode: screen;
    z-index: -1;
}

.page-wrap::before {
    left: 0;
    background: url("batik.jpg") center/cover no-repeat;
}

.page-wrap::after {
    right: 0;
    background: url("batik.jpg") center/cover no-repeat;
}

        .page-wrap {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* HEADER pastel */
        header {
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background:
        linear-gradient(
            90deg,
            var(--stripe-1),
            var(--stripe-2),
            var(--stripe-3),
            var(--stripe-4),
            var(--stripe-5)
        );
    border-bottom: 1px solid rgba(148, 163, 184, 0.5);
    position: relative;
    overflow: hidden;
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.8);
}

header::after {
    content: "";
    position: absolute;
    left: -10%;
    right: -10%;
    bottom: -10px;
    height: 12px;
    background: radial-gradient(circle, rgba(244, 114, 182, 0.8), transparent 55%);
    opacity: 0.7;
}

        .brand { display: flex; align-items: center; gap: 10px; }

        .brand-logo {
            width: 32px; height: 32px; border-radius: 999px;
            background: radial-gradient(circle at 30% 30%, #fee2e2, #f97373);
            box-shadow: 0 0 0 2px #ffffff, 0 0 18px rgba(248, 113, 113, 0.6);
        }

        .brand-text {
    font-family: "Cinzel Decorative", cursive;
    font-weight: 600;
    letter-spacing: .12em;
    font-size: 0.9rem;
    text-transform: uppercase;
    color: #f9fafb;
}

        .user-mini { font-size: 0.84rem; text-align: right; color: #111827; }
        .user-mini span { display: block; }

        .btn-back {
            margin-top: 4px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 10px;
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.8);
            background: rgba(255, 255, 255, 0.9);
            color: #111827;
            font-size: 0.78rem;
            text-decoration: none;
        }
        .btn-back span.icon { font-size: 0.9rem; }

        main {
            flex: 1;
            padding: 24px 16px 28px;
            max-width: 1180px;
            margin: 0 auto;
        }

        /* Judul gradient */
        .title {
    font-family: "Cinzel Decorative", cursive;
    font-size: 1.7rem;
    font-weight: 600;
    margin-bottom: 4px;
    background: linear-gradient(90deg, #f9fafb, #f97316, #ec4899, #60a5fa);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    text-shadow: 0 0 14px rgba(236, 72, 153, 0.6);
}

.photo-title {
    font-family: "Cinzel Decorative", cursive;
    font-size: 0.84rem;
    font-weight: 600;
    margin-bottom: 2px;
    background: linear-gradient(90deg, #f9fafb, #a855f7, #ec4899);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

        .subtitle {
    font-size: 1.15rem !important;        /* lebih besar dari default */
    font-weight: 600 !important;
    color: #e9d5ff !important;            /* ungu pastel terang, sangat terlihat */
    letter-spacing: 0.5px;

    text-shadow:
        0 0 6px rgba(255, 255, 255, 0.9),
        0 0 12px rgba(216, 180, 254, 0.7),
        0 0 22px rgba(168, 85, 247, 0.5);

    opacity: 1 !important;                /* hilangkan efek transparan */
}

        .card {
    background: radial-gradient(circle at top left, rgba(79, 70, 229, 0.28), rgba(15, 23, 42, 0.96));
    border-radius: 26px;
    border: 1px solid rgba(148, 163, 184, 0.65);
    box-shadow:
        0 0 25px rgba(56, 189, 248, 0.28),
        0 0 50px rgba(147, 51, 234, 0.4);
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(18px);
}

.card::before {
    content: "";
    position: absolute;
    inset: -40%;
    background:
        radial-gradient(circle at 0% 0%, rgba(244, 114, 182, 0.35), transparent 60%),
        radial-gradient(circle at 100% 100%, rgba(56, 189, 248, 0.4), transparent 60%);
    opacity: 0.7;
    pointer-events: none;
}

        .card-inner {
            position: relative;
            z-index: 1;
            padding: 16px 18px 18px;
        }

        /* MAP full width */
        .map-card { margin-bottom: 16px; }

        .map-wrapper {
    position: relative;
    width: 100%;
    max-width: 100%;
    border-radius: 22px;
    overflow-x: auto;
    overflow-y: hidden;
    border: 1px solid rgba(129, 140, 248, 0.9);
    background: radial-gradient(circle at top, #0f172a, #020617);
    padding-bottom: 6px;
    box-shadow:
        0 0 24px rgba(129, 140, 248, 0.7),
        0 0 42px rgba(56, 189, 248, 0.5);
}

/* inner-nya selebar gambar */
.map-inner {
    position: relative;
    width: 1411px;
    height: 550px;
}

/* gambar jangan di-stretch ke 100%, biarkan ikut ukuran asli inner */
.map-inner img {
    display: block;
    width: 100%;
    height: 100%;
}

        .map-note {
            font-size: 0.78rem;
            color: var(--muted);
            margin-top: 6px;
        }

/* Titik provinsi: area klik tak terlihat (supaya map gak penuh bulatan) */
.province-dot {
    position: absolute;
    width: 3.5%;      /* area klik agak lebar biar gampang di-klik */
    height: 6%;
    transform: translate(-50%, -50%);
    background: transparent;
    border: none;
    box-shadow: none;
    cursor: pointer;
    z-index: 3;
}

/* optional: efek hover sangat halus (bayangan kecil) */
.province-dot:hover {
    box-shadow: 0 0 10px rgba(248, 113, 113, 0.4);
}

.map-pin {
    position: absolute;
    width: 18px;
    height: 18px;
    border-radius: 999px;
    background: #facc15;
    border: 3px solid #f97373;
    box-shadow: 0 0 16px rgba(248, 113, 113, 0.9);
    transform: translate(-50%, -100%); /* sudah oke, bikin pin naik dikit */
    z-index: 4;
}

        .map-pin::after {
            content: "";
            position: absolute;
            left: 50%;
            top: 100%;
            transform: translateX(-50%);
            width: 4px;
            height: 8px;
            border-radius: 999px;
            background: #f97373;
        }

        .region-title {
            font-size: 0.94rem;
            font-weight: 600;
            margin: 10px 0 6px;
        }

        .region-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            max-height: 120px;
            overflow-y: auto;
        }

        .region-chip {
    border-radius: 999px;
    border: 1px solid rgba(129, 140, 248, 0.8);
    background: radial-gradient(circle at 0% 0%, rgba(76, 29, 149, 0.7), rgba(15, 23, 42, 0.95));
    color: #e5e7eb;
    font-size: 0.8rem;
    padding: 4px 11px;
    cursor: pointer;
    box-shadow: 0 0 8px rgba(129, 140, 248, 0.8);
}

.region-chip.active {
    background: linear-gradient(135deg, #4c1d95, #ec4899, #f97316);
    border-color: #fb7185;
    box-shadow:
        0 0 12px rgba(248, 113, 113, 0.9),
        0 0 22px rgba(252, 165, 165, 0.8);
}

        /* Info card */
        .info-card { margin-top: 10px; }

        .info-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .detail-name { font-size: 1.1rem; font-weight: 600; }
        .detail-tag {
    color: #a855f7 !important;        /* ungu jelas */
    font-weight: 600;
    font-size: 0.8rem;
    background: rgba(255, 255, 255, 0.9);
    padding: 4px 14px;
    border-radius: 999px;
    opacity: 1 !important;           /* pastikan gak transparan */
}

        .info-layout {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 10px;
            margin-top: 8px;
        }

        .info-item {
            padding: 8px 9px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(203, 213, 225, 0.9);
            font-size: 0.82rem;
        }

        /* Khusus judul kategori */
.info-label {
    color: #df7b09ff !important;          /* ungu magical */
    font-weight: 700;
    letter-spacing: 1px;
    opacity: 1 !important;              /* hilangkan efek pudar */
    text-shadow: 
        0 0 6px rgba(237, 119, 8, 0.99),
        0 0 12px rgba(255, 171, 3, 0.86);
}

        .info-value {
            color: #8484fcff !important;          /* ungu magical */
    font-weight: 700;
    letter-spacing: 1px;
    opacity: 1 !important;              /* hilangkan efek pudar */
    text-shadow: 
        0 0 6px rgba(164, 132, 252, 0.7),
        0 0 12px rgba(85, 128, 247, 0.5);
        }

        /* Foto-foto adat */
        .photo-grid {
            margin-top: 14px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
        }

        .photo-card {
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(148, 163, 184, 0.85);
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(148, 163, 184, 0.4);
        }

        .photo-card img {
            width: 100%;
            height: 130px;
            object-fit: cover;
            display: block;
        }

        .photo-body {
            padding: 8px 10px 9px;
        }

        .photo-title {
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 2px;
            background: linear-gradient(90deg, #fb7185, #f97316, #38bdf8);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .photo-caption {
            font-size: 0.78rem;
            color: var(--muted);
        }

        .info-desc {
            margin-top: 12px;
            font-size: 0.84rem;
            color: var(--muted);
            line-height: 1.4;
        }

        footer {
            padding: 10px 18px;
            font-size: 0.78rem;
            color: #6b7280;
            text-align: center;
            border-top: 1px solid rgba(148, 163, 184, 0.5);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
        }

        /* Backsound button */
        .audio-toggle {
    position: fixed;
    bottom: 18px;
    left: 18px;
    z-index: 20;
    border-radius: 999px;
    border: 1px solid rgba(129, 140, 248, 0.9);
    background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.9), rgba(15, 23, 42, 0.96));
    color: #e5e7eb;
    font-size: 0.78rem;
    padding: 6px 12px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    box-shadow:
        0 0 18px rgba(59, 130, 246, 0.8),
        0 0 32px rgba(56, 189, 248, 0.8);
}
.audio-toggle span.icon { font-size: 1rem; }

        @media (max-width: 720px) {
            .title { font-size: 1.3rem; }
        }
    </style>
</head>
<body>
<div class="page-wrap">
    <header>
        <div class="brand">
            <div class="brand-logo"></div>
            <div class="brand-text">Peta Kebudayaan Indonesia</div>
        </div>
        <div class="user-mini">
            <span>Halo, <strong><?= htmlspecialchars($username); ?></strong></span>
            <a href="home.php" class="btn-back">
                <span class="icon">⟵</span>
                <span>Halaman Utama</span>
            </a>
        </div>
    </header>

    <main>
        <div class="title">Peta Interaktif Kebudayaan Indonesia</div>
        <div class="subtitle">
            Klik titik pada peta <strong>(per provinsi)</strong> atau pilih dari daftar di bawah.  
            Contoh: klik titik di Sumatera Selatan, info Sumsel langsung muncul beserta pinnya.
        </div>

        <!-- MAP -->
        <section class="card map-card">
            <div class="card-inner">
                <div class="map-wrapper" id="mapWrapper">
    <div class="map-inner" id="mapInner">
        <img src="peta-indonesia.png" alt="Peta Indonesia" width="1411" height="550" id="petaImg">
        <!-- Titik & pin akan disuntik lewat JS ke dalam mapInner -->
    </div>
</div>

                <div class="map-note">
                    *Titik-titik kecil di peta mewakili posisi provinsi. Kamu bisa geser (scroll) peta ke kanan/kiri di layar kecil.
                </div>
                <div id="coordDebug" style="margin-top:4px;font-size:11px;color:#9ca3af;">
    Mode kalibrasi: klik pada gambar peta (bukan titik) untuk melihat nilai posX/posY di sini.
</div>

                <div class="region-title">Daftar provinsi:</div>
                <div class="region-list" id="regionChips"></div>
            </div>
        </section>

        <!-- INFO -->
        <section class="card info-card">
            <div class="card-inner">
                <div class="info-header">
                    <div>
                        <div class="detail-name" id="detailNama">Pilih provinsi</div>
                        <div class="detail-tag" id="detailTag">Belum ada provinsi yang dipilih</div>
                    </div>
                </div>

                <div class="info-layout">
                    <div class="info-item">
                        <div class="info-label">Tarian Tradisional</div>
                        <div class="info-value" id="detailTari">-</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Rumah Adat</div>
                        <div class="info-value" id="detailRumah">-</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Makanan Khas</div>
                        <div class="info-value" id="detailMakanan">-</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Senjata Tradisional</div>
                        <div class="info-value" id="detailSenjata">-</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Bahasa Daerah</div>
                        <div class="info-value" id="detailBahasa">-</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Wilayah</div>
                        <div class="info-value" id="detailWilayah">-</div>
                    </div>
                </div>

                <!-- Foto adat -->
                <div class="photo-grid">
                    <div class="photo-card">
                        <img id="imgTari" src="img/default-tari.jpg"
                             alt="Tarian tradisional"
                             onerror="this.onerror=null;this.src='img/default-tari.jpg';">
                        <div class="photo-body">
                            <div class="photo-title">Tarian Tradisional</div>
                            <div class="photo-caption" id="capTari">Foto contoh tarian tradisional.</div>
                        </div>
                    </div>
                    <div class="photo-card">
                        <img id="imgRumah" src="img/default-rumah.jpg"
                             alt="Rumah adat"
                             onerror="this.onerror=null;this.src='img/default-rumah.jpg';">
                        <div class="photo-body">
                            <div class="photo-title">Rumah Adat</div>
                            <div class="photo-caption" id="capRumah">Foto contoh rumah adat.</div>
                        </div>
                    </div>
                    <div class="photo-card">
                        <img id="imgMakanan" src="img/default-makanan.jpg"
                             alt="Makanan khas"
                             onerror="this.onerror=null;this.src='img/default-makanan.jpg';">
                        <div class="photo-body">
                            <div class="photo-title">Makanan Khas</div>
                            <div class="photo-caption" id="capMakanan">Foto contoh makanan khas.</div>
                        </div>
                    </div>
                </div>

                <div class="info-desc" id="detailDeskripsi">
                    Silakan klik salah satu titik provinsi di peta atau gunakan tombol provinsi di atas.
                </div>
            </div>
        </section>
    </main>

    <footer>
        &copy; <?= date("Y"); ?> Kebudayaan Indonesia • Peta Kebudayaan
    </footer>
</div>

<!-- BACKSOUND -->
<audio id="bgm" loop>
    <source src="backsound.mp3" type="audio/mpeg">
</audio>

<button type="button" class="audio-toggle" id="audioToggle">
    <span class="icon">🔊</span>
    <span id="audioLabel">Backsound: ON</span>
</button>

<script>
    // ====== DATA 38 PROVINSI ======
    // posX & posY dalam % relatif ke gambar (perkiraan, cukup untuk interaktif)
    const provinces = [
        // SUMATRA
        { id:"aceh",    nama:"Aceh",              wilayah:"Sumatra",
          tari:"Tari Saman, Seudati", rumah:"Rumoh Aceh", makanan:"Mie Aceh", senjata:"Rencong",
          bahasa:"Aceh, Gayo", deskripsi:"Dikenal sebagai Serambi Mekkah dengan tradisi Islam yang kuat.",
          posX:4.6,  posY:6.5 },
        { id:"sumut",   nama:"Sumatera Utara",    wilayah:"Sumatra",
          tari:"Tari Tor-tor", rumah:"Rumah Bolon", makanan:"Bika Ambon", senjata:"Hujur",
          bahasa:"Batak Toba, Karo", deskripsi:"Budaya Batak yang kental dengan sistem marga dan gondang.",
          posX:9.7, posY:17.4 },
        { id:"sumbar",  nama:"Sumatera Barat",    wilayah:"Sumatra",
          tari:"Tari Piring", rumah:"Rumah Gadang", makanan:"Rendang", senjata:"Karih",
          bahasa:"Minangkabau", deskripsi:"Minangkabau dengan adat matrilineal dan kuliner kuat rempah.",
          posX:14, posY:38 },
        { id:"riau",    nama:"Riau",              wilayah:"Sumatra",
          tari:"Tari Zapin", rumah:"Rumah Melayu Selaso", makanan:"Gulai Ikan Patin", senjata:"Keris Melayu",
          bahasa:"Melayu", deskripsi:"Wilayah kerajaan Melayu lama dengan budaya Melayu pesisir.",
          posX:15, posY:30.7 },
        { id:"kepri",   nama:"Kepulauan Riau",    wilayah:"Sumatra",
          tari:"Tari Zapin Rentak", rumah:"Rumah Panggung Melayu", makanan:"Otak-otak, Gonggong",
          senjata:"Keris", bahasa:"Melayu", deskripsi:"Gugus pulau dengan budaya bahari Melayu.",
          posX:23.1, posY:35.9 },
        { id:"jambi",   nama:"Jambi",             wilayah:"Sumatra",
          tari:"Tari Sekapur Sirih", rumah:"Rumah Panggung Jambi", makanan:"Gulai Tepek Ikan",
          senjata:"Keris", bahasa:"Melayu Jambi", deskripsi:"Dikenal dengan tradisi sekapur sirih dan sungai Batanghari.",
          posX:18.2, posY:42.7 },
        { id:"bengkulu",nama:"Bengkulu",          wilayah:"Sumatra",
          tari:"Tari Andun", rumah:"Rumah Bubungan Lima", makanan:"Pendap", senjata:"Keris",
          bahasa:"Melayu Bengkulu, Rejang", deskripsi:"Provinsi pesisir barat Sumatra dengan peninggalan Inggris.",
          posX:17.1, posY:56.1 },
        { id:"sumsel",  nama:"Sumatera Selatan",  wilayah:"Sumatra",
          tari:"Gending Sriwijaya", rumah:"Rumah Limas", makanan:"Pempek", senjata:"Keris",
          bahasa:"Melayu Palembang", deskripsi:"Penerus kejayaan Sriwijaya, terkenal dengan kuliner berbahan ikan.",
          posX:22.1, posY:53.4 },
        { id:"babel",   nama:"Kep. Bangka Belitung", wilayah:"Sumatra",
          tari:"Tari Campak", rumah:"Rumah Panggung Melayu", makanan:"Lempah Kuning",
          senjata:"Keris", bahasa:"Melayu Bangka Belitung", deskripsi:"Gugus pulau penghasil timah dengan pantai indah.",
          posX:26.2, posY:46.8 },
        { id:"lampung", nama:"Lampung",           wilayah:"Sumatra",
          tari:"Tari Sigeh Penguten", rumah:"Nuwo Sesat", makanan:"Seruit", senjata:"Badik",
          bahasa:"Lampung", deskripsi:"Gerbang Sumatra dengan motif tapis dan adat pepadun/saibatin.",
          posX:23.6, posY:63.6 },

        // JAWA
        { id:"banten",  nama:"Banten",            wilayah:"Jawa",
          tari:"Tari Rampak Bedug", rumah:"Rumah Adat Baduy", makanan:"Sate Bandeng", senjata:"Golok",
          bahasa:"Sunda Banten", deskripsi:"Wilayah Kesultanan Banten dan komunitas Baduy.",
          posX:25.8, posY:73.0 },
        { id:"dki",     nama:"DKI Jakarta",       wilayah:"Jawa",
          tari:"Tari Topeng Betawi", rumah:"Rumah Kebaya", makanan:"Kerak Telor, Soto Betawi", senjata:"Golok",
          bahasa:"Betawi, Indonesia", deskripsi:"Ibu kota negara, percampuran budaya Nusantara.",
          posX:28.7, posY:70.3 },
        { id:"jabar",   nama:"Jawa Barat",        wilayah:"Jawa",
          tari:"Tari Jaipong", rumah:"Rumah Kasepuhan Sunda", makanan:"Nasi Timbel, Surabi", senjata:"Kujang",
          bahasa:"Sunda", deskripsi:"Tanah Sunda dengan musik degung dan alam pegunungan.",
          posX:29.6, posY:74.7 },
        { id:"jateng",  nama:"Jawa Tengah",       wilayah:"Jawa",
          tari:"Gambyong, Bedhaya", rumah:"Joglo", makanan:"Lumpia, Nasi Liwet", senjata:"Keris",
          bahasa:"Jawa", deskripsi:"Pusat budaya Jawa, keraton dan Candi Borobudur.",
          posX:35.5, posY:76.8 },
        { id:"diy",     nama:"DI Yogyakarta",     wilayah:"Jawa",
          tari:"Tari Bedhaya Ketawang", rumah:"Joglo Yogyakarta", makanan:"Gudeg", senjata:"Keris",
          bahasa:"Jawa", deskripsi:"Daerah istimewa dengan kerajaan Ngayogyakarta Hadiningrat.",
          posX:36.2, posY:81.2 },
        { id:"jatim",   nama:"Jawa Timur",        wilayah:"Jawa",
          tari:"Reog Ponorogo, Gandrung", rumah:"Joglo Jawa Timuran", makanan:"Rawon, Rujak Cingur",
          senjata:"Clurit", bahasa:"Jawa, Madura", deskripsi:"Kaya kesenian Reog dan budaya pesisir Madura.",
          posX:40.7, posY:79.6 },

        // BALI & NUSA TENGGARA
        { id:"bali",    nama:"Bali",              wilayah:"Bali & Nusa Tenggara",
          tari:"Kecak, Pendet, Legong", rumah:"Bale Bali", makanan:"Ayam Betutu, Lawar", senjata:"Keris Bali",
          bahasa:"Bali", deskripsi:"Pulau Dewata dengan tradisi Hindu dan seni yang hidup.",
          posX:47.6, posY:83.9 },
        { id:"ntb",     nama:"Nusa Tenggara Barat", wilayah:"Bali & Nusa Tenggara",
          tari:"Tari Gandrung Sasak", rumah:"Bale Tani, Bale Lumbung", makanan:"Ayam Taliwang", senjata:"Golok",
          bahasa:"Sasak", deskripsi:"Didominasi suku Sasak dengan budaya Islam lokal.",
          posX:52.9, posY:85.4 },
        { id:"ntt",     nama:"Nusa Tenggara Timur", wilayah:"Bali & Nusa Tenggara",
          tari:"Tari Likurai", rumah:"Rumah Adat Sumba, Lopo", makanan:"Se'i, Jagung Bose", senjata:"Parang",
          bahasa:"Beragam bahasa Sumba, Flores, Timor", deskripsi:"Wilayah tenun ikat dan ritual adat yang kuat.",
          posX:60, posY:85.2 },

        // KALIMANTAN
        { id:"kalbar",  nama:"Kalimantan Barat",  wilayah:"Kalimantan",
          tari:"Tari Monong", rumah:"Rumah Panjang Dayak", makanan:"Pengkang", senjata:"Mandau",
          bahasa:"Dayak, Melayu", deskripsi:"Dikenal dengan Sungai Kapuas dan budaya Dayak.",
          posX:37.5, posY:34.1 },
        { id:"kalteng", nama:"Kalimantan Tengah", wilayah:"Kalimantan",
          tari:"Tari Tambun & Bungai", rumah:"Rumah Betang", makanan:"Juhu Singkah", senjata:"Mandau",
          bahasa:"Dayak Ngaju", deskripsi:"Wilayah hutan dan rumah panjang Dayak Ngaju.",
          posX:42.6, posY:43.6 },
        { id:"kalsel",  nama:"Kalimantan Selatan", wilayah:"Kalimantan",
          tari:"Tari Baksa Kembang", rumah:"Rumah Bubungan Tinggi", makanan:"Soto Banjar", senjata:"Mandau",
          bahasa:"Banjar", deskripsi:"Budaya sungai dan pasar terapung Banjarmasin.",
          posX:48.2, posY:51.6 },
        { id:"kaltim",  nama:"Kalimantan Timur",  wilayah:"Kalimantan",
          tari:"Tari Gong", rumah:"Rumah Lamin", makanan:"Nasi Bekepor", senjata:"Mandau",
          bahasa:"Dayak, Kutai", deskripsi:"Lokasi Ibu Kota Nusantara (IKN) dan budaya Dayak-Kutai.",
          posX:51, posY:32.5 },
        { id:"kaltara", nama:"Kalimantan Utara",  wilayah:"Kalimantan",
          tari:"Tari Magunatip", rumah:"Rumah Adat Suku Dayak", makanan:"Ikan Asin Richa", senjata:"Mandau",
          bahasa:"Dayak, Tidung", deskripsi:"Provinsi termuda di Kalimantan, perbatasan dengan Sabah.",
          posX:50.6, posY:15.2 },

        // SULAWESI
        { id:"sulut",   nama:"Sulawesi Utara",    wilayah:"Sulawesi",
          tari:"Tari Maengket", rumah:"Rumah Walewangko", makanan:"Tinutuan", senjata:"Tombak",
          bahasa:"Manado, Minahasa", deskripsi:"Wilayah ujung utara dengan budaya Minahasa.",
          posX:69.3, posY:30.1 },
        { id:"gorontalo",nama:"Gorontalo",        wilayah:"Sulawesi",
          tari:"Tari Dana-dana", rumah:"Rumah Adat Dulohupa", makanan:"Binte Biluhuta", senjata:"Badik",
          bahasa:"Gorontalo", deskripsi:"Budaya pesisir dengan tradisi Islam kuat.",
          posX:64.2, posY:28.8 },
        { id:"sulteng", nama:"Sulawesi Tengah",   wilayah:"Sulawesi",
          tari:"Tari Dero", rumah:"Rumah Tambi", makanan:"Kaledo", senjata:"Tombak",
          bahasa:"Beragam bahasa Kaili dkk.", deskripsi:"Provinsi luas dengan danau Poso dan Lindu.",
          posX:60.3, posY:41.7 },
        { id:"sulbar",  nama:"Sulawesi Barat",    wilayah:"Sulawesi",
          tari:"Tari Pattuddu", rumah:"Rumah Adat Mandar", makanan:"Jepa", senjata:"Badik",
          bahasa:"Mandar", deskripsi:"Wilayah pemekaran dari Sulawesi Selatan, budaya Mandar.",
          posX:57.1, posY:47.5 },
        { id:"sulsel",  nama:"Sulawesi Selatan",  wilayah:"Sulawesi",
          tari:"Tari Pakarena", rumah:"Rumah Panggung Bugis", makanan:"Coto Makassar", senjata:"Badik",
          bahasa:"Bugis, Makassar", deskripsi:"Dikenal dengan pelaut Bugis dan kuliner coto.",
          posX:58.8, posY:59 },
        { id:"sultra",  nama:"Sulawesi Tenggara", wilayah:"Sulawesi",
          tari:"Tari Lulo", rumah:"Rumah Adat Buton", makanan:"Sinonggi", senjata:"Badik",
          bahasa:"Tolaki, Muna, Wolio", deskripsi:"Wilayah kepulauan dengan benteng Keraton Buton.",
          posX:63.5, posY:55.7 },

        // MALUKU
        { id:"maluku",  nama:"Maluku",            wilayah:"Maluku",
          tari:"Tari Cakalele", rumah:"Baileo", makanan:"Papeda Maluku, Ikan Bakar", senjata:"Parang Salawaku",
          bahasa:"Beragam bahasa Maluku", deskripsi:"Gugus Kepulauan Rempah dengan budaya pela gandong.",
          posX:80.9, posY:51.7 },
        { id:"malut",   nama:"Maluku Utara",      wilayah:"Maluku",
          tari:"Tari Soya-soya", rumah:"Baileo Ternate-Tidore", makanan:"Gohu Ikan", senjata:"Parang",
          bahasa:"Ternate, Tidore", deskripsi:"Bekas pusat Kesultanan Ternate dan Tidore.",
          posX:77.9, posY:29.2 },

        // PAPUA (6 provinsi)
        { id:"papua",   nama:"Papua",             wilayah:"Papua",
          tari:"Tari Perang, Yospan", rumah:"Honai", makanan:"Papeda", senjata:"Busur & panah",
          bahasa:"Beragam bahasa Papua", deskripsi:"Papua pesisir utara dengan teluk Jayapura.",
          posX:99.6, posY:44.4 },
        { id:"pabar",   nama:"Papua Barat",       wilayah:"Papua",
          tari:"Tari Selamat Datang", rumah:"Rumah Kaki Seribu", makanan:"Ikan bakar, papeda",
          senjata:"Busur & panah", bahasa:"Beragam bahasa Papua Barat", deskripsi:"Mencakup Raja Ampat dan sekitarnya.",
          posX:89.8, posY:51.2 },
        { id:"pabd",    nama:"Papua Barat Daya",  wilayah:"Papua",
          tari:"Tari tradisional Sorong", rumah:"Rumah adat Sorong", makanan:"Seafood Papua", senjata:"Busur",
          bahasa:"Papua setempat", deskripsi:"Provinsi baru dengan pusat di Sorong.",
          posX:87, posY:40.6 },
        { id:"papt",    nama:"Papua Tengah",      wilayah:"Papua",
          tari:"Tari suku Mee dan Moni", rumah:"Honai dataran tinggi", makanan:"Ubi bakar, papeda",
          senjata:"Busur", bahasa:"Beragam bahasa pegunungan tengah", deskripsi:"Wilayah pegunungan tengah Papua.",
          posX:98.3, posY:48.8 },
        { id:"papg",    nama:"Papua Pegunungan",  wilayah:"Papua",
          tari:"Tari suku Dani", rumah:"Honai Lembah Baliem", makanan:"Ubi, sayur bakar batu", senjata:"Busur",
          bahasa:"Dani dan bahasa pegunungan lain", deskripsi:"Daerah pegunungan tinggi dengan budaya batu panas.",
          posX:99.3, posY:54.2 },
        { id:"paps",    nama:"Papua Selatan",     wilayah:"Papua",
          tari:"Tari Asmat", rumah:"Rumah adat Asmat", makanan:"Sagu bakar", senjata:"Tombak, busur",
          bahasa:"Bahasa Asmat dan sekitarnya", deskripsi:"Terkenal dengan ukiran kayu Asmat.",
          posX:98.9, posY:61.7 }
    ];

    // ====== DOM element ======
    const detailNama      = document.getElementById("detailNama");
    const detailTag       = document.getElementById("detailTag");
    const detailTari      = document.getElementById("detailTari");
    const detailRumah     = document.getElementById("detailRumah");
    const detailMakanan   = document.getElementById("detailMakanan");
    const detailSenjata   = document.getElementById("detailSenjata");
    const detailBahasa    = document.getElementById("detailBahasa");
    const detailWilayah   = document.getElementById("detailWilayah");
    const detailDeskripsi = document.getElementById("detailDeskripsi");

    const regionChipsContainer = document.getElementById("regionChips");
    const mapWrapper = document.getElementById("mapWrapper");
    const mapInner = document.getElementById("mapInner");

    const imgTariEl    = document.getElementById("imgTari");
    const imgRumahEl   = document.getElementById("imgRumah");
    const imgMakananEl = document.getElementById("imgMakanan");
    const capTariEl    = document.getElementById("capTari");
    const capRumahEl   = document.getElementById("capRumah");
    const capMakananEl = document.getElementById("capMakanan");

    let pinEl = null;
    // ===== SOUND EFFECT KLIK =====
const clickSfx = new Audio("click.mp3"); // ganti nama file kalau beda
clickSfx.volume = 0.7;                   // atur volume 0.0 - 1.0

function playClickSfx() {
    // reset ke awal supaya bisa diputar cepat berkali-kali
    clickSfx.currentTime = 0.5;
    clickSfx.play().catch(() => {
        // kalau ada error (misal browser blokir), biarin aja, jangan ganggu
    });
}

    function placePinForProvince(prov) {
        if (!pinEl) {
            pinEl = document.createElement("div");
            pinEl.className = "map-pin";
            mapInner.appendChild(pinEl);
        }
        pinEl.style.left = prov.posX + "%";
        pinEl.style.top  = prov.posY + "%";
    }

    function selectProvince(id) {
    const prov = provinces.find(p => p.id === id);
    if (!prov) return;

    // mainkan sound effect setiap memilih provinsi
    playClickSfx();

    // aktifkan chip
    document.querySelectorAll(".region-chip").forEach(chip => {
        chip.classList.toggle("active", chip.dataset.id === id);
    });

        detailNama.textContent      = prov.nama;
        detailTag.textContent       = "Provinsi di " + prov.wilayah;
        detailTari.textContent      = prov.tari;
        detailRumah.textContent     = prov.rumah;
        detailMakanan.textContent   = prov.makanan;
        detailSenjata.textContent   = prov.senjata;
        detailBahasa.textContent    = prov.bahasa;
        detailWilayah.textContent   = prov.wilayah;
        detailDeskripsi.textContent = prov.deskripsi;

        // set foto (nama file: id-tari.jpg, id-rumah.jpg, id-makanan.jpg)
        imgTariEl.src    = "img/" + prov.id + "-tari.jpg";
        imgRumahEl.src   = "img/" + prov.id + "-rumah.jpg";
        imgMakananEl.src = "img/" + prov.id + "-makanan.jpg";

        capTariEl.textContent    = prov.nama + " • " + prov.tari;
        capRumahEl.textContent   = prov.nama + " • " + prov.rumah;
        capMakananEl.textContent = prov.nama + " • " + prov.makanan;

        placePinForProvince(prov);
    }

    function renderProvinceChipsAndDots() {
        provinces.forEach(prov => {
            // chip
            const chip = document.createElement("button");
            chip.type = "button";
            chip.className = "region-chip";
            chip.dataset.id = prov.id;
            chip.textContent = prov.nama;
            chip.addEventListener("click", () => selectProvince(prov.id));
            regionChipsContainer.appendChild(chip);

            // titik di peta
            const dot = document.createElement("button");
            dot.type = "button";
            dot.className = "province-dot";
            dot.style.left = prov.posX + "%";
            dot.style.top  = prov.posY + "%";
            dot.title = prov.nama;
            dot.addEventListener("click", () => selectProvince(prov.id));
            mapInner.appendChild(dot);
        });
    }

    // Fungsi bantu: saat user klik gambar peta, tampilkan koordinat posX / posY (dalam %)
if (petaImg && coordDebug) {
    petaImg.addEventListener("click", function (e) {
        const rect = petaImg.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const posX = (x / rect.width) * 100;
        const posY = (y / rect.height) * 100;

        const text = `Koordinat klik → posX: ${posX.toFixed(1)}% • posY: ${posY.toFixed(1)}%`;
        coordDebug.textContent = text;
        console.log(text);
    });
}

    document.addEventListener("DOMContentLoaded", () => {
        renderProvinceChipsAndDots();
        // default: Sumatera Selatan biar langsung kelihatan
        selectProvince("sumsel");
    });

    // ===== BACKSOUND =====
    const audio = document.getElementById("bgm");
    const toggleBtn = document.getElementById("audioToggle");
    const audioLabel = document.getElementById("audioLabel");
    const iconSpan = toggleBtn.querySelector(".icon");

    audio.volume = 0.6;
    let audioInitialized = false;

    function initAudioOnce() {
        if (!audioInitialized) {
            audio.play().catch(() => {});
            audioInitialized = true;
        }
    }
    document.addEventListener("click", initAudioOnce, { once: true });

    toggleBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        if (audio.muted || audio.paused) {
            audio.muted = false;
            audio.play().catch(() => {});
            audioLabel.textContent = "Backsound: ON";
            iconSpan.textContent = "🔊";
        } else {
            audio.muted = true;
            audioLabel.textContent = "Backsound: OFF";
            iconSpan.textContent = "🔇";
        }
    });
</script>
</body>
</html>
