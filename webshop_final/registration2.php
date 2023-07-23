<?php
  include_once 'php_include/header1.php';
  if (!isset($_SESSION))
  {
    session_start();
  }
  if (isset($_SESSION['u_id'])){
    header('location: index.php');
  }
?>

    <!-- Start main-->
    <main id="main-site">
        
        <div class="container vh-100">
            <div class="row justify-content-center h-100">
                <div>
                    <div class="card-header">
                        <br><br>
                        <h1>Neues Kundenkonto erstellen</h1>
                        <br>
                    </div>


                    <div class="card-body" class="card w-30 my-auto">

                        <form action="registration1.php" method="post">
                            <div class="row">
                                <h4>Adresse</h4>

        <!--Zeile 1-->          <div class="form-group name1 col-md-6 ">
                                    <label for="u_anrede" class="formText" style="margin-right:0.7cm;">Anrede</label>
                                    <select name="u_anrede" id="u_anrede" required style="width:5cm; height:0.9cm; text-align-last:center;">
                                        <option value="Frau">Frau</option>
                                        <option value="Herr">Herr</option>
                                    </select>
                                </div>
                                <div class="form-group name1 col-md-6">
                                    <br><br> <!--leer neben Anrede-->
                                </div>

           <!--Zeile 2-->       <div class="form-group name1 col-md-6 ">
                                    <label for="u_fname" class="formText">Vorname</label>
                                    <input type="text" id="u_fname" class="form-control" name="u_fname" required>
                                </div>
                                <div class="form-group name1 col-md-6">
                                    <label for="u_lname" class="formText">Nachname</label>
                                    <input type="text" id="u_lname" class="form-control" name="u_lname" required>
                                    <br>
                                </div>                          

           <!--Zeile 3-->       <div class="form-group name1 col-md-6 ">
                                    <label for="u_strasse" class="formText">Stra&szlig;e</label>
                                    <input type="text" id="u_strasse" class="form-control" name="u_strasse" required>
                                </div>
                                <div class="form-group name1 col-md-6">
                                    <label for="u_hausnr" class="formText">Hausnummer</label>
                                    <input type="text" id="u_hausnr" class="form-control" name="u_hausnr" required>
                                    <br>
                                </div>

          <!--Zeile 4-->        <div class="form-group">
                                    <label for="u_land">Land</label>
                                    <input type="text" id="u_land" class="form-control" name="u_land" required>
                                    <br>
                                </div>

          <!--Zeile 5-->        <div class="form-group name1 col-md-6 ">
                                    <label for="u_plz" class="formText">PLZ</label>
                                    <input type="text" id="u_plz" class="form-control" name="u_plz" required>
                                </div>
                                <div class="form-group name1 col-md-6">
                                    <label for="u_ort" class="formText">Ort</label>
                                    <input type="text" id="u_ort" class="form-control" name="u_ort" required>
                                    <br>
                                </div>

                            </div>
                            <hr>
                            <br>        <!--Zeilenumbruch  neuer Abschnitt (nach grauem Strich)-->
                            <div class="row">

          <!--Zeile 6-->        <h4>Kontaktdaten</h4>
                                <!-- E-Mail Eingabe mit RegEx und onblur für AJAX -->
                                        <!-- Meine AJAX Abfrage für die Email bei der Registrierung -->
                                <script type="text/javascript" src="js/pruefeEmail.js"></script>
                                <div class="form-group">
                                    <label for="u_mail">E-Mail</label>
                                    <input type="email" id="u_mail" class="form-control" name="u_mail" required pattern="[^ ]{5,}@{1}[^ ]*" onblur="pruefeEmail()">
                                    <label for="u_mail" id="emailwarnung" style="color:red;"></label>
                                    <br><br>
                                </div>

          <!--Zeile 7-->        <div class="form-group name1 col-md-6 ">
                                    <label for="u_tele" class="formText">Telefon</label>
                                    <input type="text" id="u_tele" class="form-control" name="u_tele" required>
                                </div>
                                <div class="form-group name1 col-md-6">
                                    <br> <!--leer neben Telefon-->
                                </div>
                            
                            </div>
                            <br>

          <!--Zeile 8-->    <div class="form-group name1 col-md-6 ">
                                <p style="display: flex;">
                                    <input type="checkbox" id="privacy" class="form-check" name="privacy" required>
                                    <label for="privacy" class="formText" style="padding-left:11px;">Datenschutz (lesen)</label>
                                </p>
                            </div>
          <!--Zeile 9-->    <p style="margin-top:-1.9%; color:rgb(188, 188, 188);">
                                Ich bin einverstanden, dass meine Daten zum Zweck der Kontaktaufnahme von coco.bello
                                gespeichert und verarbeitet werden. Ich erkl&auml;re mich mit der Datenschutzbestimmung, die ich zur Kenntnis genommen habe, einverstanden.
                            </p>                           
                            <hr color="black"><hr color="black" style="margin-top:0.75cm;">

          <!--Zeile 10-->   <div>
                                <a href="privacy1.php" style="color:black;float:right;margin-top:0.2cm;">Bitte beachten Sie unsere Datenschutzerkl&auml;rung</a>
                            </div>
                            <br>

          <!--Zeile 11-->   <div class="form-group name1 col-md-6 ">
                                <br> <!--leer neben Button-->
                            </div>
                            <div class="form-group name1 col-md-6" style="float:right;">
                                <input type="submit" class="btn btn-primary w-100" value="KUNDENDATEN ABSCHICKEN" name="" style="background-color:rgb(71, 71, 71); border-color:rgb(71, 71, 71); margin-top:0.35cm;margin-bottom:0.5cm;">
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