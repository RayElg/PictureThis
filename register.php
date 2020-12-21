<?php

    include("./config.php");
    
    $errorText = "";
    if(isset($_POST["submit"])){ //POST data sent
        $name = $_POST["username"];
        $hash = $_POST["password"];
        if(strlen($hash) > 4 and strlen($name) > 4){ //Pass & username valid
            $hash = password_hash($hash, PASSWORD_DEFAULT);

            $rand = random_bytes(10); //Generate token (unused, can be used later for token authentication)
            $rand = bin2hex($rand);

            $sql = mysqli_connect($server, $username, $password, $db); //Query for existing user
            $query = "SELECT name FROM Users WHERE name='$name'";
            $data = mysqli_query($sql, $query);
            
            if(mysqli_num_rows($data) > 0){
                $errorText = "User already exists!";
            }else{ //No user found
                $query = "INSERT INTO Users (name, hash, token) VALUES ('$name', '$hash','$rand')"; //Add user to DB
                if(mysqli_query($sql, $query)){
                    session_start();
                    $_SESSION["name"] = $name; //Add name to session data
                    $_SESSION["token"] = $token;



                    header("Location: ./profile.php?user=$name"); //Redirect
                }else{
                    $errorText = "Issue creating user";
                }
                
            }
        }else{
            $errorText = "Make sure your password and username are both 5 characters or longer.";
        }

    }

?>

<!DOCTYPE html>
<html>
<head>    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>PictureThis - Profile</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="./">PictureThis</a>&nbsp&nbsp&nbsp&nbsp&nbsp
        <?php
            session_start();
            if(isset($_SESSION['name'])){
                echo("<a class=\"btn btn-link\" href=\"./profile.php?user=". $_SESSION['name'] ."\">". $_SESSION['name'] ."</a>");
                echo("<a class=\"btn btn-link\" href=\"./logout.php\"> Logout </a>");
            }else{
                echo("<a class=\"btn btn-link\" href=\"./register.php\"> Register </a>&nbsp&nbsp");
                echo("<a class=\"btn btn-link\" href=\"./login.php\"> Login </a>");
            }
        ?>

        <div class="collapse navbar-collapse justify-content-end"><a class="btn btn-primary" href="./upload.php">Upload</a></div>
    </nav>
    <br>
    <div class="container">
        <div class="row">
            <div class="col align-self-center">
                <h2>Register for PictureThis:</h2>
                <form method="post" action="./register.php">
                    Username: <input type="text" name="username"><br> <br>
                    Password: <input type="password" name="password"><br> <br>
                    <?php
                        echo($errorText . "<br>");
                    ?>
                    <input type="submit" class="btn btn-primary" value="Register" name="submit">
                </form>
            </div>
        </div>
    </div>
</body>
</html>