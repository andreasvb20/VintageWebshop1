<?php

// Datenbankverbindung
$conn = new mysqli('localhost', 'root', '', 'webshop1');
if($conn->connect_error){
  die('Connection Failed : '.$conn->connec_error);
}else{
  // Abfrage auf Tabelle User, ob email schon vorhanden
  $query = $conn->prepare("SELECT * FROM user WHERE u_mail = ?");
  $query->bind_param("s", $_POST['email']);
  $query->execute();
  $query_result = $query->get_result();
  $query_data = $query_result->fetch_assoc();
  if(count($query_data) > 0){
    // Email is already in use
    echo "schonVerwendet";
  }else{
    // Email is available
    echo "verfügbar";
  }
  $query->close();
}

?>
