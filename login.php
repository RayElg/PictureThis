<?php

    include("./config.php");

        
    $errorText = "";
    if(isset($_POST["submit"])){ //If POST data sent
        $name = $_POST["username"];
        $pass = $_POST["password"];

        $rand = random_bytes(10); //Generate a random token (unused at this point. Can be used later for token authentication)
        $rand = bin2hex($rand);

        $sql = mysqli_connect($server, $username, $password, $db); //Query DB for user
        $query = "SELECT name, hash FROM Users WHERE name='$name'";
        $data = mysqli_query($sql, $query);
        echo(mysqli_error($sql));
        if(mysqli_num_rows($data) > 0){ //User found
            while($row = mysqli_fetch_assoc($data)) {
                if(password_verify($pass, $row['hash'])){ //Verify password
                    
                    session_start();
                    $_SESSION["name"] = $name; //Store name in session data
                    $_SESSION['token'] = $token;
                    header("Location: ./profile.php?user=$name"); //Redirect
                }else{
                    
                    $errorText = "Password doesn't match.";
                }
            }
        }else{
            $errorText = "No user found with this name and password. $name , $hash";
        }


    }
    

?>

<!DOCTYPE html>
<html>
<head>    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>PictureThis - Login</title>
</head>
<body>
    <nav class="navbar navbar-expand-sm navbar-light bg-light">
        <a class="navbar-brand" href="./">PictureThis</a>
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

        <div class="navbar-collapse justify-content-end"><a class="btn btn-primary" href="./upload.php">Upload</a></div>
    </nav>
    <br>
    <div class="container">
        <div class="row">
            <div class="col align-self-center">
                <h2>Login to PictureThis:</h2>
                <form method="post" action="./login.php">
                    Username: <input type="text" name="username"><br> <br>
                    Password: <input type="password" name="password"><br> <br>
                    <?php
                        echo($errorText);
                    ?>
                    <input type="submit" class="btn btn-primary" value="Login" name="submit">
                </form>
            </div>
        </div>
    </div>
</body>
</html>