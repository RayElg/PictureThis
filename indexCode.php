<?php

if(isset($_GET["search"])){ //If a search has been performed

    include("./config.php"); //Secret values (login info, etc)
    
    $search = $_GET["search"];
    echo("<h2>" . $search . ": </h2>");

    $sql = mysqli_connect($server, $username, $password, $db); //Connect & perform query
    $query = "SELECT img FROM tags WHERE tag LIKE '%$search%'";
    $data = mysqli_query($sql, $query);
    
    

    $imgSet = []; //Using an associative array as a set

    if(mysqli_num_rows($data) > 0){
        while($row = mysqli_fetch_assoc($data)) {
            $img = $row["img"]; //Add these ids to the 'set'
            $imgSet["$img"] = $img;
        }
    }

    $query = "SELECT id FROM Images WHERE title LIKE '%$search%'"; //This query is for searching title rather than tags
    $data = mysqli_query($sql, $query);
    if(mysqli_num_rows($data) > 0){
        while($row = mysqli_fetch_assoc($data)) {
            $img = $row["id"]; //Add these ids to the 'set'
            $imgSet["$img"] = $img;
        }
    }

    echo("<div class=\"row\">");


    foreach($imgSet as &$img){ //Iterate over the img ids...
        $query = "SELECT fname, author, title, time, public FROM Images WHERE id='$img'"; //Query for img info based on id
        $imgData = mysqli_query($sql, $query);
        if(mysqli_num_rows($imgData) > 0){
            while($row = mysqli_fetch_assoc($imgData)) { //While there are images left, output them & their info into the bootstrap grid system
                if($row['public']==1){
                    echo("<div class=\"col-md-3 align-self-center\">");
                    $fname = $row['fname'];
                    echo("<div style=\"background-color: lightgray; padding: 4px; margin: 4px;\">");
                    echo("<img src=\"./images/$fname\" style=\"width: 100%;\">");
                    echo("<p>" . $row["title"] .  "</p>");
                    echo("<p>Uploaded by <a href=\"./profile.php?user=" . $row["author"] . "\">" . $row["author"] .  "</a></p>");
                    echo("<p>" . $row["time"] .  "</p>");
                    echo("</div></div>");
                }
            }
        }
    }




}else{ //A search has not been performed: can simply output an "about" section.
    $str = <<<DOC
        <div class="row">
            <div class="col md-4">
                <h3>Welcome to PictureThis!</h3>
                <p>This image repository allows you to upload your images, choose if they appear in search results and to others viewing your profile ('public' option), and
                view other user's public images. In addition, upon uploading an image it is automatically tagged using an image classification API, allowing public images
                to be searched by both the title of the image and the characteristics of the image's subject.
                </p>

                <p>To get started, select "Register" or "Upload" from the navbar, or enter your search query in the box above.</p>
            </div>
        </div>
    DOC;
    echo($str);
}

?>