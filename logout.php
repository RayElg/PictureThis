<?php //Removes session data, redirects to index
    session_start();
    session_destroy();

    header("Location: ./");
?>