<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - GastroCheck APP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style/home.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav class="navbar">
                <a href="home.php" class="logo">
                    <i class="fas fa-stethoscope"></i> GastroCheck
                </a>
                <div class="user-menu">
                    <div class="user-info">
                        <h3><?php echo htmlspecialchars($user['nama_lengkap']); ?></h3>
                        <p><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                    <a href="logout.php" class="btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <!-- Welcome Section -->
        <section class="welcome-section">
            <h1>Selamat Datang, <?php echo htmlspecialchars($user['nama_lengkap']); ?>! 🎉</h1>
            <p>Anda berhasil login ke GastroCheck. Mari mulai pantau kesehatan lambung Anda.</p>
        </section>

        <!-- Features Grid -->
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>Deteksi Gejala</h3>
                <p>Analisis gejala lambung yang Anda alami</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Pemantauan</h3>
                <p>Pantau perkembangan kesehatan Anda</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <h3>Makanan</h3>
                <p>Rekomendasi makanan sehat</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <h3>Konsultasi</h3>
                <p>Konsultasi dengan dokter</p>
            </div>
        </div>

        <!-- Health Conditions Section -->
        <section class="health-conditions">
            <h2><i class="fas fa-heartbeat"></i> Kondisi Kesehatan Terkait</h2>
            <div class="conditions-grid">
                <div class="condition-card">
                    <div class="condition-title">Penyakit</div>
                    <div class="condition-name">GERD</div>
                </div>
                <div class="condition-card">
                    <div class="condition-title">Penyakit</div>
                    <div class="condition-name">Gastroparesis</div>
                </div>
                <div class="condition-card">
                    <div class="condition-title">Penyakit</div>
                    <div class="condition-name">Gastritis</div>
                </div>
            </div>
        </section>

        <!-- Video Section -->
        <section class="video-section">
            <h2>ASAM LAMBUNG NAIK? JANGAN PANIK! INI SOLUSINYA...!!</h2>
            <button class="video-button" onclick="openVideo()">
                <i class="fas fa-play-circle"></i> Tonton Video Selengkapnya !!
            </button>
        </section>

        <!-- Diagnosis Section -->
        <section class="diagnosis-section">
            <h2>Diagnosa Gejala</h2>
            <button class="diagnosis-button" onclick="startDiagnosis()">
                <i class="fas fa-stethoscope"></i> Mulai Diagnosa
            </button>
        </section>

        <!-- Profile Section -->
        <section class="profile-section">
            <h2><i class="fas fa-user"></i> Informasi Profil</h2>
            <div class="profile-info">
                <div class="info-item">
                    <div class="info-label">Nama Lengkap</div>
                    <div><?php echo htmlspecialchars($user['nama_lengkap']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div><?php echo htmlspecialchars($user['email']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Jenis Kelamin</div>
                    <div><?php echo htmlspecialchars(ucfirst($user['kelamin'])); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div style="color: green;">✓ Aktif</div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2023 GastroCheck. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script>
        // Simple welcome message
        console.log('Selamat datang di GastroCheck Dashboard!');
        
        // Add interactivity to feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('click', function() {
                const featureName = this.querySelector('h3').textContent;
                alert(`Anda memilih fitur: ${featureName}`);
            });
        });
        
        // Video button functionality
        function openVideo() {
            alert('Video akan diputar di halaman baru.');
            window.open('https://youtu.be/pd2iEPb_msc?si=A9KqGccPlzpmyMMp', '_blank');
        }
        
        // Diagnosis button functionality
        function startDiagnosis() {
            alert('Anda akan diarahkan ke halaman diagnosa gejala.');
            window.location.href = 'diagnosa.php';
        }
    </script>
</body>
</html>