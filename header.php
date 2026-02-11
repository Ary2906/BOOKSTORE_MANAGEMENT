<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <div class="header-1">
      <div class="flex">
         <div class="social-links">
            <span class="social-label">Follow us:</span>
            <a href="#" class="social-icon fab fa-facebook-f" title="Facebook"></a>
            <a href="#" class="social-icon fab fa-twitter" title="Twitter"></a>
            <a href="#" class="social-icon fab fa-instagram" title="Instagram"></a>
         </div>
         <div class="auth-links">
            <a href="login.php" class="auth-btn login-btn">
               <i class="fas fa-sign-in-alt"></i> Login
            </a>
            <a href="register.php" class="auth-btn register-btn">
               <i class="fas fa-user-plus"></i> Register
            </a>
         </div>
      </div>
   </div>

   <div class="header-2">
      <div class="flex">
         <div class="menu-toggle" id="menu-toggle">
            <span></span>
            <span></span>
            <span></span>
         </div>

         <a href="home.php" class="logo">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline-block; margin-right: 0.8rem;">
               <rect width="40" height="40" rx="8" fill="url(#gradient)"/>
               <path d="M12 10H28V30H12V10Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
               <path d="M16 14V26" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
               <path d="M20 14V26" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
               <path d="M24 14V26" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
               <defs>
                  <linearGradient id="gradient" x1="0" y1="0" x2="40" y2="40">
                     <stop offset="0%" style="stop-color:#059669;stop-opacity:1" />
                     <stop offset="100%" style="stop-color:#10B981;stop-opacity:1" />
                  </linearGradient>
               </defs>
            </svg>
            LOOKBOOK
         </a>

         <nav class="navbar">
            <a href="home.php"><i class="fas fa-home"></i> Home</a>
            <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
            <a href="shop.php"><i class="fas fa-store"></i> Shop</a>
            <a href="wishlist.php"><i class="fas fa-heart"></i> Wishlist</a>
            <a href="contact.php"><i class="fas fa-envelope"></i> Contact</a>
            <a href="orders.php"><i class="fas fa-box"></i> Orders</a>
         </nav>

         <div class="icons">
            <a href="search_page.php" class="search-icon" title="Search"><i class="fas fa-search"></i></a>
            <div id="user-btn" class="user-icon" title="User"><i class="fas fa-user-circle"></i></div>
            <a href="wishlist.php" class="wishlist-icon" title="Wishlist">
               <i class="fas fa-heart"></i>
            </a>
            <?php
               $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
               $cart_rows_number = mysqli_num_rows($select_cart_number); 
            ?>
            <a href="cart.php" class="cart-icon" title="Cart">
               <i class="fas fa-shopping-bag"></i>
               <span class="cart-badge">(<?php echo $cart_rows_number; ?>)</span>
            </a>
         </div>

         <div class="user-box">
            <p>username : <span><?php echo $_SESSION['user_name']; ?></span></p>
            <p>email : <span><?php echo $_SESSION['user_email']; ?></span></p>
            <a href="logout.php" class="delete-btn">logout</a>
         </div>
      </div>
   </div>

</header>

<script>
   const menuToggle = document.getElementById('menu-toggle');
   const navbar = document.querySelector('.navbar');
   const userBtn = document.getElementById('user-btn');
   const userBox = document.querySelector('.user-box');

   menuToggle.addEventListener('click', function() {
      this.classList.toggle('active');
      navbar.classList.toggle('active');
   });

   // User profile button functionality
   userBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      userBox.classList.toggle('active');
   });

   // Close user box when clicking outside
   document.addEventListener('click', function(e) {
      if (!userBox.contains(e.target) && !userBtn.contains(e.target)) {
         userBox.classList.remove('active');
      }
   });

   // Close menu when clicking on a link
   document.querySelectorAll('.navbar a').forEach(link => {
      link.addEventListener('click', function() {
         menuToggle.classList.remove('active');
         navbar.classList.remove('active');
      });
   });
</script>