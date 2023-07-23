<?php
    if (!isset($_SESSION))
    {
        session_start();
    }

    if (!isset($_SESSION['u_id'])){

        header("location: login2.php");
    }

    //Anzeigen aller früheren Bestellungen
    //Datenbankabfrage aller Produkte im Warenkorb des Users
    $con = new mysqli("localhost", "root","", "webshop1");
    if ($con->connect_error) {
        die("Failed to connect : ".$con->connect_error);
    }
    $query1 = $con->prepare("SELECT * FROM bestellung WHERE u_idf =? ");
    $query1->bind_param("i", $_SESSION['u_id']);
    $query1->execute();
    $query1_result = $query1->get_result();
    $query1->close();


    include_once 'php_include/header1.php';
    require_once ('php_include/functions.php');
?>

<!-- Start main-->
<main id="main-site">
        <div class="container vh-100">
            <div class="row justify-content-center h-100">
                <div>
                    <div class="card-header">
                        <br><br>
                        <h1>Frühere Bestellungen</h1>
                        <br>
                    </div>
                    <div class="card-body" class="card w-30 my-auto">
                                <?php
                                    //Anzeigen jedes einzelnen Produkt in der Bestellübersicht
                                    while ($row = mysqli_fetch_assoc($query1_result)){
                                        //Datenbankabfrage der Produktdetails aus Tabelle Produkt

                                        orderElement($row['bes_id'], $row['bes_datum'], $row['bes_versand'], $row['bes_total'], $row['bes_totalrabatt']);
                                    }
                                ?>
                        <br><br><br><br>
                    </div>
                    <div class="card-footer">
                        <small style="float:right;">&copy; Technical Andy</small>
                    </div>
                </div>
            </div>
        </div>

</main>
<!-- End main-->