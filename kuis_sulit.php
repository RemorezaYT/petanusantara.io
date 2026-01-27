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
    <title>Kuis Sejarah Budaya Indonesia - Mode Sulit</title>
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
                <span>Kuis Mode Sulit</span>
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
                    MODE LATIHAN • SULIT
                </div>
                <div class="progress">
                    <span id="progress-text">Soal 1 / 15</span>
                    <div class="progress-bar">
                        <div id="progress-fill" class="progress-fill"></div>
                    </div>
                </div>
            </div>

            <div class="quiz-title">Kuis Sejarah & Budaya Indonesia (Tingkat Sulit)</div>
            <div class="quiz-sub">
                Soal-soal ini menuntut pemahaman lebih dalam: konsep, tokoh, naskah, dan konteks sejarah-budaya Nusantara.
            </div>

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
        question: "Kitab perjalanan yang sering dijadikan sumber utama untuk merekonstruksi struktur politik dan ritual Kerajaan Majapahit pada abad ke-14 adalah…",
        option_a: "Pararaton",
        option_b: "Negarakertagama",
        option_c: "Sutasoma",
        option_d: "Tantu Panggelaran",
        correct_answer: "b",
        explanation: "Negarakertagama karya Mpu Prapanca memuat deskripsi rinci perjalanan Hayam Wuruk ke berbagai daerah, struktur pemerintahan, dan ritual negara Majapahit."
    },
    {
        question: "Istilah 'sinkretisme' dalam konteks agama Jawa pada masa Hindu–Buddha dan Islam merujuk pada…",
        option_a: "Penolakan total terhadap kepercayaan lama",
        option_b: "Penggabungan unsur kepercayaan berbeda menjadi sistem baru yang relatif harmonis",
        option_c: "Penghapusan simbol-simbol lokal demi kemurnian agama impor",
        option_d: "Pemisahan tegas antara ritual negara dan ritual desa",
        correct_answer: "b",
        explanation: "Sinkretisme adalah proses percampuran unsur kepercayaan yang berbeda (Hindu, Buddha, Islam, animisme) menjadi praktik keagamaan baru yang tampak menyatu."
    },
    {
        question: "Konsep 'tanah lungguh' dalam struktur sosial Jawa tradisional berkaitan dengan…",
        option_a: "Tanah yang hanya boleh digarap oleh keluarga raja",
        option_b: "Tanah jabatan yang menjadi sumber nafkah pejabat dan abdi dalem",
        option_c: "Tanah persawahan milik bersama satu desa",
        option_d: "Tanah yang dikuasai langsung pemerintah kolonial",
        correct_answer: "b",
        explanation: "Tanah lungguh adalah tanah jabatan yang hasilnya menjadi imbalan bagi pejabat atau abdi dalem, menggantikan gaji uang dalam sistem birokrasi tradisional."
    },
    {
        question: "Salah satu ciri khas gerakan Pujangga Baru dalam sastra Indonesia awal abad ke-20 adalah…",
        option_a: "Bahasa Melayu pasar apa adanya tanpa idealisme",
        option_b: "Penekanan pada individualisme dan nasionalisme modern",
        option_c: "Penulisan kembali hikayat klasik dengan huruf Arab-Melayu",
        option_d: "Penggunaan eksklusif bahasa Belanda sebagai bahasa sastra",
        correct_answer: "b",
        explanation: "Pujangga Baru (Armijn Pane, Sutan Takdir Alisjahbana, dkk.) menonjolkan semangat individualisme, pembaruan, dan nasionalisme dalam sastra Indonesia modern."
    },
    {
        question: "Dalam wayang purwa, tokoh Semar dan para punakawan memiliki fungsi penting sebagai…",
        option_a: "Simbol murni kelucuan tanpa makna lain",
        option_b: "Perwujudan resmi dewa-dewa utama India",
        option_c: "Jembatan antara dunia para ksatria dan suara rakyat kecil serta kritik sosial",
        option_d: "Tokoh antagonis yang selalu melawan kebenaran",
        correct_answer: "c",
        explanation: "Punakawan (Semar, Gareng, Petruk, Bagong) sering menjadi saluran kritik sosial, refleksi moral, dan suara rakyat dalam lakon wayang."
    },
    {
        question: "Tradisi lisan 'Hikayat Hang Tuah' dari kawasan Melayu–Nusantara banyak dibaca sebagai…",
        option_a: "Catatan kronologis resmi Kesultanan Malaka",
        option_b: "Dongeng tanpa kaitan dengan sejarah sama sekali",
        option_c: "Kisah kepahlawanan yang memuat konflik antara loyalitas pada raja dan nurani pribadi",
        option_d: "Naskah hukum dagang antarbangsa",
        correct_answer: "c",
        explanation: "Hikayat Hang Tuah memuat tema kuat tentang loyalitas ekstrem Hang Tuah kepada raja vs sikap Hang Jebat yang mempertanyakan keadilan."
    },
    {
        question: "Istilah 'orientalisme' dalam kajian budaya juga berpengaruh pada cara pelukis dan penulis Eropa menggambarkan Hindia Belanda. Yang dimaksud orientalisme adalah…",
        option_a: "Upaya akademik netral untuk memetakan budaya Timur",
        option_b: "Cara pandang yang kerap menempatkan Timur sebagai eksotis, irasional, dan inferior dibanding Barat",
        option_c: "Gerakan perlawanan seniman Timur terhadap Barat",
        option_d: "Kebijakan resmi pemerintah kolonial tentang pendidikan",
        correct_answer: "b",
        explanation: "Orientalisme (misalnya dalam lukisan Mooi Indie) sering merepresentasikan Timur sebagai eksotis dan 'lain', sehingga memperkuat hierarki kolonial."
    },
    {
        question: "Salah satu perbedaan utama corak batik pesisir (misalnya Pekalongan) dengan batik keraton (misalnya Yogyakarta–Solo) adalah…",
        option_a: "Batik pesisir dilarang menggunakan warna cerah",
        option_b: "Batik keraton tidak mengenal motif simbolis",
        option_c: "Batik pesisir lebih banyak dipengaruhi motif Tiongkok, Eropa, dan warna-warna cerah",
        option_d: "Batik keraton dibuat dengan teknik cetak, batik pesisir selalu tulis",
        correct_answer: "c",
        explanation: "Batik pesisir mendapat pengaruh kuat dari pedagang Tiongkok, Arab, dan Eropa sehingga motifnya lebih bebas dan warnanya lebih cerah."
    },
    {
        question: "Gerakan 'Politik Etis' yang dicanangkan Belanda sekitar tahun 1901 berdampak besar pada…",
        option_a: "Penghapusan pajak bagi penduduk pribumi",
        option_b: "Pendirian sekolah-sekolah modern yang melahirkan kaum terpelajar bumiputra",
        option_c: "Peniadaan seluruh kerja paksa dan tanam paksa",
        option_d: "Penyerahan kedaulatan lebih awal kepada Indonesia",
        correct_answer: "b",
        explanation: "Politik Etis (irigasi, edukasi, emigrasi) membuka akses pendidikan bagi bumiputra, melahirkan kaum cendekia yang kemudian memimpin pergerakan nasional."
    },
    {
        question: "Dalam struktur kepercayaan tradisional Jawa, istilah 'Ratu Adil' merujuk pada…",
        option_a: "Gelar resmi raja Mataram Islam",
        option_b: "Figur mesianis yang diyakini akan datang memulihkan keadilan dan tatanan dunia",
        option_c: "Pemimpin desa terpilih dalam musyawarah",
        option_d: "Sebutan untuk pejabat kolonial yang adil",
        correct_answer: "b",
        explanation: "Mitos Ratu Adil adalah figur mesianis yang akan muncul ketika ketidakadilan memuncak, menjadi sumber harapan dan kadang legitimasi gerakan perlawanan."
    },
    {
        question: "Salah satu alasan penting mengapa Candi Prambanan sempat lama terbengkalai dan rusak berat adalah…",
        option_a: "Dibongkar sengaja oleh kerajaan Islam",
        option_b: "Serangkaian gempa bumi dan letusan gunung berapi serta ditinggalkannya pusat politik di wilayah tersebut",
        option_c: "Dijadikan tambang batu oleh VOC secara resmi",
        option_d: "Terendam banjir laut selama berabad-abad",
        correct_answer: "b",
        explanation: "Perpindahan pusat politik, gempa, dan letusan Merapi mengakibatkan Prambanan runtuh dan lama terbengkalai sebelum dipugar pada era modern."
    },
    {
        question: "Dalam musik tradisional Jawa, konsep 'pathet' berhubungan dengan…",
        option_a: "Jumlah pemain gamelan",
        option_b: "Sistem laras pentatonik diatonis",
        option_c: "Rasa atau mode musikal tertentu yang menentukan suasana dan pemakaian nada dalam suatu gending",
        option_d: "Volume suara yang harus dimainkan",
        correct_answer: "c",
        explanation: "Pathet adalah konsep mode musikal yang menentukan pilihan nada, suasana emosional, dan penempatan lagu dalam siklus pertunjukan."
    },
    {
        question: "Perkembangan film Indonesia awal (era Usmar Ismail) sering dibaca sebagai…",
        option_a: "Perpanjangan propaganda kolonial Belanda",
        option_b: "Usaha memadukan bahasa film modern dengan tema identitas nasional pascakemerdekaan",
        option_c: "Adaptasi langsung film Hollywood tanpa lokalitas",
        option_d: "Proyek eksklusif untuk konsumsi penonton Eropa",
        correct_answer: "b",
        explanation: "Usmar Ismail dan sezamannya berupaya menggunakan medium film untuk mengartikulasikan pengalaman dan identitas Indonesia merdeka."
    },
    {
        question: "Dalam studi budaya, istilah 'hibriditas' (hybridity) relevan dengan kebudayaan Indonesia karena…",
        option_a: "Indonesia hanya memiliki satu sumber budaya murni",
        option_b: "Kebudayaan Indonesia terbentuk dari percampuran beragam pengaruh lokal dan global yang terus dinegosiasikan",
        option_c: "Budaya Indonesia menolak seluruh pengaruh asing",
        option_d: "Hibriditas berarti hilangnya semua budaya lokal",
        correct_answer: "b",
        explanation: "Hibriditas menekankan bahwa identitas budaya tidak statis, melainkan hasil pertemuan, negosiasi, dan percampuran berbagai tradisi dan pengaruh."
    },
    {
        question: "Fenomena 'kebudayaan pop' (pop culture) di kota-kota besar Indonesia pasca-1998 berkaitan erat dengan…",
        option_a: "Menguatnya sensor negara atas musik dan film",
        option_b: "Dibukanya keran globalisasi media, internet, dan industri kreatif yang memengaruhi gaya hidup generasi muda",
        option_c: "Pelaksanaan kembali Politik Etis",
        option_d: "Kembali ke pola hiburan tradisional murni",
        correct_answer: "b",
        explanation: "Pasca-1998, liberalisasi politik dan perkembangan media/internet mempercepat arus budaya global, membentuk budaya pop urban yang sangat cair dan hibrid."
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
        const progressPercent = (currentIndex / questions.length) * 100;
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
        const allButtons = document.querySelectorAll(".option-btn");
        allButtons.forEach(btn => btn.classList.add("disabled"));

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
            explanationEl.innerHTML =
                `<strong style="color:var(--success)">Jawaban kamu benar!</strong>${q.explanation}`;
        } else {
            clickedBtn.classList.add("wrong");
            explanationEl.innerHTML =
                `<strong style="color:var(--danger)">Jawaban kamu belum tepat.</strong>${q.explanation}`;
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
            progressFillEl.style.width = "100%";
            questionTextEl.textContent = "Kuis selesai! 🎉";
            optionsContainerEl.innerHTML = "";
            explanationEl.style.display = "block";
            explanationEl.innerHTML =
                `Kamu telah menyelesaikan semua soal mode sulit.<br><br><strong>Total poin: ${score} dari ${questions.length * 10}.</strong>`;
            nextBtnEl.style.display = "none";
        }
    });

    renderQuestion();
</script>
</body>
</html>
