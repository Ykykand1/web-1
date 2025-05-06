
<?php


$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "web_database";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


if (isset($_POST['register'])) {
    
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);
    $age = sanitize_input($_POST['age']);
    $email = sanitize_input($_POST['email']);
    
  
    $error = "";
    
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
    
   
    $stmt = $conn->prepare("SELECT * FROM sign_up2 WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $error .= "Username already exists.<br>";
    }
    
    
    if (empty($error)) {
       
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        
        $stmt = $conn->prepare("INSERT INTO sign_up2 (username, password, age, email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $username, $hashed_password, $age, $email);
        
        if ($stmt->execute()) {
            $success = "Registration successful! You can now log in.";
        } else {
            $error = "Error: " . $stmt->error;
        }
        
        $stmt->close();
    }
}

if (isset($_POST['login'])) {
   
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);
    
    
    $error = "";
    
    if (empty($username)) {
        $error .= "Username is required.<br>";
    }
    
    if (empty($password)) {
        $error .= "Password is required.<br>";
    }
    
    
    if (empty($error)) {
        
        $stmt = $conn->prepare("SELECT * FROM sign_up2 WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
           
            if (password_verify($password, $user['password'])) {
                
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['logged_in'] = true;
                
              
                header("Location: index.html");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
        
        $stmt->close();
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register - Shopfinity</title>
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
        .form-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .login-section, .register-section {
            width: 48%;
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
        #eyeicon {
            position: absolute;
            right: 10px;
            top: 35px;
            width: 20px;
            cursor: pointer;
        }
        .login-button, .register-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        .login-button:hover, .register-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">Shopfinity</div>
                <nav class="nav-menu">
                    <a href="/project/index.html">Home</a>
                    <a href="/project/delivery_check.php">Delivery Check</a>
                    <a href="/project/lokacion.php">Stores</a>
                </nav>
                <a href="/project/index.html" class="back-button">← Back</a>
            </div>
        </div>
    </header>

    <main>
                <!-- Register Section -->
                <div class="register-section">
                    <h2>Register</h2>
                    <?php
                    if (isset($error) && isset($_POST['register'])) {
                        echo '<div class="error-message">' . $error . '</div>';
                    }
                    if (isset($success)) {
                        echo '<div class="success-message">' . $success . '</div>';
                    }
                    ?>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="register-username">Username:</label>
                            <input type="text" id="register-username" name="username" required>
                        </div>

                        <div class="form-group">
                            <label for="register-email">Email:</label>
                            <input type="email" id="register-email" name="email" required>
                        </div>

                        <div class="form-group form-password">
                            <label for="register-password">Password:</label>
                            <input type="password" id="register-password" name="password" required>
                            <img src="/project/photo/img_2_eyes_shut.jpeg" alt="eyes_shut" id="register-eyeicon">
                        </div>

                        <div class="form-group">
                            <label for="register-age">Age:</label>
                            <input type="number" id="register-age" name="age" required>
                            <p id="register-display" style="color: crimson"></p>
                        </div>

             <button type="submit" class="register-button" id="register-check" name="register">
             Register
             </button>
          </form>
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