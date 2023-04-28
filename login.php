<?php 
SESSION_START();

include "lib/connection.php";

$msg = '';

if (isset($_POST['submit'])) 
{
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $pass = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    $loginquery="SELECT * FROM users WHERE email='$email'";
    $loginres = $conn->query($loginquery);

    if ($loginres->num_rows > 0) 
    {
        $result=$loginres->fetch_assoc();

        $verify = password_verify($pass, $result['pass']);

        if($verify){
            $username=htmlspecialchars($result['f_name'], ENT_QUOTES, 'UTF-8');
            $userid=$result['id'];

            $_SESSION['username'] = $username;
            $_SESSION['userid'] = $userid;
            $_SESSION['auth'] = 1;
            header("location:index.php");

        }else{
            $msg="<h3 class=error-msg>Login Failed!!!</h3>";
        }
    } else {
        $msg="<h3 class=error-msg>Account not found!!!</h3>";
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
    <title>cse411</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/responsive.css">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="post">
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                        <?php echo $msg;  ?>
                                    </div>
                                    <form class="user">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                name="email"
                                                placeholder="Enter Email Address">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Password" name="password">
                                        </div>
                                        <div class="form-group">
                                            
                                        </div>
                                                                  
                                            <input class="btn btn-primary btn-user btn-block" type="submit" name="submit" value="login">
                                  
                                        <hr>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
                                    </div>
                                </div>
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