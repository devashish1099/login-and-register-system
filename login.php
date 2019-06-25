<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <link rel="stylesheet" href="login.css">
    <link href="https://fonts.googleapis.com/css?family=Shadows+Into+Light|Swanky+and+Moo+Moo&display=swap" rel="stylesheet">
    <script>
      if ( window.history.replaceState ) {
          window.history.replaceState( null, null, window.location.href );
      }


    function Show(){
      var ShowPassword = document.getElementById('Password');
      if (ShowPassword.type == "password") {
        ShowPassword.type = "text";
      }else {
        ShowPassword.type = "password";
      }
    }

    </script>
    <?php
    $submiterror = "";
    $userID = "";
    $password = "";
    $userIDErr = "";
    $passwordErr = "";
    $error = "fine";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

      $error = false;

      if(empty($_POST['userID'])){
        $userIDErr = "UserID is required";
        $error = true;
      }elseif ( filter_var($_POST['userID'] , FILTER_VALIDATE_EMAIL )){
        $userID = $_POST['userID'];
      }
      else {
        $userIDErr = "UserID is not valid";
        $error = true;
      }

      if(empty($_POST['password'])){
        $passwordErr = "age is required";
        $error = true;
      }else {
        $password = $_POST['password'];
      }

    }

    //echo "your name is $name and you are $age year old and also biologically you are $gender";

    if(isset($_POST['userID'])){

      $conndb = mysqli_connect("localhost","root","","practice");

      if($conndb == false){
        die("error in connecting database : ".mysqli_connect_error());
      }

      if($error == false){

        $sql = "SELECT * FROM information where userID = '$userID'";
        $result = mysqli_query($conndb,$sql);

        if(mysqli_num_rows($result)>0){
          $row = mysqli_fetch_array($result);
          if($row['password'] == $password){

            session_start();

            $_SESSION['userID'] = $userID;
            $_SESSION['name'] = $row['name'];
            $_SESSION['gender'] = $row['gender'];
            $_SESSION['age'] = $row['age'];

            header("Location: home.php");


          }else {
            $passwordErr = 'incorrect password';
          }

        }elseif (mysqli_num_rows($result) == 0) {
          $submiterror = "no such user exists";
        }
      }
    }

    ?>
  <div class="form">
    <form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" submit="true">
      <fieldset>
        <legend>LOGIN TO ENJOY</legend>
        <span class="error"><?php echo $submiterror; ?></span>

        <label for="UserID">USER ID</label>
        <input id="userID" type="text" name="userID" value="<?php echo $userID; ?>">
        <span class="error"><?php echo $userIDErr; ?></span><br>

        <label for="Password">PASSWORD</label>
        <input id="Password" type="password" name="password" pattern="(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain One UPPERCASE and LOWERCASE Letter and it must contain more than 8 character">
        <span class="error"><?php echo $passwordErr; ?></span>

        <div>
        <input type="checkbox" name="ShowPassword" onclick="Show()" >
        <label id="checkbox" for="checkbox">Show Password</label><br>
        </div>

         <input id="submit" type="submit" value="submit" name="submit">

         <a href="signup.php"> Not a user, want to register </a>
      </fieldset>
    </form>
  </div>
  </body>
</html>
