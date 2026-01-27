<?php
session_start();

// Cek login
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION["username"] ?? "Pengguna";

// Poin sementara, biasanya ini bisa disimpan di database atau session
$user_points = $_SESSION["user_points"] ?? 0; // Ambil poin dari sesi (atau database)
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kuis Sejarah Budaya Indonesia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --bg: #050814;
            --panel: rgba(17, 24, 39, 0.55);
            --border: rgba(255,255,255,0.12);
            --text: rgba(255,255,255,0.92);
            --muted: rgba(255,255,255,0.68);

            --brand1:#f97316; /* orange */
            --brand2:#facc15; /* yellow */
            --brand3:#6366f1; /* indigo */
        }

        *{ box-sizing:border-box; margin:0; padding:0; }
        body{
            font-family:"Plus Jakarta Sans", system-ui, -apple-system, "Segoe UI", sans-serif;
            min-height:100vh;
            background: var(--bg);
            color: var(--text);
            overflow-x:hidden;
        }

        /* Background glow + grid */
        .bg{
            position:fixed; inset:0; z-index:-2;
            background:
                radial-gradient(circle at 15% 20%, rgba(250,204,21,0.22), transparent 45%),
                radial-gradient(circle at 80% 25%, rgba(99,102,241,0.18), transparent 45%),
                radial-gradient(circle at 35% 90%, rgba(249,115,22,0.18), transparent 45%),
                linear-gradient(180deg, rgba(2,6,23,0.65), rgba(2,6,23,0.95));
        }
        .grid{
            position:fixed; inset:0; z-index:-1;
            background-image:
                linear-gradient(rgba(255,255,255,0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 44px 44px;
            mask-image: radial-gradient(circle at 40% 15%, rgba(0,0,0,0.8), transparent 60%);
            opacity:.35;
            pointer-events:none;
        }

        .page-wrap{
            min-height:100vh;
            display:flex;
            flex-direction:column;
        }

        /* Header */
        header{
            position:sticky;
            top:0;
            z-index:10;
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            background: rgba(2,6,23,0.55);
            backdrop-filter: blur(16px);
        }

        .header-inner{
            max-width: 1040px;
            margin:0 auto;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:16px;
        }

        .brand{
            display:flex;
            align-items:center;
            gap:12px;
        }

        .brand-logo{
            width:42px;
            height:42px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--brand1), var(--brand2));
            box-shadow: 0 18px 50px rgba(249,115,22,0.25);
            display:grid;
            place-items:center;
            font-weight:900;
            color:#111827;
        }

        .brand-text b{
            display:block;
            letter-spacing:-0.01em;
        }
        .brand-text small{
            display:block;
            color: var(--muted);
            margin-top:2px;
            font-weight:700;
        }

        .user-mini{
            text-align:right;
            font-size: .92rem;
            color: rgba(255,255,255,0.82);
        }
        .user-mini strong{
            color: rgba(250,204,21,0.95);
        }

        /* Main */
        .main{
            flex:1;
            width: min(1040px, 100%);
            margin: 0 auto;
            padding: 26px 18px 28px;
        }

        .hero{
            border: 1px solid var(--border);
            background: rgba(17, 24, 39, 0.35);
            backdrop-filter: blur(14px);
            border-radius: 24px;
            padding: 22px 22px;
            box-shadow: 0 28px 90px rgba(0,0,0,0.35);
            overflow:hidden;
            position:relative;
        }
        .hero::before{
            content:"";
            position:absolute;
            inset:-30%;
            background:
                radial-gradient(circle at 20% 20%, rgba(250,204,21,0.16), transparent 55%),
                radial-gradient(circle at 85% 65%, rgba(99,102,241,0.14), transparent 55%),
                radial-gradient(circle at 40% 95%, rgba(249,115,22,0.12), transparent 55%);
            z-index:0;
        }
        .hero-inner{ position:relative; z-index:1; }

        .badge{
            display:inline-flex;
            align-items:center;
            gap:10px;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.14);
            background: rgba(2,6,23,0.30);
            color: rgba(255,255,255,0.80);
            font-size: .85rem;
            font-weight:800;
            letter-spacing:.06em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .badge .dot{
            width:10px;height:10px;border-radius:999px;
            background: linear-gradient(135deg, var(--brand1), var(--brand2));
            box-shadow: 0 0 0 6px rgba(250,204,21,0.10);
        }

        .headline-title{
            font-size: clamp(1.6rem, 3.5vw, 2.3rem);
            font-weight: 900;
            letter-spacing:-0.02em;
            margin-bottom: 6px;
        }
        .headline-subtitle{
            color: var(--muted);
            font-size: 1rem;
            max-width: 72ch;
        }

        .grid-menu{
            margin-top: 18px;
            display:grid;
            grid-template-columns: repeat(2, minmax(0,1fr));
            gap: 18px;
        }

        /* Card */
        .menu-card{
            position:relative;
            overflow:hidden;
            border-radius: 24px;
            border: 1px solid rgba(255,255,255,0.14);
            background: rgba(17,24,39,0.45);
            backdrop-filter: blur(16px);
            box-shadow: 0 26px 80px rgba(0,0,0,0.35);
            transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
            min-height: 210px;
        }
        .menu-card:hover{
            transform: translateY(-2px);
            border-color: rgba(250,204,21,0.22);
            box-shadow: 0 34px 110px rgba(0,0,0,0.45);
        }

        .menu-card::before{
            content:"";
            position:absolute;
            inset:-30%;
            opacity:.95;
        }

        .menu-card.easy::before{
            background:
                radial-gradient(circle at 20% 15%, rgba(250,204,21,0.28), transparent 55%),
                radial-gradient(circle at 85% 65%, rgba(249,115,22,0.22), transparent 60%);
        }
        .menu-card.normal::before{
            background:
                radial-gradient(circle at 20% 15%, rgba(34,197,94,0.25), transparent 55%),
                radial-gradient(circle at 85% 65%, rgba(56,189,248,0.22), transparent 60%);
        }
        .menu-card.hard::before{
            background:
                radial-gradient(circle at 20% 15%, rgba(34,197,94,0.25), transparent 55%),
                radial-gradient(circle at 85% 65%, rgba(56,189,248,0.22), transparent 60%);
        }

        .menu-card-inner{
            position:relative;
            z-index:1;
            padding: 18px 18px 16px;
            display:flex;
            flex-direction:column;
            height:100%;
        }

        .menu-top{
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:12px;
        }

        .menu-badge{
            display:inline-flex;
            gap:8px;
            align-items:center;
            padding: 7px 10px;
            border-radius: 999px;
            background: rgba(255,255,255,0.14);
            border: 1px solid rgba(255,255,255,0.18);
            font-size: .78rem;
            color: rgba(255,255,255,0.85);
            font-weight: 800;
        }
        .menu-badge .mini-dot{
            width:8px; height:8px; border-radius:999px;
            background: rgba(255,255,255,0.85);
            opacity:.8;
        }

        .menu-icon{
            width:44px; height:44px;
            border-radius: 16px;
            display:grid;
            place-items:center;
            background: rgba(2,6,23,0.35);
            border: 1px solid rgba(255,255,255,0.14);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.10);
            font-size: 1.25rem;
        }

        .menu-title{
            margin-top: 12px;
            font-size: 1.16rem;
            font-weight: 900;
            letter-spacing:-0.01em;
            color: rgba(255,255,255,0.92);
        }
        .menu-desc{
            margin-top: 8px;
            color: rgba(255,255,255,0.78);
            font-size: .92rem;
            line-height: 1.45;
            max-width: 60ch;
        }

        .menu-footer{
            margin-top:auto;
            padding-top: 14px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            color: rgba(255,255,255,0.70);
            font-size: .88rem;
        }

        .menu-btn{
            display:inline-flex;
            align-items:center;
            gap:10px;
            padding: 10px 14px;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.18);
            background: rgba(2,6,23,0.45);
            backdrop-filter: blur(10px);
            color: rgba(255,255,255,0.90);
            font-weight: 900;
            text-decoration:none;
            letter-spacing: .10em;
            text-transform: uppercase;
            transition: transform .12s ease, background .15s ease, border-color .15s ease;
            white-space: nowrap;
        }
        .menu-btn:hover{
            transform: translateY(-1px);
            background: rgba(2,6,23,0.60);
            border-color: rgba(250,204,21,0.24);
        }
        .menu-btn:active{ transform: translateY(1px); }

        footer{
            padding: 12px 18px;
            text-align:center;
            font-size: .82rem;
            color: rgba(255,255,255,0.55);
            border-top: 1px solid rgba(255,255,255,0.10);
            background: rgba(2,6,23,0.65);
            backdrop-filter: blur(16px);
        }

        @media (max-width: 860px){
            .grid-menu{ grid-template-columns: 1fr; }
            .user-mini{ display:none; }
        }
    </style>
</head>

<body>
    <div class="bg"></div>
    <div class="grid"></div>

    <div class="page-wrap">
        <header>
            <div class="header-inner">
                <div class="brand">
                    <div class="brand-logo">KI</div>
                    <div class="brand-text">
                        <b>Kebudayaan Indonesia</b>
                        <small>Tugas Proyek Pemograman Lanjutan</small>
                    </div>
                </div>

                <div class="user-mini">
                    Selamat datang, <strong><?php echo htmlspecialchars($username); ?></strong>
                </div>
            </div>
        </header>

        <main class="main">
            <section class="hero">
                <div class="hero-inner">
                    <div class="badge"><span class="dot"></span> Kuis Sejarah Budaya Indonesia</div>
                    <div class="headline-title">Pilih Mode Kuis</div>
                    <div class="headline-subtitle">
                        Pilih tingkat kesulitan yang ingin kamu coba: mudah, normal, sulit, atau lihat ranking!
                    </div>
                </div>
            </section>

            <section class="grid-menu">
                <!-- EASY -->
                <article class="menu-card easy">
                    <div class="menu-card-inner">
                        <div class="menu-top">
                            <div class="menu-badge"><span class="mini-dot"></span> Mudah • Sejarah</div>
                            <div class="menu-icon">🔰</div>
                        </div>
                        <div class="menu-title">Mode Mudah</div>
                        <div class="menu-desc">
                            Mulailah dengan soal-soal sejarah budaya Indonesia yang mudah dan menyenangkan.
                        </div>
                        <div class="menu-footer">
                            <span>Mode kesulitan rendah</span>
                            <a href="kuis_mudah.php" class="menu-btn">Mulai Kuis →</a>
                        </div>
                    </div>
                </article>

                <!-- NORMAL -->
                <article class="menu-card normal">
                    <div class="menu-card-inner">
                        <div class="menu-top">
                            <div class="menu-badge"><span class="mini-dot"></span> Normal • Sejarah</div>
                            <div class="menu-icon">🔹</div>
                        </div>
                        <div class="menu-title">Mode Normal</div>
                        <div class="menu-desc">
                            Tantang dirimu dengan soal-soal sejarah budaya Indonesia yang menantang.
                        </div>
                        <div class="menu-footer">
                            <span>Mode kesulitan sedang</span>
                            <a href="kuis_normal.php" class="menu-btn">Mulai Kuis →</a>
                        </div>
                    </div>
                </article>

                <!-- HARD -->
                <article class="menu-card hard">
                    <div class="menu-card-inner">
                        <div class="menu-top">
                            <div class="menu-badge"><span class="mini-dot"></span> Sulit • Sejarah</div>
                            <div class="menu-icon">🔶</div>
                        </div>
                        <div class="menu-title">Mode Sulit</div>
                        <div class="menu-desc">
                            Uji pengetahuanmu dengan soal-soal sejarah budaya Indonesia yang lebih sulit.
                        </div>
                        <div class="menu-footer">
                            <span>Mode kesulitan tinggi</span>
                            <a href="kuis_sulit.php" class="menu-btn">Mulai Kuis →</a>
                        </div>
                    </div>
                </article>
            </section>
        </main>

        <footer>
            &copy; <?php echo date("Y"); ?> Kebudayaan Indonesia • Halaman utama
        </footer>
    </div>
</body>
</html>
