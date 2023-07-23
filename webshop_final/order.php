<?php
    if (!isset($_SESSION))
    {
        session_start();
    }

    if (!isset($_SESSION['u_id'])){

        header("refresh:0; url=login2.php");
    }
    if (!isset($_SESSION['korbmenge'])){

        header("refresh:0; url=cart.php");
    }
    if ($_SESSION['korbmenge']==0){

        header("refresh:0; url=cart.php");
    }
    
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
                                <!-- Formular für Geschenkgutscheine -->
                                <h4>Geschenkgutscheine, Gutscheine und Werbecodes</h4>
                                <br> <br>
                                <form action="redeemGiftcard.php" method="post">
                                    <!-- Gutscheincode eingeben -->
                                    <div class="form-group name1 col-md-6 ">
                                        <label for="gutscheincode" class="formText">Gutscheincode</label>
                                        <input type="text" id="gutscheincode" class="form-control" name="gutscheincode">
                                    </div>
                                    <!-- Gutscheincode einlösen-->
                                    <div class="form-group name1 col-md-6">
                                        <button type="submit" id="einloesen" class="btn btn-primary w-100" name="einloesen" style="background-color:gold; border-color:black; color:black; font-size:23px; font-weight:500; border-width: 2px; width: 75px;" >Einlösen</button>
                                    </div>
                                </form>
                                <div class="form-group name1 col-md-6">
                                    <label id="discountlabel" name="discountlabel" style="color: green; font-size: 22px;">
                                        <?php 
                                            if(isset($_SESSION['gutscheinrabatt'])&& ($_SESSION['gutscheinrabatt']>0)){
                                                echo "Wir gewähren Ihnen einen Gutscheinrabatt von insgesamt: ".$_SESSION['gutscheinrabatt']."% <br><br>"; 
                                            }
                                            if(!isset($_SESSION['gutscheinrabatt'])){
                                                $_SESSION['gutscheinrabatt'] = 0; 
                                            }
                                        ?>
                                    </label>
                                </div>

                    
                        <form action="payment2.php" method="post">
                            <div class="row">
                                <h4>Zahlungsart wählen</h4>
                                <br><br>

        <!--Zeile 1-->          <div class="form-group name1 col-md-6 ">
                                    <input type="checkbox" name="zahlungsart" required>
                                    <label for="zahlungsart" class="formText" style="margin-right:0.7cm;">Bankeinzug Bankkonto ***123 <?php echo $_SESSION['u_fname']." ".$_SESSION['u_lname'] ?></label>
                                </div>
                                <div class="form-group name1 col-md-6">
                                    <br><br><br> <!--leer neben Anrede-->
                                </div>

                                <h4>Versandart wählen</h4>
                                <br> <br>
           <!--Zeile 2-->       <div class="form-group">
                                    <input type="radio" name='myversand' value="DPD" checked> DPD + 11€,    Lieferung innerhalb von 14 Tagen<br>
                                    <input type="radio" name='myversand' value="DHL"> DHL + 20€,    Lieferung innerhalb von 7 Tagen<br>
                                    <input type="radio" name='myversand' value="DHL Express"> DHL Express + 33€,    Lieferung am nächsten Werktag<br><br>
                                </div>                       
           <!--Zeile 3-->       <div>
                                    <h6>Versand an</h6>
                                    <?php
                                    $element = '
                                        '.$_SESSION['u_mail'].'<br>
                                        '.$_SESSION['u_fname'].' '.$_SESSION['u_lname'].'<br>
                                        '.$_SESSION['u_strasse'].' '.$_SESSION['u_hausnr'].'<br>
                                        '.$_SESSION['u_plz'].' '.$_SESSION['u_ort'].'<br> <br> <br>
                                    ';
                                    echo $element;
                                    ?> 
                                </div>

                           


                                <br> <br><br> <br>
              <!--Zeile 9-->    <div class="form-group">
                                    <br> <!--leer neben Button-->
                                    <input type="submit" class="btn btn-primary w-100" value="WEITER" name="" style="background-color:gold; border-color:black; margin-top:0.35cm;margin-bottom:0.5cm; color:black; font-size:23px; font-weight:700; border-width: 3px;">
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