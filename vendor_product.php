<?php

@include 'config.php';

session_start();

// Correct session variable name
$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit(); // Ensure no further code is executed after redirect
}

if (isset($_POST['add_product'])) {

    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
    $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);

    $image = filter_var($_FILES['image']['name'], FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

    // Check if the product name already exists
    $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
    $select_products->execute([$name]);

    if ($select_products->rowCount() > 0) {
        $message[] = 'Product name already exists!';
    } else {
        // Insert new product with the logged-in admin's ID
        $insert_products = $conn->prepare("INSERT INTO `products` (name, category, details, price, image, user_id) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_products->execute([$name, $category, $details, $price, $image, $admin_id]);

        if ($insert_products) {
            if ($image_size > 2000000) {
                $message[] = 'Image size is too large!';
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'New product added!';
            }
        }
    }
}

if (isset($_GET['delete'])) {

    $delete_id = $_GET['delete'];
    $select_delete_image = $conn->prepare("SELECT image FROM `products` WHERE id = ?");
    $select_delete_image->execute([$delete_id]);
    $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
    unlink('uploaded_img/' . $fetch_delete_image['image']);
    $delete_products = $conn->prepare("DELETE FROM `products` WHERE id = ?");
    $delete_products->execute([$delete_id]);
    $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
    $delete_wishlist->execute([$delete_id]);
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
    $delete_cart->execute([$delete_id]);
    header('location:vendor_products.php');
    exit(); // Ensure no further code is executed after redirect
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>

   <!-- Icon link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- CSS file -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'vendor_header.php'; ?>

<section class="add-products">

   <h1 class="title">Add New Product</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <input type="text" name="name" class="box" required placeholder="Enter product name">
            <select name="category" class="box" required>
               <option value="" selected disabled>Select category</option>
               <option value="Bakery">Bakery</option>
               <option value="Stationery">Stationery</option>
               <option value="Instant Foods">Instant Foods</option>
               <option value="Drinks">Drinks</option>
            </select>
         </div>
         <div class="inputBox">
            <input type="number" min="0" name="price" class="box" required placeholder="Enter product price">
            <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
         </div>
      </div>
      <textarea name="details" class="box" required placeholder="Enter product details" cols="30" rows="10"></textarea>
      <input type="submit" class="btn" value="Add Product" name="add_product">
   </form>

</section>

<section class="show-products">

   <h1 class="title">Products Added</h1>

   <div class="box-container">

   <?php
      // Show only products added by the logged-in admin
      $show_products = $conn->prepare("SELECT * FROM `products` WHERE `user_id` = ?");
      $show_products->execute([$admin_id]);

      if ($show_products->rowCount() > 0) {
         while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {  
   ?>
   <div class="box">
      <div class="price">Rs. <?= htmlspecialchars($fetch_products['price']); ?>/-</div>
      <img src="uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="">
      <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
      <div class="cat"><?= htmlspecialchars($fetch_products['category']); ?></div>
      <div class="details"><?= htmlspecialchars($fetch_products['details']); ?></div>
      <div class="flex-btn">
         <a href="vendor_update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Update</a>
         <a href="vendor_products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
      </div>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">No products added yet!</p>';
      }
   ?>

   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
