<?php
    $errorText = "";
    $user = "ANON";

    session_start();
    if(isset($_SESSION["name"])){ //If the user is logged in...
        $user = $_SESSION["name"];
    }

    include("./config.php");
    

    $sql = mysqli_connect($server, $username, $password, $db); //Connect to DB
    $succ = false;
    if(isset($_POST["submit"])){ //If POST data submitted

        $numFiles = count($_FILES['imgFile']['name']); //How many images submitted
        $images = [];
        
        for($i = 0; $i < $numFiles; $i = $i + 1){ //Iterate over images

            $rand = random_bytes(30); //Generate random string of bytes to store the image as
            $rand = bin2hex($rand);

            $imgSize = getimagesize($_FILES["imgFile"]["tmp_name"][$i]);
            
            if($imgSize != false){ //If this is an image at all
                $target = $baseDir . $rand; //Target location of the image (/var/www/...../PictureThis/images/...)
                if(move_uploaded_file($_FILES['imgFile']['tmp_name'][$i], $target)){ //If we were able to move to our image directory...
                    $name = $_FILES['imgFile']['name'][$i]; //Get original filename
                    
                    if(isset($_POST["public"])){ //Get if this image should be public
                        if($_POST["public"] == "on"){
                            $public = 1;
                        }else{
                            $public = 0;
                        }
                    }else{
                        $public = 0;
                    }
                    $query = "INSERT INTO Images (title, author, fname, public) VALUES ('$name', '$user', '$rand', '$public')"; //Insert image into database
                    if(mysqli_query($sql, $query)){
                        $entry = []; //Remembert the image info, we pack it into $entry and append entry to $images[]
                        $entry["title"] = "$name";
                        $entry["fname"] = $rand;
                        $entry["url"] =  "<a href=\"./images/$rand\">Image $i</a>";
                        $entry["public"] = $public;
                        $entry["id"] = mysqli_insert_id($sql);
                        $images[] = $entry;
                        $succ = true;

                        //NOW TAGGING...
                        $curl = curl_init("https://api.imagga.com/v2/tags?image_url=https://raynorelgie.com/PictureThis/images/$rand"); //Tag the images
                        curl_setopt($curl, CURLOPT_USERPWD, $imagga);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        $response = curl_exec($curl);   
                        #echo($response);

                        //$response = <<<DOC
                        //{"result":{"tags":[{"confidence":100,"tag":{"en":"turbine"}},{"confidence":64.8014373779297,"tag":{"en":"wind"}},{"confidence":63.3033409118652,"tag":{"en":"generator"}},{"confidence":61.4765892028809,"tag":{"en":"energy"}}]}}
                        //DOC;

                        $response = json_decode($response, true);
                        $response = $response["result"]["tags"];
                        $tags = array_slice($response,0,3); //Get only 3 tags from the API
                        
                        
                        $idWas = mysqli_insert_id($sql);  //Most recent image inserted...
                        $querys = [];
                        foreach($tags as &$t){ //Add tag to database
                            $t = $t["tag"]["en"];
                            $tquery = "INSERT INTO tags (img, tag) VALUES ('$idWas', '$t')";
                            if(mysqli_query($sql, $tquery)){
                            }else{
                                echo(mysqli_error($sql));
                            }
                        }

                    }else{
                        $errorText = "There was an error uploading your image. Please try again later";
                    }


                }else{
                    echo("Upload failed...");
                }

            }else{
                $errorText = $_FILES['imgFile']['name'][$i] . ": invalid image.";
                break;
            }

        }
    }
?>
<!DOCTYPE html>
<html>
<head>    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>PictureThis - Upload</title>
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
                <form action="./upload.php" method="post" enctype="multipart/form-data" style="background-color: lightgray; padding: 18px;">
                    <h3>Upload your images...</h3>
                    Image... &nbsp <input type="file" name="imgFile[]" multiple>
                    <br>
                    Public? <input type="checkbox" name="public">
                    <br>
                    <?php
                        echo($errorText);
                    ?>
                    <br>
                    <input type="submit" class="btn btn-primary" name="submit" value="Upload">
                </form>
                <br>
                <?php
                    if($succ){ //If we succesfully added images earlier...
                        echo("Your images are below. Remember their URL, especially if they are private. <br>");
                        

                        echo("<div class=\"row\">");

                        for($i = 0; $i < count($images); $i = $i + 1){ //Iterate over the entrys we created earlier, outputting image information
                            
                        

                            echo("<div class=\"col-md-3 align-self-center\">");
                            echo($images[$i]["url"] . "<br>");
                            $fname = $images[$i]['fname'];
                            echo("<div style=\"background-color: lightgray; padding: 4px; margin: 4px;\">");
                            echo("<img src=\"./images/$fname\" style=\"width: 100%;\">");
                            echo("<p>" . $images[$i]["title"] .  "</p>");
                            if($images[$i]['public']==1){
                                echo("<p>Public</p>");
                            }
                            echo("<p> TAGS: ");
                                $id = $images[$i]['id'];
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
                        echo("</div>");
                    }


                ?>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>