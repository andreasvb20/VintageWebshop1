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


    //Auslesen der E-Mail aus Passwort vergessen Formular
    $u_mail = $_POST['u_mail'];
    $u_password = hash('sha512', $standard_password);//hashen des Passworts
    $u_firstlogin = 0;


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
        $query1->close();
        if(!is_countable($query1_data)){
            $num_rows = 0;
        }else{
            $num_rows = count($query1_data);
        }

        if(($num_rows> 0)){
            //E-Mail existiert schon  -> Passwort Änderung Eintrag in Datenbank
            $stmt1 = $conn->prepare("UPDATE user SET u_password = ?, u_firstlogin = ? WHERE u_mail = ?");
            $stmt1->bind_param("sis", $u_password, $u_firstlogin, $u_mail);
            $stmt1->execute();
            $stmt1->close();
            
            //Passwort vergessen Mail verschicken
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
            $customername = 'Damen und Herren';
            $mail->AddAddress($u_mail, "Coco Bello");

            $mail->Subject = 'Passwort zuruecksetzen';
            $mail->Body = '
            
            Sehr geehrte
            Damen und Herren,

            Ihr Passwort kann jetzt
            zurückgesetzt werden.
            Im Folgenden sind Ihre 
            Anmeldedaten, um Ihr
            Passwort zurueckzusetzen:
            
            ------------------------
            Username:
            '.$u_mail.'
            Passwort:
            '.$standard_password.'
            ------------------------
            
            Nachdem Sie sich auf der
            folgenden Seite
            angemeldet haben können Sie
            ein neues Passwort
            festlegen, welches Sie in
            Zukunft immer für die Anmeldung
            verwenden werden.

            Bitte hier klicken, um Ihr 
            Passwort zurückzusetzen:
            http://localhost/HTML%20Tutorial/Login_Funktion/forgotpassword2.html
            
            '; // Our message above including the link

            $mail->WordWrap = 50;

            if(!$mail->Send()) {
            echo '<center>Die E-Mail konnte nicht versendet werden.';
            echo '<center>Mailer error: ' . $mail->ErrorInfo;
            exit;
            } else {
                echo "<br> <center><h1/>Ihr Passwort kann zur&uuml;ckgesetzt werden</h1></center> <br><br><center><h3>Wir haben Ihnen eine E-Mail mit Ihrem Standard-Passwort für die Passwort-Neuvergabe zugeschickt. <br>Bitte loggen Sie sich auf der folgenden Seite mit diesem Passwort ein, um Ihr Passwort zur&uuml;ckzusetzen.</h3></center> <br> <br>";
                echo '<center>E-Mail wurde an '.$u_mail.' versendet.</center> <br><br> <center><h3>Bitte schauen Sie nun in Ihr Postfach.</h3></center><br>';
            }
            echo '<center><h3><a href="password2.php">Weiter zur Passwort-Neuvergabe</a></h3></center>';
        } else {
            //E-Mail existiert schon ->Duplikat
            $error = "Mit dieser E-Mail Adresse ist noch kein Account verbunden";

            echo "<br><br><br>"; 
            echo "<center><h2>Fehler</h2></center>";
            echo "<center><h2>Es existiert kein Account mit dieser E-Mail</h2></center>";
            echo "<center><h2>Bitte versuchen Sie es mit einer anderen Mail-Adresse</h2></center>";
            header("refresh:3; url=forgotpassword2.php");  //zeigt Text, wartet und redirected

        }
    }
    //Verbindung schließen
    $conn = null;
?>