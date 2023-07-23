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
                        <h1>Passwort zur&uuml;cksetzen</h1>
                        <br>
                    </div>

                    <div class="card-body" class="card w-30 my-auto">


                        <form method="post" name="passform1" action="forgotpassword1.php">
                            <div class="row">
                                <p>Wenn Sie Ihr Passwort vergessen haben, k&ouml;nnen Sie hier einen Link zum Zur&uuml;cksetzen des Passworts anfordern. Dieser Link wird Ihnen per E-Mail zugesandt. Dann k&ouml;nnen Sie ein neues Passwort vergeben.</p>

          <!--Zeile 1-->        <div class="form-group">
                                    <label for="u_mail">Ihre E-Mail-Adresse</label>
                                    <input type="email" id="u_mail" class="form-control" name="u_mail" required pattern="[^ ]{5,}@{1}[^ ]*">
                                </div>
                                <div class="form-group" style="float:right;">
                                    <input type="submit" class="btn btn-primary w-100" value="Link anfordern" name="" style="background-color:rgb(71, 71, 71); border-color:rgb(71, 71, 71); margin-top:0.35cm;margin-bottom:0.5cm;">
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