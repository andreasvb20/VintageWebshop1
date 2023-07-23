<?php

    if (!isset($_SESSION))
    {
    session_start();
    }
    if (isset($_SESSION['u_id'])){
        header('location: index.php');
    }

    //Vorbereitungen für Mail verschicken
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'vendor\autoload.php';

    $mail = new PHPMailer(TRUE);

    //Zufälliges Standardpasswort erstellen
    $r1 = (string) rand(1000,9999);
    $r2 = (string) rand(1000,9999);
    $standard_password = $r1 . 'Aa' .$r2;


    //Auslesen der Daten aus Registrierungformular
    $u_anrede = $_POST['u_anrede'];
    $u_fname = $_POST['u_fname'];
    $u_lname = $_POST['u_lname'];
    $u_mail = $_POST['u_mail'];
    $u_password = hash('sha512', $standard_password);//hashen des Passworts
    $u_lastlogin = 0;
    $u_screenheight = 0;
    $u_screenwidth = 0;
    $u_os = 0;
    $u_firstlogin = 0;
    $u_loginstatus = 0;
    $u_strasse = $_POST['u_strasse'];
    $u_hausnr = $_POST['u_hausnr'];
    $u_land = $_POST['u_land'];
    $u_plz = $_POST['u_plz'];
    $u_ort = $_POST['u_ort'];
    $u_tele = $_POST['u_tele'];



    //Database connection
    $conn = new mysqli('localhost', 'root', '', 'webshop1');
    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        //Existiert die E-Mail bereits?
        $query1 = $conn->prepare("SELECT * FROM user WHERE u_mail= ?");
        $query1->bind_param("s", $u_mail);
        $query1->execute();
        $query1_result = $query1->get_result();
        $query1_data = $query1_result->fetch_assoc();
        if(!is_countable($query1_data)){
            $num_rows = 0;
        }else{
            $num_rows = count($query1_data);
        }

        $query1->close();
        if($num_rows> 0){
            //E-Mail existiert schon ->Duplikat
            $error = "Die E-Mail existiert schon.";
            header("location: registration2.php");
                                                    //Hier vll noch Nachricht, dass E-Mail-Adresse schon existiert
        }
        else{
            //E-Mail existiert noch nicht -> Eintrag in Datenbank
            $stmt = $conn->prepare("insert into user(u_anrede, u_fname, u_lname, u_mail, u_password, u_lastlogin, u_screenheight, u_screenwidth, u_os, u_firstlogin, u_loginstatus, u_strasse, u_hausnr, u_land, u_plz, u_ort, u_tele)
                values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("sssssssssiissssss",$u_anrede, $u_fname, $u_lname, $u_mail, $u_password, $u_lastlogin, $u_screenheight, $u_screenwidth, $u_os, $u_firstlogin, $u_loginstatus, $u_strasse, $u_hausnr, $u_land, $u_plz, $u_ort, $u_tele);
            $stmt->execute();
            $stmt->close();
            
            //Registrierungsbestätigun Mail verschicken
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPDebug = 0;
            $mail->Mailer = "smtp";
            $mail->Host = "smtp.gmail.com";
            $mail->Port = "587"; // 8025, 587 and 25 can also be used. Use Port 465 for SSL.
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Username = "cocobellovinted@gmail.com";
            $mail->Password = "zrndcbokrbczwqjl";    //wichtig!
            $customername = $u_fname . ' ' .$u_lname;
            $mail->AddAddress($u_mail, "Coco Bello");

            $mail->Subject = 'Registrierungsbestätigung | Verifizierung';
            $mail->Body = '
            
            Sehr geehrte/r
            '.$u_anrede.' '.$customername.',

            vielen Dank für Ihre Registrierung!
            Ihr Account ist jetzt registriert.
            Im Folgenden sind Ihre 
            Anmeldedaten für die 
            Passwortvergabe:
            
            ------------------------
            Username:
            '.$u_mail.'
            Passwort:
            '.$standard_password.'
            ------------------------
            
            Nachdem Sie sich erstmalig
            angemeldet haben können Sie
            ein eigenes Passwort
            festlegen, welches Sie in
            Zukunft immer für die Anmeldung
            verwenden werden.

            Bitte hier klicken zur
            Passwortvergabe:
            http://localhost/HTML%20Tutorial/Login_Funktion/password2.php
            
            '; // Our message above including the link

            $mail->WordWrap = 50;

            if(!$mail->Send()) {
            echo '<center>Die E-Mail konnte nicht versendet werden.';
            echo '<center>Mailer error: ' . $mail->ErrorInfo;
            exit;
            } else {


                echo "<br> <center><h1/>Registrierung erfolgreich!</h1></center> <br><br><center><h3>Ihr Account wurde registriert! <br> <br>Wir haben Ihnen eine E-Mail mit Ihrem Login-Passwort zugeschickt. <br>Bitte loggen Sie sich mit diesem Passwort ein, um Ihre Registrierung abzuschließen.</h3></center> <br> <br>";
                echo '<center>E-Mail wurde an '.$u_mail.' versendet.</center> <br><br> <center><h3>Bitte schauen Sie nun in Ihr Postfach.</h3></center><br><br><br>';
           
           
                // Gooogle 2 Faktoren Authentifizierung für Registrierung
                require_once 'GoogleAuth/PHPGangsta/GoogleAuthenticator.php';

                $ga = new PHPGangsta_GoogleAuthenticator();
                $secret = $ga->createSecret();

                // QR Code dem User anzeigen
                $qrCodeUrl = $ga->getQRCodeGoogleUrl('Blog', $secret);
                echo "<br> <center><h2/>Aber vorher:</h2></center><br><center><h3>Bitte scannen Sie diesen QR Code mit der Google Authenticator App und merken Sie sich den secret gut!<br> <br>Sie werden die Authenticator App fortan immer zur Anmeldung benötigen. Die Authenticator App finden Sie im Appstore.</h3></center> <br> <br>";
                echo '<center><img src="' . $qrCodeUrl . '" alt="QR Code"></center>';

                //secret für User in user Tabelle speichern
                $query3 = $conn->prepare("UPDATE user SET u_secret = ? WHERE u_mail = ?");
                $query3->bind_param("ss", $secret, $u_mail);
                $query3->execute();
                $query3->close();

           
            }
            echo '<br><br><center><h3><a href="password2.php">Weiter zur Passwortvergabe</a></h3></center>';
        }

    }
    //Verbindung schließen
    $conn = null;
?>