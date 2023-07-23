<?php
  include_once 'php_include/header1.php';
  if (!isset($_SESSION))
  {
    session_start();
  }
  require_once ('php_include/functions.php');
?>

    <!-- Start main-->
    <main id="main-site">
        <div class="container">
            <div class="row text-center py-5">
                <?php
                    $con = new mysqli("localhost", "root","", "webshop1");
                    if ($con->connect_error) {
                        die("Failed to connect : ".$con->connect_error);
                    }

                    $queryx = $con->prepare("select * from produkt");
                    $queryx->execute();
                    $queryx_result = $queryx->get_result();
                    $queryx->close();
                    //loopen über Produkt-Tabelle um einzelne Produkte alle anzuzeigen
                    //Dafür extra Methode component-Methode erstellt
                    while ($row = mysqli_fetch_assoc($queryx_result)){
                        component($row['pro_name'], $row['pro_preis'], $row['pro_image'], $row['pro_id'],$row['pro_groesse'],$row['pro_farbe']);
                    }
                ?>
            </div>
        </div>
    </main>
    <!-- End main-->

<?php
  include_once 'php_include/footer1.php';
?>