<?php
    if (!isset($_SESSION))
    {
        session_start();
    }

    if (!isset($_SESSION['u_id'])){

        header("location: login2.php");
    }
    $bes_id = $_POST['bestellid'];
    $u_id = $_SESSION['u_id'];

    //Datenbank Verbindung
    $con = new mysqli("localhost", "root","", "webshop1");
    if ($con->connect_error) {
        die("Failed to connect : ".$con->connect_error);
    }
    //alle Produkte aus Warenkorb löschen
    $query1 = $con->prepare("DELETE FROM warenkorb WHERE u_idf =? ");
    $query1->bind_param("i", $u_id);
    $query1->execute();
    $query1->close();


    //alle Produkte der Bestell-ID wieder in den Warenkorb legen und zur Kasse gehen
    $query2 = $con->prepare("SELECT * FROM posten WHERE bes_idf =? ");
    $query2->bind_param("i", $bes_id);
    $query2->execute();
    $query2_result = $query2->get_result();
    $query2->close();

    while ($row = mysqli_fetch_assoc($query2_result)){
        //Produkt in Tabelle Posten einfügen
        $query3 = $con->prepare("INSERT INTO warenkorb (u_idf, pro_idf, w_menge) VALUES(?, ?, ?)");
        $query3->bind_param("iii", $u_id, $row['pro_idf'], $row['posten_menge']);
        $query3->execute();
        $query3->close();
    }

// Gesamtanzahl und Gesamtpreis für Warenkorb ermitteln
$korbmenge = 0; //Gesamtanzahl
$total = 0;     //Gesamtpreis

$query4 = $con->prepare("SELECT * FROM warenkorb WHERE u_idf= ?");
$query4->bind_param("i", $u_id);
$query4->execute();
$query4_result = $query4->get_result();
$query4->close();
while ($row = mysqli_fetch_assoc($query4_result)){
    $korbmenge = $korbmenge + $row['w_menge'];

    $query5 = $con->prepare("SELECT * FROM produkt WHERE pro_id= ?");
    $query5->bind_param("i", $row['pro_idf']);
    $query5->execute();
    $query5_result = $query5->get_result();
    $product_data = mysqli_fetch_assoc($query5_result);
    $total = $total + $row['w_menge'] * $product_data['pro_preis'];
    $query5->close();

}
$_SESSION['total'] = $total;
$_SESSION['korbmenge'] = $korbmenge;

    //Weiterleitung zur Kasse
    header("location: order.php")
?>