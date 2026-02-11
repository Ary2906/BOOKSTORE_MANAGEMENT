<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_product'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = $_POST['price'];
   $discount = isset($_POST['discount']) ? $_POST['discount'] : 0;
   $stock = isset($_POST['stock']) ? $_POST['stock'] : 0;
   $category = mysqli_real_escape_string($conn, $_POST['category']);
   $description = mysqli_real_escape_string($conn, $_POST['description']);
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

   if(mysqli_num_rows($select_product_name) > 0){
      $message[] = 'product name already added';
   }else{
      // Check if stock column exists
      $check_stock_col = mysqli_query($conn, "SHOW COLUMNS FROM `products` LIKE 'stock'");
      if(mysqli_num_rows($check_stock_col) > 0) {
         $add_product_query = mysqli_query($conn, "INSERT INTO `products`(name, price, discount, category, image, description, stock) VALUES('$name', '$price', '$discount', '$category', '$image', '$description', '$stock')") or die('query failed');
      } else {
         $add_product_query = mysqli_query($conn, "INSERT INTO `products`(name, price, discount, category, image, description) VALUES('$name', '$price', '$discount', '$category', '$image', '$description')") or die('query failed');
         $message[] = '⚠️ Stock feature not yet enabled. Please run setup_stock.php!';
      }

      if($add_product_query){
         if($image_size > 2000000){
            $message[] = 'image size is too large';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'product added successfully!';
         }
      }else{
         $message[] = 'product could not be added!';
      }
   }
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_products.php');
}

if(isset($_POST['update_product'])){

   $update_p_id = $_POST['update_p_id'];
   $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
   $update_price = $_POST['update_price'];
   $update_discount = isset($_POST['update_discount']) ? $_POST['update_discount'] : 0;
   $update_stock = isset($_POST['update_stock']) ? $_POST['update_stock'] : 0;
   $update_description = mysqli_real_escape_string($conn, $_POST['update_description']);

   // Check if stock column exists before updating it
   $check_stock_col = mysqli_query($conn, "SHOW COLUMNS FROM `products` LIKE 'stock'");
   if(mysqli_num_rows($check_stock_col) > 0) {
      $update_query = "UPDATE `products` SET name = '$update_name', price = '$update_price', discount = '$update_discount', description = '$update_description', stock = '$update_stock' WHERE id = '$update_p_id'";
   } else {
      $update_query = "UPDATE `products` SET name = '$update_name', price = '$update_price', discount = '$update_discount', description = '$update_description' WHERE id = '$update_p_id'";
      $message[] = '⚠️ Warning: Stock column not yet created. Please run setup_stock.php first!';
   }
   
   $result = mysqli_query($conn, $update_query);
   if($result) {
      $message[] = 'product updated successfully!';
   } else {
      $message[] = 'Error updating product: ' . mysqli_error($conn);
   }

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/'.$update_image;
   $update_old_image = $_POST['update_old_image'];

   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = 'image file size is too large';
      }else{
         mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/'.$update_old_image);
      }
   }

   header('location:admin_products.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<!-- product CRUD section starts  -->

<section class="add-products">

   <h1 class="title">shop products</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>add product</h3>
      <input type="text" name="name" class="box" placeholder="enter product name" required>
      <input type="number" min="0" name="price" class="box" placeholder="enter product price" required>
      <input type="number" min="0" max="50" name="discount" class="box" placeholder="enter discount percentage (0-50%)">
      <select name="category" class="box" required>
         <option value="">Select Category</option>
         <option value="Fantasy & Adventure">🐉 Fantasy & Adventure</option>
         <option value="Science Fiction">🚀 Science Fiction</option>
         <option value="Mystery & Thriller">🔍 Mystery & Thriller</option>
         <option value="Romance & Love Stories">💕 Romance & Love Stories</option>
         <option value="Historical Fiction">📜 Historical Fiction</option>
         <option value="Self-Help & Personal Development">🌟 Self-Help & Personal Development</option>
         <option value="Biography & Memoirs">🎬 Biography & Memoirs</option>
         <option value="Young Adult">🎪 Young Adult</option>
         <option value="Horror & Supernatural">👻 Horror & Supernatural</option>
         <option value="Philosophy & Classics">⚖️ Philosophy & Classics</option>
      </select>
      <textarea name="description" class="box" placeholder="enter product description (2-3 lines)" rows="4" required></textarea>
      <input type="number" min="0" name="stock" class="box" placeholder="enter product stock quantity" required>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <input type="submit" value="add product" name="add_product" class="btn">
   </form>

</section>

<!-- product CRUD section ends -->

<!-- show products  -->

<section class="show-products">

   <div class="box-container">

      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <div class="box">
         <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <div class="category"><?php echo $fetch_products['category']; ?></div>
         <div class="description" style="font-size: 0.9rem; color: #666; margin: 0.5rem 0; max-height: 4.5rem; overflow: hidden; text-overflow: ellipsis;"><?php echo htmlspecialchars(substr($fetch_products['description'], 0, 150)); ?></div>
         <div class="price">Rs. <?php echo $fetch_products['price']; ?>/-</div>
         <?php if(!empty($fetch_products['discount']) && $fetch_products['discount'] > 0): ?>
         <div style="font-size: 0.9rem; color: #dc2626; font-weight: 600;"><i class="fas fa-tag"></i> Discount: <?php echo $fetch_products['discount']; ?>%</div>
         <?php endif; ?>
         <div class="stock" style="font-size: 1rem; font-weight: 600; color: #059669; margin: 0.5rem 0;"><i class="fas fa-cube"></i> Stock: <?php echo isset($fetch_products['stock']) ? $fetch_products['stock'] : 0; ?></div>
         <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">update</a>
         <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>

</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
      <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
      <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="enter product name">
      <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="enter product price">
      <input type="number" name="update_discount" value="<?php echo isset($fetch_update['discount']) ? $fetch_update['discount'] : 0; ?>" min="0" max="50" class="box" placeholder="enter discount percentage (0-50%)">
      <textarea name="update_description" class="box" rows="4" required placeholder="enter product description"><?php echo htmlspecialchars($fetch_update['description']); ?></textarea>
      <input type="number" name="update_stock" value="<?php echo isset($fetch_update['stock']) ? $fetch_update['stock'] : 0; ?>" min="0" class="box" required placeholder="enter product stock">
      <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="update" name="update_product" class="btn">
      <input type="reset" value="cancel" id="close-update" class="option-btn">
   </form>
   <?php
         }
      }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>

</section>







<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>