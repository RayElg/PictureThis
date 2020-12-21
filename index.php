<!DOCTYPE html>
<html>
<head>    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>PictureThis</title>
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
            <form action="./" method="get">
                <input class="form-control" type="text" placeholder="Search" name="search">
            </form>
            </div>
        </div>
        <br>
        <br>

        <?php
            include("indexCode.php"); //DYNAMIC LOGIC MOVED TO indexCode.php
        ?>
            
    </div>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>