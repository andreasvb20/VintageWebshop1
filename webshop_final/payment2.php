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
    
    //Versandart einlesen
    $myversand = $_POST['myversand'];
    $_SESSION['versandart'] = $myversand;
    if($_SESSION['versandart']=='DPD'){
        $_SESSION['versand'] = 11;
    }else if($_SESSION['versandart']=='DHL'){
        $_SESSION['versand'] = 20;
    }else if($_SESSION['versandart']=='DHL Express'){
        $_SESSION['versand'] = 33;
    }else{
        //Fehler
    }

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
                        <h1>Meine Bestellung</h1>
                        <br>
                    </div>


                    <div class="card-body" class="card w-30 my-auto">

                        <form action="payment1.php" method="post">
                            <div class="row">
               
                                <h4>Bestellübersicht</h4>
                                <br> <br>

                                <table width="100%" border="1">
                                <tr style="border-width: 2px; border-color:black;">
                                    <th>Art-Nr</th>
                                    <th>Bezeichnung</th>
                                    <th>Einzelpreis</th>
                                    <th>Menge</th>
                                    <th>Gesamt</th>
                                </tr>
                                <?php
                                    //Anzeigen jedes einzelnen Produkt in der Bestellübersicht
                                    while ($row = mysqli_fetch_assoc($query1_result)){
                                        //Datenbankabfrage der Produktdetails aus Tabelle Produkt
                                        $query2 = $con->prepare("SELECT * FROM produkt WHERE pro_id =? ");
                                        $query2->bind_param("i",$row['pro_idf']);
                                        $query2->execute();
                                        $query2_result = $query2->get_result();
                                        $query2->close();
                                        $product_data = mysqli_fetch_assoc($query2_result);
                                        
                                        billElement($product_data['pro_id'], $product_data['pro_name'], $product_data['pro_preis'], $row['w_menge']);
                                    }

                                ?>
                                <tr style="border-top: solid;">
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <th>Summe:</th>
                                    <th><?php echo $_SESSION['total']." €"?></th>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <th>-Rabatt:</th>
                                    <td><?php echo $_SESSION['totalrabatt']+ ($_SESSION['total']-$_SESSION['totalrabatt']) * ($_SESSION['gutscheinrabatt']/100)." €"?></td>
                                </tr>
                                <tr style="border-bottom: solid;">
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <th>+Lieferkosten:</th>
                                    <td><?php echo $_SESSION['versand']." €"?></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <th>Gesamtbetrag:</th>
                                    <th><?php echo $_SESSION['total']- ($_SESSION['totalrabatt']+ ($_SESSION['total']-$_SESSION['totalrabatt']) * ($_SESSION['gutscheinrabatt']/100)) + $_SESSION['versand']." €"?></th>
                                </tr>
                                </table>



              <!--Zeile 6-->    <div class="form-group name1 col-md-6 ">
                                    <br><br>
                                    <p style="display: flex;">
                                        <input type="checkbox" id="privacy" class="form-check" name="privacy" required>
                                        <label for="privacy" class="formText" style="padding-left:11px;">Datenschutz (lesen)</label>
                                    </p>
                                </div>
                <!--Zeile 7-->      <p style="margin-top:-1.9%; color:rgb(188, 188, 188);">
                                        Ich bin einverstanden, dass meine Daten zum Zweck der Kontaktaufnahme von coco.bello
                                        gespeichert und verarbeitet werden. Ich erkl&auml;re mich mit der Datenschutzbestimmung, die ich zur Kenntnis genommen habe, einverstanden.
                                    </p>                           
                                    <hr color="black"><hr color="black" style="margin-top:0.75cm;">

                <!--Zeile 8-->      <div>
                                        <a href="privacy1.php" style="color:black;float:right;margin-top:0.2cm;">Bitte beachten Sie unsere Datenschutzerkl&auml;rung</a>
                                    </div>
                                    <br>                                
              <!--Zeile 9-->    <div class="form-group">
                                    <br> <!--leer neben Button-->
                                    <input type="submit" class="btn btn-primary w-100" value="BEZAHLEN" name="" style="background-color:gold; border-color:black; margin-top:0.35cm;margin-bottom:0.5cm; color:black; font-size:23px; font-weight:700; border-width: 3px;">
                                </div>
                        </form>
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
<?php
  include_once 'php_include/footer1.php';
?>