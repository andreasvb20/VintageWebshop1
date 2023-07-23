<?php
    if (!isset($_SESSION))
    {
        session_start();
    }

    if (!isset($_SESSION['u_id'])){

        header("location: login2.php");
    }
    if (!isset($_SESSION['korbmenge'])){

        header("location: product.php");
    }
    if ($_SESSION['korbmenge']==0){

        header("location: product.php");
    }
    require_once ('php_include/functions.php');


    //Variablen sammeln für Datenbank Eintrag in Bestellung und Posten
    $bes_versand = $_SESSION['versandart'];
    $versand = $_SESSION['versand'];
    $u_id = $_SESSION['u_id'];
    $bes_datum = date('m/d/Y h:i:s a', time());
    $bes_total = $_SESSION['total'];
    //Mengenrabatt und Gutscheinrabatt zusammenrechnen
    $_SESSION['totalrabatt'] = ($_SESSION['totalrabatt']+ ($_SESSION['total']-$_SESSION['totalrabatt']) * ($_SESSION['gutscheinrabatt']/100));
    $bes_totalrabatt = $_SESSION['totalrabatt'];

    //Datenbank Verbindung
    $con = new mysqli("localhost", "root","", "webshop1");
    if ($con->connect_error) {
        die("Failed to connect : ".$con->connect_error);
    }
    //Abfrage der ID der letzten Bestellung, da kein Autowert für ID möglich
    $query1 = $con->prepare("SELECT * FROM bestellung WHERE bes_id=(SELECT max(bes_id) FROM bestellung)");
    $query1->execute();
    $query1_result = $query1->get_result();
    $query1->close();
    if ($lastentry = mysqli_fetch_assoc($query1_result)){
        $bes_id = $lastentry['bes_id'] + 1;
    }else {
        $bes_id = 1;
    }

    //eine neue Bestellung in Tabelle bestellung einfürgen
    $query2 = $con->prepare("INSERT INTO bestellung (bes_id, u_idf, bes_datum, bes_versand, bes_total, bes_totalrabatt) VALUES(?, ?, ?, ?, ?, ?)");
    $query2->bind_param("iisidd", $bes_id, $u_id, $bes_datum, $versand, $bes_total, $bes_totalrabatt);
    $query2->execute();
    $query2->close();


    //Für jedes Produkt einen neuen Eintrag in Tabelle Posten
    //Finde alle Produkte des Users in Tabelle Warenkorb
    $query3 = $con->prepare("SELECT * FROM warenkorb WHERE u_idf =? ");
    $query3->bind_param("i", $u_id);
    $query3->execute();
    $query3_result = $query3->get_result();
    $query3->close();

    while ($row = mysqli_fetch_assoc($query3_result)){
        //Produkt in Tabelle Posten einfügen
        $query4 = $con->prepare("INSERT INTO posten (bes_idf, pro_idf, posten_menge) VALUES(?, ?, ?)");
        $query4->bind_param("iii", $bes_id, $row['pro_idf'], $row['w_menge']);
        $query4->execute();
        $query4->close();
    }

    //Variablen vorbereiten für Mail
    $u_anrede = $_SESSION['u_anrede'];
    $u_fname = $_SESSION['u_fname'];
    $u_lname = $_SESSION['u_lname'];
    $u_mail = $_SESSION['u_mail'];
    $tabellenstring = '';

    //Für Mail: finde alle Produkte des Users in Tabelle Warenkorb
    $query5 = $con->prepare("SELECT * FROM warenkorb WHERE u_idf =? ");
    $query5->bind_param("i", $u_id);
    $query5->execute();
    $query5_result = $query5->get_result();
    $query5->close();
        //Anzeigen jedes einzelnen Produkt in der Rechnung
        while ($row = mysqli_fetch_assoc($query5_result)){
            //Datenbankabfrage der Produktdetails aus Tabelle Produkt
            $query6 = $con->prepare("SELECT * FROM produkt WHERE pro_id =? ");
            $query6->bind_param("i",$row['pro_idf']);
            $query6->execute();
            $query6_result = $query6->get_result();
            $query6->close();
            $product_data = mysqli_fetch_assoc($query6_result);
            
            $element = mailBillElement($product_data['pro_id'], $product_data['pro_name'], $product_data['pro_preis'], $row['w_menge']);
            $tabellenstring .= (string) $element;
        }

    //Vorbereitungen für Mail verschicken
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'vendor\autoload.php';

    $mail = new PHPMailer(TRUE);

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
    $mail->isHTML( true );

    $mail->Subject = 'Rechnung für Ihre Bestellung';
    $mail->Body = '
            
    <h6>Versand an <br>
        '.$_SESSION['u_fname'].' '.$_SESSION['u_lname'].'<br>
        '.$_SESSION['u_strasse'].' '.$_SESSION['u_hausnr'].'<br>
        '.$_SESSION['u_plz'].' '.$_SESSION['u_ort'].'</h6>
    <div>
        <p>Vielen Dank für Ihre Bestellung vom '.$bes_datum.'.<br>
        Wir werden uns so schnell wie möglich um den Versand
        kümmern!<br> <br>
        Viele Grüße <br>
        Ihr cocobello Team</p>
    </div>
    <h6>Bestellnummer: '.$bes_id.'</h6>
    <table width="100%" border="1">
    <tr style="border-width: 2px; border-color:black;">
        <th>Art-Nr</th>
        <th>Bezeichnung</th>
        <th>Einzelpreis</th>
        <th>Menge</th>
        <th>Gesamt</th>
    </tr>

        '.$tabellenstring.'
        
    <tr style="border-top: solid;">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <th>Summe:</th>
        <th>'.$_SESSION["total"].' €</th>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <th>-Rabatt:</th>
        <td>'.$_SESSION["totalrabatt"].' €</td>
    </tr>
    <tr style="border-bottom: solid;">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <th>+Lieferkosten:</th>
        <td>'.$_SESSION["versand"].' €</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <th>Gesamtbetrag:</th>
        <th>'.$_SESSION["total"]-$_SESSION["totalrabatt"]+$_SESSION["versand"].' €</th>
    </tr>
    </table>


    '; // Our message above

    $mail->WordWrap = 50;

    if(!$mail->Send()) {
        echo '<center>Die E-Mail konnte nicht versendet werden.';
        echo '<center>Mailer error: ' . $mail->ErrorInfo;
        exit;
    }
        echo "<br><br><br><br><br> <center><h1/>Vielen Dank für Ihre Bestellung!</h1></center><br><br>";
        echo '<center>Eine Bestellbestätigungs E-Mail wurde an '.$u_mail.' versendet.</center> <br><br>';
        echo '<center><h1><a href="index.php">Weiter zum Haupmenü</a></h1></center>';
        

    //Alle Produkte aus Warenkorb entfernen
    $query7 = $con->prepare("DELETE FROM warenkorb WHERE u_idf =? ");
    $query7->bind_param("i", $u_id);
    $query7->execute();
    $query7->close();

    //SESSION Variable aktualisieren
    $_SESSION['korbmenge'] = 0;
    $_SESSION['total'] = 0;
    $_SESSION['totalrabatt'] = 0;
    $_SESSION['versandart'] = "";
    $_SESSION['versand'] = 0;
    if (isset($_SESSION['gutscheinrabatt'])){
        $_SESSION['gutscheinrabatt'] = null;
    } 

    // Bestellung erfolgreich abgeschlossen!
?>