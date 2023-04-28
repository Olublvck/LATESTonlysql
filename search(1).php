<?php
 include'header.php';
 include'lib/connection.php';
 $name=htmlspecialchars($_POST['name']);
 $sql = "SELECT * FROM product where name='$name'  OR catagory='$name'";
 $result = $conn -> query ($sql);
 if(isset($_POST['add_to_cart'])){

  if(isset($_SESSION['auth']))
  {
     if($_SESSION['auth']!=1)
     {
         header("location:login.php");
     }
  }
  else
  {
     header("location:login.php");
  }
  $user_id = htmlspecialchars($_POST['user_id'], ENT_QUOTES, 'UTF-8');
  $product_name = htmlspecialchars($_POST['product_name'], ENT_QUOTES, 'UTF-8');
  $product_price = htmlspecialchars($_POST['product_price'], ENT_QUOTES, 'UTF-8');
  $product_id = htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8');
    $product_quantity = 1;
  
    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE productid = '$product_id'  && userid='$user_id'");
  
    if(mysqli_num_rows($select_cart) > 0){
      echo $message[] = 'product already added to cart';
    }
    else{
       $insert_product = mysqli_query($conn, "INSERT INTO `cart`(userid, productid, name, quantity, price) VALUES('$user_id', '$product_id', '$product_name', '$product_quantity', '$product_price')");
     echo $message[] = 'product added to cart succesfully';
     
    }
  
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/pending_orders.css">

</head>
<body>

<div class="container pendingbody">
  <h5>Search Result</h5>
  <div class="container">
   <div class="row">
   <?php
          if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
              ?>
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="post">
            <div class="col-md-3 col-sm-6 col-6">
              <div>
              <img src="admin/product_img/<?php echo htmlspecialchars($row['imgname'], ENT_QUOTES, 'UTF-8'); ?>" width="" height="300" style="vertical-align:left">
                <!-- <img src="smiley.gif" alt="Smiley face" width="42" height="42" style="vertical-align:middle"> -->
              </div>
              <div>
              <div>
              <h6><?php echo htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8'); ?></h6>
                <span><?php echo htmlspecialchars($row["Price"], ENT_QUOTES, 'UTF-8'); ?></span>
               <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['userid'], ENT_QUOTES, 'UTF-8'); ?>">
               <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>">
               <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');?>">
               <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($row['Price'], ENT_QUOTES, 'UTF-8'); ?>">              
              </div>
              <input type="submit" class="btn btn btn-primary" value="Add to Cart" name="add_to_cart">
              </div>
              
            </div>
            </form>
            <?php 
    }
        } 
        else 
            echo "0 results";
        ?>

            
          </div>
  </div>
</div>
    
</body>
</html>