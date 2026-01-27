<?php
session_start();

// Cek login
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
    <title>Kuis Sejarah Budaya Indonesia - Mode Mudah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --bg: #020617;
            --card-bg: rgba(15,23,42,0.78);
            --card-border: rgba(148,163,184,0.45);
            --text-main: #e5e7eb;
            --text-muted: #9ca3af;
            --accent1: #4f46e5;
            --accent2: #06b6d4;
            --accent3: #f97316;
            --danger: #f97373;
            --success: #4ade80;
        }

        *{
            box-sizing:border-box;
            margin:0;
            padding:0;
        }

        body{
            font-family:"Plus Jakarta Sans", system-ui, -apple-system, "Segoe UI", sans-serif;
            min-height:100vh;
            background: radial-gradient(circle at 0% 0%, #1d2244 0, #020617 45%, #000 100%);
            color: var(--text-main);
            display:flex;
            justify-content:center;
            align-items:center;
            padding:16px;
            overflow-x:hidden;
        }

        /* BACKGROUND GLOW + GRID */
        .bg-glow{
            position:fixed;
            inset:0;
            z-index:-2;
            background:
                radial-gradient(circle at 10% 5%, rgba(79,70,229,0.45), transparent 55%),
                radial-gradient(circle at 90% 10%, rgba(6,182,212,0.35), transparent 55%),
                radial-gradient(circle at 15% 95%, rgba(249,115,22,0.40), transparent 55%),
                radial-gradient(circle at 80% 90%, rgba(79,70,229,0.30), transparent 55%);
            filter: blur(4px);
        }
        .bg-grid{
            position:fixed;
            inset:0;
            z-index:-1;
            background-image:
                linear-gradient(rgba(148,163,184,0.13) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148,163,184,0.13) 1px, transparent 1px);
            background-size: 38px 38px;
            opacity:.45;
            mask-image: radial-gradient(circle at 50% 20%, black 0%, transparent 70%);
            pointer-events:none;
        }

        /* WRAPPER */
        .quiz-shell{
            width:min(1040px, 100%);
            position:relative;
        }

        header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:18px;
            padding-inline:4px;
        }

        .brand{
            display:flex;
            align-items:center;
            gap:10px;
        }
        .brand-logo{
            width:40px;height:40px;
            border-radius: 14px;
            background: conic-gradient(from 180deg, var(--accent1), var(--accent2), var(--accent3), var(--accent1));
            display:grid;place-items:center;
            color:#020617;
            font-weight:900;
            box-shadow:0 10px 30px rgba(59,130,246,0.55);
        }
        .brand-text b{
            display:block;
            font-size:.96rem;
            letter-spacing:-0.02em;
        }
        .brand-text span{
            display:block;
            font-size:.75rem;
            color:var(--text-muted);
            text-transform:uppercase;
            letter-spacing:.16em;
        }

        .user-mini{
            font-size:.9rem;
            color:var(--text-muted);
            text-align:right;
        }
        .user-mini strong{
            color: #eab308;
        }

        /* CARD MAIN */
        .quiz-card{
            position:relative;
            border-radius:24px;
            padding:22px 22px 20px;
            background: linear-gradient(145deg, rgba(15,23,42,0.96), rgba(15,23,42,0.85));
            border:1px solid var(--card-border);
            box-shadow:
                0 26px 80px rgba(15,23,42,0.95),
                0 0 0 1px rgba(148,163,184,0.15);
            overflow:hidden;
            backdrop-filter: blur(18px);
        }
        .quiz-card::before{
            content:"";
            position:absolute;
            inset:-40%;
            background:
                radial-gradient(circle at 10% 0%, rgba(79,70,229,0.22), transparent 55%),
                radial-gradient(circle at 80% 30%, rgba(6,182,212,0.18), transparent 55%),
                radial-gradient(circle at 40% 100%, rgba(249,115,22,0.20), transparent 55%);
            opacity:.85;
            z-index:0;
            pointer-events:none;
        }
        .quiz-inner{
            position:relative;
            z-index:1;
        }

        /* TOP BADGE + PROGRESS */
        .quiz-top{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
            margin-bottom:16px;
        }
        .badge-mode{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:6px 12px;
            border-radius:999px;
            background: rgba(15,23,42,0.88);
            border:1px solid rgba(148,163,184,0.55);
            font-size:.76rem;
            letter-spacing:.14em;
            text-transform:uppercase;
            color:var(--text-muted);
        }
        .badge-dot{
            width:9px;height:9px;border-radius:999px;
            background: radial-gradient(circle at 30% 30%, #ffffff, #22c55e);
            box-shadow:0 0 0 5px rgba(34,197,94,0.20);
        }

        .progress{
            flex-shrink:0;
            display:flex;
            flex-direction:column;
            gap:4px;
            align-items:flex-end;
        }
        .progress span{
            font-size:.78rem;
            color:var(--text-muted);
        }
        .progress-bar{
            width:140px;
            height:6px;
            border-radius:999px;
            background:rgba(30,64,175,0.65);
            overflow:hidden;
            box-shadow:inset 0 0 4px rgba(15,23,42,0.85);
        }
        .progress-fill{
            height:100%;
            width:0%;
            border-radius:999px;
            background:linear-gradient(90deg, var(--accent2), var(--accent1), var(--accent3));
            transition:width .25s ease-out;
        }

        /* TITLE & QUESTION */
        .quiz-title{
            font-size:1.4rem;
            font-weight:800;
            letter-spacing:-0.02em;
            margin-bottom:4px;
        }
        .quiz-sub{
            font-size:.92rem;
            color:var(--text-muted);
            margin-bottom:14px;
        }

        .question-box{
            margin-top:8px;
            padding:14px 14px 16px;
            border-radius:18px;
            background: rgba(15,23,42,0.82);
            border:1px solid rgba(148,163,184,0.50);
            box-shadow:0 16px 45px rgba(15,23,42,0.85);
        }

        #question-text{
            font-size:1.08rem;
            font-weight:600;
            line-height:1.6;
        }

        /* OPTIONS */
        #options-container{
            margin-top:14px;
            display:grid;
            grid-template-columns:repeat(2, minmax(0,1fr));
            gap:10px;
        }

        .option-btn{
            position:relative;
            border:none;
            border-radius:14px;
            padding:10px 12px;
            background: radial-gradient(circle at 0% 0%, rgba(79,70,229,0.70), rgba(15,23,42,0.96));
            color:var(--text-main);
            font-size:.92rem;
            text-align:left;
            cursor:pointer;
            display:flex;
            align-items:center;
            gap:8px;
            overflow:hidden;
            transition: transform .12s ease, box-shadow .12s ease, background .15s ease, border .15s ease;
            box-shadow:0 14px 36px rgba(15,23,42,0.9);
        }
        .option-btn::before{
            content:"";
            position:absolute;
            inset:-40%;
            background:radial-gradient(circle at 0% 0%, rgba(255,255,255,0.12), transparent 60%);
            opacity:0;
            transition:opacity .18s ease;
        }
        .option-label{
            width:24px;height:24px;border-radius:999px;
            border:1px solid rgba(226,232,240,0.6);
            display:grid;place-items:center;
            font-size:.78rem;
            flex-shrink:0;
            background:rgba(15,23,42,0.75);
        }
        .option-text{
            flex:1;
        }

        .option-btn:hover{
            transform:translateY(-1px);
            box-shadow:0 18px 46px rgba(15,23,42,0.95);
        }
        .option-btn:hover::before{
            opacity:1;
        }

        .option-btn.correct{
            background:linear-gradient(135deg, rgba(34,197,94,0.12), rgba(21,128,61,0.85));
            border:1px solid rgba(74,222,128,0.85);
        }
        .option-btn.wrong{
            background:linear-gradient(135deg, rgba(248,113,113,0.10), rgba(127,29,29,0.90));
            border:1px solid rgba(248,113,113,0.85);
        }
        .option-btn.disabled{
            cursor:default;
            opacity:.92;
            box-shadow:none;
            transform:none;
        }

        /* EXPLANATION + SCORE */
        #explanation{
            margin-top:12px;
            font-size:.9rem;
            color:var(--text-muted);
            padding:10px 12px;
            border-radius:14px;
            background:rgba(15,23,42,0.86);
            border:1px solid rgba(148,163,184,0.55);
            display:none;
        }
        #explanation strong{
            display:block;
            margin-bottom:4px;
        }

        .meta-row{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:10px;
            margin-top:14px;
            font-size:.85rem;
            color:var(--text-muted);
        }
        .score-pill{
            padding:6px 10px;
            border-radius:999px;
            background:rgba(15,23,42,0.88);
            border:1px solid rgba(148,163,184,0.60);
            font-weight:600;
        }

        #next-btn{
            border:none;
            border-radius:999px;
            padding:9px 18px;
            background:linear-gradient(135deg, #22c55e, #16a34a);
            color:#022c22;
            font-weight:800;
            font-size:.86rem;
            text-transform:uppercase;
            letter-spacing:.12em;
            cursor:pointer;
            display:none;
            box-shadow:0 16px 40px rgba(22,163,74,0.7);
            transition: transform .10s ease, box-shadow .10s ease, filter .12s ease;
        }
        #next-btn:hover{
            transform:translateY(-1px);
            filter:brightness(1.05);
            box-shadow:0 20px 55px rgba(22,163,74,0.9);
        }
        #next-btn:active{
            transform:translateY(1px);
            box-shadow:0 8px 24px rgba(22,163,74,0.8);
        }

        @media (max-width:720px){
            .quiz-card{ padding:18px 14px 16px; }
            #options-container{ grid-template-columns:1fr; }
            .quiz-title{ font-size:1.22rem; }
        }
    </style>
</head>
<body>
<div class="bg-glow"></div>
<div class="bg-grid"></div>

<div class="quiz-shell">
    <header>
        <div class="brand">
            <div class="brand-logo">KI</div>
            <div class="brand-text">
                <b>Kebudayaan Indonesia</b>
                <span>Kuis Mode Mudah</span>
            </div>
        </div>
        <div class="user-mini">
            Selamat datang,<br><strong><?php echo htmlspecialchars($username); ?></strong>
        </div>
    </header>

    <main class="quiz-card">
        <div class="quiz-inner">
            <div class="quiz-top">
                <div class="badge-mode">
                    <span class="badge-dot"></span>
                    MODE LATIHAN • MUDAH
                </div>
                <div class="progress">
                    <span id="progress-text">Soal 1 / 15</span>
                    <div class="progress-bar">
                        <div id="progress-fill" class="progress-fill"></div>
                    </div>
                </div>
            </div>

            <div class="quiz-title">Kuis Sejarah & Budaya Indonesia</div>
            <div class="quiz-sub">Jawab pertanyaan satu per satu. Kamu akan langsung tahu apakah jawabanmu benar atau salah, lengkap dengan penjelasan.</div>

            <section class="question-box">
                <div id="question-text"></div>
                <div id="options-container"></div>

                <div id="explanation"></div>

                <div class="meta-row">
                    <span class="score-pill" id="score-text">Poin: 0</span>
                    <button id="next-btn">Soal Berikutnya</button>
                </div>
            </section>
        </div>
    </main>
</div>

<script>
    const questions = [
        { question: "Siapa yang menciptakan tari Pendet?", option_a: "I Gusti Ngurah Rai", option_b: "Bali Aga", option_c: "I Made Bandem", option_d: "Wayan Wija", correct_answer: "c", explanation: "Tari Pendet diciptakan oleh I Made Bandem pada tahun 1960-an di Bali." },
        { question: "Apa makanan khas dari Yogyakarta?", option_a: "Pempek", option_b: "Gudeg", option_c: "Nasi Goreng", option_d: "Rendang", correct_answer: "b", explanation: "Gudeg adalah makanan khas Yogyakarta yang terbuat dari nangka muda yang dimasak dengan santan." },
        { question: "Apa nama rumah adat dari Papua?", option_a: "Tongkonan", option_b: "Rumah Gadang", option_c: "Honai", option_d: "Joglo", correct_answer: "c", explanation: "Rumah adat Papua disebut Honai, terbuat dari kayu dengan atap jerami tebal untuk menahan dingin." },
        { question: "Apa nama tarian tradisional dari Aceh?", option_a: "Tari Kecak", option_b: "Tari Saman", option_c: "Tari Piring", option_d: "Tari Reog", correct_answer: "b", explanation: "Tari Saman terkenal dengan gerakan serempak para penari yang duduk berbaris rapat." },
        { question: "Apa yang dimaksud dengan Batik?", option_a: "Teknik pewarnaan kain dengan lilin malam", option_b: "Jenis kain tenun", option_c: "Proses pencelupan kain dengan warna alami", option_d: "Jenis ukiran kayu", correct_answer: "a", explanation: "Batik adalah teknik menghias kain menggunakan lilin malam sebagai perintang warna." },
        { question: "Siapa pencipta lagu Indonesia Raya?", option_a: "Sudirman", option_b: "W.R. Supratman", option_c: "Ismail Marzuki", option_d: "H. Mutahar", correct_answer: "b", explanation: "W.R. Supratman memperdengarkan Indonesia Raya pertama kali pada Kongres Pemuda II tahun 1928." },
        { question: "Apa nama alat musik tradisional yang banyak digunakan di Bali?", option_a: "Angklung", option_b: "Gamelan", option_c: "Sasando", option_d: "Kolintang", correct_answer: "b", explanation: "Gamelan digunakan dalam berbagai upacara dan pertunjukan seni di Bali maupun Jawa." },
        { question: "Apa nama rumah adat dari Sumatera Barat?", option_a: "Joglo", option_b: "Rumah Gadang", option_c: "Limas", option_d: "Tongkonan", correct_answer: "b", explanation: "Rumah Gadang memiliki atap bergonjong menyerupai tanduk kerbau, simbol kejayaan Minangkabau." },
        { question: "Di provinsi mana Candi Borobudur berada?", option_a: "DI Yogyakarta", option_b: "Jawa Timur", option_c: "Jawa Tengah", option_d: "Banten", correct_answer: "c", explanation: "Borobudur terletak di Magelang, Jawa Tengah, dan merupakan candi Buddha terbesar di dunia." },
        { question: "Tari Jaipong berasal dari daerah mana?", option_a: "Jawa Barat", option_b: "Aceh", option_c: "Bali", option_d: "Maluku", correct_answer: "a", explanation: "Tari Jaipong berkembang di Jawa Barat dengan gerakan enerjik dan irama kendang yang kuat." },
        { question: "Batik Solo dikenal dengan ciri apa?", option_a: "Motif cerah dengan gambar ikan", option_b: "Motif halus dengan warna sogan", option_c: "Motif garis tegas warna neon", option_d: "Motif laut dengan warna biru terang", correct_answer: "b", explanation: "Batik Solo umumnya menggunakan warna sogan (cokelat keemasan) dengan motif halus dan klasik." },
        { question: "Siapa yang dijuluki Bapak Proklamator Indonesia?", option_a: "Sukarno", option_b: "Sultan Hasanuddin", option_c: "Diponegoro", option_d: "Mohammad Natsir", correct_answer: "a", explanation: "Sukarno memproklamasikan kemerdekaan Indonesia bersama Mohammad Hatta pada 17 Agustus 1945." },
        { question: "Apa nama seni pertunjukan dengan suara \"cak\" berulang dari Bali?", option_a: "Wayang Orang", option_b: "Tari Kecak", option_c: "Lenong", option_d: "Randai", correct_answer: "b", explanation: "Tari Kecak menonjolkan koor vokal \"cak-cak-cak\" dari para penari laki-laki yang duduk melingkar." },
        { question: "Candi Prambanan adalah kompleks candi agama apa?", option_a: "Buddha", option_b: "Hindu", option_c: "Khonghucu", option_d: "Kristen", correct_answer: "b", explanation: "Candi Prambanan adalah kompleks candi Hindu terbesar di Indonesia, didedikasikan untuk Trimurti." },
        { question: "Bahasa daerah yang digunakan masyarakat Bali adalah…", option_a: "Bahasa Sunda", option_b: "Bahasa Jawa", option_c: "Bahasa Bali", option_d: "Bahasa Sasak", correct_answer: "c", explanation: "Selain bahasa Indonesia, masyarakat Bali menggunakan Bahasa Bali dalam kehidupan sehari-hari dan adat." }
    ];

    let currentIndex = 0;
    let score = 0;
    let hasAnswered = false;

    const questionTextEl = document.getElementById("question-text");
    const optionsContainerEl = document.getElementById("options-container");
    const explanationEl = document.getElementById("explanation");
    const scoreTextEl = document.getElementById("score-text");
    const nextBtnEl = document.getElementById("next-btn");
    const progressTextEl = document.getElementById("progress-text");
    const progressFillEl = document.getElementById("progress-fill");

    function renderQuestion() {
        const q = questions[currentIndex];
        hasAnswered = false;

        progressTextEl.textContent = `Soal ${currentIndex + 1} / ${questions.length}`;
        const progressPercent = ((currentIndex) / questions.length) * 100;
        progressFillEl.style.width = progressPercent + "%";

        questionTextEl.textContent = q.question;
        explanationEl.style.display = "none";
        explanationEl.innerHTML = "";

        optionsContainerEl.innerHTML = "";
        const options = [
            { key: "a", label: "A", text: q.option_a },
            { key: "b", label: "B", text: q.option_b },
            { key: "c", label: "C", text: q.option_c },
            { key: "d", label: "D", text: q.option_d },
        ];

        options.forEach(opt => {
            const btn = document.createElement("button");
            btn.className = "option-btn";
            btn.innerHTML = `<span class="option-label">${opt.label}</span><span class="option-text">${opt.text}</span>`;
            btn.addEventListener("click", () => handleAnswer(opt.key, btn));
            optionsContainerEl.appendChild(btn);
        });

        nextBtnEl.style.display = "none";
    }

    function handleAnswer(selectedKey, clickedBtn) {
        if (hasAnswered) return;
        hasAnswered = true;

        const q = questions[currentIndex];

        // disable all buttons
        const allButtons = document.querySelectorAll(".option-btn");
        allButtons.forEach(btn => {
            btn.classList.add("disabled");
        });

        // highlight correct & wrong
        allButtons.forEach(btn => {
            const label = btn.querySelector(".option-label").textContent.trim();
            const keyFromLabel = label.toLowerCase();
            if (keyFromLabel === q.correct_answer) {
                btn.classList.add("correct");
            }
        });

        if (selectedKey === q.correct_answer) {
            score += 10;
            clickedBtn.classList.add("correct");
            explanationEl.innerHTML = `<strong style="color:${getComputedStyle(document.documentElement).getPropertyValue('--success')}">Jawaban kamu benar!</strong>${q.explanation}`;
        } else {
            clickedBtn.classList.add("wrong");
            explanationEl.innerHTML = `<strong style="color:${getComputedStyle(document.documentElement).getPropertyValue('--danger')}">Jawaban kamu belum tepat.</strong>${q.explanation}`;
        }

        explanationEl.style.display = "block";
        scoreTextEl.textContent = `Poin: ${score}`;
        nextBtnEl.style.display = "inline-flex";
    }

    nextBtnEl.addEventListener("click", () => {
        if (currentIndex < questions.length - 1) {
            currentIndex++;
            renderQuestion();
        } else {
            // Finish
            progressFillEl.style.width = "100%";
            questionTextEl.textContent = "Kuis selesai! 🎉";
            optionsContainerEl.innerHTML = "";
            explanationEl.style.display = "block";
            explanationEl.innerHTML = `Kamu telah menyelesaikan semua soal. <br><br><strong>Total poin kamu: ${score} dari ${questions.length * 10}.</strong><br>Silakan kembali ke menu utama atau coba mode lain.`;
            nextBtnEl.style.display = "none";
        }
    });

    // initial render
    renderQuestion();
</script>
</body>
</html>
