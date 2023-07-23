<?php
    if (!isset($_SESSION))
    {
      session_start();
    }
    if (isset($_SESSION['u_id'])){
      header('location: index.php');
    }
    
    $u_mail = $_POST['u_mail'];
    $u_password = $_POST['u_password'];
    $u_screenheight = $_POST['u_screenheight'];
    $u_screenwidth = $_POST['u_screenwidth'];
    $u_os = $_POST['u_os'];
    $u_loginstatus = 1;
    $u_lastlogin = date('l j. F Y', time());


    //Database connection here
    $con = new mysqli("localhost", "root","", "webshop1");
    if ($con->connect_error) {
        die("Failed to connect : ".$con->connect_error);
    } else {
        //Prüfen, ob Login Daten mit Daten aus Datenbank übereinstimmen
        $stmt = $con->prepare("select * from user where u_mail = ?");
        $stmt->bind_param("s", $u_mail);
        $stmt->execute();
        $stmt_result = $stmt->get_result();
        $stmt->close();
        if($stmt_result->num_rows > 0) {
            $data = $stmt_result->fetch_assoc();
            //hashen des Passworts
            $u_password = hash("sha512", $u_password);
            //Prüfen ob Passwort übereinstimmt
            if($data["u_password"] === $u_password){

                //Weiterleitung zur eigenen Passworterstellung, falls ErstLogin also firstlogin==0
                $abfrage1 = $con->prepare("SELECT * FROM user WHERE u_mail= ?");
                $abfrage1->bind_param("s", $u_mail);
                $abfrage1->execute();
                $abfrage1_result = $abfrage1->get_result();
                $abfrage1_data = $abfrage1_result->fetch_assoc();
                $abfrage1->close();

                if($abfrage1_data["u_firstlogin"] == 0){
                    echo $abfrage1_data["u_firstlogin"];
                    //Weiterleitung zur eigenen Passworterstellung
                    header ("location: password2.php");
                }else{
                    //Anmeldung erfolgreich!


                    //Achtung: Hier kommt noch die 2 Faktoren Authentfizierung
                    $code = $_POST['u_2FA'];
                    // secret des Users aus der Datenbank abfragen
                    $query4 = $con->prepare("SELECT * FROM user WHERE u_mail= ?");
                    $query4->bind_param("s",$u_mail);
                    $query4->execute();
                    $query4_result = $query4->get_result();
                    $query4_data = $query4_result->fetch_assoc();
                    $query4->close();

                    $secret = $query4_data['u_secret'];
                    //Google Authenticator Bibliothek laden
                    require_once 'GoogleAuth/PHPGangsta/GoogleAuthenticator.php';
                    $ga = new PHPGangsta_GoogleAuthenticator();
                    // Verify the code submitted by the user
                    $checkResult = $ga->verifyCode($secret, $code, 1);
                    if ($checkResult) {
                        //Anmeldung erfolgreich!! Alle Eingaben korrekt!


                        echo "<h2>Anmeldung erfolgreich!</h2>";
                        //Finde User ID heraus
                        $query2 = $con->prepare("SELECT * FROM user WHERE u_mail= ?");
                        $query2->bind_param("s",$u_mail);
                        $query2->execute();
                        $query2_result = $query2->get_result();
                        $query2_data = $query2_result->fetch_assoc();
                        $query2->close();
                        //Setting der Session Variable mit Userdaten mit u_id
                        $_SESSION['u_id'] = $query2_data['u_id'];
                        $_SESSION['u_mail'] = $u_mail;
                        $_SESSION['u_lastlogin'] = $query2_data['u_lastlogin'];
                        $_SESSION['u_anrede'] = $query2_data['u_anrede'];
                        $_SESSION['u_fname'] = $query2_data['u_fname'];
                        $_SESSION['u_lname'] = $query2_data['u_lname'];
                        $_SESSION['u_strasse'] = $query2_data['u_strasse'];
                        $_SESSION['u_hausnr'] = $query2_data['u_hausnr'];
                        $_SESSION['u_plz'] = $query2_data['u_plz'];
                        $_SESSION['u_ort'] = $query2_data['u_ort'];

                        //Danach setze Login-Status=1, width=?, height=? und os = ?
                        $query1 = $con->prepare("UPDATE user SET u_loginstatus = ?, u_lastlogin = ?, u_screenheight = ?, u_screenwidth = ?, u_os = ? WHERE u_mail = ?");
                        $query1->bind_param("isssss", $u_loginstatus, $u_lastlogin, $u_screenheight, $u_screenwidth, $u_os, $u_mail);
                        $query1->execute();
                        $query1->close();

                        //Anmeldung erfolgreich abgeschlossen, Weiterleitung zu Startseite
                        //Außerdem Warenkorbmenge anzeigen
                        $query = $con->prepare("SELECT SUM(w_menge) AS shopcardsum FROM warenkorb WHERE u_idf = ?");
                        $query->bind_param("i", $_SESSION['u_id']);
                        $query->execute();
                        $korbmenge = $query->get_result();
                        if ($korbmenge->num_rows > 0) {
                            $row = $korbmenge->fetch_assoc();
                            $_SESSION['korbmenge'] = $row['shopcardsum'];
                        } else {
                            $_SESSION['korbmenge'] = 0;
                        }
                        $query->close();
                        //Außerdem Warenkorb Gesamtsumme anzeigen
                        $query2 = $con->prepare("SELECT SUM(p.pro_preis * w.w_menge) AS total_cost
                        FROM warenkorb w
                        JOIN produkt p ON w.pro_idf = p.pro_id
                        WHERE w.u_idf = ?
                        ");
                        $query2->bind_param("i", $_SESSION['u_id']);
                        $query2->execute();
                        $total = $query2->get_result();
                        if ($total->num_rows > 0) {
                            $row = $total->fetch_assoc();
                            $_SESSION['total'] = $row['total_cost'];
                        } else {
                            $_SESSION['total'] = 0;
                        }
                        $query2->close();

                        header ("location: index.php");


                    }else{
                        echo "<br><br><br>";
                        echo "<center><h2>Fehler: Ungültiger 2 Faktoren Code!</h2></center>";
                        echo "<center><h2>Bitte versuchen Sie es erneut.</h2></center>";
                        echo "<center><h2>Achtung: Falls Sie den Authenticator-Secret vergessen, wird ihr Account unbrauchbar!</h2></center>";
                        header("refresh:4; url=login2.php");
                    }
                }

            } else {
                echo "<br><br><br>";
                echo "<center><h2>Fehler: Ungültige E-Mail oder ungültiges Passwort</h2></center>";
                header("refresh:3; url=login2.php");
            }
        } else {
            echo "<br><br><br>";
            echo "<center><h2>Fehler: Ungültige E-Mail oder ungültiges Passwort</h2></center>";
        }   header("refresh:3; url=login2.php");
        $con =null; //schließen der Datenbankverbindung
    }
?>
