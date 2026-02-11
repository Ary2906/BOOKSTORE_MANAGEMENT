<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['add_to_cart'])){

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';
   }else{
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'product added to cart!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>LookBook - Discover Your Style</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <!-- chatbot styles -->
   <link rel="stylesheet" href="css/chatbot_style.css">

   <style>
      /* Floating Pictures Animations */
      @keyframes float1 {
         0%, 100% { transform: translateY(0px) rotate(3deg); }
         50% { transform: translateY(-25px) rotate(-2deg); }
      }

      @keyframes float2 {
         0%, 100% { transform: translateY(-5px) rotate(-3deg); }
         50% { transform: translateY(15px) rotate(3deg); }
      }

      @keyframes float3 {
         0%, 100% { transform: translateY(5px) rotate(2deg); }
         50% { transform: translateY(-20px) rotate(-2deg); }
      }

      @keyframes float4 {
         0%, 100% { transform: translateY(-8px) rotate(-2deg); }
         50% { transform: translateY(18px) rotate(2deg); }
      }

      @keyframes float5 {
         0%, 100% { transform: translateY(3px) rotate(3deg); }
         50% { transform: translateY(-22px) rotate(-3deg); }
      }

      @keyframes glow {
         0%, 100% { box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3); }
         50% { box-shadow: 0 15px 45px rgba(59, 130, 246, 0.5); }
      }

      /* Home section with floating images */
      .home {
         position: relative;
         padding: 80px 20px;
         background: linear-gradient(135deg, #ffffff 0%, #f3f4f6 100%);
         overflow: hidden;
         min-height: 550px;
      }

      .home::before {
         content: '';
         position: absolute;
         top: 0;
         right: 0;
         width: 400px;
         height: 400px;
         background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
         border-radius: 50%;
         z-index: 1;
      }

      .home-grid {
         display: grid;
         grid-template-columns: 1fr 1.2fr;
         gap: 60px;
         max-width: 1400px;
         margin: 0 auto;
         align-items: center;
         position: relative;
         z-index: 2;
      }

      .home-content {
         color: #1f2937;
         z-index: 3;
      }

      .home-title {
         font-size: 3.2em;
         margin-bottom: 25px;
         font-weight: 900;
         line-height: 1.2;
         background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
         -webkit-background-clip: text;
         -webkit-text-fill-color: transparent;
         background-clip: text;
      }

      .home-subtitle {
         font-size: 1.15em;
         margin-bottom: 35px;
         color: #4b5563;
         line-height: 1.7;
         font-weight: 500;
      }

      .home-features {
         display: flex;
         gap: 20px;
         margin-bottom: 35px;
         flex-wrap: wrap;
      }

      .feature-item {
         display: flex;
         align-items: center;
         gap: 10px;
         font-size: 1em;
         background: white;
         color: #1f2937;
         padding: 12px 22px;
         border-radius: 50px;
         box-shadow: 0 4px 15px rgba(59, 130, 246, 0.1);
         font-weight: 600;
      }

      .feature-icon {
         font-size: 1.4em;
      }

      .btn-home-primary {
         display: inline-block;
         padding: 16px 45px;
         background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
         color: white;
         text-decoration: none;
         border-radius: 50px;
         font-weight: 700;
         font-size: 1.05em;
         transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
         cursor: pointer;
         box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
      }

      .btn-home-primary:hover {
         transform: translateY(-4px);
         box-shadow: 0 12px 30px rgba(59, 130, 246, 0.4);
      }

      /* Floating Pictures Container */
      .floating-images {
         position: relative;
         height: 450px;
         display: flex;
         justify-content: center;
         align-items: center;
      }

      .floating-book {
         position: absolute;
         width: 140px;
         height: 200px;
         border-radius: 12px;
         overflow: hidden;
         background: white;
         animation: glow 3s ease-in-out infinite;
      }

      .floating-book:nth-child(1) {
         top: 30px;
         left: 0;
         animation: float1 4s ease-in-out infinite, glow 3s ease-in-out infinite;
         z-index: 5;
      }

      .floating-book:nth-child(2) {
         top: 50px;
         left: 140px;
         animation: float2 5s ease-in-out infinite, glow 3s ease-in-out infinite;
         z-index: 4;
      }

      .floating-book:nth-child(3) {
         top: 80px;
         left: 280px;
         animation: float3 4.5s ease-in-out infinite, glow 3s ease-in-out infinite;
         z-index: 3;
      }

      .floating-book:nth-child(4) {
         top: 120px;
         left: 420px;
         animation: float4 5.2s ease-in-out infinite, glow 3s ease-in-out infinite;
         z-index: 2;
      }

      .floating-book:nth-child(5) {
         top: 180px;
         left: 200px;
         animation: float5 4.8s ease-in-out infinite, glow 3s ease-in-out infinite;
         z-index: 1;
      }

      .floating-book img {
         width: 100%;
         height: 100%;
         object-fit: cover;
      }

      /* Category Button Section */
      .category-buttons-section {
         padding: 70px 20px;
         background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
      }

      .category-buttons-section h2 {
         text-align: center;
         font-size: 2.8em;
         margin-bottom: 60px;
         color: #1f2937;
         font-weight: 900;
      }

      .category-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
         gap: 25px;
         max-width: 1400px;
         margin: 0 auto;
      }

      .category-btn {
         padding: 0;
         border: none;
         border-radius: 15px;
         cursor: pointer;
         text-decoration: none;
         color: #1f2937;
         transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
         display: flex;
         flex-direction: column;
         position: relative;
         overflow: hidden;
         background: white;
         box-shadow: 0 8px 25px rgba(0,0,0,0.1);
         min-height: 200px;
      }

      .category-btn span {
         position: relative;
         z-index: 1;
      }

      .category-btn:hover {
         transform: translateY(-10px);
         box-shadow: 0 20px 50px rgba(59, 130, 246, 0.3);
      }

      .category-card-header {
         flex: 1;
         background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
         color: white;
         padding: 30px 25px;
         display: flex;
         align-items: center;
         justify-content: center;
         font-size: 1.3em;
         font-weight: 700;
         text-align: center;
      }

      .category-card-body {
         padding: 20px 25px;
         flex: 1;
         display: flex;
         flex-direction: column;
         justify-content: space-between;
      }

      .category-card-desc {
         font-size: 0.95em;
         color: #666;
         margin-bottom: 15px;
         line-height: 1.5;
      }

      .category-card-btn {
         display: inline-block;
         padding: 12px 30px;
         background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
         color: white;
         text-decoration: none;
         border-radius: 50px;
         font-weight: 700;
         font-size: 0.95em;
         transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
         cursor: pointer;
         box-shadow: 0 6px 15px rgba(59, 130, 246, 0.3);
      }

      .category-card-btn:hover {
         transform: translateY(-2px);
         box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
         background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
      }

      /* Carousel for Featured Books */
      .carousel-container {
         position: relative;
         margin: 0 auto;
         max-width: 1400px;
         padding: 0 50px;
      }

      .carousel-track {
         display: flex;
         gap: 25px;
         overflow-x: auto;
         padding: 20px 0;
         scroll-behavior: smooth;
         scrollbar-width: thin;
         scrollbar-color: #3b82f6 #e0e7ff;
      }

      .carousel-track::-webkit-scrollbar {
         height: 8px;
      }

      .carousel-track::-webkit-scrollbar-track {
         background: #e0e7ff;
         border-radius: 10px;
      }

      .carousel-track::-webkit-scrollbar-thumb {
         background: #3b82f6;
         border-radius: 10px;
      }

      .carousel-track::-webkit-scrollbar-thumb:hover {
         background: #2563eb;
      }

      .carousel-slide {
         flex: 0 0 auto;
         width: 200px;
      }

      .carousel-btn {
         position: absolute;
         top: 50%;
         transform: translateY(-50%);
         background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
         color: white;
         border: none;
         width: 45px;
         height: 45px;
         border-radius: 50%;
         cursor: pointer;
         font-size: 1.2em;
         transition: all 0.3s ease;
         box-shadow: 0 6px 15px rgba(59, 130, 246, 0.3);
         z-index: 10;
      }

      .carousel-btn:hover {
         transform: translateY(-50%) scale(1.1);
         box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
      }

      .carousel-btn.prev {
         left: 0;
      }

      .carousel-btn.next {
         right: 0;
      }

      /* Trending Section with Carousel */
      .trending-section {
         padding: 70px 20px;
         background: linear-gradient(135deg, #f0f4f8 0%, #fff 100%);
      }

      .section-title {
         text-align: center;
         font-size: 2.8em;
         margin-bottom: 60px;
         color: #1f2937;
         font-weight: 900;
      }

      .trending-carousel-container {
         position: relative;
         margin: 0 auto;
         max-width: 1500px;
         padding: 0 50px;
      }

      .trending-track {
         display: flex;
         gap: 25px;
         overflow-x: auto;
         padding: 20px 0;
         scroll-behavior: smooth;
         scrollbar-width: thin;
         scrollbar-color: #3b82f6 #e0e7ff;
      }

      .trending-track::-webkit-scrollbar {
         height: 8px;
      }

      .trending-track::-webkit-scrollbar-track {
         background: #e0e7ff;
         border-radius: 10px;
      }

      .trending-track::-webkit-scrollbar-thumb {
         background: #3b82f6;
         border-radius: 10px;
      }

      .trending-track::-webkit-scrollbar-thumb:hover {
         background: #2563eb;
      }

      .trending-slide {
         flex: 0 0 auto;
         width: 280px;
         height: 350px;
      }

      .trending-card {
         position: relative;
         width: 100%;
         height: 100%;
         border-radius: 15px;
         overflow: hidden;
         display: block;
         text-decoration: none;
         box-shadow: 0 10px 30px rgba(59, 130, 246, 0.15);
         transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
      }

      .trending-card:hover {
         transform: translateY(-15px) scale(1.05);
         box-shadow: 0 25px 60px rgba(59, 130, 246, 0.3);
      }

      .trending-card img {
         width: 100%;
         height: 100%;
         object-fit: cover;
         transition: transform 0.5s ease;
      }

      .trending-card:hover img {
         transform: scale(1.1);
      }

      .trending-overlay {
         position: absolute;
         top: 0;
         left: 0;
         right: 0;
         bottom: 0;
         background: linear-gradient(180deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.7) 100%);
         display: flex;
         flex-direction: column;
         justify-content: flex-end;
         padding: 25px;
         color: white;
         transition: all 0.4s ease;
      }

      .trending-card:hover .trending-overlay {
         background: linear-gradient(180deg, rgba(59,130,246,0.4) 0%, rgba(30,64,175,0.9) 100%);
      }

      .trending-overlay h3 {
         font-size: 1.3em;
         font-weight: 800;
         margin-bottom: 10px;
         margin-top: 0;
      }

      .trending-overlay p {
         font-size: 0.95em;
         opacity: 0.95;
         margin: 0;
         line-height: 1.5;
      }

      .trending-carousel-btn {
         position: absolute;
         top: 50%;
         transform: translateY(-50%);
         background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
         color: white;
         border: none;
         width: 50px;
         height: 50px;
         border-radius: 50%;
         cursor: pointer;
         font-size: 1.3em;
         transition: all 0.3s ease;
         box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
         z-index: 10;
      }

      .trending-carousel-btn:hover {
         transform: translateY(-50%) scale(1.15);
         box-shadow: 0 12px 35px rgba(59, 130, 246, 0.5);
      }

      .trending-carousel-btn.prev {
         left: 0;
      }

      .trending-carousel-btn.next {
         right: 0;
      }

      /* Reading Tips Section with Enhanced Animations */
      .reading-tips-section {
         padding: 70px 20px;
         background: #ffffff;
      }

      .reading-tips-section h2 {
         text-align: center;
         font-size: 2.8em;
         margin-bottom: 60px;
         color: #1f2937;
         font-weight: 900;
      }

      .tips-carousel-container {
         position: relative;
         margin: 0 auto;
         max-width: 1500px;
         padding: 0 50px;
      }

      .tips-track {
         display: flex;
         gap: 25px;
         overflow-x: auto;
         padding: 20px 0;
         scroll-behavior: smooth;
         scrollbar-width: thin;
         scrollbar-color: #3b82f6 #e0e7ff;
      }

      .tips-track::-webkit-scrollbar {
         height: 8px;
      }

      .tips-track::-webkit-scrollbar-track {
         background: #e0e7ff;
         border-radius: 10px;
      }

      .tips-track::-webkit-scrollbar-thumb {
         background: #3b82f6;
         border-radius: 10px;
      }

      .tips-track::-webkit-scrollbar-thumb:hover {
         background: #2563eb;
      }

      .tips-slide {
         flex: 0 0 auto;
         width: 320px;
      }

      .tip-card {
         background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
         padding: 35px;
         border-radius: 15px;
         color: white;
         transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
         box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);
         border-left: 5px solid rgba(255,255,255,0.3);
         height: 100%;
         display: flex;
         flex-direction: column;
         justify-content: space-between;
      }

      .tip-card:hover {
         transform: translateY(-15px) scale(1.05);
         box-shadow: 0 25px 60px rgba(59, 130, 246, 0.4);
      }

      .tip-number {
         font-size: 2.8em;
         font-weight: 900;
         margin-bottom: 15px;
         opacity: 0.85;
      }

      .tip-title {
         font-size: 1.4em;
         font-weight: 800;
         margin-bottom: 15px;
      }

      .tip-description {
         font-size: 1em;
         line-height: 1.7;
         opacity: 0.95;
      }

      .tips-carousel-btn {
         position: absolute;
         top: 50%;
         transform: translateY(-50%);
         background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
         color: white;
         border: none;
         width: 50px;
         height: 50px;
         border-radius: 50%;
         cursor: pointer;
         font-size: 1.3em;
         transition: all 0.3s ease;
         box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
         z-index: 10;
      }

      .tips-carousel-btn:hover {
         transform: translateY(-50%) scale(1.15);
         box-shadow: 0 12px 35px rgba(59, 130, 246, 0.5);
      }

      .tips-carousel-btn.prev {
         left: 0;
      }

      .tips-carousel-btn.next {
         right: 0;
      }

      /* Bookstore Insights & Statistics Section */
      .insights-section {
         padding: 80px 20px;
         background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
         color: #1f2937;
         position: relative;
         overflow: hidden;
      }

      .insights-section::before {
         content: '';
         position: absolute;
         top: -50%;
         right: -10%;
         width: 600px;
         height: 600px;
         background: radial-gradient(circle, rgba(59, 130, 246, 0.08) 0%, transparent 70%);
         border-radius: 50%;
         z-index: 1;
      }

      .insights-section::after {
         content: '';
         position: absolute;
         bottom: -30%;
         left: 0;
         width: 400px;
         height: 400px;
         background: radial-gradient(circle, rgba(59, 130, 246, 0.05) 0%, transparent 70%);
         border-radius: 50%;
         z-index: 1;
      }

      .insights-header {
         text-align: center;
         margin-bottom: 60px;
         position: relative;
         z-index: 2;
      }

      .insights-header h2 {
         font-size: 3em;
         font-weight: 900;
         margin-bottom: 15px;
         color: #1f2937;
      }

      .insights-header p {
         font-size: 1.2em;
         color: #666;
         max-width: 600px;
         margin: 0 auto;
      }

      .insights-container {
         display: grid;
         grid-template-columns: 1fr 1fr;
         gap: 50px;
         max-width: 1400px;
         margin: 0 auto;
         position: relative;
         z-index: 2;
         align-items: center;
      }

      /* Visual Graph */
      .graph-container {
         display: flex;
         align-items: flex-end;
         justify-content: space-around;
         height: 300px;
         gap: 15px;
         background: white;
         padding: 40px;
         border-radius: 20px;
         box-shadow: 0 10px 40px rgba(59, 130, 246, 0.1);
         border: 1px solid rgba(59, 130, 246, 0.1);
      }

      @keyframes barGrow {
         0% { height: 0; opacity: 0; }
         100% { height: var(--height); opacity: 1; }
      }

      @keyframes barFloat {
         0%, 100% { transform: translateY(0); }
         50% { transform: translateY(-10px); }
      }

      .graph-bar {
         flex: 1;
         border-radius: 15px 15px 0 0;
         position: relative;
         animation: barGrow 1.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
         animation-fill-mode: both;
      }

      .graph-bar:hover {
         animation: barFloat 1s ease-in-out infinite;
      }

      .bar-label {
         position: absolute;
         bottom: -30px;
         left: 50%;
         transform: translateX(-50%);
         white-space: nowrap;
         font-size: 0.9em;
         font-weight: 600;
         color: #666;
      }

      .bar-value {
         position: absolute;
         top: -35px;
         left: 50%;
         transform: translateX(-50%);
         font-size: 1.1em;
         font-weight: 800;
         color: #3b82f6;
         opacity: 0;
         animation: fadeIn 1s ease 1.2s forwards;
      }

      @keyframes fadeIn {
         0% { opacity: 0; transform: translateX(-50%) translateY(10px); }
         100% { opacity: 1; transform: translateX(-50%) translateY(0); }
      }

      .graph-bar:nth-child(1) { 
         --height: 180px;
         background: linear-gradient(180deg, #3b82f6 0%, #60a5fa 100%);
         animation-delay: 0s;
      }

      .graph-bar:nth-child(2) { 
         --height: 240px;
         background: linear-gradient(180deg, #2563eb 0%, #3b82f6 100%);
         animation-delay: 0.1s;
      }

      .graph-bar:nth-child(3) { 
         --height: 200px;
         background: linear-gradient(180deg, #1e40af 0%, #2563eb 100%);
         animation-delay: 0.2s;
      }

      .graph-bar:nth-child(4) { 
         --height: 260px;
         background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
         animation-delay: 0.3s;
      }

      .graph-bar:nth-child(5) { 
         --height: 220px;
         background: linear-gradient(180deg, #3b82f6 0%, #60a5fa 100%);
         animation-delay: 0.4s;
      }

      .bar-value:nth-child(2) { animation-delay: 1.2s; }
      .bar-value:nth-child(4) { animation-delay: 1.3s; }
      .bar-value:nth-child(6) { animation-delay: 1.4s; }
      .bar-value:nth-child(8) { animation-delay: 1.5s; }
      .bar-value:nth-child(10) { animation-delay: 1.6s; }

      /* Stats Text Container */
      .stats-text {
         display: flex;
         flex-direction: column;
         gap: 25px;
      }

      .stat-item {
         background: white;
         padding: 25px 30px;
         border-radius: 15px;
         box-shadow: 0 8px 25px rgba(59, 130, 246, 0.08);
         border-left: 5px solid #3b82f6;
         transition: all 0.3s ease;
      }

      .stat-item:hover {
         transform: translateX(10px);
         box-shadow: 0 12px 35px rgba(59, 130, 246, 0.15);
      }

      .stat-item h3 {
         font-size: 1.3em;
         margin: 0 0 8px 0;
         color: #1f2937;
      }

      .stat-item p {
         margin: 0;
         color: #666;
         font-size: 0.95em;
         line-height: 1.6;
      }

      @media (max-width: 768px) {
         .home-grid {
            grid-template-columns: 1fr;
         }

         .home-title {
            font-size: 2.2em;
         }

         .floating-images {
            height: 300px;
         }

         .floating-book {
            width: 100px;
            height: 150px;
         }

         .floating-book:nth-child(1) { left: 10px; }
         .floating-book:nth-child(2) { left: 100px; }
         .floating-book:nth-child(3) { left: 190px; }
         .floating-book:nth-child(4) { left: 280px; }
         .floating-book:nth-child(5) { left: 100px; }

         .category-grid {
            grid-template-columns: repeat(2, 1fr);
         }

         .category-btn {
            min-height: 80px;
         }
      }
   </style>

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="home">

   <div class="home-grid">
      <div class="home-content">
         <h1 class="home-title">Your Gateway to Literary Treasures</h1>
         <p class="home-subtitle">Explore thousands of carefully curated books, from timeless classics to contemporary bestsellers. Find your next favorite read and join millions of book lovers worldwide.</p>
         <div class="home-features">
            <div class="feature-item">
               <span class="feature-icon">📖</span>
               <span class="feature-text">50+ Books</span>
            </div>
            <div class="feature-item">
               <span class="feature-icon">🚚</span>
               <span class="feature-text">Fast Delivery</span>
            </div>
            <div class="feature-item">
               <span class="feature-icon">⭐</span>
               <span class="feature-text">5-Star Reviews</span>
            </div>
         </div>
         <a href="shop.php" class="btn-home-primary">Start Exploring</a>
      </div>

      <div class="floating-images">
         <div class="floating-book">
            <img src="https://picsum.photos/140/200?random=1" alt="Book">
         </div>
         <div class="floating-book">
            <img src="https://picsum.photos/140/200?random=2" alt="Book">
         </div>
         <div class="floating-book">
            <img src="https://picsum.photos/140/200?random=3" alt="Book">
         </div>
         <div class="floating-book">
            <img src="https://picsum.photos/140/200?random=4" alt="Book">
         </div>
         <div class="floating-book">
            <img src="https://picsum.photos/140/200?random=5" alt="Book">
         </div>
      </div>
   </div>

</section>

<!-- Bookstore Insights & Statistics Section -->
<section class="insights-section">
   <div class="insights-header">
      <h2>📊 Why Readers Love Our Bookstore</h2>
      <p>Join thousands of book lovers who have found their next favorite read with us</p>
   </div>

   <div class="insights-container">
      <div class="graph-container">
         <div class="graph-bar">
            <div class="bar-value">1.2K</div>
            <div class="bar-label">Fantasy</div>
         </div>
         <div class="graph-bar">
            <div class="bar-value">1.6K</div>
            <div class="bar-label">Romance</div>
         </div>
         <div class="graph-bar">
            <div class="bar-value">1.3K</div>
            <div class="bar-label">Mystery</div>
         </div>
         <div class="graph-bar">
            <div class="bar-value">1.8K</div>
            <div class="bar-label">Self-Help</div>
         </div>
         <div class="graph-bar">
            <div class="bar-value">1.4K</div>
            <div class="bar-label">Sci-Fi</div>
         </div>
      </div>

      <div class="stats-text">
         <div class="stat-item">
            <h3>📚 50+ Curated Books</h3>
            <p>Handpicked selection across 10 diverse genres to match every reader's taste</p>
         </div>
         <div class="stat-item">
            <h3>⭐ 4.9/5 Rating</h3>
            <p>Customer satisfaction is our priority with consistent 5-star reviews</p>
         </div>
         <div class="stat-item">
            <h3>🚀 Fast & Reliable</h3>
            <p>24-hour delivery with 100% satisfaction guarantee on every order</p>
         </div>
      </div>
   </div>
</section>

<section class="trending-section">
   <h2 class="section-title">📖 Explore All Categories - Trending Now</h2>
   <div class="trending-carousel-container">
      <button class="trending-carousel-btn prev" onclick="scrollTrending('left')">❮</button>
      <div class="trending-track" id="trendingTrack">
         <?php
            // Get all unique categories
            $cat_query = mysqli_query($conn, "SELECT DISTINCT category FROM `products` ORDER BY category") or die('query failed');
            $categories = array();
            while($cat_row = mysqli_fetch_assoc($cat_query)) {
               $categories[] = $cat_row['category'];
            }
            
            // Get one featured book from each category for the carousel
            foreach($categories as $category) {
               $product_query = mysqli_query($conn, "SELECT * FROM `products` WHERE category = '$category' LIMIT 1") or die('query failed');
               if(mysqli_num_rows($product_query) > 0) {
                  $product = mysqli_fetch_assoc($product_query);
                  $image_path = "uploaded_img/" . $product['image'];
                  $fallback_image = "https://picsum.photos/280/350?random=" . $product['id'];
         ?>
         <div class="trending-slide">
            <a href="shop.php?category=<?php echo urlencode($category); ?>" class="trending-card">
               <img src="<?php echo $image_path; ?>" alt="<?php echo $product['name']; ?>" onerror="this.src='<?php echo $fallback_image; ?>';">
               <div class="trending-overlay">
                  <h3><?php echo ucfirst($category); ?></h3>
                  <p><?php echo $product['name']; ?></p>
               </div>
            </a>
         </div>
         <?php
               }
            }
         ?>
      </div>
      <button class="trending-carousel-btn next" onclick="scrollTrending('right')">❯</button>
   </div>
</section>

<script>
   function scrollTrending(direction) {
      const trending = document.getElementById('trendingTrack');
      const scrollAmount = 300;
      if(direction === 'left') {
         trending.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
      } else {
         trending.scrollBy({ left: scrollAmount, behavior: 'smooth' });
      }
   }
</script>

<!-- Reading Tips Section -->
<section class="reading-tips-section">
   <h2>📖 Pro Tips for Better Reading</h2>
   <div class="tips-carousel-container">
      <button class="tips-carousel-btn prev" onclick="scrollTips('left')">❮</button>
      <div class="tips-track" id="tipsTrack">
         <div class="tips-slide">
            <div class="tip-card">
               <div>
                  <div class="tip-number">01</div>
                  <div class="tip-title">Find Your Favorite Genre</div>
               </div>
               <div class="tip-description">Explore different genres and discover what resonates with you. From fantasy to mystery, romance to self-help, there's something for everyone.</div>
            </div>
         </div>
         <div class="tips-slide">
            <div class="tip-card">
               <div>
                  <div class="tip-number">02</div>
                  <div class="tip-title">Create a Reading Habit</div>
               </div>
               <div class="tip-description">Dedicate 15-30 minutes daily to reading. Consistency beats intensity. Whether it's morning coffee or bedtime, make reading a ritual.</div>
            </div>
         </div>
         <div class="tips-slide">
            <div class="tip-card">
               <div>
                  <div class="tip-number">03</div>
                  <div class="tip-title">Join a Reading Community</div>
               </div>
               <div class="tip-description">Connect with fellow book lovers, share recommendations, and discuss your favorite reads. Community makes reading more enjoyable.</div>
            </div>
         </div>
         <div class="tips-slide">
            <div class="tip-card">
               <div>
                  <div class="tip-number">04</div>
                  <div class="tip-title">Keep a Reading Journal</div>
               </div>
               <div class="tip-description">Note your thoughts, favorite quotes, and insights from each book. This deepens comprehension and creates lasting memories.</div>
            </div>
         </div>
         <div class="tips-slide">
            <div class="tip-card">
               <div>
                  <div class="tip-number">05</div>
                  <div class="tip-title">Mix Your Selections</div>
               </div>
               <div class="tip-description">Balance light reads with challenging books. Variety keeps your mind engaged and prevents reading fatigue.</div>
            </div>
         </div>
         <div class="tips-slide">
            <div class="tip-card">
               <div>
                  <div class="tip-number">06</div>
                  <div class="tip-title">Support Great Authors</div>
               </div>
               <div class="tip-description">Buy books from your favorite authors. Your purchase directly supports their work and encourages more great stories.</div>
            </div>
         </div>
         <div class="tips-slide">
            <div class="tip-card">
               <div>
                  <div class="tip-number">07</div>
                  <div class="tip-title">Set Reading Goals</div>
               </div>
               <div class="tip-description">Challenge yourself with yearly reading goals. Track your progress and celebrate milestones. Every page brings you closer to new knowledge.</div>
            </div>
         </div>
         <div class="tips-slide">
            <div class="tip-card">
               <div>
                  <div class="tip-number">08</div>
                  <div class="tip-title">Revisit Classics</div>
               </div>
               <div class="tip-description">Don't shy away from timeless classics. These books have shaped literature and offer profound insights that remain relevant across generations.</div>
            </div>
         </div>
      </div>
      <button class="tips-carousel-btn next" onclick="scrollTips('right')">❯</button>
   </div>
</section>

<script>
   function scrollTips(direction) {
      const tips = document.getElementById('tipsTrack');
      const scrollAmount = 340;
      if(direction === 'left') {
         tips.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
      } else {
         tips.scrollBy({ left: scrollAmount, behavior: 'smooth' });
      }
   }
</script>

<section class="products">

   <h1 class="title">📚 Featured Collection</h1>

   <div class="carousel-container">
      <button class="carousel-btn prev" onclick="scrollCarousel('left')">❮</button>
      <div class="carousel-track" id="carouselTrack">
         <?php  
            $select_products = mysqli_query($conn, "SELECT * FROM `products` ORDER BY id LIMIT 30") or die('query failed');
            if(mysqli_num_rows($select_products) > 0){
               while($fetch_products = mysqli_fetch_assoc($select_products)){
                  $image_path = "uploaded_img/" . $fetch_products['image'];
                  $fallback_image = "https://picsum.photos/400/500?random=" . $fetch_products['id'];
         ?>
        <form action="" method="post" class="carousel-slide">
         <div class="box book-card">
            <div class="book-wrapper">
               <div class="book-cover">
                  <img class="image book-image" src="<?php echo $image_path; ?>" alt="<?php echo $fetch_products['name']; ?>" onerror="this.src='<?php echo $fallback_image; ?>';">
                  <div class="price-badge">Rs.<?php echo $fetch_products['price']; ?>/-</div>
               </div>
               <div class="book-shadow"></div>
            </div>
            <div class="product-info">
               <div class="name"><?php echo $fetch_products['name']; ?></div>
               <div class="category" style="font-size: 0.85em; color: #666; margin: 5px 0;"><?php echo $fetch_products['category']; ?></div>
               <input type="number" min="1" name="product_quantity" value="1" class="qty">
               <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
               <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
               <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
               <input type="submit" value="add to cart" name="add_to_cart" class="btn">
            </div>
         </div>
        </form>
         <?php
            }
         }else{
            echo '<p class="empty">no products added yet!</p>';
         }
         ?>
      </div>
      <button class="carousel-btn next" onclick="scrollCarousel('right')">❯</button>
   </div>

   <div class="load-more" style="margin-top: 2rem; text-align:center">
      <a href="shop.php" class="option-btn">Browse All Items</a>
   </div>

</section>

<script>
   function scrollCarousel(direction) {
      const carousel = document.getElementById('carouselTrack');
      const scrollAmount = 250;
      if(direction === 'left') {
         carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
      } else {
         carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
      }
   }
</script>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<!-- chatbot widget -->
<script src="js/chatbot.js"></script>

</body>
</html>
