<?php
session_start();

// proses login sederhana
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    if ($username === "reza" && $password === "reza") {
        $_SESSION["logged_in"] = true;
        $_SESSION["username"]  = $username;
        header("Location: home.php"); // halaman setelah login
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Kebudayaan Indonesia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --bg: #050814;
            --card: rgba(17, 24, 39, 0.55);
            --border: rgba(255,255,255,0.14);
            --text: rgba(255,255,255,0.92);
            --muted: rgba(255,255,255,0.68);
            --brand1: #f97316; /* orange */
            --brand2: #facc15; /* yellow */
            --brand3: #6366f1; /* indigo */
            --danger: #fb7185;
            --ok: #22c55e;
        }

        *{ box-sizing:border-box; margin:0; padding:0; }
        body{
            font-family: "Plus Jakarta Sans", system-ui, -apple-system, "Segoe UI", sans-serif;
            min-height:100vh;
            background: var(--bg);
            color: var(--text);
            overflow:hidden;
        }

        /* ===== VIDEO BG ===== */
        .video-bg{
            position:fixed; inset:0;
            width:100vw; height:100vh;
            object-fit:cover;
            z-index:-3;
            filter: saturate(1.1) contrast(1.05);
            transform: scale(1.02);
        }

        /* Overlay biar lebih “film look” */
        .overlay{
            position:fixed; inset:0;
            background:
                radial-gradient(circle at 20% 15%, rgba(250,204,21,0.35), transparent 40%),
                radial-gradient(circle at 80% 20%, rgba(99,102,241,0.35), transparent 45%),
                radial-gradient(circle at 30% 85%, rgba(249,115,22,0.30), transparent 45%),
                linear-gradient(135deg, rgba(2,6,23,0.70), rgba(2,6,23,0.35));
            z-index:-2;
        }

        /* Noise halus biar ga “flat” */
        .grain{
            position:fixed; inset:0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='.12'/%3E%3C/svg%3E");
            mix-blend-mode: overlay;
            opacity: .35;
            z-index:-1;
            pointer-events:none;
        }

        /* ===== Layout ===== */
        .container{
            min-height:100vh;
            display:grid;
            place-items:center;
            padding:24px;
        }

        .wrap{
            width:min(1040px, 100%);
            display:grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap:28px;
            align-items:center;
        }

        /* Kiri (Hero) */
        .hero{
            padding:22px;
            animation: floatIn .7s ease both;
        }

        .badge{
            display:inline-flex;
            align-items:center;
            gap:10px;
            padding:10px 14px;
            border-radius:999px;
            border: 1px solid rgba(255,255,255,0.16);
            background: rgba(2,6,23,0.35);
            backdrop-filter: blur(12px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.35);
            width:max-content;
            margin-bottom:14px;
        }
        .project-title{
    margin: 10px 0 10px;
    font-size: 1.02rem;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.88);
    text-shadow: 0 14px 40px rgba(0,0,0,0.55);
}
        .badge .dot{
            width:10px; height:10px; border-radius:999px;
            background: linear-gradient(135deg, var(--brand1), var(--brand2));
            box-shadow: 0 0 0 6px rgba(250,204,21,0.12);
        }

        .hero h1{
            font-size: clamp(2rem, 4vw, 3.2rem);
            line-height: 1.06;
            letter-spacing: -0.02em;
            font-weight: 800;
            text-transform: uppercase;
            text-shadow: 0 18px 55px rgba(0,0,0,0.65);
        }

        .hero p{
            margin-top:12px;
            font-size: 1.02rem;
            color: var(--muted);
            max-width: 52ch;
        }

        .hero .mini{
            margin-top:18px;
            display:flex;
            gap:12px;
            flex-wrap:wrap;
            color: rgba(255,255,255,0.80);
            font-size: .9rem;
        }

        .pill{
            padding:10px 12px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.14);
            background: rgba(2,6,23,0.28);
            backdrop-filter: blur(10px);
        }

        /* Kanan (Card Login) */
        .card{
            position:relative;
            border-radius: 26px;
            border: 1px solid var(--border);
            background: var(--card);
            backdrop-filter: blur(18px);
            box-shadow:
                0 40px 110px rgba(0,0,0,0.55),
                inset 0 1px 0 rgba(255,255,255,0.10);
            overflow:hidden;
            animation: floatIn .85s ease both;
        }

        .card::before{
            content:"";
            position:absolute; inset:-30%;
            background:
                radial-gradient(circle at 20% 25%, rgba(250,204,21,0.22), transparent 55%),
                radial-gradient(circle at 80% 75%, rgba(99,102,241,0.25), transparent 60%),
                radial-gradient(circle at 35% 90%, rgba(249,115,22,0.18), transparent 60%);
            z-index:0;
        }

        .card-inner{
            position:relative;
            padding:26px 24px 22px;
            z-index:1;
        }

        .brand{
            display:flex;
            align-items:center;
            gap:12px;
            margin-bottom: 14px;
        }

        .logo{
            width:44px; height:44px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--brand1), var(--brand2));
            box-shadow: 0 18px 40px rgba(250,204,21,0.18);
            display:grid;
            place-items:center;
            color:#111827;
            font-weight: 900;
        }

        .brand h2{
            font-size:1.15rem;
            letter-spacing:-0.01em;
            margin:0;
        }
        .brand small{
            display:block;
            color: var(--muted);
            margin-top:2px;
            font-weight:600;
        }

        .divider{
            height:1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.22), transparent);
            margin: 14px 0 16px;
        }

        .field{ margin-bottom: 12px; }
        label{
            display:block;
            font-size: .86rem;
            font-weight: 700;
            color: rgba(255,255,255,0.78);
            margin-bottom: 7px;
        }

        .control{
            position:relative;
        }

        .input{
            width:100%;
            padding: 12px 44px 12px 44px;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.14);
            outline:none;
            color: rgba(255,255,255,0.92);
            background: rgba(2,6,23,0.60);
            transition: border .15s ease, transform .12s ease, box-shadow .15s ease;
            font-size: .95rem;
        }

        .input:focus{
            border-color: rgba(250,204,21,0.45);
            box-shadow: 0 0 0 4px rgba(250,204,21,0.12);
        }

        .icon-left{
            position:absolute;
            left:14px; top:50%;
            transform: translateY(-50%);
            opacity:.75;
            font-size: 1rem;
            pointer-events:none;
        }

        .icon-right{
            position:absolute;
            right:12px; top:50%;
            transform: translateY(-50%);
            border:none;
            background: transparent;
            color: rgba(255,255,255,0.82);
            cursor:pointer;
            padding:6px 8px;
            border-radius: 10px;
            transition: background .15s ease;
        }
        .icon-right:hover{
            background: rgba(255,255,255,0.08);
        }

        .row{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            margin: 10px 0 6px;
            color: rgba(255,255,255,0.75);
            font-size: .88rem;
        }

        .checkbox{
            display:flex; align-items:center; gap:8px;
            user-select:none;
        }
        .checkbox input{ accent-color: #facc15; }

        .hint{
            margin-top:10px;
            font-size: .82rem;
            color: rgba(255,255,255,0.70);
            text-align:center;
        }
        .hint b{ color: rgba(250,204,21,0.95); }

        .btn{
            width:100%;
            margin-top: 12px;
            padding: 12px 14px;
            border-radius: 16px;
            border:none;
            cursor:pointer;
            font-weight: 900;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #111827;
            background: linear-gradient(135deg, var(--brand1), var(--brand2));
            box-shadow: 0 24px 60px rgba(249,115,22,0.30);
            transition: transform .12s ease, filter .15s ease, box-shadow .15s ease;
        }
        .btn:hover{
            filter: brightness(1.05);
            transform: translateY(-1px);
            box-shadow: 0 30px 76px rgba(249,115,22,0.38);
        }
        .btn:active{ transform: translateY(1px); }

        .toast{
            margin-top: 12px;
            padding: 10px 12px;
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,0.14);
            background: rgba(2,6,23,0.55);
            display:flex;
            gap:10px;
            align-items:flex-start;
            font-size:.88rem;
        }
        .toast.danger{
            border-color: rgba(251,113,133,0.65);
            background: rgba(251,113,133,0.10);
        }
        .toast .mark{
            width:28px; height:28px; border-radius: 10px;
            display:grid; place-items:center;
            background: rgba(251,113,133,0.16);
            flex: 0 0 auto;
        }

        /* Toggle backsound */
        .audio-toggle{
            position: fixed;
            bottom: 18px;
            left: 18px;
            z-index: 10;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.14);
            background: rgba(2,6,23,0.70);
            backdrop-filter: blur(12px);
            color: rgba(255,255,255,0.88);
            font-size: 0.82rem;
            padding: 8px 12px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: 0 18px 44px rgba(0,0,0,0.45);
            transition: transform .12s ease, background .15s ease;
        }
        .audio-toggle:hover{ transform: translateY(-1px); background: rgba(2,6,23,0.78); }
        .audio-toggle .icon{ font-size: 1rem; }

        @keyframes floatIn{
            from{ opacity:0; transform: translateY(10px); filter: blur(6px); }
            to{ opacity:1; transform: translateY(0); filter: blur(0); }
        }

        /* Responsive */
        @media (max-width: 920px){
            .wrap{ grid-template-columns: 1fr; }
            .hero{ text-align:center; }
            .hero p{ margin-left:auto; margin-right:auto; }
            .hero .mini{ justify-content:center; }
        }
    </style>
</head>

<body>
    <video class="video-bg" autoplay muted loop playsinline>
        <source src="bglogin.mp4" type="video/mp4">
    </video>
    <div class="overlay"></div>
    <div class="grain"></div>

    <div class="container">
        <div class="wrap">

            <!-- HERO LEFT -->
            <section class="hero" aria-label="Intro">
                <div class="badge">
                    <span class="dot"></span>
                    <span><b>Explore</b> Budaya Nusantara</span>
                </div>
                <div class="project-title">Tugas Proyek Pemograman Lanjutan</div>

                <h1>Kamu udah mengenal lebih jauh tentang Indonesia?</h1>
                <p>
                    Jelajahi warna-warni kebudayaan Indonesia, dari Sabang sampai Merauke.
                    Login dulu, baru kita mulai petualangannya! 🎉
                </p>

                <div class="mini">
                    <div class="pill">✨ Modern UI</div>
                    <div class="pill">🔒 Simple Login</div>
                    <div class="pill">🎧 Backsound</div>
                </div>
            </section>

            <!-- LOGIN CARD RIGHT -->
            <section class="card" aria-label="Login">
                <div class="card-inner">
                    <div class="brand">
                        <div class="logo">WI</div>
                        <div>
                            <h2>Wonderful Indonesia</h2>
                            <small>Silakan login untuk melanjutkan</small>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <form method="post" action="" autocomplete="off">
                        <div class="field">
                            <label for="username">Username</label>
                            <div class="control">
                                <span class="icon-left">👤</span>
                                <input class="input" type="text" id="username" name="username"
                                       placeholder="Masukkan username" required>
                            </div>
                        </div>

                        <div class="field">
                            <label for="password">Password</label>
                            <div class="control">
                                <span class="icon-left">🔑</span>
                                <input class="input" type="password" id="password" name="password"
                                       placeholder="Masukkan password" required>
                                <button class="icon-right" type="button" id="togglePass" aria-label="Tampilkan password">
                                    👁️
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <label class="checkbox">
                                <input type="checkbox" id="remember">
                                <span>Remember me</span>
                            </label>
                            <span style="opacity:.7;">Tip: gunakan akun demo 😉</span>
                        </div>

                        <button type="submit" class="btn" id="btnLogin">Login</button>

                        <?php if (!empty($error)): ?>
                            <div class="toast danger" role="alert">
                                <div class="mark">⚠️</div>
                                <div>
                                    <div style="font-weight:800; margin-bottom:2px;">Gagal login</div>
                                    <div><?php echo htmlspecialchars($error); ?></div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="hint">
                            Hint: username <b>reza</b> &amp; password <b>reza</b>
                        </div>
                    </form>
                </div>
            </section>

        </div>
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
        // ===== Show/Hide Password =====
        const pass = document.getElementById("password");
        const togglePass = document.getElementById("togglePass");

        togglePass.addEventListener("click", () => {
            const isHidden = pass.getAttribute("type") === "password";
            pass.setAttribute("type", isHidden ? "text" : "password");
            togglePass.textContent = isHidden ? "🙈" : "👁️";
        });

        // ===== Backsound Control =====
        const audio = document.getElementById("bgm");
        const toggleBtn = document.getElementById("audioToggle");
        const audioLabel = document.getElementById("audioLabel");
        const iconSpan = toggleBtn.querySelector(".icon");

        audio.volume = 0.55;

        // Autoplay biasanya diblokir, jadi init setelah klik pertama
        let audioInitialized = false;
        function initAudio() {
            if (!audioInitialized) {
                audio.play().catch(() => {});
                audioInitialized = true;
            }
        }
        document.addEventListener("click", initAudio, { once: true });

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

        // ===== Micro UX: tombol login animasi kecil =====
        const btn = document.getElementById("btnLogin");
        btn.addEventListener("click", () => {
            btn.style.transform = "translateY(1px)";
            setTimeout(()=> btn.style.transform = "", 120);
        });
    </script>
</body>
</html>
