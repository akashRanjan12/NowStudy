<?php
session_start();
require_once '../config/database.php';

// Debug: Check if database connection exists
if (!isset($pdo)) {
    die("Database connection not established. Check your database.php file");
}

// Debug: Display all session data
error_log("Session data: " . print_r($_SESSION, true));

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Login logic (your existing code)
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        
        try {
            $stmt = $pdo->prepare("SELECT * FROM userdetails WHERE `e-mail` = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['e-mail'];
                $_SESSION['user_name'] = $user['Name'];
                
                header('Location: home.php');
                exit();
            } else {
                $login_error = "Invalid email or password!";
            }
        } catch (PDOException $e) {
            $login_error = "Database error: " . $e->getMessage();
        }
    }
    
    if (isset($_POST['register'])) {
        // Registration logic
        $name = filter_var($_POST['Name'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['e-mail'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Validation
        if (empty($name) || empty($email) || empty($password)) {
            $registration_error = "All fields are required!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $registration_error = "Invalid email format!";
        } elseif ($password !== $confirm_password) {
            $registration_error = "Passwords do not match!";
        } elseif (strlen($password) < 6) {
            $registration_error = "Password must be at least 6 characters long!";
        } else {
            try {
                // Check if email already exists
                $stmt = $pdo->prepare("SELECT id FROM userdetails WHERE `e-mail` = ?");
                $stmt->execute([$email]);
                
                if ($stmt->fetch()) {
                    $registration_error = "Email already registered!";
                } else {
                    // Generate OTP
                    $otp = rand(100000, 999999);
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Store registration data in session
                    $_SESSION['registration_data'] = [
                        'name' => $name,
                        'email' => $email,
                        'password' => $hashed_password,
                        'otp' => $otp,
                        'otp_expiry' => time() + 600
                    ];
                    
                    error_log("OTP generated: " . $otp . " for email: " . $email);
                    
                    // For testing - display OTP on screen (remove in production)
                    $test_otp_display = $otp;
                    
                    // Send OTP email
                    if (sendOtpEmail($email, $name, $otp)) {
                        $show_otp_modal = true;
                        $otp_success = "OTP sent to your email!";
                    } else {
                        $registration_error = "Failed to send OTP. Please try again.";
                    }
                }
            } catch (PDOException $e) {
                $registration_error = "Database error: " . $e->getMessage();
                error_log("Database error in registration: " . $e->getMessage());
            }
        }
    }
    
    if (isset($_POST['verify_otp'])) {
        error_log("OTP verification attempt");
        
        // Get OTP from all input fields
        $entered_otp = '';
        for ($i = 0; $i < 6; $i++) {
            if (isset($_POST['otp'][$i])) {
                $entered_otp .= $_POST['otp'][$i];
            }
        }
        
        error_log("Entered OTP: " . $entered_otp);
        
        if (isset($_SESSION['registration_data'])) {
            $reg_data = $_SESSION['registration_data'];
            error_log("Stored OTP: " . $reg_data['otp']);
            error_log("Session data: " . print_r($_SESSION['registration_data'], true));
            
            if (time() > $reg_data['otp_expiry']) {
                $otp_error = "OTP has expired! Please register again.";
                unset($_SESSION['registration_data']);
                error_log("OTP expired");
            } elseif ($entered_otp == $reg_data['otp']) {
                error_log("OTP verified successfully");
                
                // OTP verified, create user account
                try {
                    $stmt = $pdo->prepare("INSERT INTO userdetails (Name, `e-mail`, password) VALUES (?, ?, ?)");
                    
                    if ($stmt->execute([$reg_data['name'], $reg_data['email'], $reg_data['password']])) {
                        $last_id = $pdo->lastInsertId();
                        error_log("User created with ID: " . $last_id);
                        
                        $registration_success = true;
                        unset($_SESSION['registration_data']);
                        
                        // Show success message and switch to login after 2 seconds
                        echo "<script>
                            setTimeout(function() {
                                showLogin();
                            }, 2000);
                        </script>";
                    } else {
                        $registration_error = "Failed to create account. Please try again.";
                        error_log("Failed to execute INSERT query");
                    }
                } catch (PDOException $e) {
                    $registration_error = "Database error: " . $e->getMessage();
                    error_log("Database error in OTP verification: " . $e->getMessage());
                }
            } else {
                $otp_error = "Invalid OTP! Please try again.";
                error_log("OTP mismatch");
            }
        } else {
            $otp_error = "Registration session expired! Please register again.";
            error_log("No registration data in session");
        }
    }
    
    if (isset($_POST['resend_otp'])) {
        if (isset($_SESSION['registration_data'])) {
            $reg_data = $_SESSION['registration_data'];
            $new_otp = rand(100000, 999999);
            
            $_SESSION['registration_data']['otp'] = $new_otp;
            $_SESSION['registration_data']['otp_expiry'] = time() + 600;
            
            error_log("New OTP generated: " . $new_otp);
            
            // For testing - display new OTP
            $test_otp_display = $new_otp;
            
            if (sendOtpEmail($reg_data['email'], $reg_data['name'], $new_otp)) {
                $otp_success = "New OTP sent to your email!";
            } else {
                $otp_error = "Failed to resend OTP. Please try again.";
            }
        }
    }
}

// Email function (same as before)
function sendOtpEmail($email, $name, $otp) {
    try {
        $mail = new PHPMailer(true);
        
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'akash12ranjan@gmail.com';
        $mail->Password = 'qseeejorbuwqznqi';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        $mail->setFrom('akash12ranjan@gmail.com', 'NoteShare');
        $mail->addAddress($email, $name);
        
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for NoteShare Registration';
        $mail->Body = "
            <h2>NoteShare Registration OTP</h2>
            <p>Hello $name,</p>
            <p>Your OTP for registration is: <strong>$otp</strong></p>
            <p>This OTP will expire in 10 minutes.</p>
            <br>
            <p>Best regards,<br>NoteShare Team</p>
        ";
        
        $mail->AltBody = "Your OTP for NoteShare registration is: $otp. This OTP will expire in 10 minutes.";
        
        return $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $e->getMessage());
        return false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NoteShare</title>
    <link rel="stylesheet" href="loginstyle.css">
    <link rel="stylesheet" href="otpstyle.css">
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="login-container">
        <div class="form-card">
            <?php if (isset($registration_success) && $registration_success): ?>
                <div class="success-message">
                    ✅ Account created successfully! Please login.
                </div>
            <?php endif; ?>

            <?php if (isset($login_error)): ?>
                <div class="error-message">
                    ❌ <?php echo htmlspecialchars($login_error); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($registration_error)): ?>
                <div class="error-message">
                    ❌ <?php echo htmlspecialchars($registration_error); ?>
                </div>
            <?php endif; ?>

            <!-- Test OTP Display -->
            <?php if (isset($test_otp_display)): ?>
                <div class="test-otp">
                    🔧 TESTING: Your OTP is: <strong><?php echo $test_otp_display; ?></strong>
                </div>
            <?php endif; ?>

            <div class="form-toggle">
                <div class="toggle-slider" id="toggleSlider"></div>
                <button type="button" class="toggle-btn active" onclick="showLogin()">Login</button>
                <button type="button" class="toggle-btn" onclick="showRegister()">Create Account</button>
            </div>

            <!-- Login Form -->
            <div class="form-section active" id="loginSection">
                <h2 class="form-title">Welcome Back</h2>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <span class="input-icon">📧</span>
                        <input type="email" class="form-input" name="email" placeholder="Enter your email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <span class="input-icon">🔒</span>
                        <input type="password" class="form-input" name="password" placeholder="Enter your password" required>
                    </div>
                    
                    <button type="submit" name="login" class="submit-btn">Sign In</button>
                </form>

                <div class="forgot-link">
                    <a href="forgot_password.php">Forgot your password?</a>
                </div>

                <div class="divider">
                    <span>Or continue with</span>
                </div>

                <div class="back-home">
                    <a href="../index.php">← Back to Home</a>
                </div>
            </div>

            <!-- Register Form -->
            <div class="form-section" id="registerSection">
                <h2 class="form-title">Create Account</h2>
                
                <form method="POST" action="" id="registerForm">
                    <div class="form-group">
                        <span class="input-icon">👤</span>
                        <input type="text" class="form-input" name="Name" placeholder="Full Name" required value="<?php echo isset($_POST['Name']) ? htmlspecialchars($_POST['Name']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <span class="input-icon">📧</span>
                        <input type="email" class="form-input" name="e-mail" placeholder="Email Address" required value="<?php echo isset($_POST['e-mail']) ? htmlspecialchars($_POST['e-mail']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <span class="input-icon">🔒</span>
                        <input type="password" class="form-input" name="password" placeholder="Create Password" required>
                    </div>
                    
                    <div class="form-group">
                        <span class="input-icon">🔒</span>
                        <input type="password" class="form-input" name="confirm_password" placeholder="Confirm Password" required>
                    </div>
                    
                    <button type="submit" name="register" class="submit-btn">Create Account</button>
                </form>

                <div class="divider">
                    <span>Or sign up with</span>
                </div>

                <div class="back-home">
                    <a href="../index.php">← Back to Home</a>
                </div>
            </div>
        </div>
    </div>

    <!-- OTP Modal -->
    <div class="otp-modal <?php echo isset($show_otp_modal) && $show_otp_modal ? 'active' : ''; ?>" id="otpModal">
        <div class="otp-container">
            <h2 class="otp-title">Verify Your Email</h2>
            <p class="otp-message">We've sent a 6-digit OTP to your email address. Please enter it below to complete your registration.</p>
            
            <?php if (isset($otp_error)): ?>
                <div class="error-message">
                    ❌ <?php echo htmlspecialchars($otp_error); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($otp_success)): ?>
                <div class="success-message">
                    ✅ <?php echo htmlspecialchars($otp_success); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="otpForm">
                <div class="otp-input-group">
                    <input type="text" class="otp-input" name="otp[]" maxlength="1" required oninput="moveToNext(this, 1)">
                    <input type="text" class="otp-input" name="otp[]" maxlength="1" required oninput="moveToNext(this, 2)">
                    <input type="text" class="otp-input" name="otp[]" maxlength="1" required oninput="moveToNext(this, 3)">
                    <input type="text" class="otp-input" name="otp[]" maxlength="1" required oninput="moveToNext(this, 4)">
                    <input type="text" class="otp-input" name="otp[]" maxlength="1" required oninput="moveToNext(this, 5)">
                    <input type="text" class="otp-input" name="otp[]" maxlength="1" required oninput="moveToNext(this, 6)">
                </div>
                
                <div class="otp-timer" id="otpTimer">
                    OTP expires in: <span id="timer">10:00</span>
                </div>
                
                <div class="otp-actions">
                    <button type="submit" name="verify_otp" class="otp-btn otp-verify">Verify OTP</button>
                    <button type="submit" name="resend_otp" class="otp-btn otp-resend" id="resendBtn" disabled>Resend OTP</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showLogin() {
            document.getElementById('loginSection').classList.add('active');
            document.getElementById('registerSection').classList.remove('active');
            document.getElementById('toggleSlider').classList.remove('register');
            document.querySelectorAll('.toggle-btn')[0].classList.add('active');
            document.querySelectorAll('.toggle-btn')[1].classList.remove('active');
        }

        function showRegister() {
            document.getElementById('registerSection').classList.add('active');
            document.getElementById('loginSection').classList.remove('active');
            document.getElementById('toggleSlider').classList.add('register');
            document.querySelectorAll('.toggle-btn')[1].classList.add('active');
            document.querySelectorAll('.toggle-btn')[0].classList.remove('active');
        }

        // OTP Input handling
        function moveToNext(input, nextIndex) {
            if (input.value.length === 1) {
                const inputs = document.querySelectorAll('.otp-input');
                if (nextIndex < inputs.length) {
                    inputs[nextIndex].focus();
                }
            }
        }

        // Timer for OTP
        let timeLeft = 600;
        const timerElement = document.getElementById('timer');
        const resendBtn = document.getElementById('resendBtn');

        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft > 0) {
                timeLeft--;
                setTimeout(updateTimer, 1000);
            } else {
                resendBtn.disabled = false;
                timerElement.textContent = 'OTP expired';
                timerElement.style.color = '#c62828';
            }
        }

        // Start timer if OTP modal is active
        <?php if (isset($show_otp_modal) && $show_otp_modal): ?>
        updateTimer();
        <?php endif; ?>

        // Auto-focus first OTP input when modal opens
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($show_otp_modal) && $show_otp_modal): ?>
            const firstOtpInput = document.querySelector('.otp-input');
            if (firstOtpInput) {
                firstOtpInput.focus();
            }
            <?php endif; ?>
        });
    </script>
</body>
</html>