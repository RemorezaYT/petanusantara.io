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
    <title>Kuis Sejarah Budaya Indonesia - Mode Normal</title>
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
                <span>Kuis Mode Normal</span>
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
                    MODE LATIHAN • NORMAL
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
    {
        question: "Naskah 'Piagam Jakarta' tanggal 22 Juni 1945 terutama dikenal karena memuat rumusan…",
        option_a: "Sistem multipartai di Indonesia",
        option_b: "Kewajiban menjalankan syariat Islam bagi pemeluknya",
        option_c: "Bentuk negara federal setelah kemerdekaan",
        option_d: "Penghapusan sistem kerajaan di Nusantara",
        correct_answer: "b",
        explanation: "Piagam Jakarta memuat rumusan dasar negara dengan tujuh kata 'dengan kewajiban menjalankan syariat Islam bagi pemeluk-pemeluknya', yang kemudian dihapus pada 18 Agustus 1945 demi persatuan."
    },
    {
        question: "Prasasti Ciaruteun di Jawa Barat yang memuat tapak kaki raja merupakan peninggalan kerajaan…",
        option_a: "Tarumanegara",
        option_b: "Mataram Kuno",
        option_c: "Majapahit",
        option_d: "Singasari",
        correct_answer: "a",
        explanation: "Prasasti Ciaruteun di tepi Sungai Citarum berhuruf Pallawa dan berbahasa Sanskerta, menyinggung Raja Purnawarman dari Tarumanegara."
    },
    {
        question: "Konsep politik 'Mandala' dalam kerajaan-kerajaan Asia Tenggara, termasuk di Nusantara, menggambarkan…",
        option_a: "Negara dengan batas teritorial tegas seperti sekarang",
        option_b: "Lingkaran pengaruh kekuasaan yang makin melemah ke daerah pinggiran",
        option_c: "Konfederasi kota-kota dagang otonom",
        option_d: "Sistem kerajaan dengan suksesi elektif",
        correct_answer: "b",
        explanation: "Model mandala menggambarkan pusat kekuasaan yang memiliki lingkar pengaruh; semakin jauh dari pusat, semakin lemah kontrolnya, sering berupa kerajaan vasal."
    },
    {
        question: "Tokoh Walisongo yang dikenal menyebarkan Islam di wilayah Cirebon dan mendirikan Kesultanan Cirebon adalah…",
        option_a: "Sunan Ampel",
        option_b: "Sunan Giri",
        option_c: "Sunan Gunung Jati",
        option_d: "Sunan Bonang",
        correct_answer: "c",
        explanation: "Sunan Gunung Jati (Syarif Hidayatullah) berperan besar dalam Islamisasi pantai utara Jawa dan mendirikan Kesultanan Cirebon."
    },
    {
        question: "Salah satu ciri khas arsitektur Masjid Demak yang menunjukkan proses akulturasi dengan budaya Jawa adalah…",
        option_a: "Menara berbentuk pagoda Tiongkok",
        option_b: "Atap tumpang tiga menyerupai bangunan joglo",
        option_c: "Kubus sederhana tanpa ornamen",
        option_d: "Atap kubah bawang besar tunggal",
        correct_answer: "b",
        explanation: "Masjid Demak menggunakan atap tumpang tiga mirip rumah joglo, mencerminkan perpaduan budaya Islam dengan arsitektur tradisional Jawa."
    },
    {
        question: "Kitab 'Arjunawiwaha' karya Mpu Kanwa ditulis pada masa pemerintahan raja…",
        option_a: "Airlangga",
        option_b: "Dharmawangsa Teguh",
        option_c: "Hayam Wuruk",
        option_d: "Kertanegara",
        correct_answer: "a",
        explanation: "Arjunawiwaha digubah oleh Mpu Kanwa pada masa Raja Airlangga (abad 11) dan menggambarkan kepahlawanan Arjuna sebagai alegori raja."
    },
    {
        question: "Dalam tradisi Bali, upacara Ngaben memiliki makna utama sebagai…",
        option_a: "Upacara syukuran panen padi pertama",
        option_b: "Simbol pelepasan roh dari ikatan ragawi",
        option_c: "Ritual penyucian mata air suci",
        option_d: "Perayaan siklus kalender saka",
        correct_answer: "b",
        explanation: "Ngaben adalah upacara pembakaran jenazah untuk melepaskan roh (atma) agar dapat melanjutkan perjalanan menuju alam berikutnya."
    },
    {
        question: "Istilah 'Gerakan Poetera' (Pusat Tenaga Rakyat) yang dibentuk tahun 1943 dimotori oleh…",
        option_a: "Empat serangkai: Sukarno, Hatta, Ki Hadjar, dan Sjahrir",
        option_b: "Empat serangkai: Sukarno, Hatta, Ki Hadjar Dewantara, dan K.H. Mas Mansyur",
        option_c: "Dua serangkai: Sukarno dan Hatta",
        option_d: "Gerakan pemuda non-kooperatif tanpa tokoh tua",
        correct_answer: "b",
        explanation: "Poetera dibentuk Jepang sebagai alat propaganda, dipimpin Empat Serangkai: Sukarno, Hatta, Ki Hadjar Dewantara, dan K.H. Mas Mansyur."
    },
    {
        question: "Salah satu fungsi penting wayang dalam masyarakat Jawa tradisional, selain hiburan, adalah…",
        option_a: "Pajak tidak langsung untuk kas desa",
        option_b: "Media dakwah dan pendidikan moral serta politik",
        option_c: "Sarana lelang tanah bengkok",
        option_d: "Alat ukur status ekonomi keluarga",
        correct_answer: "b",
        explanation: "Pertunjukan wayang sering dipakai untuk menyampaikan pesan etika, kritik sosial, maupun ajaran keagamaan melalui simbol dan dialog tokoh-tokohnya."
    },
    {
        question: "Sistem pelapisan sosial tradisional di Bali yang paling banyak didasarkan pada…",
        option_a: "Stratifikasi rasial kolonial",
        option_b: "Struktur kasta yang dipengaruhi Hindu",
        option_c: "Pembagian kekayaan kapital industri",
        option_d: "Keanggotaan dalam parpol modern",
        correct_answer: "b",
        explanation: "Masyarakat Bali mengenal pelapisan sosial yang berkaitan dengan sistem kasta Hindu (Brahmana, Ksatria, Waisya, Sudra), meski penerapannya kini lebih fleksibel."
    },
    {
        question: "Organisasi Taman Siswa yang didirikan Ki Hadjar Dewantara menekankan asas…",
        option_a: "Politik etis dan multikulturalisme kolonial",
        option_b: "Pendidikan nasional yang berjiwa kebangsaan dan memerdekakan",
        option_c: "Militerisme dan kedisiplinan ala barak",
        option_d: "Pendidikan teknis untuk perusahaan Belanda",
        correct_answer: "b",
        explanation: "Taman Siswa didirikan 1922 untuk menyediakan pendidikan nasional yang menumbuhkan rasa kebangsaan dan memerdekakan murid dari penindasan kolonial."
    },
    {
        question: "Ciri utama lukisan Affandi yang membedakannya dari pelukis lain sezamannya adalah…",
        option_a: "Penggunaan perspektif linear renaisans Eropa",
        option_b: "Teknik impresionisme lembut dengan warna pastel",
        option_c: "Sapuan cat langsung dari tube dengan ekspresi spontan",
        option_d: "Dominasi garis geometris dan bidang datar",
        correct_answer: "c",
        explanation: "Affandi terkenal dengan gaya ekspresionisnya: cat sering dipencet langsung dari tube dan disapukan dengan tangan, menghasilkan garis tebal dan spontan."
    },
    {
        question: "Gerakan 'diaspora Minangkabau' sejak abad ke-19 berpengaruh besar pada…",
        option_a: "Penyusunan hukum agraria kolonial",
        option_b: "Perkembangan jaringan perdagangan dan intelektual di Nusantara",
        option_c: "Produksi rempah di Maluku",
        option_d: "Penguatan kekuasaan feodal di Sumatera Barat",
        correct_answer: "b",
        explanation: "Tradisi merantau Minangkabau melahirkan pedagang, ulama, dan intelektual yang tersebar di berbagai kota, berkontribusi pada jaringan ekonomi dan gerakan modernis."
    },
    {
        question: "Dalam seni pertunjukan Randai Minangkabau, unsur cerita biasanya diambil dari…",
        option_a: "Epos India klasik",
        option_b: "Legenda lokal dan kaba (cerita rakyat prosa berirama)",
        option_c: "Catatan harian pejabat kolonial",
        option_d: "Novel modern berbahasa Belanda",
        correct_answer: "b",
        explanation: "Randai menggabungkan silat, musik, dan dialog, dengan kisah yang diambil dari kaba—cerita rakyat Minangkabau yang disampaikan secara lisan."
    },
    {
        question: "Konsep 'Tri Hita Karana' yang memengaruhi tata ruang tradisional Bali menekankan keseimbangan antara…",
        option_a: "Negara, pasar, dan keluarga",
        option_b: "Gunung, laut, dan sungai",
        option_c: "Hubungan manusia dengan Tuhan, sesama, dan alam",
        option_d: "Raja, bangsawan, dan rakyat jelata",
        correct_answer: "c",
        explanation: "Tri Hita Karana adalah filosofi harmoni tiga relasi: parhyangan (manusia–Tuhan), pawongan (manusia–manusia), dan palemahan (manusia–alam), tercermin dalam arsitektur dan tata ruang Bali."
    }
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
