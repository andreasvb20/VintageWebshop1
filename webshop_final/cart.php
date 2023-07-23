<?php
    if (!isset($_SESSION))
    {
        session_start();
    }

    if (!isset($_SESSION['u_id'])){

        header("location: login2.php");
    }
    include_once 'php_include/header1.php';
    require_once ('php_include/functions.php');
?>
    <!-- Start main-->
    <main id="main-site">
        <br>
            <div class="container-fluid">
            <div class="row px-5">
                <div class="col-md-7">
                    <div class="shopping-cart">
                        <h4>Mein Warenkorb</h4>
                        <hr>

                        <?php

                        $total = 0;
                            if (isset($_SESSION['korbmenge'])AND $_SESSION['korbmenge']!==0){
                                //Datenbankabfrage aller Produkte im Warenkorb des Users
                                $con = new mysqli("localhost", "root","", "webshop1");
                                if ($con->connect_error) {
                                    die("Failed to connect : ".$con->connect_error);
                                }

                                $query1 = $con->prepare("SELECT * FROM warenkorb WHERE u_idf =? ");
                                $query1->bind_param("i", $_SESSION['u_id']);
                                $query1->execute();
                                $query1_result = $query1->get_result();
                                $query1->close();
                                //Rabatt aktuallisieren
                                $_SESSION['totalrabatt'] = 0;

                                //Anzeigen jedes einzelnen Produkt im Warenkorb mit Produktdetails
                                while ($row = mysqli_fetch_assoc($query1_result)){
                                    //Datenbankabfrage der Produktdetails aus Tabelle Produkt
                                    $query2 = $con->prepare("SELECT * FROM produkt WHERE pro_id =? ");
                                    $query2->bind_param("i",$row['pro_idf']);
                                    $query2->execute();
                                    $query2_result = $query2->get_result();
                                    $query2->close();
                                    $product_data = mysqli_fetch_assoc($query2_result);
                                    
                                    cartElement($product_data['pro_image'], $product_data['pro_name'], $product_data['pro_preis'], $product_data['pro_id'], $product_data['pro_groesse'], $row['w_menge']);  
                                }

                        ?>
                    </div>
                </div>
                <div class="col-md-4 offset-md-1 border rounded mt-5 bg-white h-25">
                    <div class="pt-4">
                        <h5>Preis Details</h5>
                        <hr>
                        <div class="row price-details">
                            <div class="col-md-6">
                                <?php
                                    if (isset($_SESSION['korbmenge'])){
                                        $count  = $_SESSION['korbmenge'];
                                        echo "<h6>Preis ($count Artikel):</h6>";
                                    }else{
                                        echo "<h6>Preis (0 Produkte)</h6>";
                                    }
                                ?>
                                <h6>- Rabatt:</h6>
                                <h6>+ Lieferkosten:</h6>
                                <hr>
                                <h6>Gesamtbetrag:</h6>
                            </div>
                            <div class="col-md-6">
                                <h6> <?php echo $_SESSION['total']; ?> €</h6>
                                <h6>
                                    <?php
                                    echo $_SESSION['totalrabatt'];
                                    ?> €
                                </h6>
                                <h6 style="color: red;">später festlegen</h6>
                                <hr>
                                <h6>
                                    <?php
                                    echo $_SESSION['total'] - $_SESSION['totalrabatt'];
                                    ?> €
                                </h6>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <a href="order.php"></a>
                    <button class="btn" onclick="location.href='order.php'" class="btn btn-primary" style="background-color:gold; color:black; font-weight:600; border-color:black; border-width: 3px;"><h5>Zur Kassen gehen (<?php echo$count;?> Artikel)</h5></button>
                    </a>
                    <p></p>
                </div>
                <?php
                            }else{
                                echo "<h5>Warenkorb ist leer</h5>";
                            }
                ?>
            </div>
        </div>
    <br><br><br>
    </main>
    <!-- End main-->
<?php
  include_once 'php_include/footer1.php';
?>