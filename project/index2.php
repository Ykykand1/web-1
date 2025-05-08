<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Shopfinity</title>
    <link rel="stylesheet" href="/project/index2.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
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
      let fjalekalimi = document.getElementById("password");

      eyeicon.onclick = function () {
        if (fjalekalimi.type == "password") {
          fjalekalimi.type = "text";
          eyeicon.src = "/project/photo/img_eyes_open.png";
        } else {
          fjalekalimi.type = "password";
          eyeicon.src = "/project/photo/img_2_eyes_shut.jpeg";
        }
      };
    </script>
  </body>
</html>