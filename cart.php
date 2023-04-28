<?php
 include'header.php';
 include'lib/connection.php';


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
if(isset($_POST['order_btn'])){
  $userid = htmlspecialchars($_POST['user_id'], ENT_QUOTES, 'UTF-8');
  $name = htmlspecialchars($_POST['user_name'], ENT_QUOTES, 'UTF-8');
  $number = htmlspecialchars($_POST['number'], ENT_QUOTES, 'UTF-8');
  $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
  $mobnumber = htmlspecialchars($_POST['mobnumber'], ENT_QUOTES, 'UTF-8');
  $txid = htmlspecialchars($_POST['txid'], ENT_QUOTES, 'UTF-8');
  /*$price_total = $_POST['total'];*/
  $status="pending";

  $cart_query = mysqli_query($conn, "SELECT * FROM `cart` where userid='$userid'");
  $price_total = 0;
  if(mysqli_num_rows($cart_query) > 0){
     while($product_item = mysqli_fetch_assoc($cart_query)){
        $product_name[] = $product_item['productid'] .' ('. $product_item['quantity'] .') ';
        $product_price = number_format($product_item['price'] * $product_item['quantity']);
        $price_total += $product_price;
        $sql = "SELECT * FROM product";
        $result = $conn -> query ($sql);
      
        if (mysqli_num_rows($result) > 0) {
          // output data of each row
          while($row = mysqli_fetch_assoc($result)) {
            if($row['id']===$product_item['productid'])
            {
              if($product_item['quantity']<=$row['quantity'])
              {
                $update_id=$row['id'];
                $t=$row['quantity']-$product_item['quantity'];
                $update_quantity_query = mysqli_query($conn, "UPDATE `product` SET quantity = '$t' WHERE id = '$update_id'");
                

                $flag=1;


                

              }
              else
              {
                echo "out of stock " .htmlspecialchars($row['name']." Quantity:".$row['quantity']);
              }
            }
          }

        }

     };
     if($flag==1)
     {
       $total_product = implode(', ',$product_name);
       $detail_query = mysqli_query($conn, "INSERT INTO `orders`(userid, name, address, phone,  mobnumber, txid, totalproduct, totalprice, status) VALUES('$userid','$name','$address','$number','$mobnumber','$txid','$total_product','$price_total','$status')") or die($conn ->error);
           
             $cart_query1 = mysqli_query($conn, "delete FROM `cart` where userid='$userid'");
             header("location:index.php");

     }
  };



}

$id = htmlspecialchars($_SESSION['userid'], ENT_QUOTES);
 $sql = "SELECT * FROM cart where userid='$id'";
 $result = $conn -> query ($sql);

 if(isset($_POST['update_update_btn'])){
  $update_value = htmlspecialchars($_POST['update_quantity'], ENT_QUOTES);
  $update_id = htmlspecialchars($_POST['update_quantity_id'], ENT_QUOTES);
  $update_quantity_query = mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_value' WHERE id = '$update_id'");
  if($update_quantity_query){
     header('location:cart.php');
  };
};

if(isset($_GET['remove'])){
  $remove_id = htmlspecialchars($_GET['remove'], ENT_QUOTES);
  mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'");
  header('location:cart.php');
};


?>

<div class="container pendingbody">
  <h5>cart</h5>
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Quantity</th>
      <th scope="col">Price</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  <?php
  $total=0;
          if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
              ?>
    <tr>
      <th scope="row">1</th>
      <td><?php echo htmlspecialchars($row["name"], ENT_QUOTES); ?><
  
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES); ?>" method="post">
      <input type="hidden" name="update_quantity_id" value="<?php echo htmlspecialchars($row['id'], ENT_QUOTES); ?>">
      <input type="number" name="update_quantity" min="1" value="<?php echo htmlspecialchars($row['quantity'], ENT_QUOTES); ?>">
      <input type="submit" value="update" name="update_update_btn">
      </form></td> 
      <td><?php echo htmlspecialchars($row["price"] * $row["quantity"], ENT_QUOTES); ?></td>
      <?php $total=$total+$row["price"]*$row["quantity"] ;?>
     

      <input type="hidden" name="status" value="pending">   
      <td><a href="cart.php?remove=<?php echo htmlspecialchars($row['id'], ENT_QUOTES); ?>">remove</a></td>
    </tr>
    <?php 
    }
    echo "total=" . htmlspecialchars($total, ENT_QUOTES);
        } 
        else 
            echo "0 results";
        ?>
  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES); ?>" method="post">

        <h5>if Cash On delivary Then Put 0 in bkash Field</h5>
      <div class="input-group form-group">
      <input type="hidden" name="total" value="<?php echo htmlspecialchars($total, ENT_QUOTES); ?>">
      <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['userid'], ENT_QUOTES); ?>">
      <input type="hidden" name="user_name" value="<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES); ?>">
      <input type="text" class="form-control" placeholder="Address" name="address">
       </div>
       <div class="input-group form-group">
        <input type="number" class="form-control" placeholder="Phone Number" name="number">
       </div>
       <div class="input-group form-group">
        <input type="number" class="form-control" placeholder="Bkash/Nogod/Rocket Number" name="mobnumber">
       </div>
       <div class="input-group form-group">
        <input type="text" class="form-control" placeholder="Txid" name="txid">
       </div>

      <div class="form-group">
      <input type="submit" value="Order Now" name="order_btn">
    </div>

    </form>
  </tbody>
</table>
</div>


<?php
 include'footer.php';
?>