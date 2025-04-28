
<?php
    include('header.php');
?>
<?php

session_start();
include('server/connection.php');

if(isset($_POST['login_btn'])){

    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt=$conn->prepare("SELECT user_id, user_name, user_email, user_password FROM users WHERE user_email=? AND user_password=? LIMIT 1");
    $stmt->bind_param('ss',$email,$password);
    if($stmt->execute()){
        $stmt->bind_result($user_id,$user_name,$user_email,$user_password);
        $stmt->store_result();
        if($stmt->num_rows()==1){
            $stmt->fetch();
            $_SESSION['user_id']=$user_id;
            $_SESSION['user_name']=$user_name;
            $_SESSION['user_email']=$user_email;
            $_SESSION['logged_in']=true;
            header('location: Account.php?message=Logged in successfully!');
        }else{
            header('location: login.php?error=Could not verify your account!');
        }

    }else{
        header('location: login.php?error=Something went wrong!');
    }

}

?>

<?php include('header.php');?>
    <!-- login -->
     <section class="my-5 py-5">
        <div class="container text-center mt-3 pt-5">
            <h2 class="from-weight-bold">Login</h2>
            <hr class="mx-auto">
        </div>
        <div class="mx-auto container">
            <form id="login-form" action="login.php" method="POST">
                <p style="color: red" class="text-center"><?php if(isset($_GET['error'])){echo $_GET['error'];}?></p>
                <div class="form-group">
                    <label> Email</label>
                    <input type="text" class="form-control" id="login-email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label> Password</label>
                    <input type="password" class="form-control" id="login-password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <input name="login_btn" type="submit" class="btn" id="login-btn" value="Login">
                </div>
                <div class="form-group">
                    <a id="register-url" class="btn" href="register.php">Don't have an account? Register </a>
                    <p><a id="register-url" class="btn" href="admin/login.php">Login as Admin? </a></p>
                </div>
            </form>
        </div>
     </section>


     <?php include('footer.php');?>