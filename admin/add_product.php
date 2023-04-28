<?php

SESSION_START();

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
 include'header.php';
 include'lib/connection.php';
 $result=null;
if (isset($_POST['submit'])) 
{
  $name=htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
  $catagory=htmlspecialchars($_POST['catagory'], ENT_QUOTES, 'UTF-8');
  $description=htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
  $quantity=htmlspecialchars($_POST['quantity'], ENT_QUOTES, 'UTF-8');
  $price=htmlspecialchars($_POST['price'], ENT_QUOTES, 'UTF-8');
  $filename = htmlspecialchars($_FILES["uploadfile"]["name"], ENT_QUOTES, 'UTF-8');
    // sanitize file name to remove any possible directory traversal characters
    $filename = preg_replace("/[^a-zA-Z0-9-_\.]/", "", $filename);
        // extract filename without any directory information
    $filename = basename($filename);

    $insertSql = "INSERT INTO product(name, catagory, description, quantity, price, imgname) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("ssssss", $name, $catagory, $description, $quantity, $price, $filename);
    if ($stmt->execute())
    {
        $result="<h2>*******Data insert success*******</h2>";
        $tempname = htmlspecialchars($_FILES["uploadfile"]["tmp_name"], ENT_QUOTES, 'UTF-8');   
        $folder = "product_img/".$filename;

        move_uploaded_file($tempname, $folder);
    }
    else
    {
     die($conn -> error);
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
</head>
<body>
    <div class="container">
      <?php echo htmlentities ($result);?>
        <h4>Add Product</h4>
    <form action="<?php echo htmlspecialchars ($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
  <div class="mb-3">
    <label for="exampleInputName" class="form-label">Product Name</label>
    <input type="text" name="name" class="form-control" id="exampleInputName"> value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
  </div>
  <div class="mb-3">
    <label for="exampleInputType" class="form-label">Catagory</label>
    <input type="text" name="catagory"  class="form-control" id="exampleInputType"> value="<?php echo htmlspecialchars($_POST['category'] ?? ''); ?>">
  </div>
  <div class="mb-3">
    <label for="exampleInputDescription" class="form-label">Description</label>
    <input type="text" name="description" class="form-control" id="exampleInputDescription"> value="<?php echo htmlspecialchars($_POST['description'] ?? ''); ?>">
  </div>
  <div class="mb-3">
    <label for="exampleInputQuantity" class="form-label">Quantity</label>
    <input type="number" name="quantity" class="form-control" id="exampleInputQuantity"> value="<?php echo htmlspecialchars($_POST['quantity'] ?? ''); ?>">
  </div>
  <div class="mb-3">
    <label for="exampleInputPrice" class="form-label">Price</label>
    <input type="Number" name="price" class="form-control" id="exampleInputPrice"> value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>">
  </div>
  <div class="mb-3">
        <label for="uploadfile" class="form-label">Image</label>
        <input type="file" name="uploadfile" />
    </div>
  <button type="submit" name="submit" class="btn btn-primary">Submit</button>
</form>
    </div>
</body>
</html>