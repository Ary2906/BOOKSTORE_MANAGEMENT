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

// Handle AJAX wishlist requests
if(isset($_POST['action'])) {
   if($_POST['action'] == 'add_wishlist') {
      $product_id = $_POST['product_id'];
      $product_name = $_POST['product_name'];
      $product_price = $_POST['product_price'];
      $product_image = $_POST['product_image'];
      $product_category = $_POST['product_category'];
      $discount = isset($_POST['discount']) ? $_POST['discount'] : 0;
      
      $check_wishlist = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE user_id = '$user_id' AND product_id = '$product_id'");
      
      if(mysqli_num_rows($check_wishlist) > 0) {
         echo json_encode(['status' => 'exists', 'message' => 'Already in wishlist']);
      } else {
         $add_wishlist = mysqli_query($conn, "INSERT INTO `wishlist`(user_id, product_id, product_name, price, discount, image, category) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$discount', '$product_image', '$product_category')");
         if($add_wishlist) {
            echo json_encode(['status' => 'success', 'message' => 'Added to wishlist']);
         } else {
            echo json_encode(['status' => 'error', 'message' => 'Error adding to wishlist']);
         }
      }
      exit;
   } elseif($_POST['action'] == 'remove_wishlist') {
      $product_id = $_POST['product_id'];
      $remove = mysqli_query($conn, "DELETE FROM `wishlist` WHERE user_id = '$user_id' AND product_id = '$product_id'");
      if($remove) {
         echo json_encode(['status' => 'success', 'message' => 'Removed from wishlist']);
      } else {
         echo json_encode(['status' => 'error', 'message' => 'Error removing from wishlist']);
      }
      exit;
   } elseif($_POST['action'] == 'check_wishlist') {
      $product_id = $_POST['product_id'];
      $check = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE user_id = '$user_id' AND product_id = '$product_id'");
      if(mysqli_num_rows($check) > 0) {
         echo json_encode(['in_wishlist' => true]);
      } else {
         echo json_encode(['in_wishlist' => false]);
      }
      exit;
   }
}

$selected_category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';

$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'default';

$discount_filter = isset($_GET['discount']) ? $_GET['discount'] : 'all';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shop - LookBook</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/chatbot_style.css">

   <style>
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

      /* Enhanced Category Section */
      .category-filter {
         background: linear-gradient(135deg, #FFFFFF 0%, #F0F9FF 50%, #DBEAFE 100%);
         padding: 3rem 2rem;
         margin: 3rem 0;
         border-radius: 16px;
         box-shadow: 0 8px 24px rgba(5, 150, 105, 0.15);
         border: 2px solid rgba(5, 150, 105, 0.1);
         max-width: 1200px;
         margin-left: auto;
         margin-right: auto;
      }

      .category-filter h3 {
         margin-bottom: 2.5rem;
         color: var(--darker);
         font-size: 2.8rem;
         font-weight: 700;
         text-align: center;
         letter-spacing: -0.5px;
      }

      .category-buttons {
         display: flex;
         flex-wrap: wrap;
         gap: 1.5rem;
         justify-content: center;
         align-items: center;
      }

      .category-btn {
         position: relative;
         padding: 1.2rem 2.5rem;
         background: white;
         color: var(--darker);
         text-decoration: none;
         border-radius: 12px;
         font-size: 1.1rem;
         font-weight: 600;
         transition: all 0.3s ease;
         border: 2px solid transparent;
         box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
         cursor: pointer;
         display: inline-flex;
         align-items: center;
         gap: 0.8rem;
      }

      .category-btn i {
         font-size: 1.4rem;
      }

      .category-btn:hover {
         transform: translateY(-3px);
         box-shadow: 0 8px 20px rgba(5, 150, 105, 0.2);
         border-color: var(--primary);
         color: var(--primary);
      }

      .category-btn.active,
      .category-btn.all {
         background: linear-gradient(135deg, var(--primary) 0%, #047857 100%);
         color: white;
         border-color: var(--primary);
      }

      .category-btn.active:hover {
         transform: translateY(-3px);
         box-shadow: 0 8px 25px rgba(5, 150, 105, 0.4);
      }

      /* Products Section */
      .products {
         max-width: 1200px;
         margin: 0 auto;
         padding: 2rem;
      }

      .products .title {
         text-align: center;
         font-size: 2.5rem;
         color: var(--darker);
         margin-bottom: 3rem;
         font-weight: 700;
      }

      .products .box-container {
         display: grid;
         grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
         gap: 2rem;
         margin-bottom: 3rem;
      }

      .products .box {
         background: white;
         border-radius: 12px;
         overflow: hidden;
         box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
         transition: all 0.3s ease;
         display: flex;
         flex-direction: column;
      }

      .products .box:hover {
         transform: translateY(-5px);
         box-shadow: 0 8px 25px rgba(5, 150, 105, 0.2);
      }

      .book-cover {
         width: 100%;
         height: 300px;
         background: linear-gradient(135deg, #E8F5E9 0%, #F1F8F6 100%);
         display: flex;
         align-items: center;
         justify-content: center;
         border-bottom: 2px solid #E0E0E0;
         overflow: hidden;
         position: relative;
      }

      .book-cover img {
         width: 100%;
         height: 100%;
         object-fit: cover;
      }

      .book-placeholder {
         text-align: center;
         color: #999;
         width: 100%;
         height: 100%;
         display: flex;
         flex-direction: column;
         align-items: center;
         justify-content: center;
         background: linear-gradient(135deg, #E8F5E9 0%, #F1F8F6 100%);
      }

      .book-placeholder i {
         font-size: 3rem;
         color: #059669;
         margin-bottom: 0.5rem;
      }

      .book-placeholder p {
         color: #666;
         font-size: 0.9rem;
      }

      .product-info {
         padding: 1.5rem;
         flex: 1;
         display: flex;
         flex-direction: column;
      }

      .product-info .name {
         font-size: 1.2rem;
         font-weight: 600;
         color: var(--darker);
         margin-bottom: 0.5rem;
         min-height: 2.4em;
      }

      .product-info .price {
         font-size: 1.5rem;
         font-weight: 700;
         color: var(--primary);
         margin-bottom: 1rem;
      }

      .product-info .category-tag {
         font-size: 0.85rem;
         color: #666;
         background: #F0F0F0;
         padding: 0.4rem 0.8rem;
         border-radius: 6px;
         display: inline-block;
         margin-bottom: 1rem;
         width: fit-content;
      }

      .qty {
         width: 100%;
         padding: 0.8rem;
         border: 1px solid #ddd;
         border-radius: 6px;
         margin-bottom: 1rem;
         font-size: 1rem;
      }

      .btn {
         background: var(--primary);
         color: white;
         padding: 0.8rem 1.5rem;
         border: none;
         border-radius: 6px;
         cursor: pointer;
         font-weight: 600;
         transition: all 0.3s ease;
         width: 100%;
         margin-top: auto;
      }

      .btn:hover {
         background: #047857;
         transform: scale(1.02);
      }

      .btn-group {
         display: flex;
         gap: 0.8rem;
         margin-top: auto;
      }

      .btn-group .btn {
         flex: 1;
         margin-top: 0;
      }

      .wishlist-btn {
         background: white;
         color: #059669;
         border: 2px solid #059669;
         padding: 0.8rem 1rem;
         border-radius: 6px;
         cursor: pointer;
         font-weight: 600;
         transition: all 0.3s ease;
         display: flex;
         align-items: center;
         justify-content: center;
         gap: 0.5rem;
         font-size: 0.95rem;
      }

      .wishlist-btn:hover {
         background: #059669;
         color: white;
         transform: scale(1.05);
      }

      .wishlist-btn.active {
         background: #dc2626;
         color: white;
         border-color: #dc2626;
      }

      .empty {
         text-align: center;
         padding: 3rem;
         color: #666;
         font-size: 1.2rem;
      }

      /* Sorting Section */
      .sorting-section {
         background: linear-gradient(135deg, #FFFFFF 0%, #F8FFFE 50%, #F0FDF4 100%);
         padding: 2rem;
         margin: 2rem auto;
         border-radius: 12px;
         max-width: 1200px;
         box-shadow: 0 4px 12px rgba(5, 150, 105, 0.1);
         border: 2px solid rgba(5, 150, 105, 0.1);
      }

      .sorting-section h4 {
         color: var(--darker);
         font-size: 1.2rem;
         margin-bottom: 1.5rem;
         display: flex;
         align-items: center;
         gap: 0.5rem;
      }

      .sorting-section {
         background: linear-gradient(135deg, #FFFFFF 0%, #F8FFFE 50%, #F0FDF4 100%);
         padding: 2rem;
         margin: 2rem auto;
         border-radius: 12px;
         max-width: 1200px;
         box-shadow: 0 4px 12px rgba(5, 150, 105, 0.1);
         border: 2px solid rgba(5, 150, 105, 0.1);
      }

      .sorting-section h4 {
         color: var(--darker);
         font-size: 1.2rem;
         margin-bottom: 1.5rem;
         display: flex;
         align-items: center;
         gap: 0.5rem;
      }

      .filter-container {
         display: flex;
         flex-wrap: wrap;
         gap: 2rem;
         justify-content: center;
         align-items: center;
      }

      .filter-group {
         display: flex;
         flex-direction: column;
         align-items: flex-start;
         gap: 0.5rem;
      }

      .filter-group label {
         font-weight: 600;
         color: var(--darker);
         font-size: 0.95rem;
      }

      .filter-group select {
         padding: 0.9rem 1.5rem;
         background: white;
         color: var(--darker);
         border: 2px solid #E0E0E0;
         border-radius: 8px;
         font-size: 1rem;
         font-weight: 600;
         cursor: pointer;
         transition: all 0.3s ease;
         min-width: 250px;
      }

      .filter-group select:hover,
      .filter-group select:focus {
         border-color: var(--primary);
         box-shadow: 0 4px 12px rgba(5, 150, 105, 0.2);
         outline: none;
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
   <h3>📚 Our Shop</h3>
   <p> <a href="home.php">home</a> / shop </p>
</div>

<!-- Interactive Category Filter Section -->
<section class="category-filter">
   <h3>📖 Explore by Category</h3>
   <div class="category-buttons">
      <a href="shop.php" class="category-btn <?php echo empty($selected_category) ? 'all' : ''; ?>">
         <i class="fas fa-book"></i> All Books
      </a>
      <?php
         $categories_query = mysqli_query($conn, "SELECT DISTINCT category FROM `products` ORDER BY category ASC") or die('query failed');
         $category_icons = [
            'Mystery' => 'fa-mask',
            'History' => 'fa-hourglass-end',
            'Thriller' => 'fa-bolt',
            'Education' => 'fa-graduation-cap',
            'Fiction' => 'fa-wand-magic-sparkles'
         ];
         
         while($cat = mysqli_fetch_assoc($categories_query)) {
            $active = ($selected_category == $cat['category']) ? 'active' : '';
            $icon = isset($category_icons[$cat['category']]) ? $category_icons[$cat['category']] : 'fa-bookmark';
            echo '<a href="shop.php?category='.urlencode($cat['category']).'" class="category-btn '.$active.'">
                     <i class="fas '.$icon.'"></i> '.$cat['category'].'
                  </a>';
         }
      ?>
   </div>
</section>

<!-- Sorting & Filter Section -->
<section class="sorting-section">
   <h4><i class="fas fa-sliders-h"></i> Sort & Filter</h4>
   <div class="filter-container">
      <div class="filter-group">
         <label for="sort-select">Sort By:</label>
         <select id="sort-select" onchange="handleSort()">
            <option value="default" <?php echo ($sort_by == 'default') ? 'selected' : ''; ?>>📋 Default</option>
            <option value="a-z" <?php echo ($sort_by == 'a-z') ? 'selected' : ''; ?>>📤 A-Z</option>
            <option value="z-a" <?php echo ($sort_by == 'z-a') ? 'selected' : ''; ?>>📥 Z-A</option>
            <option value="price-low" <?php echo ($sort_by == 'price-low') ? 'selected' : ''; ?>>💰 Price (Low to High)</option>
            <option value="price-high" <?php echo ($sort_by == 'price-high') ? 'selected' : ''; ?>>💳 Price (High to Low)</option>
            <option value="stock" <?php echo ($sort_by == 'stock') ? 'selected' : ''; ?>>📦 In Stock</option>
         </select>
      </div>

      <div class="filter-group">
         <label for="discount-select">Filter by Discount:</label>
         <select id="discount-select" onchange="handleDiscount()">
            <option value="all" <?php echo ($discount_filter == 'all') ? 'selected' : ''; ?>>All Books</option>
            <option value="discounted" <?php echo ($discount_filter == 'discounted') ? 'selected' : ''; ?>>🏷️ Discounted Only</option>
            <option value="no-discount" <?php echo ($discount_filter == 'no-discount') ? 'selected' : ''; ?>>No Discount</option>
         </select>
      </div>
   </div>
</section>

<script>
function handleSort() {
   const sortSelect = document.getElementById('sort-select');
   const sortValue = sortSelect.value;
   const category = '<?php echo addslashes($selected_category); ?>';
   const discount = document.getElementById('discount-select')?.value || 'all';
   
   let url = 'shop.php';
   if(category) {
      url += '?category=' + encodeURIComponent(category);
      if(sortValue !== 'default') {
         url += '&sort=' + sortValue;
      }
      if(discount !== 'all') {
         url += '&discount=' + discount;
      }
   } else {
      if(sortValue !== 'default') {
         url += '?sort=' + sortValue;
      }
      if(discount !== 'all') {
         url += (sortValue !== 'default' ? '&' : '?') + 'discount=' + discount;
      }
   }
   window.location.href = url;
}

function handleDiscount() {
   const discountSelect = document.getElementById('discount-select');
   const discountValue = discountSelect.value;
   const category = '<?php echo addslashes($selected_category); ?>';
   const sortValue = document.getElementById('sort-select')?.value || 'default';
   
   let url = 'shop.php';
   if(category) {
      url += '?category=' + encodeURIComponent(category);
      if(sortValue !== 'default') {
         url += '&sort=' + sortValue;
      }
      if(discountValue !== 'all') {
         url += '&discount=' + discountValue;
      }
   } else {
      if(sortValue !== 'default') {
         url += '?sort=' + sortValue;
      }
      if(discountValue !== 'all') {
         url += (sortValue !== 'default' ? '&' : '?') + 'discount=' + discountValue;
      }
   }
   window.location.href = url;
}
</script>

<!-- Products Display Section -->
<section class="products">
   <h1 class="title">
      <?php 
         if($selected_category) {
            echo '📚 ' . $selected_category;
         } else {
            echo '📖 All Books';
         }
      ?>
   </h1>

   <div class="box-container">
      <?php  
         // Build the base query
         $where_clause = $selected_category ? "WHERE category = '$selected_category'" : "";
         
         // Add discount filter
         if($discount_filter === 'discounted') {
            if($where_clause) {
               $where_clause .= " AND discount > 0";
            } else {
               $where_clause = "WHERE discount > 0";
            }
         } elseif($discount_filter === 'no-discount') {
            if($where_clause) {
               $where_clause .= " AND (discount IS NULL OR discount = 0)";
            } else {
               $where_clause = "WHERE (discount IS NULL OR discount = 0)";
            }
         }
         
         // Determine ORDER BY based on sort parameter
         $order_by = "ORDER BY category, name ASC"; // default
         
         switch($sort_by) {
            case 'a-z':
               $order_by = "ORDER BY name ASC";
               break;
            case 'z-a':
               $order_by = "ORDER BY name DESC";
               break;
            case 'price-low':
               $order_by = "ORDER BY price ASC, name ASC";
               break;
            case 'price-high':
               $order_by = "ORDER BY price DESC, name ASC";
               break;
            case 'stock':
               $order_by = "ORDER BY stock DESC, name ASC";
               break;
            default:
               if($selected_category) {
                  $order_by = "ORDER BY name ASC";
               } else {
                  $order_by = "ORDER BY category, name ASC";
               }
         }
         
         $select_products = mysqli_query($conn, "SELECT * FROM `products` $where_clause $order_by") or die('query failed');
         
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
         <form action="" method="post" class="box">
            <div class="book-cover">
               <?php if(!empty($fetch_products['image'])): ?>
                  <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="<?php echo $fetch_products['name']; ?>">
               <?php else: ?>
                  <div class="book-placeholder">
                     <i class="fas fa-book-open"></i>
                     <p>Add Book Cover</p>
                  </div>
               <?php endif; ?>
            </div>
            <div class="product-info">
               <div class="name"><?php echo $fetch_products['name']; ?></div>
               <div class="category-tag"><?php echo $fetch_products['category']; ?></div>
               
               <!-- Price and Discount Display -->
               <?php 
                  $original_price = $fetch_products['price'];
                  $discount = isset($fetch_products['discount']) ? $fetch_products['discount'] : 0;
                  $discounted_price = $discount > 0 ? round($original_price - ($original_price * $discount / 100)) : $original_price;
               ?>
               
               <?php if($discount > 0): ?>
                  <div style="margin: 0.8rem 0;">
                     <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <div class="price" style="margin: 0; text-decoration: line-through; color: #999; font-size: 0.9rem;">Rs. <?php echo $original_price; ?>/-</div>
                        <span style="background: #dc2626; color: white; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 900; font-size: 1.3rem; display: inline-block;">-<?php echo $discount; ?>%</span>
                     </div>
                     <div class="price" style="margin: 0; color: #059669; font-size: 2rem; font-weight: 900;">Rs. <?php echo $discounted_price; ?>/-</div>
                  </div>
               <?php else: ?>
                  <div class="price">Rs. <?php echo $original_price; ?>/-</div>
               <?php endif; ?>
               
               <!-- Stock Display -->
               <div class="stock-info" style="font-weight: 600; margin: 0.8rem 0; padding: 0.8rem; background: #f0fdf4; border-left: 4px solid #059669; border-radius: 4px;">
                  <?php 
                     $stock = isset($fetch_products['stock']) ? $fetch_products['stock'] : 0;
                     if($stock > 0) {
                        echo '<i class="fas fa-check-circle" style="color: #059669;"></i> <span style="color: #059669;"><strong>In Stock:</strong> ' . $stock . ' available</span>';
                     } else {
                        echo '<i class="fas fa-times-circle" style="color: #dc2626;"></i> <span style="color: #dc2626;"><strong>Out of Stock</strong></span>';
                     }
                  ?>
               </div>
               
               <?php if(!empty($fetch_products['description'])): ?>
               <div class="description" style="font-size: 0.85rem; color: #666; margin: 0.8rem 0; line-height: 1.5; word-wrap: break-word;"><?php echo htmlspecialchars($fetch_products['description']); ?></div>
               <?php endif; ?>
               
               <input type="number" min="1" name="product_quantity" value="1" class="qty">
               <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
               <input type="hidden" name="product_price" value="<?php echo $discounted_price; ?>">
               <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
               <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
               <input type="hidden" name="product_category" value="<?php echo $fetch_products['category']; ?>">
               <input type="hidden" name="discount" value="<?php echo $discount; ?>">
               
               <div class="btn-group">
                  <input type="submit" value="Add to Cart" name="add_to_cart" class="btn" <?php echo ($stock <= 0) ? 'disabled style="opacity: 0.6; cursor: not-allowed;"' : ''; ?>>
                  <button type="button" class="wishlist-btn" onclick="toggleWishlist(<?php echo $fetch_products['id']; ?>, '<?php echo addslashes($fetch_products['name']); ?>', <?php echo $discounted_price; ?>, '<?php echo $fetch_products['image']; ?>', '<?php echo $fetch_products['category']; ?>', <?php echo $discount; ?>)" id="wishlist-btn-<?php echo $fetch_products['id']; ?>">
                     <i class="far fa-heart"></i> <span class="wishlist-text">Add to Wishlist</span>
                  </button>
               </div>
            </div>
         </form>
      <?php
         }
      }else{
         echo '<p class="empty">no products found!</p>';
      }
      ?>
   </div>
</section>

<?php include 'footer.php'; ?>

<script>
function toggleWishlist(productId, productName, productPrice, productImage, productCategory, discount) {
   const btn = document.getElementById('wishlist-btn-' + productId);
   
   if(btn.classList.contains('active')) {
      // Remove from wishlist
      fetch('shop.php', {
         method: 'POST',
         headers: {'Content-Type': 'application/x-www-form-urlencoded'},
         body: 'action=remove_wishlist&product_id=' + productId
      })
      .then(response => response.json())
      .then(data => {
         if(data.status === 'success') {
            btn.classList.remove('active');
            btn.querySelector('.wishlist-text').textContent = 'Add to Wishlist';
            btn.querySelector('i').className = 'far fa-heart';
            showNotification('Removed from wishlist', 'info');
         }
      });
   } else {
      // Add to wishlist
      fetch('shop.php', {
         method: 'POST',
         headers: {'Content-Type': 'application/x-www-form-urlencoded'},
         body: 'action=add_wishlist&product_id=' + productId + '&product_name=' + encodeURIComponent(productName) + '&product_price=' + productPrice + '&product_image=' + encodeURIComponent(productImage) + '&product_category=' + encodeURIComponent(productCategory) + '&discount=' + discount
      })
      .then(response => response.json())
      .then(data => {
         if(data.status === 'success') {
            btn.classList.add('active');
            btn.querySelector('.wishlist-text').textContent = 'In Wishlist';
            btn.querySelector('i').className = 'fas fa-heart';
            showNotification('Added to wishlist', 'success');
         } else if(data.status === 'exists') {
            btn.classList.add('active');
            btn.querySelector('.wishlist-text').textContent = 'In Wishlist';
            btn.querySelector('i').className = 'fas fa-heart';
            showNotification('Already in wishlist', 'info');
         }
      });
   }
}

function showNotification(message, type) {
   const notification = document.createElement('div');
   notification.className = 'message';
   notification.style.background = type === 'success' ? '#059669' : '#3b82f6';
   notification.textContent = message;
   document.body.appendChild(notification);
   
   setTimeout(() => {
      notification.remove();
   }, 3000);
}

// Check wishlist status on page load
window.addEventListener('load', function() {
   const wishlistBtns = document.querySelectorAll('.wishlist-btn');
   wishlistBtns.forEach(btn => {
      const productId = btn.id.split('-')[2];
      fetch('shop.php', {
         method: 'POST',
         headers: {'Content-Type': 'application/x-www-form-urlencoded'},
         body: 'action=check_wishlist&product_id=' + productId
      })
      .then(response => response.json())
      .then(data => {
         if(data.in_wishlist) {
            btn.classList.add('active');
            btn.querySelector('.wishlist-text').textContent = 'In Wishlist';
            btn.querySelector('i').className = 'fas fa-heart';
         }
      });
   });
});
</script>

<script src="js/admin_script.js"></script>
<script src="js/chatbot.js"></script>

</body>
</html>
