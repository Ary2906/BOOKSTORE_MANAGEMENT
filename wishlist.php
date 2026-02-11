<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `wishlist` WHERE id = '$delete_id' AND user_id = '$user_id'") or die('query failed');
   header('location:wishlist.php');
}

if(isset($_GET['add_to_cart'])){
   $product_id = $_GET['add_to_cart'];
   $wishlist_product = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE id = '$product_id' AND user_id = '$user_id'") or die('query failed');
   
   if(mysqli_num_rows($wishlist_product) > 0){
      $fetch_product = mysqli_fetch_assoc($wishlist_product);
      
      $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '{$fetch_product['product_name']}' AND user_id = '$user_id'");
      
      if(mysqli_num_rows($check_cart) > 0){
         $message[] = 'Product already in cart!';
      } else {
         mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '{$fetch_product['product_name']}', '{$fetch_product['price']}', 1, '{$fetch_product['image']}')") or die('query failed');
         $message[] = 'Product added to cart!';
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>My Wishlist - LookBook</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

   <style>
      .wishlist-container {
         max-width: 1200px;
         margin: 3rem auto;
         padding: 2rem;
      }

      .wishlist-title {
         font-size: 2.5rem;
         font-weight: 700;
         color: var(--darker);
         margin-bottom: 2rem;
         text-align: center;
      }

      .wishlist-grid {
         display: grid;
         grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
         gap: 2rem;
         margin-bottom: 2rem;
      }

      .wishlist-item {
         background: white;
         border-radius: 12px;
         overflow: hidden;
         box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
         transition: all 0.3s ease;
         display: flex;
         flex-direction: column;
      }

      .wishlist-item:hover {
         transform: translateY(-5px);
         box-shadow: 0 8px 25px rgba(5, 150, 105, 0.2);
      }

      .wishlist-image {
         width: 100%;
         height: 250px;
         background: linear-gradient(135deg, #E8F5E9 0%, #F1F8F6 100%);
         display: flex;
         align-items: center;
         justify-content: center;
         overflow: hidden;
      }

      .wishlist-image img {
         width: 100%;
         height: 100%;
         object-fit: cover;
      }

      .wishlist-details {
         padding: 1.5rem;
         flex: 1;
         display: flex;
         flex-direction: column;
      }

      .wishlist-name {
         font-size: 1.2rem;
         font-weight: 600;
         color: var(--darker);
         margin-bottom: 0.5rem;
      }

      .wishlist-category {
         font-size: 0.85rem;
         color: #666;
         background: #F0F0F0;
         padding: 0.4rem 0.8rem;
         border-radius: 6px;
         display: inline-block;
         margin-bottom: 1rem;
         width: fit-content;
      }

      .wishlist-price {
         font-size: 1.5rem;
         font-weight: 700;
         color: var(--primary);
         margin-bottom: 1rem;
      }

      .wishlist-discount {
         display: flex;
         align-items: center;
         gap: 0.5rem;
         margin-bottom: 1rem;
         color: #dc2626;
         font-weight: 600;
      }

      .wishlist-buttons {
         display: flex;
         gap: 0.8rem;
         margin-top: auto;
      }

      .wishlist-buttons button,
      .wishlist-buttons a {
         flex: 1;
         padding: 0.8rem;
         border: none;
         border-radius: 6px;
         cursor: pointer;
         font-weight: 600;
         transition: all 0.3s ease;
         text-decoration: none;
         display: flex;
         align-items: center;
         justify-content: center;
         gap: 0.5rem;
         font-size: 0.9rem;
      }

      .add-to-cart-btn {
         background: var(--primary);
         color: white;
      }

      .add-to-cart-btn:hover {
         background: #047857;
         transform: scale(1.02);
      }

      .remove-btn {
         background: #f0f0f0;
         color: #dc2626;
         border: 2px solid #dc2626;
      }

      .remove-btn:hover {
         background: #dc2626;
         color: white;
      }

      .empty-wishlist {
         text-align: center;
         padding: 4rem 2rem;
         color: #666;
      }

      .empty-wishlist i {
         font-size: 4rem;
         color: #059669;
         margin-bottom: 1rem;
      }

      .empty-wishlist p {
         font-size: 1.2rem;
         margin-bottom: 2rem;
      }

      .empty-wishlist a {
         background: var(--primary);
         color: white;
         padding: 1rem 2rem;
         border-radius: 6px;
         text-decoration: none;
         font-weight: 600;
         display: inline-block;
         transition: all 0.3s ease;
      }

      .empty-wishlist a:hover {
         background: #047857;
         transform: scale(1.05);
      }

      .message {
         position: fixed;
         top: 20px;
         right: 20px;
         background: #059669;
         color: white;
         padding: 15px 20px;
         border-radius: 4px;
         z-index: 1000;
      }
   </style>

</head>
<body>
   
<?php include 'header.php'; ?>

<?php 
if(isset($message)){
   foreach($message as $msg){
      echo '<div class="message">'.$msg.'</div>';
   }
}
?>

<div class="heading">
   <h3>❤️ My Wishlist</h3>
   <p> <a href="home.php">home</a> / wishlist </p>
</div>

<div class="wishlist-container">
   <h1 class="wishlist-title">💝 My Wishlist</h1>

   <?php
      $select_wishlist = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE user_id = '$user_id' ORDER BY added_date DESC") or die('query failed');
      
      if(mysqli_num_rows($select_wishlist) > 0){
         echo '<div class="wishlist-grid">';
         
         while($fetch_wishlist = mysqli_fetch_assoc($select_wishlist)){
   ?>
      <div class="wishlist-item">
         <div class="wishlist-image">
            <?php if(!empty($fetch_wishlist['image'])): ?>
               <img src="uploaded_img/<?php echo $fetch_wishlist['image']; ?>" alt="<?php echo $fetch_wishlist['product_name']; ?>">
            <?php else: ?>
               <i class="fas fa-book-open" style="font-size: 3rem; color: #059669;"></i>
            <?php endif; ?>
         </div>
         <div class="wishlist-details">
            <div class="wishlist-name"><?php echo $fetch_wishlist['product_name']; ?></div>
            <div class="wishlist-category"><?php echo $fetch_wishlist['category']; ?></div>
            
            <?php if(!empty($fetch_wishlist['discount']) && $fetch_wishlist['discount'] > 0): ?>
               <div class="wishlist-discount">
                  <i class="fas fa-tag"></i> Discount: <?php echo $fetch_wishlist['discount']; ?>%
               </div>
            <?php endif; ?>
            
            <div class="wishlist-price">Rs. <?php echo $fetch_wishlist['price']; ?>/-</div>
            
            <div class="wishlist-buttons">
               <a href="wishlist.php?add_to_cart=<?php echo $fetch_wishlist['id']; ?>" class="add-to-cart-btn">
                  <i class="fas fa-shopping-cart"></i> Cart
               </a>
               <a href="wishlist.php?delete=<?php echo $fetch_wishlist['id']; ?>" class="remove-btn" onclick="return confirm('Remove from wishlist?');">
                  <i class="fas fa-trash"></i> Remove
               </a>
            </div>
         </div>
      </div>
   <?php
         }
         
         echo '</div>';
      } else {
         echo '
         <div class="empty-wishlist">
            <i class="far fa-heart"></i>
            <p>Your wishlist is empty!</p>
            <a href="shop.php">Continue Shopping</a>
         </div>
         ';
      }
   ?>
</div>

<?php include 'footer.php'; ?>

<script src="js/admin_script.js"></script>

</body>
</html>
