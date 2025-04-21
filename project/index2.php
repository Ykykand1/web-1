<?php 

include("C:/Users/anila/OneDrive/Desktop/web 1/project/lidhja.php");


?>



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
            <a href="#">Services</a>
            <a href="#">Products</a>
          </nav>
          <a href="/project/index.html" class="back-button">← Back</a>
        </div>
      </div>
    </header>

    <main>
      <div class="login-container">
        <div class="login-header">Log In</div>
        <div class="login-form">
          <form method="post" action="/project/login.php">
            <div class="form-group">
              <label for="username">Username:</label>
              <input type="text" id="username" name="username" required />
            </div>

            <div class="form-group form-password">
              <label for="password">Password:</label>
              <input type="password" id="password" name="password" required />
              <img src="/project/img_2_eyes_shut.jpeg" alt="eyes_shut" id="eyeicon">
            </div>

            <div class="form-group">
              <label for="age">Age:</label>
              <input type="number" id="age" name="age" />
              <p id="display" style="color: crimson"></p>
            </div>

            <div class="forgot-password">
              <a href="#">Forgot password?</a>
            </div>

            <button type="submit" class="login-button" id="check" name="submit">
              Log In
            </button>
          </form>
        </div>
      </div>
    </main>

    <script>
      document.getElementById("check").addEventListener("click", function (event) {
        let a = document.getElementById("age");
        let b = parseInt(a.value);
        if (b < 18) {
          event.preventDefault();
          document.getElementById("display").innerText = "You can't log in";
        }
      });

      let eyeicon = document.getElementById("eyeicon");
      let fjalekalimi = document.getElementById("password");

      eyeicon.onclick = function () {
        if (fjalekalimi.type == "password") {
          fjalekalimi.type = "text";
          eyeicon.src = "/project/img_eyes_open.png";
        } else {
          fjalekalimi.type = "password";
          eyeicon.src = "/project/img_2_eyes_shut.jpeg";
        }
      };
    </script>
  </body>
</html>