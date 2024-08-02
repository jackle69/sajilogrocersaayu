<?php

@include 'config.php';

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
   <meta http-equiv="X-UA-Compatible" content="IE=edge">  //yo chai aba default mero edge ma chalne//
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <!-- font awesome cdn link taneko  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- hamrai link css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="about">

   <div class="row"> //css ko row//

      <div class="box">
         <img src="images/about-img-1.png" alt="">
         <h3>Why choose us?</h3>
         <p>Choose Sajilo Grocers for a streamlined, user-friendly solution that offers real-time inventory tracking, comprehensive reporting, and seamless integration with existing tools. Our customizable platform ensures your unique business needs are met, while our secure, reliable system and exceptional customer support provide peace of mind. Enjoy competitive pricing and a hassle-free experience as you optimize your grocery store operations with us.</p>
         <a href="contact.php" class="btn">Contact us</a>
      </div>

      <div class="box">
         <img src="images/about-img-2.png" alt="">
         <h3>what we provide?</h3>
         <p>Sajilo Grocers strengthens the relationship between buyers and sellers by ensuring real-time inventory tracking, so customers always find what they need. We offer insights into customer preferences, helping you tailor offerings to build loyalty. With an easy-to-use interface, customizable features, and secure, reliable support, we help you create a personalized shopping experience that fosters lasting connections and trust.</p>
         <a href="shop.php" class="btn">Our shop</a> <!--tyo shop ma thicheko ani redirect -->
      </div>

   </div>

</section>

<section class="reviews">

   <h1 class="title">Clients reivews</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/pic-1.png" alt="">
         <p>This has truly connected us with our shops, making our operations smoother and more personalized.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Shiva</h3>
      </div>


      <div class="box">
         <img src="images/pic-6.png" alt="">
         <p>This platform has streamlined our store's operations and helped us build stronger, more meaningful relationships with our customers.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Shankhar</h3>
      </div>

   </div>

</section>









<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>