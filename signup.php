<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <link rel="stylesheet" href="signup.css">
    <link href="https://fonts.googleapis.com/css?family=Shadows+Into+Light|Swanky+and+Moo+Moo&display=swap" rel="stylesheet">
    <script>
    // for stopping resubmission on reload
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
      $name = "";
      $age = NULL;
      $gender = "";
      $userID = "";
      $password = "";
      $nameErr = "";
      $ageErr = "";
      $genderErr = "";
      $userIDErr = "";
      $passwordErr = "";
      $error = "fine";
      $male_status = "unchecked";
      $female_status = "unchecked";


      if ($_SERVER['REQUEST_METHOD'] == "POST"){

        $error = false;

        if(empty($_POST['name'])){
          $nameErr = "name is required";
          $error = true;
        }
        elseif (preg_match("/^[a-zA-Z'-]+$/",$_POST['name'])) {  //to ensure name only contains letters , preg_match checks for pattern and returns a boolean variable
          $name = $_POST['name'];
        }else {
          $nameErr = "name is invalid, it should only contain letters";
          $error = true;  // code...
        }

        if(empty($_POST['age'])){
          $ageErr = "age is required";
          $error = true;
        }
        else {
          $age = $_POST['age'];
        }

        if (empty($_POST['gender'])) {
          $genderErr = "gender is required";
          $error = true;
        }
        else {
          $gender = $_POST['gender'];
          if ($gender == "MALE") {
            $male_status = "checked";
          }elseif ($gender == "FEMALE") {
            $female_status = "checked";
          }
        }

        if(empty($_POST['userID'])){
          $userIDErr = "user ID is required";
          $error = true;
        }elseif (filter_var($_POST['userID'],FILTER_VALIDATE_EMAIL)) {
          $userID = $_POST['userID'];
        }
        else {
          $userIDErr = "user ID is valid";
          $error = true;
        }

        if(empty($_POST['password'])){
          $passwordErr = "password is required";
          $error = true;
        }
        else {
          $password = $_POST['password'];
        }
      }


      if(isset($_POST['submit'])){

        $conndb = mysqli_connect("localhost","root","","practice");

        if($conndb == false){
          die("error in connecting database : ".mysqli_connect_error());
        }


        $sql = "SELECT userID FROM information where userID = '$userID'";
        $result = mysqli_query($conndb,$sql);

        if(mysqli_num_rows($result)>0){
          $row = mysqli_fetch_array($result);
          $userIDErr = "username already taken";
          $error = true;
        }

        if ($error == false) {

          $sql = "INSERT INTO information(name,age,gender,userID,password) VALUES('$name','$age','$gender','$userID','$password')";
          if (mysqli_query($conndb,$sql)) {
            header("Location: login.php");
          }else {
            echo "error: ".mysqli_error($conndb);
            echo "try after some time.";
          }
        }
      }
    ?>
    <div class="form">
      <form class="signup" action="signup.php" method="post">
        <fieldset>
          <legend>SIGNUP TO ENJOY</legend>

          <label for="Name">NAME</label>
          <input id="Name" type="text" name="name" pattern="[A-Za-z].{1,}" value="<?php echo $name;?>">
          <span class="error"><?php echo $nameErr; ?></span><br>

          <label for="Age">AGE</label>
          <input id="Age" type="text" name="age" pattern="(?=.*\d).{1,}"  value="<?php echo $age;?>">
          <span class="error"><?php echo $ageErr; ?></span><br>

          <div class="gender">
          GENDER :
           <label for="Male">MALE</label>
           <input id="Male" type="radio" name="gender" value="MALE" <?php echo $male_status; ?>>
           <label for="Female">FEMALE</label>
           <input id="Female" type="radio" name="gender" value="FEMALE" <?php echo $female_status; ?>>
           <span class="error"><?php echo $genderErr; ?></span><br>
         </div><br>

           <label for="UserID">USER ID</label>
           <input id="UserID" type="text" name="userID" value="<?php echo $userID;?>">
           <span class="error"><?php echo $userIDErr; ?></span><br>

           <label for="Password">PASSWORD</label>
           <input id="Password" type="password" name="password" pattern="(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain One Capital Letter and it must contain more than 8 character" >
           <span class="error"><?php echo $passwordErr; ?></span>

           <div class="checkbox">
           <input type="checkbox" name="ShowPassword" onclick="Show()" >
           <label class="checkbox" id="checkbox" for="checkbox">Show Password</label><br>
           </div>

           <input id="submit" type="submit" name="submit" value="submit">

           <a href="login.php"> Already a user, want to login </a>
        </fieldset>
      </form>
    </div>
  </body>
</html>
