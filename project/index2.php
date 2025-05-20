<?php
session_start();

$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "web_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

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

// Login handling
if (isset($_POST['login'])) {
    // Get form data
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);
    
    // Validation
    if (empty($username)) {
        $error .= "Username is required.<br>";
    }
    
    if (empty($password)) {
        $error .= "Password is required.<br>";
    }
    
    // If no errors, check credentials
    if (empty($error)) {
        // Check if username exists
        $stmt = $conn->prepare("SELECT * FROM sign_up2 WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Authentication successful, start session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['logged_in'] = true;
                $_SESSION['login_success'] = true; // Set flag for popup
                
                // Redirect to home page
                header("Location: index.php");
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
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Shopfinity</title>
    <link rel="stylesheet" href="/project/index2.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <style>
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
        .login-button {
            background-color: #1464f8;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        .login-button:hover {
            background-color: #042f7e;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
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
            <div class="login-section">
                <h2>Login to Your Account</h2>
                <?php
                if (!empty($error)) {
                    echo '<div class="error-message">' . $error . '</div>';
                }
                ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="login-username">Username:</label>
                        <input type="text" class="form-control" id="login-username" name="username" required>
                    </div>

                    <div class="form-group form-password">
                        <label for="login-password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <img src="/project/photo/img_2_eyes_shut.jpeg" alt="eyes_shut" id="eyeicon">
                    </div>

                    <button type="submit" class="login-button" name="login">Login</button>
                </form>
                <div class="register-link">
                    Don't have an account? <a href="/project/index3.php">Register here</a>
                </div>
            </div>
        </div>
    </main>

    <script>
        let eyeicon = document.getElementById("eyeicon");
        let password = document.getElementById("password");

        eyeicon.onclick = function() {
            if (password.type == "password") {
                password.type = "text";
                eyeicon.src = "/project/photo/img_eyes_open.png";
            } else {
                password.type = "password";
                eyeicon.src = "/project/photo/img_2_eyes_shut.jpeg";
            }
        };
    </script>
</body>
</html>
