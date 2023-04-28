
<?php

include "lib/connection.php";
$result = null;

if (isset($_POST['u_submit'])) {

  $f_name = $_POST['u_name'];
  if (!preg_match("/^[a-zA-Z]+$/", $f_name)) {
    echo "Error: Firstname should only contain letters.";
 }
 else {
    // valid input, continue with processing
 }
  $l_name = $_POST['l_name'];
  if (!preg_match("/^[a-zA-Z]+$/", $l_name)) {
    echo "Error: Firstname should only contain letters.";
 }
 else {
    // valid input, continue with processing
 }
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $cpass = $_POST['c_pass'];

  // Validate input fields
  if (empty($f_name) || empty($l_name) || empty($email) || empty($pass) || empty($cpass)) {
    $result = "<h3 class=error-msg>Please fill in all fields!</h3>";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $result = "<h3 class=error-msg>Invalid email format!</h3>";
  } elseif (strlen($pass) < 8) {
    $result = "<h3 class=error-msg>Password should be at least 8 characters long!</h3>";
  } elseif ($pass !== $cpass) {
    $result = "<h3 class=error-msg>Passwords do not match!</h3>";
  } else {
    // Check if email already exists
    $loginquery = "SELECT * FROM users WHERE email='$email'";
    $loginres = $conn->query($loginquery);

    if ($loginres->num_rows > 0) {
      $result = "<h3 class=error-msg>Account already exists!</h3>";
    } else {
        // Then submit once all the checks are done.
      $hashPassword = password_hash($pass, PASSWORD_DEFAULT);
      $insertSql = "INSERT INTO users(f_name ,l_name, email, pass) VALUES ('$f_name', '$l_name','$email', '$hashPassword')";

      if ($conn->query($insertSql)) {
        $result = "<h3 class=success-msg>Account Open success!!!</h3>";
        header("location:login.php");
      } else {
        die($conn->error);
      }
    }
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/responsive.css">
    <title>411</title>



</head>

<body class="bg-gradient-primary">

    <div class="container">

     <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                                <?php echo $result;  ?>
                            </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" pattern="[a-zA-Z]+" id="exampleFirstName"
                                            placeholder="First Name" name="u_name">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" pattern="[a-zA-Z]+" id="exampleLastName"
                                            placeholder="Last Name" name="l_name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="exampleInputEmail"
                                        placeholder="Email Address" name="email">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            id="exampleInputPassword" placeholder="Password" name="pass">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            id="exampleRepeatPassword" placeholder="Repeat Password" name="c_pass">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block" name="u_submit">Register Account</button>
                            

                            <hr>
                            <div class="text-center">
                                <a class="small" href="login.php">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    </div>


</body>

</html>