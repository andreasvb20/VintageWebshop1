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
                        <h1>Mein Konto</h1>
                        <br>
                        <p style="border:0cm; padding:15px; background-color:rgb(238, 250, 255);color:rgba(11, 108, 140, 0.643);">
                            Sie sind nicht angemeldet. Bitte geben Sie Ihre Daten ein, um sich anzumelden.</p>
                    </div>
                    <div class="card-body" class="card w-30 my-auto">
                        <p style="font-size:24px;">Anmelden f&uuml;r registrierte Kunden</p>
                        <hr color="black" style="margin-bottom:0.7cm">

                        <form action="login1.php" method="post">
<!-- Zeile 1   -->          <div class="form-group">
                                <label for="u_mail">E-Mail-Adresse</label>
                                <input type="email" id="u_mail" class="form-control" name="u_mail" required pattern="[^ ]{5,}@{1}[^ ]*">
                            </div>
                            <br>
<!-- Zeile 2   -->          <div class="form-group">
                                <label for="u_password">Passwort</label>
                                <input type="password" id="u_password" class="form-control" name="u_password" required pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{9,}">
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="u_2FA">2FA Code aus Google Authenticator App</label>
                                <input type="text" id="u_2FA" class="form-control" name="u_2FA" required>
                            </div>
                            <br>



                            <div class="row">
<!--Zeile 3   -->               <div class="form-group name1 col-md-6 ">
                                    <img src="images/questionmark.PNG" alt="questionmark" style="height:0.49cm; width:0.49cm;">
                                    <a href="forgotpassword2.php" style="color:black;">Passwort vergessen</a>
                                </div>
                                <div class="form-group name1 col-md-6">
                                    <img src="images/safe.PNG" alt="safe" style="height:0.49cm; width:0.49cm;">
                                    <a href="password2.php" style="color:black;">Konto freischalten</a>
                                    <br>
                                </div>
                            </div>
<!--Screen resolution-->    <div class="form-group name1 col-md-6 ">
                                <input type="hidden" id="u_screenheight" class="form-control" name="u_screenheight" value="">
                            </div>
                            <div class="form-group name1 col-md-6">
                                <input type="hidden" id="u_screenwidth" class="form-control" name="u_screenwidth" value="">
                                <br>
                            </div>
<!--operating system -->    <div class="form-group">
                                <input type="hidden" id="u_os" class="form-control" name="u_os" value="">
                            </div>
                            <script>
                                // finde screen resolution und os
                                document.getElementById('u_screenheight').value = screen.height;
                                document.getElementById('u_screenwidth').value = screen.width;

                                var osname = "";
                                if (navigator.appVersion.indexOf("Win") != -1) osname = "Windows";
                                if (navigator.appVersion.indexOf("Mac") != -1) osname = "Mac OS";
                                if (navigator.appVersion.indexOf("X11") != -1) osname = "UNIX OS";
                                if (navigator.appVersion.indexOf("Linux") != -1) osname = "Linux";
                                if (navigator.appVersion.indexOf("Android") != -1) osname = "Android";
                                document.getElementById('u_os').value = osname;
                            </script>

                            <div>
                                <input type="submit" class="btn btn-primary w-100" value="ANMELDEN" name="" style="background-color:rgb(71, 71, 71); border-color:rgb(71, 71, 71);">
                                <h6></h6>
                                <img src="images/pencil.PNG" alt="pencil" style="height:0.49cm; width:0.49cm;">
                                <a href="registration2.php" style="color:black;">Neu hier? Jetzt registrieren!</a>
                            </div>
                        </form>

                    </div>
                    <div class="card-footer">
                        <small>&copy; Technical Andy</small>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <!-- End main-->

<?php
  include_once 'php_include/footer1.php';
?>