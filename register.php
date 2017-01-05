<?php
 ob_start();
 session_start();
 if( isset($_SESSION['user'])!="" ){
  header("Location: home.php");
 }
 include_once 'dbconnect.php';

 $error = false;

 if ( isset($_POST['btn-signup']) ) {

  // clean user inputs to prevent sql injections
  $name = trim($_POST['name']);
  $name = strip_tags($name);
  $name = htmlspecialchars($name);

  $email = trim($_POST['email']);
  $email = strip_tags($email);
  $email = htmlspecialchars($email);

  $pass = trim($_POST['pass']);
  $pass = strip_tags($pass);
  $pass = htmlspecialchars($pass);

  $inst = trim($_POST['inst']);
  $inst = strip_tags($inst);
  $inst = htmlspecialchars($inst);

  $years = trim($_POST['years']);
  $years = strip_tags($years);
  $years = htmlspecialchars($years);

  $utype = trim($_POST['utype']);
  $utype = strip_tags($utype);
  $utype = htmlspecialchars($utype);

  // basic name validation
  if (empty($name)) {
   $error = true;
   $nameError = "Please enter your full name.";
  } else if (strlen($name) < 3) {
   $error = true;
   $nameError = "Name must have atleat 3 characters.";
  } else if (!preg_match("/^[a-zA-Z ]+$/",$name)) {
   $error = true;
   $nameError = "Name must contain alphabets and space.";
  }

  //basic email validation
  if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
   $error = true;
   $emailError = "Please enter valid email address.";
  } else {
   // check email exist or not
   $query = "SELECT userEmail FROM users WHERE userEmail='$email'";
   $result = mysql_query($query);
   $count = mysql_num_rows($result);
   if($count!=0){
    $error = true;
    $emailError = "Provided Email is already in use.";
   }
  }
  // password validation
  if (empty($pass)){
   $error = true;
   $passError = "Please enter password.";
  } else if(strlen($pass) < 6) {
   $error = true;
   $passError = "Password must have atleast 6 characters.";
  }

  // password encrypt using SHA256();
  $password = hash('sha256', $pass);

  // basic institution name validation
  if (empty($inst)) {
   $error = true;
   $instError = "Please enter your Institution's full name.";
 } else if (strlen($inst) < 1) {
   $error = true;
   $instError = "Name must have at least 2 characters.";
 } else if (!preg_match("/^[a-zA-Z ]+$/",$inst)) {
   $error = true;
   $instError = "Name must contain alphabets and space.";
  }

  // years experience validation
  if (empty($years)) {
   $error = true;
   $yearsError = "Please enter number of years experience with medical imaging.";
 } else if (!preg_match("/^[1-9][0-9]{0,2}$/",$years)) {
   $error = true;
   $instError = "Enter integer number of years with minimum of 1.";
  }

  // basic user type validation
  if (empty($utype)) {
   $error = true;
   $utypeError = "Please enter user type (MD, Physicist, etc.).";
 } else if (strlen($inst) < 1) {
   $error = true;
   $utypeError = "User type musee be at least 2 characters.";
 } else if (!preg_match("/^[a-zA-Z ]+$/",$utype)) {
   $error = true;
   $utypeError = "Name must contain only alphabets and space.";
  }

  // if there's no error, continue to signup
  if( !$error ) {

   $query = "INSERT INTO users(userName,userEmail,userPass,userInst,years,utype) VALUES('$name','$email','$password','$inst','$years','$utype')";
   $res = mysql_query($query);

   if ($res) {
    //$errTyp = "success";
    //$errMSG = "Successfully registered, you may login now";
    unset($name);
    unset($email);
    unset($pass);
    unset($inst);
    unset($years);
    unset($utype);
    $res2=mysql_query("SELECT userId FROM users WHERE email='$email'");
    if ($res2){
      $row=mysql_fetch_array($res2);
      $idname = echo $row['userId'];
      $query2 = "CREATE TABLE $idname (study_index INT(3) UNSIGNED PRIMARY KEY, fnl VARCHAR(30) NOT NULL, fnr VARCHAR(30) NOT NULL, slices INT(3) UNSIGNED)";
      // $res3 = mysql_query($query2);
      // if (!$res3){
      //   $errTyp = "danger";
      //   $errMSG = "Something went wrong with database, try again later...";
      // }else {
      //   $errTyp = "success";
      //   $errMSG = echo $row['userId'];
      // }
    }
   } else {
    $errTyp = "danger";
    $errMSG = "Something went wrong, try again later...";
   }

  }


 }
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Duke RAILabs MAFC - Login & Registration System</title>
<link rel="stylesheet" href="bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="style.css" type="text/css" />
<style>
body {
  background-color:#0C090A;
  color:#737CA1
}

</style>
</head>
<body>

<div class="container">

 <div id="login-form">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">

     <div class="col-md-12">

         <div class="form-group">
             <h2 class="">Duke RAILabs MAFC Sign Up</h2>
            </div>

         <div class="form-group">
             <hr />
            </div>

            <?php
   if ( isset($errMSG) ) {

    ?>
    <div class="form-group">
             <div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
    <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                </div>
             </div>
                <?php
   }
   ?>

            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
             <input type="text" name="name" class="form-control" placeholder="Enter Name" maxlength="50" value="<?php echo $name ?>" />
                </div>
                <span class="text-danger"><?php echo $nameError; ?></span>
            </div>

            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
             <input type="email" name="email" class="form-control" placeholder="Enter Your Email" maxlength="40" value="<?php echo $email ?>" />
                </div>
                <span class="text-danger"><?php echo $emailError; ?></span>
            </div>

            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
             <input type="password" name="pass" class="form-control" placeholder="Enter Password" maxlength="15" />
                </div>
                <span class="text-danger"><?php echo $passError; ?></span>
            </div>

            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
             <input type="text" name="inst" class="form-control" placeholder="Enter Institution Name" maxlength="50" value="<?php echo $inst ?>" />
                </div>
                <span class="text-danger"><?php echo $instError; ?></span>
            </div>

            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
             <input type="text" name="years" class="form-control" placeholder="Enter Years of Experience with Medical Imaging" maxlength="2" value="<?php echo $years ?>" />
                </div>
                <span class="text-danger"><?php echo $yearsError; ?></span>
            </div>

            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
             <input type="text" name="utype" class="form-control" placeholder="Enter User Type (MD, Physicist, etc.)" maxlength="20" value="<?php echo $utype ?>" />
                </div>
                <span class="text-danger"><?php echo $utypeError; ?></span>
            </div>

            <div class="form-group">
             <hr />
            </div>

            <div class="form-group">
             <button type="submit" class="btn btn-block btn-primary" name="btn-signup" style="background-color:#737CA1;">Sign Up</button>
            </div>

            <div class="form-group">
             <hr />
            </div>

            <div class="form-group">
             <a href="index.php"> <h4 style="color:#737CA1;">Sign in Here...</h4></a>
            </div>

        </div>

    </form>
    </div>

</div>

</body>
</html>
<?php ob_end_flush(); ?>
