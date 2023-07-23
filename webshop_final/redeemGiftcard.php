<?php
    if (!isset($_SESSION))
    {
        session_start();
    }

    if (!isset($_SESSION['u_id'])){

        header("refresh:0; url=login2.php");
    }
    if (!isset($_SESSION['korbmenge'])){

        header("refresh:0; url=cart.php");
    }
    if ($_SESSION['korbmenge']==0){

        header("refresh:0; url=cart.php");
    }

    //Abfrage nach Gutscheincode in Tabelle
    //gib den gut_rabatt wenn $_POST['gutscheincode'] in Tabelle vorhanden

    // Datenbankverbindung
    $conn = mysqli_connect("localhost", 'root', '', "webshop1");
    if (!$conn) {
      // Bei Verbindungsfehler zeige den Fehler
      die("Connection failed: " . mysqli_connect_error());
    }
    
    // Prüfe, ob Gutscheincode eingegeben
    if (isset($_POST['gutscheincode'])) {
      $gutscheincode = mysqli_real_escape_string($conn, $_POST['gutscheincode']);
    
      // Prüfe, ob Gutscheincode in Datenbank existiert
      $query1 = "SELECT gut_rabatt FROM gutscheincode WHERE gut_code='$gutscheincode'";
      $result = mysqli_query($conn, $query1);
      $row = mysqli_fetch_assoc($result);
    
      if ($row) {
        // Gutscheincode existiert in Datenbank
        //Gutscheinrabatt aus der Tabelle in Session Variable speichern
        $gutscheinrabatt = intval($row['gut_rabatt']);
        $_SESSION['gutscheinrabatt'] += $gutscheinrabatt;
        
        //Lösche den Gutscheincode aus der Tabelle
        $query2 = "DELETE FROM gutscheincode WHERE gut_code='$gutscheincode'";
        mysqli_query($conn, $query2);
        echo "<br><br><br>"; 
        echo "<center><h2>Gutscheincode erfolgreich!</h2></center>";
        echo "<center><h2>Der Rabatt wurder erfolgreich angewandt! <br> Ihnen wurden ".$gutscheinrabatt."% Rabatt gewährt!</h2></center>";
        header("refresh:3; url=order.php");
      } else {
        // Gutscheincode NICHT in Datenbank
        echo "<br><br><br>"; 
        echo "<center><h2>Fehler</h2></center>";
        echo "<center><h2>Der Gutscheincode ist leider fehlerhaft oder nicht vorhanden!</h2></center>";
        echo "<center><h2>Versuchen Sie es nochmal.</h2></center>";
        header("refresh:3; url=order.php");
      }
    }
   

?>
