<?php
if (!isset($_SESSION))
{
  session_start();
}

if (!isset($_SESSION['u_id'])){
  header('location: login2.php');
}


$u_id = $_SESSION['u_id'];
$pro_id = $_POST['currentproid'];
//Database connection
$conn = new mysqli('localhost', 'root', '', 'webshop1');
if($conn->connect_error){
    die('Connection Failed : '.$conn->connec_error);
}

if ($_POST['action'] == '+') {

    //Produkt um 1 addieren
    $query1 = $conn->prepare("UPDATE warenkorb SET w_menge = w_menge + 1 WHERE u_idf= ? AND pro_idf= ?");
    $query1->bind_param("ii", $u_id, $pro_id);
    $query1->execute();
    $query1->close();
} else if ($_POST['action'] == '-') {

    //Produkt um 1 reduzieren
    $query2 = $conn->prepare("UPDATE warenkorb SET w_menge = w_menge - 1 WHERE u_idf= ? AND pro_idf= ?");
    $query2->bind_param("ii", $u_id, $pro_id);
    $query2->execute();
    $query2->close();
    //Produkt aus Warenkorb entfernen, falls Menge= 0
    $query3 = $conn->prepare("DELETE FROM warenkorb WHERE u_idf= ? AND pro_idf= ? AND w_menge= 0");
    $query3->bind_param("ii", $u_id, $pro_id);
    $query3->execute();
    $query3->close();
} else if ($_POST['action'] == 'entfernen') {

    //Produkt aus Warenkorb entfernen
    $query4 = $conn->prepare("DELETE FROM warenkorb WHERE u_idf= ? AND pro_idf= ?");
    $query4->bind_param("ii", $u_id, $pro_id);
    $query4->execute();
    $query4->close();
}

// Gesamtanzahl und Gesamtpreis für Warenkorb ermitteln
$korbmenge = 0; //Gesamtanzahl
$total = 0;     //Gesamtpreis

$query5 = $conn->prepare("SELECT * FROM warenkorb WHERE u_idf= ?");
$query5->bind_param("i", $u_id);
$query5->execute();
$query5_result = $query5->get_result();
$query5->close();
while ($row = mysqli_fetch_assoc($query5_result)){
    $korbmenge = $korbmenge + $row['w_menge'];

    $query6 = $conn->prepare("SELECT * FROM produkt WHERE pro_id= ?");
    $query6->bind_param("i", $row['pro_idf']);
    $query6->execute();
    $query6_result = $query6->get_result();
    $product_data = mysqli_fetch_assoc($query6_result);
    $total = $total + $row['w_menge'] * $product_data['pro_preis'];
    $query6->close();

}
$_SESSION['total'] = $total;
$_SESSION['korbmenge'] = $korbmenge;
echo $total;
echo "<br>";
echo $korbmenge;
header("location: cart.php");

?>