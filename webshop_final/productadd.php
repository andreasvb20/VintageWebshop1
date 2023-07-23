<?php

if (!isset($_SESSION))
  {
    session_start();
  }

if (!isset($_SESSION['u_id'])){
    header('location: login2.php');
}

//Auslesen des Produkts und dessen Menge, die in den Warenkorb gelegt werden soll
$pro_id = $_POST['pro_id'];
$w_menge_add = $_POST['w_menge_add'];
$u_id = $_SESSION['u_id'];

//Database connection
$conn = new mysqli('localhost', 'root', '', 'webshop1');
if($conn->connect_error){
    die('Connection Failed : '.$conn->connec_error);
}

//Existiert das Produkt bereits im Warenkorb für den User?
$query1 = $conn->prepare("SELECT * FROM warenkorb WHERE u_idf= ? AND pro_idf= ?");
$query1->bind_param("ii", $u_id, $pro_id);
$query1->execute();
$query1_result = $query1->get_result();
$query1->close();
if($row = mysqli_fetch_assoc($query1_result)){

    //Produkt existiert schon im Warenkorb für User -> Produktmenge addieren
    $w_menge_add = $row['w_menge'] + $w_menge_add;
    $query2 = $conn->prepare("UPDATE warenkorb SET w_menge = ? WHERE u_idf= ? AND pro_idf= ?");
    $query2->bind_param("iii", $w_menge_add, $u_id, $pro_id);
    $query2->execute();
    $query2->close();
}else{

    //Produkt existiert noch nicht im Warenkorb für User -> neuen Warenkorb Eintrag für Produkt
    $query3 = $conn->prepare("insert into warenkorb(u_idf, pro_idf, w_menge) values(?,?,?)");
    $query3->bind_param("iii",$u_id, $pro_id, $w_menge_add);
    $query3->execute();
    $query3->close();
}

// Gesamtanzahl und Gesamtpreis für Warenkorb ermitteln
$korbmenge = 0; //Gesamtanzahl
$total = 0;     //Gesamtpreis

$query4 = $conn->prepare("SELECT * FROM warenkorb WHERE u_idf= ?");
$query4->bind_param("i", $u_id);
$query4->execute();
$query4_result = $query4->get_result();
$query4->close();
while ($row = mysqli_fetch_assoc($query4_result)){
    $korbmenge = $korbmenge + $row['w_menge'];

    $query5 = $conn->prepare("SELECT * FROM produkt WHERE pro_id= ?");
    $query5->bind_param("i", $row['pro_idf']);
    $query5->execute();
    $query5_result = $query5->get_result();
    $product_data = mysqli_fetch_assoc($query5_result);
    $total = $total + $row['w_menge'] * $product_data['pro_preis'];
    $query5->close();

}
$_SESSION['total'] = $total;
$_SESSION['korbmenge'] = $korbmenge;
header("refresh:0.2; url=productpage.php");

?>