<?php
// Data gejala dan penyakit
$symptoms = [
    ["id" => "G01", "name" => "Mual pada perut"],
    ["id" => "G02", "name" => "Nyeri di ulu hati"],
    ["id" => "G03", "name" => "Perut kembung"],
    ["id" => "G04", "name" => "Sendawa berlebih"],
    ["id" => "G05", "name" => "Sulit tidur"],
    ["id" => "G06", "name" => "Anemia"],
    ["id" => "G07", "name" => "BAB berwarna hitam"],
    ["id" => "G08", "name" => "Sering Cegukan"],
    ["id" => "G09", "name" => "Sakit tenggorokan"],
    ["id" => "G10", "name" => "Mudah merasa kenyang"],
    ["id" => "G11", "name" => "Kadar gula darah tidak terkontrol"],
    ["id" => "G12", "name" => "Asam dan pahit pada mulut"],
    ["id" => "G13", "name" => "Muntah darah"],
    ["id" => "G14", "name" => "BAB Berdarah"],
    ["id" => "G15", "name" => "Penurunan berat badan"]
];

$diseases = [
    "A01" => [
        "name" => "Tukak Lambung",
        "description" => "Luka terbuka yang berkembang pada lapisan dalam lambung. Kondisi ini dapat menyebabkan nyeri dan ketidaknyamanan.",
        "causes" => "Infeksi bakteri H. pylori, penggunaan obat antiinflamasi nonsteroid jangka panjang, stres, konsumsi alkohol berlebihan.",
        "prevention" => "Hindari makanan pedas dan asam, kurangi konsumsi kafein dan alkohol, kelola stres, dan makan dengan porsi kecil namun sering.",
        "treatment" => "Obat antasida, antibiotik untuk infeksi H. pylori, penghambat pompa proton, perubahan gaya hidup."
    ],
    "A02" => [
        "name" => "Gastroparesis",
        "description" => "Kondisi di mana perut tidak dapat mengosongkan isinya dengan normal karena kerusakan saraf yang mengontrol otot-otot perut.",
        "causes" => "Diabetes, operasi lambung, gangguan sistem saraf, obat-obatan tertentu.",
        "prevention" => "Makan makanan kecil dan rendah lemak, hindari makanan berserat tinggi, kunyah makanan dengan baik, dan hindari berbaring setelah makan.",
        "treatment" => "Obat prokinetik, perubahan pola makan, dalam kasus parah mungkin memerlukan tabung makan atau stimulasi listrik lambung."
    ],
    "A03" => [
        "name" => "GERD (Gastroesophageal Reflux Disease)",
        "description" => "Terjadi ketika asam lambung naik ke kerongkongan secara berulang, menyebabkan iritasi dan peradangan.",
        "causes" => "Melemahnya otot kerongkongan bagian bawah, obesitas, kehamilan, hernia hiatus, makanan tertentu.",
        "prevention" => "Hindari makanan pemicu asam lambung, jangan langsung berbaring setelah makan, turunkan berat badan jika berlebih, dan hindari pakaian ketat.",
        "treatment" => "Antasida, penghambat reseptor H2, penghambat pompa proton, perubahan gaya hidup, dalam kasus parah mungkin memerlukan operasi."
    ],
    "A04" => [
        "name" => "Gastritis",
        "description" => "Peradangan pada lapisan lambung yang dapat bersifat akut atau kronis.",
        "causes" => "Infeksi bakteri H. pylori, penggunaan obat antiinflamasi jangka panjang, konsumsi alkohol berlebihan, stres, penyakit autoimun.",
        "prevention" => "Hindari alkohol dan rokok, kelola stres, makan makanan yang mudah dicerna, dan hindari obat yang mengiritasi lambung.",
        "treatment" => "Antibiotik untuk infeksi H. pylori, antasida, penghambat asam, obat pelindung lambung, perubahan pola makan."
    ],
    "A05" => [
        "name" => "Kanker Lambung",
        "description" => "Pertumbuhan sel abnormal yang tidak terkendali di dalam lambung. Dapat menyebar ke organ lain jika tidak ditangani.",
        "causes" => "Infeksi H. pylori, merokok, diet tinggi makanan asin dan diasap, riwayat keluarga, polip lambung.",
        "prevention" => "Konsumsi makanan sehat kaya serat dan antioksidan, hindari makanan asap dan diawetkan, berhenti merokok, dan lakukan pemeriksaan rutin jika memiliki riwayat keluarga.",
        "treatment" => "Operasi, kemoterapi, radioterapi, terapi target, imunoterapi tergantung pada stadium kanker."
    ]
];

// Rules berdasarkan penelitian
$rules = [
    ["id" => "Z1", "conditions" => ["G01", "G02", "G03", "G04"], "conclusion" => "A01"],
    ["id" => "Z2", "conditions" => ["G01", "G02", "G03", "G10", "G15"], "conclusion" => "A02"],
    ["id" => "Z3", "conditions" => ["G01", "G03", "G05", "G09", "G12"], "conclusion" => "A03"],
    ["id" => "Z4", "conditions" => ["G01", "G02", "G07", "G08"], "conclusion" => "A04"],
    ["id" => "Z5", "conditions" => ["G02", "G03", "G06", "G13", "G14", "G15"], "conclusion" => "A05"]
];

// Fungsi diagnosa menggunakan metode Forward Chaining
function diagnose($selectedSymptoms, $rules, $diseases) {
    foreach ($rules as $rule) {
        $conditionsMet = true;
        foreach ($rule['conditions'] as $condition) {
            if (!in_array($condition, $selectedSymptoms)) {
                $conditionsMet = false;
                break;
            }
        }
        
        if ($conditionsMet) {
            return $diseases[$rule['conclusion']];
        }
    }
    return null;
}

// Proses form jika ada data yang dikirim
$result = null;
$selectedSymptoms = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['symptoms'])) {
    $selectedSymptoms = $_POST['symptoms'];
    $result = diagnose($selectedSymptoms, $rules, $diseases);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnosa Penyakit Lambung - GastroCheck</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style/diagnosa.css">
</head>
<body>
    <!-- Header & Navigation -->
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <a href="index.php">
                        <i class="fas fa-stomach logo-icon"></i>
                        <h1>GastroCheck</h1>
                    </a>
                </div>
                <a href="home.php" class="back-button">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </nav>
        </div>
    </header>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1><i class="fas fa-stethoscope"></i> Diagnosa Penyakit Lambung</h1>
            <p>Gunakan sistem pakar kami untuk mendiagnosa masalah lambung berdasarkan gejala yang Anda alami</p>
        </div>
    </section>

    <!-- Expert System Section -->
    <section class="expert-system">
        <div class="container">
            <div class="section-title">
                <h2>Sistem Pakar Forward Chaining</h2>
                <p>Pilih gejala yang Anda alami untuk mendapatkan diagnosa awal menggunakan metode Forward Chaining</p>
            </div>
            
            <form method="POST" action="">
                <div class="diagnosis-container">
                    <div class="diagnosis-step">
                        <h3><span>1</span> Pilih Gejala yang Anda Alami</h3>
                        <p>Centang semua gejala yang sesuai dengan kondisi Anda saat ini:</p>
                        
                        <div class="symptoms-grid">
                            <?php foreach ($symptoms as $symptom): ?>
                                <div class="symptom-item">
                                    <input type="checkbox" class="symptom-checkbox" id="<?= $symptom['id'] ?>" 
                                           name="symptoms[]" value="<?= $symptom['id'] ?>"
                                           <?= in_array($symptom['id'], $selectedSymptoms) ? 'checked' : '' ?>>
                                    <label for="<?= $symptom['id'] ?>"><?= $symptom['name'] ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="selected-count">
                            Anda telah memilih <?= count($selectedSymptoms) ?> gejala
                        </div>
                    </div>
                    
                    <div class="diagnosis-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Diagnosa Sekarang
                        </button>
                        <a href="diagnosa.php" class="btn btn-outline">
                            <i class="fas fa-redo"></i> Reset Pilihan
                        </a>
                    </div>
                </div>
            </form>
            
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                <div class="result-container">
                    <?php if ($result): ?>
                        <div class="result-header">
                            <h3><i class="fas fa-diagnoses"></i> Hasil Diagnosa</h3>
                            <p>Berdasarkan gejala yang Anda pilih, berikut adalah hasil diagnosa awal:</p>
                        </div>
                        <div class="disease-info">
                            <h4><?= $result['name'] ?></h4>
                            <div class="info-section">
                                <h5><i class="fas fa-info-circle"></i> Deskripsi</h5>
                                <p><?= $result['description'] ?></p>
                            </div>
                            <div class="info-section">
                                <h5><i class="fas fa-bug"></i> Penyebab Umum</h5>
                                <p><?= $result['causes'] ?></p>
                            </div>
                            <div class="info-section">
                                <h5><i class="fas fa-shield-alt"></i> Tindakan Pencegahan</h5>
                                <p><?= $result['prevention'] ?></p>
                            </div>
                            <div class="info-section">
                                <h5><i class="fas fa-medkit"></i> Penanganan Medis</h5>
                                <p><?= $result['treatment'] ?></p>
                            </div>
                        </div>
                        <div class="selected-symptoms">
                            <h4><i class="fas fa-clipboard-list"></i> Gejala yang Dipilih</h4>
                            <div class="symptoms-list">
                                <?php foreach ($selectedSymptoms as $symptomId): 
                                    $symptomName = '';
                                    foreach ($symptoms as $s) {
                                        if ($s['id'] === $symptomId) {
                                            $symptomName = $s['name'];
                                            break;
                                        }
                                    }
                                ?>
                                    <span class="symptom-tag"><?= $symptomName ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="diagnosis-actions" style="margin-top: 30px;">
                            <button class="btn btn-success" onclick="window.print()">
                                <i class="fas fa-print"></i> Cetak Hasil
                            </button>
                            <button class="btn btn-warning" id="findDoctorBtn">
                                <i class="fas fa-user-md"></i> Cari Dokter
                            </button>
                            <a href="diagnosa.php" class="btn btn-outline">
                                <i class="fas fa-redo"></i> Diagnosa Ulang
                            </a>
                        </div>
                        <div class="warning-box">
                            <strong><i class="fas fa-exclamation-triangle"></i> Peringatan:</strong> 
                            Diagnosa ini tidak 100% valid kebenarannya. Hasil ini hanya sebagai acuan awal. 
                            Disarankan untuk berkonsultasi dengan ahli gastroenterologi untuk pemeriksaan lebih lanjut.
                        </div>
                    <?php else: ?>
                        <div class="no-match">
                            <i class="fas fa-search"></i>
                            <h3>Tidak Ada Diagnosa yang Cocok</h3>
                            <p>Gejala yang Anda pilih tidak cocok dengan penyakit lambung yang ada dalam sistem kami.</p>
                            <p>Ini mungkin disebabkan oleh:</p>
                            <ul style="text-align: left; max-width: 400px; margin: 20px auto;">
                                <li>Gejala yang tidak spesifik</li>
                                <li>Kombinasi gejala yang tidak terdeteksi</li>
                                <li>Kondisi lain yang tidak terkait lambung</li>
                            </ul>
                            <p>Disarankan untuk berkonsultasi dengan dokter untuk pemeriksaan lebih lanjut.</p>
                            <div class="diagnosis-actions" style="margin-top: 30px;">
                                <a href="diagnosa.php" class="btn btn-outline">
                                    <i class="fas fa-redo"></i> Coba Lagi
                                </a>
                                <button class="btn btn-primary" id="emergencyBtn">
                                    <i class="fas fa-ambulance"></i> Darurat
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>GastroCheck</h3>
                    <p>Aplikasi kesehatan yang membantu Anda memantau dan menjaga kesehatan lambung dengan mudah dan efektif.</p>
                </div>
                <div class="footer-column">
                    <h3>Menu</h3>
                    <ul class="footer-links">
                        <li><a href="index.php">Beranda</a></li>
                        <li><a href="diagnosa.php">Diagnosa</a></li>
                        <li><a href="tentang.php">Tentang</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Kontak</h3>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-envelope"></i> info@gastrocheck.com</a></li>
                        <li><a href="#"><i class="fas fa-phone"></i> (021) 1234-5678</a></li>
                        <li><a href="#"><i class="fas fa-map-marker-alt"></i> Jakarta, Indonesia</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Download App</h3>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fab fa-google-play"></i> Google Play</a></li>
                        <li><a href="#"><i class="fab fa-app-store"></i> App Store</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2023 GastroCheck. All rights reserved. | Sistem Pakar Diagnosa Penyakit Lambung Menggunakan Metode Forward Chaining</p>
            </div>
        </div>
    </footer>

    <script>
        // JavaScript untuk interaktivitas tambahan
        document.addEventListener('DOMContentLoaded', function() {
            // Update counter gejala yang dipilih
            const checkboxes = document.querySelectorAll('.symptom-checkbox');
            const selectedCount = document.querySelector('.selected-count');
            
            function updateSelectedCount() {
                const selected = document.querySelectorAll('.symptom-checkbox:checked').length;
                selectedCount.textContent = `Anda telah memilih ${selected} gejala`;
            }
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });
            
            // Tombol cari dokter
            const findDoctorBtn = document.getElementById('findDoctorBtn');
            if (findDoctorBtn) {
                findDoctorBtn.addEventListener('click', function() {
                    alert('Fitur pencarian dokter akan segera tersedia. Silakan hubungi rumah sakit terdekat untuk konsultasi.');
                });
            }
            
            // Tombol darurat
            const emergencyBtn = document.getElementById('emergencyBtn');
            if (emergencyBtn) {
                emergencyBtn.addEventListener('click', function() {
                    alert('Jika kondisi darurat, segera hubungi 118 atau pergi ke unit gawat darurat terdekat.');
                });
            }
        });
    </script>
</body>
</html>