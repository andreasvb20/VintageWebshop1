<?php
// Erstelle Datenbankverbindung
$conn = new mysqli("localhost", "root", "", "webshop1");

// Prüfe auf Verbindungsfehler
if ($conn->connect_error) {
    die("Failed to connect : " . $conn->connect_error);
}

//Frage Wert aus User Tabelle ab
$sql = "SELECT SUM(u_loginstatus) AS sum_u_loginstatus FROM user";
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);
$sum_u_loginstatus = $row['sum_u_loginstatus'];

//gib Ergebnis zurück
echo $sum_u_loginstatus;

$conn->close();
?>