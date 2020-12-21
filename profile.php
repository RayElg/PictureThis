<!DOCTYPE html>
<html>
<head>    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>PictureThis - Profile</title>
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
                <?php
                    $author = $_GET["user"];
                    echo("<h2>" . $author . "'s images:</h2>");

                ?>
            </div>
        </div>

            <?php

                include("./config.php");

                session_start();

                $sql = mysqli_connect($server, $username, $password, $db);
                $query = "SELECT id, title, fname, public, time, author FROM Images WHERE author='$author'"; //Query all of this user's images
                $data = mysqli_query($sql, $query);

                if(mysqli_num_rows($data) > 0){
                    echo("<div class=\"row\">");
                    while($row = mysqli_fetch_assoc($data)) {  //Iterate over all the images...
                        if($row['public'] == 1 or $row["author"]==$_SESSION["name"]){ //If either the viewer has permission to see these private images, or they are public
                            //Output the image & image information
                            echo("<div class=\"col-md-3 align-self-center\">");
                            $fname = $row['fname'];
                            echo("<div style=\"background-color: lightgray; padding: 4px; margin: 4px;\">");
                            echo("<img src=\"./images/$fname\" style=\"width: 100%;\">");
                            echo("<p>" . $row["title"] .  "</p>");
                            echo("<p>" . $row["time"] .  "</p>");
                            if($row['public']==1){
                                echo("<p>Public</p>");
                            }
                            echo("<p> TAGS: ");
                                $id = $row['id'];
                                $query = "SELECT tag, img FROM tags WHERE img='$id'";
                                $tags = mysqli_query($sql, $query);
                                if(mysqli_num_rows($tags) > 0){
                                    while($tag = mysqli_fetch_assoc($tags)) {
                                        $t = $tag['tag'];
                                        echo(" " . $t . ", ");
                                    }
                                }else{
                                    echo("None");
                                }

                            echo("</p>");
                            echo("</div></div>");
                        }
                    }
                }

            ?>


    </div>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>