<?php
    if (!isset($_SESSION))
    {
      session_start();
    }
    if (isset($_SESSION['u_id'])){
        
        //Bei Abmeldung soll User nicht mehr in der UserAnzahl online angezeigt werden
        //Setze Login-Status=0, weil abgemeldet
        $con = new mysqli("localhost", "root","", "webshop1");
        if ($con->connect_error) {
            die("Failed to connect : ".$con->connect_error);
        }else{
            $query1 = $con->prepare("UPDATE user SET u_loginstatus = 0 WHERE u_id = ?");
            $query1->bind_param("i", $_SESSION['u_id']);
            $query1->execute();
            $query1->close();
        }

        $_SESSION = array();
        session_destroy();

    }
    header('location: ../index.php');
?>

