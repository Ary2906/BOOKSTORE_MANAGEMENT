<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>our reviews</h3>
   <p> <a href="home.php">home</a> / reviews </p>
</div>

<section class="about">

   <div class="about-content-only">
      
      <div class="floating-elements">
         <div class="float-card float-1">
            <div class="card-icon">⭐</div>
            <p class="card-text">4.9/5</p>
            <span class="card-sub">Rating</span>
         </div>
         
         <div class="float-card float-2">
            <div class="card-icon">👥</div>
            <p class="card-text">10K+</p>
            <span class="card-sub">Customers</span>
         </div>
         
         <div class="float-card float-3">
            <div class="card-icon">📦</div>
            <p class="card-text">24hrs</p>
            <span class="card-sub">Delivery</span>
         </div>
      </div>

      <div class="content">
         <h3>why choose lookbook?</h3>
         <p>📚 Discover 50+ premium books across 10 diverse categories</p>
         <p>⭐ Trusted by thousands with expert customer ratings</p>
         <p>🚀 Fast, reliable shipping in 24 hours</p>
         <p>💚 Dedicated customer support available 24/7</p>
         <a href="contact.php" class="btn">contact us</a>
      </div>

   </div>

</section>

<section class="reviews">

   <div class="reviews-header">
      <h2>⭐ Trusted by Thousands</h2>
      <p>Join our community of happy readers who have discovered their favorite books with us. See why customers rate us 4.9/5 stars!</p>
      <div class="stars">
         <i class="fas fa-star"></i>
         <i class="fas fa-star"></i>
         <i class="fas fa-star"></i>
         <i class="fas fa-star"></i>
         <i class="fas fa-star-half-alt"></i>
      </div>
   </div>

   <h1 class="title">✨ Customer Reviews & Testimonials</h1>

   <div class="box-container">

      <div class="box review-card">
         <div class="review-header">
            <img src="images/boy1.jpg" alt="">
            <div class="reviewer-info">
               <h3>Laxman Rao</h3>
               <p class="role">📚 Book Enthusiast</p>
            </div>
         </div>
         <p class="review-text">"LookBook has an excellent collection! The service is outstanding and delivery was faster than expected. Highly recommended for all book lovers!"</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
         </div>
      </div>

      <div class="box review-card">
         <div class="review-header">
            <img src="images/girl1.jpg" alt="">
            <div class="reviewer-info">
               <h3>Deepti Patil</h3>
               <p class="role">📖 Student</p>
            </div>
         </div>
         <p class="review-text">"Vast collection of books across different genres. Perfect for finding exactly what you're looking for. The quality of books is amazing!"</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
      </div>

      <div class="box review-card">
         <div class="review-header">
            <img src="images/girl2.jpeg" alt="">
            <div class="reviewer-info">
               <h3>Akansha Bindiya</h3>
               <p class="role">🎓 Academic</p>
            </div>
         </div>
         <p class="review-text">"Easy website navigation and hassle-free ordering process. The return policy is customer-friendly. Will definitely order again!"</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
      </div>

      <div class="box review-card">
         <div class="review-header">
            <img src="images/boy22.jpg" alt="">
            <div class="reviewer-info">
               <h3>Manav Desai</h3>
               <p class="role">💼 Professional</p>
            </div>
         </div>
         <p class="review-text">"Customer support is absolutely top notch! They responded to my queries within hours. Best online bookstore experience ever!"</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
         </div>
      </div>

      <div class="box review-card">
         <div class="review-header">
            <img src="images/girl3.jpg" alt="">
            <div class="reviewer-info">
               <h3>Disha Patni</h3>
               <p class="role">✈️ Traveler</p>
            </div>
         </div>
         <p class="review-text">"Incredible delivery speed! I was amazed at how quickly my order arrived. Books were perfectly packaged. 10/10 experience!"</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
         </div>
      </div>

      <div class="box review-card">
         <div class="review-header">
            <img src="images/act.jpg" alt="">
            <div class="reviewer-info">
               <h3>Vashnavi Jotikar</h3>
               <p class="role">💰 Smart Shopper</p>
            </div>
         </div>
         <p class="review-text">"Great prices compared to other bookstores. Regular discounts and offers make it very affordable. Love shopping here!"</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
      </div>

   </div>

</section>

<section class="achievements">

   <h1 class="title">🏆 Why Our Customers Love Us</h1>

   <div class="achievements-container">

      <div class="achievement-card">
         <div class="achievement-icon">
            <i class="fas fa-users"></i>
         </div>
         <h3>10,000+</h3>
         <p>Happy Customers</p>
         <span class="subtitle">Serving book lovers nationwide</span>
      </div>

      <div class="achievement-card">
         <div class="achievement-icon">
            <i class="fas fa-book"></i>
         </div>
         <h3>50+</h3>
         <p>Premium Books</p>
         <span class="subtitle">Carefully selected titles</span>
      </div>

      <div class="achievement-card">
         <div class="achievement-icon">
            <i class="fas fa-star"></i>
         </div>
         <h3>4.9/5</h3>
         <p>Average Rating</p>
         <span class="subtitle">Based on customer reviews</span>
      </div>

      <div class="achievement-card">
         <div class="achievement-icon">
            <i class="fas fa-truck"></i>
         </div>
         <h3>24hrs</h3>
         <p>Fast Delivery</p>
         <span class="subtitle">In most major cities</span>
      </div>

      <div class="achievement-card">
         <div class="achievement-icon">
            <i class="fas fa-redo"></i>
         </div>
         <h3>100%</h3>
         <p>Easy Returns</p>
         <span class="subtitle">30-day hassle-free returns</span>
      </div>

      <div class="achievement-card">
         <div class="achievement-icon">
            <i class="fas fa-headset"></i>
         </div>
         <h3>24/7</h3>
         <p>Customer Support</p>
         <span class="subtitle">Always here to help</span>
      </div>

   </div>

</section>







<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>