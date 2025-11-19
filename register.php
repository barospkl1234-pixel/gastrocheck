<?php
session_start();
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $kelamin = $_POST['kelamin'];
    $agreeTerms = isset($_POST['agreeTerms']);

    // Validasi
    if (empty($nama_lengkap) || empty($email) || empty($password) || empty($confirm_password) || empty($kelamin)) {
        $error = "Semua field harus diisi!";
    } elseif (!$agreeTerms) {
        $error = "Anda harus menyetujui syarat dan ketentuan!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } elseif ($password !== $confirm_password) {
        $error = "Password tidak cocok!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } else {
        // Cek apakah email sudah terdaftar
        $check_email = $conn->prepare("SELECT email FROM user_app WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $check_email->store_result();

        if ($check_email->num_rows > 0) {
            $error = "Email sudah terdaftar!";
        } else {
            // Hash password (untuk keamanan)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user baru
            $stmt = $conn->prepare("INSERT INTO user_app (nama_lengkap, email, password, kelamin) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nama_lengkap, $email, $hashed_password, $kelamin);

            if ($stmt->execute()) {
                $success = "Pendaftaran berhasil! Mengarahkan ke halaman login...";
                // Reset form values
                $nama_lengkap = $email = $kelamin = '';
                // Redirect otomatis setelah 2 detik
                echo '<meta http-equiv="refresh" content="2;url=login.php">';
            } else {
                $error = "Terjadi kesalahan: " . $stmt->error;
            }
            $stmt->close();
        }
        $check_email->close();
    }
}
?>

<!-- HTML tetap sama -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - GastroCheck APP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style/register.css">
<body>
    <div class="container">
        <div class="logo">
            <div style="width: 180px; height: 60px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 20px; margin: 0 auto;">
                GastroCheck
            </div>
        </div>
        
        <div class="form-header">
            <h1>Buat Akun Baru</h1>
            <p>Isi data diri Anda untuk membuat akun GastroCheck</p>
        </div>

        <?php if ($error): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> 
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i> 
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" 
                       placeholder="Masukkan nama lengkap Anda" 
                       value="<?php echo isset($_POST['nama_lengkap']) ? htmlspecialchars($_POST['nama_lengkap']) : ''; ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" 
                       placeholder="contoh: email@example.com" 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                       required>
            </div>
            
            <div class="form-group password-field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" 
                       placeholder="Masukkan password (minimal 6 karakter)" 
                       required>
                <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
                <div class="password-strength">
                    <div class="password-strength-bar" id="passwordStrengthBar"></div>
                </div>
            </div>
            
            <div class="form-group password-field">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" id="confirm_password" name="confirm_password" 
                       placeholder="Masukkan ulang password" 
                       required>
                <span class="toggle-password" onclick="togglePassword('confirm_password')">👁️</span>
            </div>
            
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <div class="gender-group">
                    <div class="gender-option <?php echo (isset($_POST['kelamin']) && $_POST['kelamin'] == 'pria') ? 'selected' : ''; ?>" 
                         onclick="selectGender('pria')">
                        <input type="radio" id="pria" name="kelamin" value="pria" 
                               <?php echo (isset($_POST['kelamin']) && $_POST['kelamin'] == 'pria') ? 'checked' : ''; ?> 
                               required>
                        <label for="pria">Pria</label>
                    </div>
                    <div class="gender-option <?php echo (isset($_POST['kelamin']) && $_POST['kelamin'] == 'wanita') ? 'selected' : ''; ?>" 
                         onclick="selectGender('wanita')">
                        <input type="radio" id="wanita" name="kelamin" value="wanita" 
                               <?php echo (isset($_POST['kelamin']) && $_POST['kelamin'] == 'wanita') ? 'checked' : ''; ?> 
                               required>
                        <label for="wanita">Wanita</label>
                    </div>
                </div>
            </div>
            
            <div class="terms">
                <input type="checkbox" id="agreeTerms" name="agreeTerms" 
                       <?php echo (isset($_POST['agreeTerms']) && $_POST['agreeTerms']) ? 'checked' : ''; ?> 
                       required>
                <label for="agreeTerms">Saya menyetujui <a href="#">Syarat & Ketentuan</a> dan <a href="#">Kebijakan Privasi</a> GastroCheck</label>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Daftar
            </button>
            
            <div class="login-link">
                <p>Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
            </div>
        </form>
    </div>

    <script>
        // Toggle visibility password
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const icon = passwordField.parentNode.querySelector('.toggle-password');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.textContent = '🙈';
            } else {
                passwordField.type = 'password';
                icon.textContent = '👁️';
            }
        }
        
        // Select gender option
        function selectGender(gender) {
            const priaOption = document.querySelector('.gender-option:first-child');
            const wanitaOption = document.querySelector('.gender-option:last-child');
            const priaRadio = document.getElementById('pria');
            const wanitaRadio = document.getElementById('wanita');
            
            if (gender === 'pria') {
                priaOption.classList.add('selected');
                wanitaOption.classList.remove('selected');
                priaRadio.checked = true;
            } else {
                wanitaOption.classList.add('selected');
                priaOption.classList.remove('selected');
                wanitaRadio.checked = true;
            }
        }
        
        // Check password strength
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrengthBar');
            let strength = 0;
            
            if (password.length >= 6) strength += 25;
            if (password.length >= 8) strength += 25;
            if (/[a-z]/.test(password)) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 25;
            
            // Cap at 100%
            strength = Math.min(strength, 100);
            strengthBar.style.width = strength + '%';
            
            if (strength < 50) {
                strengthBar.style.backgroundColor = '#ff4757';
            } else if (strength < 75) {
                strengthBar.style.backgroundColor = '#ffa502';
            } else {
                strengthBar.style.backgroundColor = '#2ed573';
            }
        });

        // Form validation before submit (client-side)
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const agreeTerms = document.getElementById('agreeTerms').checked;
            
            // Basic validation
            if (password.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter!');
                return;
            }
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Password tidak cocok!');
                return;
            }
            
            if (!agreeTerms) {
                e.preventDefault();
                alert('Anda harus menyetujui syarat dan ketentuan!');
                return;
            }
        });

        // Auto-select gender on page load if previously selected
        window.onload = function() {
            <?php if (isset($_POST['kelamin'])): ?>
                selectGender('<?php echo $_POST['kelamin']; ?>');
            <?php endif; ?>
        };
    </script>
</body>
</html>