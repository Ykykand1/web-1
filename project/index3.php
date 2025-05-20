<?php
session_start();

$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "web_database";
$port = 3306;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname , $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$error = "";
$success = "";

// Registration handling
if (isset($_POST['register'])) {
    // Get form data
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);
    $age = sanitize_input($_POST['age']);
    $email = sanitize_input($_POST['email']);
    
    // Validation
    if (empty($username)) {
        $error .= "Username is required.<br>";
    }
    
    if (empty($password)) {
        $error .= "Password is required.<br>";
    } elseif (strlen($password) < 6) {
        $error .= "Password must be at least 6 characters.<br>";
    }
    
    if (empty($age)) {
        $error .= "Age is required.<br>";
    } elseif ($age < 18) {
        $error .= "You must be at least 18 years old to register.<br>";
    }
    
    if (empty($email)) {
        $error .= "Email is required.<br>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error .= "Invalid email format.<br>";
    }
    
    // Check if username already exists - Fixed error handling
    $stmt = $conn->prepare("SELECT * FROM sign_up2 WHERE username = ?");
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error .= "Username already exists.<br>";
        }
        
        // If no errors, add the user to the database
        if (empty($error)) {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user into database
            $insert_stmt = $conn->prepare("INSERT INTO sign_up2 (username, password, age, email) VALUES (?, ?, ?, ?)");
            if ($insert_stmt) {
                $insert_stmt->bind_param("ssis", $username, $hashed_password, $age, $email);
                
                if ($insert_stmt->execute()) {
                    $success = "Registration successful! You can now <a href='/project/index2.php'>log in</a>.";
                } else {
                    $error = "Error: " . $insert_stmt->error;
                }
                
                $insert_stmt->close();
            } else {
                $error = "Database error: " . $conn->error;
            }
        }
        
        $stmt->close();
    } else {
        $error = "Database error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Shopfinity</title>
    <link rel="stylesheet" href="/project/index3.css">
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
      crossorigin="anonymous"
    />
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .register-section {
            width: 100%;
            max-width: 500px;
            margin: 30px auto;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
        .success-message {
            color: green;
            margin-bottom: 15px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-password {
            position: relative;
        }
        #register-eyeicon {
            position: absolute;
            right: 10px;
            top: 35px;
            width: 20px;
            cursor: pointer;
        }
        .register-button {
            background-color: #1464f8;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        .register-button:hover {
            background-color: #042f7e;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">Shopfinity</div>
                <nav class="nav-menu">
                    <a href="/project/index.php">Home</a>
                    <a href="/project/delivery_check.php">Delivery Check</a>
                    <a href="/project/lokacion.php">Stores</a>
                </nav>
                <a href="/project/index.php" class="back-button">← Back</a>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <!-- Register Section -->
            <div class="register-section">
                <h2>Create a New Account</h2>
                <?php
                if (!empty($error)) {
                    echo '<div class="error-message">' . $error . '</div>';
                }
                if (!empty($success)) {
                    echo '<div class="success-message">' . $success . '</div>';
                }
                ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="register-username">Username:</label>
                        <input type="text" class="form-control" id="register-username" name="username" required>
                    </div>

                    <div class="form-group">
                        <label for="register-email">Email:</label>
                        <input type="email" class="form-control" id="register-email" name="email" required>
                    </div>

                    <div class="form-group form-password">
                        <label for="register-password">Password:</label>
                        <input type="password" class="form-control" id="register-password" name="password" required>
                        <img src="/project/photo/img_2_eyes_shut.jpeg" alt="eyes_shut" id="register-eyeicon">
                    </div>

                    <div class="form-group">
                        <label for="register-age">Age:</label>
                        <input type="number" class="form-control" id="register-age" name="age" required>
                        <p id="register-display" style="color: crimson"></p>
                    </div>

                    <button type="submit" class="register-button" id="register-check" name="register">
                        Register
                    </button>
                </form>
                <div class="login-link mt-3 text-center">
                    Already have an account? <a href="/project/index2.php">Login here</a>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Age validation for registration
        document.getElementById("register-check").addEventListener("click", function(event) {
            let a = document.getElementById("register-age");
            let b = parseInt(a.value);
            if (b < 18) {
                event.preventDefault();
                document.getElementById("register-display").innerText = "You must be at least 18 years old to register";
            }
        });

        // Password toggle for registration
        let registerEyeIcon = document.getElementById("register-eyeicon");
        let registerPassword = document.getElementById("register-password");

        registerEyeIcon.onclick = function() {
            if (registerPassword.type == "password") {
                registerPassword.type = "text";
                registerEyeIcon.src = "/project/photo/img_eyes_open.png";
            } else {
                registerPassword.type = "password";
                registerEyeIcon.src = "/project/photo/img_2_eyes_shut.jpeg";
            }
        };
    </script>
</body>
</html>