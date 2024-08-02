<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('Location: login.php');
    exit();
}

// Initialize message array
$message = [];

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_wishlist'])) {
        $pid = filter_input(INPUT_POST, 'pid', FILTER_SANITIZE_STRING);
        $p_name = filter_input(INPUT_POST, 'p_name', FILTER_SANITIZE_STRING);
        $p_price = filter_input(INPUT_POST, 'p_price', FILTER_SANITIZE_STRING);
        $p_image = filter_input(INPUT_POST, 'p_image', FILTER_SANITIZE_STRING);

        $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
        $check_wishlist_numbers->execute([$p_name, $user_id]);

        $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
        $check_cart_numbers->execute([$p_name, $user_id]);

        if ($check_wishlist_numbers->rowCount() > 0) {
            $message[] = 'Already added to wishlist!';
        } elseif ($check_cart_numbers->rowCount() > 0) {
            $message[] = 'Already added to cart!';
        } else {
            $insert_wishlist = $conn->prepare("INSERT INTO `wishlist` (user_id, pid, name, price, image) VALUES (?, ?, ?, ?, ?)");
            $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
            $message[] = 'Added to wishlist!';
        }
    }

    if (isset($_POST['add_to_cart'])) {
        $pid = filter_input(INPUT_POST, 'pid', FILTER_SANITIZE_STRING);
        $p_name = filter_input(INPUT_POST, 'p_name', FILTER_SANITIZE_STRING);
        $p_price = filter_input(INPUT_POST, 'p_price', FILTER_SANITIZE_STRING);
        $p_image = filter_input(INPUT_POST, 'p_image', FILTER_SANITIZE_STRING);
        $p_qty = filter_input(INPUT_POST, 'p_qty', FILTER_SANITIZE_STRING);

        $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
        $check_cart_numbers->execute([$p_name, $user_id]);

        if ($check_cart_numbers->rowCount() > 0) {
            $message[] = 'Already added to cart!';
        } else {
            $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
            $check_wishlist_numbers->execute([$p_name, $user_id]);

            if ($check_wishlist_numbers->rowCount() > 0) {
                $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
                $delete_wishlist->execute([$p_name, $user_id]);
            }

            $insert_cart = $conn->prepare("INSERT INTO `cart` (user_id, pid, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
            $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
            $message[] = 'Added to cart!';
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
    <title>Quick View</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="quick-view">
    <h1 class="title">Quick View</h1>

    <?php
    // Ensure 'id' parameter is set and valid
    $pid = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!$pid) {
        echo '<p class="empty">Invalid product ID.</p>';
        exit();
    }

    // Fetch product details
    $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
    $select_products->execute([$pid]);

    if ($select_products->rowCount() > 0) {
        while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
    ?>
    <form action="" class="box" method="POST">
        <div class="price">Rs.<span><?= htmlspecialchars($fetch_products['price']); ?></span>/-</div>
        <img src="uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="">
        <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
        <div class="details"><?= htmlspecialchars($fetch_products['details']); ?></div>
        <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_products['id']); ?>">
        <input type="hidden" name="p_name" value="<?= htmlspecialchars($fetch_products['name']); ?>">
        <input type="hidden" name="p_price" value="<?= htmlspecialchars($fetch_products['price']); ?>">
        <input type="hidden" name="p_image" value="<?= htmlspecialchars($fetch_products['image']); ?>">
        <input type="number" min="1" value="1" name="p_qty" class="qty">
        <input type="submit" value="Add to Wishlist" class="option-btn" name="add_to_wishlist">
        <input type="submit" value="Add to Cart" class="btn" name="add_to_cart">
    </form>
    <?php
        }
    } else {
        echo '<p class="empty">No products added yet!</p>';
    }
    ?>
</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
