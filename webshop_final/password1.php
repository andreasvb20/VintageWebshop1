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
    //hashen des alten Passworts
    $u_password = hash("sha512", $u_password);
    $u_passwordnew = $_POST['u_passwordnew'];
    //hashen des neuen Passworts
    $u_passwordnew = hash("sha512", $u_passwordnew);
    $u_firstlogin = 1;
    $u_loginstatus = 1;
    $u_lastlogin = date('l j. F Y', time());
    $u_screenheight = $_POST['u_screenheight'];
    $u_screenwidth = $_POST['u_screenwidth'];
    $u_os = $_POST['u_os'];

    //Database connection here
    $con = new mysqli("localhost", "root","", "webshop1");
    if ($con->connect_error) {
        die("Failed to connect : ".$con->connect_error);
    } else {

        $stmt = $con->prepare("select * from user where u_mail = ?");
        $stmt->bind_param("s", $u_mail);
        $stmt->execute();
        $stmt_result = $stmt->get_result();
        if($stmt_result->num_rows > 0) {
            $data = $stmt_result->fetch_assoc();
            $stmt->close();
            if($data["u_password"] === $u_password){
                if($data["u_firstlogin"] == "0"){
                    //Anmeldung erfolgreich! Passwortänderung erfolgreich!


                    //Achtung: Hier kommt noch die 2 Faktoren Authentfizierung
                    // secret des Users aus der Datenbank abfragen
                    $code = $_POST['u_2FA'];
                    $secret = $data["u_secret"];
                    //Google Authenticator Bibliothek laden
                    require_once 'GoogleAuth/PHPGangsta/GoogleAuthenticator.php';
                    $ga = new PHPGangsta_GoogleAuthenticator();
                    // Verify the code submitted by the user
                    $checkResult = $ga->verifyCode($secret, $code, 1);
                    if ($checkResult) {

                        //Alles korrekt -> Anmeldung und Passwortänderung erfolgreich!

                        //Danach Passwort ersetzten, Login-Status=1, Erstlogin=1 und LastLogin = aktuelle Zeit
                        $query1 = $con->prepare("UPDATE user SET u_password = ?, u_firstlogin = ?, u_loginstatus = ?, u_lastlogin = ?, u_screenheight = ?, u_screenwidth = ?, u_os = ? WHERE u_mail = ?");
                        $query1->bind_param("siisssss", $u_passwordnew, $u_firstlogin, $u_loginstatus, $u_lastlogin, $u_screenheight, $u_screenwidth, $u_os, $u_mail);
                        $query1->execute();
                        $query1->close();

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
                        $_SESSION['u_lastlogin'] = $query2_data['u_ort'];
                        $_SESSION['u_anrede'] = $query2_data['u_anrede'];
                        $_SESSION['u_fname'] = $query2_data['u_fname'];
                        $_SESSION['u_lname'] = $query2_data['u_lname'];
                        $_SESSION['u_lastlogin'] = $query2_data['u_lastlogin'];
                        $_SESSION['u_strasse'] = $query2_data['u_strasse'];
                        $_SESSION['u_hausnr'] = $query2_data['u_hausnr'];
                        $_SESSION['u_plz'] = $query2_data['u_plz'];
                        $_SESSION['u_ort'] = $query2_data['u_ort'];

                        
                        echo "<br><br><br>";
                        echo "<center><h2>Passwortvergabe erfolgreich!</h2></center>";
                        echo "<center><h2>Ihre Registrierung / Passwortvergabe ist jetzt abgeschlossen und Sie sind angemeldet!</h2></center>";
                        //Anmeldung erfolgreich abgeschlossen, Weiterleitung zu Startseite
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

                        header ("refresh:4; url=index.php");


                    } else {
                        // 2 Faktoren Authentifizierung ist falsch
                        echo "<br><br><br>"; 
                        echo "<center><h2>Fehler</h2></center>";
                        echo "<center><h2>Ungültige 2 Faktoren Authentifizierung!</h2></center>";
                        echo "<center><h2>Versuchen Sie es erneut.</h2></center>";
                        echo "<center><h2>Achtung: Falls Sie Ihren Authenticator secret verloren haben ist Ihr Account unbrauchbar!</h2></center>";
                        header("refresh:5; url=password2.php");  //zeigt Text, wartet und redirected
                    }
                }else{ // user hat Registrierung schonmal abgeschlossen und hat bereits Passwort geändert
                    echo "<br><br><br>"; 
                    echo "<center><h2>Fehler</h2></center>";
                    echo "<center><h2>Sie haben ihr Passwort schonmal geändert.</h2></center>";
                    echo "<center><h2>Bitte melden Sie sich ganz normal an.</h2></center>";
                    header("refresh:5; url=login2.php");  //zeigt Text, wartet und redirected
                }
            }else{  //Falsches Passwort
                echo "<br><br><br>";
                echo "<center><h2>Fehler</h2></center>";
                echo "<center><h2>Ungültige E-Mail Adresse oder Passwort!</h2></center>";
                echo "<center><h2>Versuchen Sie es erneut.</h2></center>";
                header("refresh:5; url=password2.php");  //zeigt Text, wartet und redirected
            }
        }else{ //Falsche E-Mail
            echo "<br><br><br>";
            echo "<center><h2>Fehler</h2></center>";
            echo "<center><h2>Ungültige E-Mail Adresse oder Passwort!</h2></center>";
            echo "<center><h2>Versuchen Sie es erneut</h2></center>";
            header("refresh:5; url=password2.php");  //zeigt Text, wartet und redirected
        }
        $con =null; //schließen der Datenbankverbindung
    }
    
?>