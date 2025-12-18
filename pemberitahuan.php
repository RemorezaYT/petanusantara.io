<?php
session_start();

// Cek login
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION["username"] ?? "Pengguna";

// Inisialisasi list info
if (!isset($_SESSION["info_list"])) {
    $_SESSION["info_list"] = [];
}

// Proses form tambah info
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["aksi"]) && $_POST["aksi"] === "tambah_info") {
    $judul = trim($_POST["judul"] ?? "");
    $isi   = trim($_POST["isi"] ?? "");

    if ($judul !== "" || $isi !== "") {
        $_SESSION["info_list"][] = [
            "judul" => $judul,
            "isi"   => $isi,
            "waktu" => date("d-m-Y H:i"),
        ];
    }

    header("Location: pemberitahuan.php");
    exit;
}

$info_list = array_reverse($_SESSION["info_list"]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemberitahuan & Literasi - Kebudayaan Indonesia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --bg: #020617;
            --text: #e5e7eb;
            --muted: #9ca3af;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, #111827, transparent 55%),
                radial-gradient(circle at bottom right, #020617, #020617 75%);
            color: var(--text);
        }

        .page-wrap {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(90deg, #020617, #0f172a, #2563eb);
            border-bottom: 1px solid rgba(37, 99, 235, 0.7);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-logo {
            width: 30px;
            height: 30px;
            border-radius: 999px;
            background: radial-gradient(circle at 30% 30%, #22c55e, #0f766e);
            box-shadow: 0 0 16px rgba(52, 211, 153, 0.7);
        }

        .brand-text {
            font-weight: 600;
            letter-spacing: .04em;
            font-size: 0.96rem;
        }

        .user-mini {
            font-size: 0.84rem;
            text-align: right;
        }

        .user-mini span {
            display: block;
        }

        .btn-back {
            margin-top: 4px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.7);
            background: rgba(15, 23, 42, 0.9);
            color: var(--text);
            font-size: 0.78rem;
            text-decoration: none;
        }

        main {
            flex: 1;
            padding: 18px 16px 22px;
            max-width: 900px;
            margin: 0 auto;
        }

        .title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .subtitle {
            font-size: 0.9rem;
            color: var(--muted);
            margin-bottom: 16px;
        }

        .card {
            background: radial-gradient(circle at top left, rgba(15, 23, 42, 0.96), rgba(15, 23, 42, 0.99));
            border-radius: 18px;
            border: 1px solid rgba(148, 163, 184, 0.5);
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.85);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: "";
            position: absolute;
            inset: -30%;
            background:
                radial-gradient(circle at top, rgba(56, 189, 248, 0.18), transparent 55%),
                radial-gradient(circle at bottom, rgba(129, 140, 248, 0.18), transparent 55%);
            opacity: 0.9;
            pointer-events: none;
        }

        .card-inner {
            position: relative;
            z-index: 1;
            padding: 16px 16px 18px;
        }

        .info-form {
            margin-bottom: 16px;
            padding: 12px;
            border-radius: 14px;
            background: rgba(15, 23, 42, 0.97);
            border: 1px dashed rgba(148, 163, 184, 0.7);
        }

        .info-form-row {
            display: grid;
            grid-template-columns: 1.1fr 2fr;
            gap: 10px;
            margin-bottom: 8px;
        }

        .info-form label {
            font-size: 0.8rem;
            display: block;
            margin-bottom: 4px;
            color: #e5e7eb;
        }

        .info-input,
        .info-textarea {
            width: 100%;
            border-radius: 10px;
            border: 1px solid rgba(148, 163, 184, 0.7);
            background: rgba(15, 23, 42, 0.9);
            color: #e5e7eb;
            font-size: 0.85rem;
            padding: 7px 9px;
            outline: none;
        }

        .info-input:focus,
        .info-textarea:focus {
            border-color: rgba(129, 140, 248, 0.9);
            box-shadow: 0 0 0 1px rgba(129, 140, 248, 0.7);
        }

        .info-textarea {
            resize: vertical;
            min-height: 60px;
        }

        .info-submit-btn {
            margin-top: 6px;
            border-radius: 999px;
            border: none;
            padding: 7px 14px;
            font-size: 0.82rem;
            cursor: pointer;
            background: linear-gradient(135deg, #38bdf8, #6366f1);
            color: #e5e7eb;
            font-weight: 600;
        }

        .info-list {
            display: grid;
            gap: 10px;
            max-height: 260px;
            overflow-y: auto;
        }

        .info-item {
            padding: 10px 11px;
            border-radius: 10px;
            background: rgba(15, 23, 42, 0.98);
            border: 1px solid rgba(148, 163, 184, 0.6);
            font-size: 0.83rem;
        }

        .info-item-title {
            font-weight: 600;
            margin-bottom: 3px;
        }

        .info-item-meta {
            font-size: 0.72rem;
            color: var(--muted);
            margin-bottom: 4px;
        }

        footer {
            padding: 10px 18px;
            font-size: 0.78rem;
            color: #9ca3af;
            text-align: center;
            border-top: 1px solid rgba(31, 41, 55, 0.9);
            background: #020617;
        }

        @media (max-width: 768px) {
            .info-form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="page-wrap">
    <header>
        <div class="brand">
            <div class="brand-logo"></div>
            <div class="brand-text">Pemberitahuan & Literasi</div>
        </div>
        <div class="user-mini">
            <span>Halo, <strong><?php echo htmlspecialchars($username); ?></strong></span>
            <a href="home.php" class="btn-back">&laquo; Kembali ke Halaman Awal</a>
        </div>
    </header>

    <main>
        <div class="title">Pemberitahuan & Informasi Kebudayaan</div>
        <div class="subtitle">
            Tambahkan catatan, fakta unik, atau informasi penting tentang kebudayaan Indonesia.
        </div>

        <div class="card">
            <div class="card-inner">
                <form class="info-form" method="post" action="">
                    <input type="hidden" name="aksi" value="tambah_info">
                    <div class="info-form-row">
                        <div>
                            <label for="judul">Judul Informasi</label>
                            <input
                                type="text"
                                id="judul"
                                name="judul"
                                class="info-input"
                                placeholder="Misal: Makna filosofi motif batik parang"
                            >
                        </div>
                        <div>
                            <label for="isi">Isi Singkat</label>
                            <textarea
                                id="isi"
                                name="isi"
                                class="info-textarea"
                                placeholder="Tuliskan informasi, tradisi, cerita rakyat, atau penjelasan singkat..."
                            ></textarea>
                        </div>
                    </div>
                    <button type="submit" class="info-submit-btn">Tambah Informasi</button>
                </form>

                <div class="info-list">
                    <?php if (empty($info_list)): ?>
                        <div class="info-item">
                            <div class="info-item-title">Belum ada informasi.</div>
                            <div class="info-item-meta">Tambahkan informasi pertama kamu menggunakan form di atas.</div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($info_list as $info): ?>
                            <div class="info-item">
                                <?php if ($info["judul"] !== ""): ?>
                                    <div class="info-item-title">
                                        <?php echo htmlspecialchars($info["judul"]); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="info-item-meta">
                                    Ditambahkan pada: <?php echo htmlspecialchars($info["waktu"]); ?>
                                </div>
                                <div>
                                    <?php echo nl2br(htmlspecialchars($info["isi"])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> Kebudayaan Indonesia • Pemberitahuan & Literasi
    </footer>
</div>
</body>
</html>
