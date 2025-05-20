
<?php
session_start();
$show_popup = false;


if (isset($_SESSION['login_success']) && $_SESSION['login_success'] === true) {
    $show_popup = true;
    
    $_SESSION['login_success'] = false;
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Shopfinity - Infinite Choices. One Destination.</title>
    <link rel="stylesheet" href="/project/index.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
      crossorigin="anonymous"
    />
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
          <div class="search-login">
            <div class="search-bar">
              <input type="text" placeholder="Search for..." />
            </div>
            <a href="/project/index2.php" class="login-btn">Log In</a>
            <a href="/project/index3.php" class="login-btn">Sign Up</a>
          </div>
        </div>
      </div>
    </header>

     <div id="overlay" class="overlay"></div>
    <div id="loginSuccessPopup" class="popup">
        <div class="popup-content">
            <h3>Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?>!</h3>
            <p>You have successfully logged in to your account.</p>
        </div>
        <button id="closePopup" class="popup-close">Close</button>
    </div>

    <main class="container">
      <section class="hero">
        <h1>Shopfinity</h1>
        <p class="hero-tagline">"Infinite Choices. One Destination."</p>
      </section>

      <section class="why-us">
        <h2>Why Us</h2>
        <p>
          At ShopInfinity, we’re more than just an online store — we’re your
          go-to destination for convenience, quality, and unbeatable value. With
          a wide range of top-rated products, fast and secure checkout, and
          lightning-fast delivery, we make shopping simple and stress-free. Our
          customer-first approach means 24/7 support, easy returns, and
          exclusive deals you won’t find anywhere else. Discover the infinite
          possibilities of online shopping — only at ShopInfinity.
        </p>
      </section>

      <section>
        <h2 class="product-heading">Newest Products</h2>
        <div class="product-grid">
          <div class="product-card">
            <div class="product-image">
              <img
                src="/project/photo/leather-shoes-2661249_1920.jpg"
                alt="Product image"
              />
            </div>
            <div class="product-info">
              <span class="product-name">Leather Shoes</span>
              <button class="buy-btn" onclick="clickBtn1()">Buy</button>
            </div>
            <p id="warning1" style="color: rgb(182, 52, 52)"></p>
          </div>

          <div class="product-card">
            <div class="product-image">
              <img
                src="/project/photo/ironing-403074_1920.jpg"
                alt="Product image"
              />
            </div>
            <div class="product-info">
              <span class="product-name">Iron</span>
              <button class="buy-btn" onclick="clickBtn2()">Buy</button>
            </div>
            <p id="warning2" style="color: rgb(182, 52, 52)"></p>
          </div>

          <div class="product-card">
            <div class="product-image">
              <img
                src="/project/photo/ai-generated-8759668_1920.jpg"
                alt="Product image"
              />
            </div>
            <div class="product-info">
              <span class="product-name">Perfume</span>
              <button class="buy-btn" onclick="clickBtn3()">Buy</button>
            </div>
            <p id="warning3" style="color: rgb(182, 52, 52)"></p>
          </div>

          <div class="product-card">
            <div class="product-image">
              <img
                src="/project/photo/apple-1282241_1920.jpg"
                alt="Product image"
              />
            </div>
            <div class="product-info">
              <span class="product-name">Laptop</span>
              <button class="buy-btn" onclick="clickBtn()">Buy</button>
            </div>
            <p id="warning" style="color: rgb(182, 52, 52)"></p>
          </div>

          <div class="product-card">
            <div class="product-image">
              <img
                src="/project/photo/car-604019_1920.jpg"
                alt="Product image"
              />
            </div>
            <div class="product-info">
              <span class="product-name">Car</span>
              <button class="buy-btn" onclick="clickBtn4()">Buy</button>
            </div>
            <p id="warning4" style="color: rgb(182, 52, 52)"></p>
          </div>

          <div class="product-card">
            <div class="product-image">
              <img
                src="/project/photo/basket-2652620_1920.jpg"
                alt="Product image"
              />
            </div>
            <div class="product-info">
              <span class="product-name">Basket</span>
              <button class="buy-btn" onclick="clickBtn5()">Buy</button>
            </div>
            <p id="warning5" style="color: rgb(182, 52, 52)"></p>
          </div>

          <div class="product-card">
            <div class="product-image">
              <img
                src="/project/photo/baby-clothes-5749670_1920.jpg"
                alt="Product image"
              />
            </div>
            <div class="product-info">
              <span class="product-name">Baby Clothes</span>
              <button class="buy-btn" onclick="clickBtn6()">Buy</button>
            </div>
            <p id="warning6" style="color: rgb(182, 52, 52)"></p>
          </div>

          <div class="product-card">
            <div class="product-image">
              <img
                src="/project/photo/sunglasses-1589229_1920.jpg"
                alt="Product image"
              />
            </div>
            <div class="product-info">
              <span class="product-name">Sunglasses</span>
              <button class="buy-btn" onclick="clickBtn7()">Buy</button>
            </div>
            <p id="warning7" style="color: rgb(182, 52, 52)"></p>
          </div>
        </div>
      </section>
    </main>
    <footer>
      <div class="footer-container">
        <p>&copy; 2025 Shopfinity. All rights reserved.</p>
        <div class="subscription">
          <p>Subscribe to our newsletter for the latest updates:</p>
          <form class="subscription-form">
            <input type="email" placeholder="Enter your email" required />
            <button type="submit">Subscribe</button>
            <br />
            <div class="error-email"><p id="check-email"></p></div>
          </form>
        </div>
      </div>
    </footer>
    <script>
      function clickBtn() {
        document.getElementById("warning").innerText =
          "This item isn't available anymore";
      }

      function clickBtn1() {
        document.getElementById("warning1").innerText =
          "This item isn't available anymore";
      }

      function clickBtn2() {
        document.getElementById("warning2").innerText =
          "This item isn't available anymore";
      }

      function clickBtn3() {
        document.getElementById("warning3").innerText =
          "This item isn't available anymore";
      }

      function clickBtn4() {
        document.getElementById("warning4").innerText =
          "This item isn't available anymore";
      }

      function clickBtn5() {
        document.getElementById("warning5").innerText =
          "This item isn't available anymore";
      }

      function clickBtn6() {
        document.getElementById("warning6").innerText =
          "This item isn't available anymore";
      }

      function clickBtn7() {
        document.getElementById("warning7").innerText =
          "This item isn't available anymore";
      }

      const emailInput = document.querySelector('input[type="email"]');
      const submitBtn = document.querySelector('button[type="submit"]');
      const errorMsg = document.getElementById("check-email");

      submitBtn.addEventListener("click", function (e) {
        if (!emailInput.value) {
          e.preventDefault();
          errorMsg.textContent = "Give your email.";
        } else if (!isValidEmail(emailInput.value)) {
          e.preventDefault();
          errorMsg.textContent = "Please give a valid email.";
        } else {
          alert("Thank you for choosing us :) ");
        }

        function isValidEmail(email) {
          const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          return re.test(email);
        }
      });

       function showLoginPopup() {
            document.getElementById('overlay').style.display = 'block';
            const popup = document.getElementById('loginSuccessPopup');
            popup.classList.add('fade-in');
            popup.style.display = 'block';
        }
        
        // Close popup when the close button is clicked
        document.getElementById('closePopup').addEventListener('click', function() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('loginSuccessPopup').style.display = 'none';
        });
        
        // Close popup when clicking outside of it
        document.getElementById('overlay').addEventListener('click', function() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('loginSuccessPopup').style.display = 'none';
        });
        
        // Show popup if user just logged in
        <?php if ($show_popup): ?>
        window.onload = function() {
            showLoginPopup();
        };
        <?php endif; ?>
    </script>
  </body>
</html>
