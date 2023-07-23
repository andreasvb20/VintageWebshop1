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
                        <h1>Konto freischalten:</h1> <br>
                        <h4>Neues Passwort vergeben</h4>
                    
                    </div>

                    <div class="card-body" class="card w-30 my-auto">


                        <form method="post" name="passform1" action="password1.php">
                            <div class="row">
                                <p>Bitte tragen Sie Ihr neues Passwort ein. Das Passwort muss 9 Zeichen lang sein, sowie mindestens einen Gro&szlig;buchstaben, einen Kleinbuchstaben und eine Zahl enthalten.</p>
                                <br>
          <!--Zeile 1-->        <div class="form-group name1 col-md-6 ">
                                    <label for="u_mail">Ihre E-Mail-Adresse</label>
                                    <input type="email" id="u_mail" class="form-control" name="u_mail" required pattern="[^ ]{5,}@{1}[^ ]*">
                                </div>
                                <div class="form-group name1 col-md-6">
                                    <label for="u_password">Altes Passwort aus E-Mail</label>
                                    <input type="text" id="u_password" class="form-control" name="u_password" required pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{9,}">
                                    <br> <br>
                                </div>

          <!--Zeile 2-->        <div class="form-group name1 col-md-6">
                                    <label for="u_passwordnew">Neues Passwort</label>
                                    <input type="password" id="u_passwordnew" class="form-control" name="u_passwordnew" required pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{9,}">
                                    <br>
                                </div>
                                <div class="form-group name1 col-md-6">
                                    <label for="u_2FA" style="font-weight:bold;">Bitte 2FA Code aus Google Authenticator App eingeben</label>
                                    <input type="text" id="u_2FA" class="form-control" name="u_2FA" required>
                                    <br>
                                </div>


<!--Screen resolution-->    <div class="form-group name1 col-md-6 ">
                                <input type="hidden" id="u_screenheight" class="form-control" name="u_screenheight" value="">
                            </div>
                            <div class="form-group name1 col-md-6">
                                <input type="hidden" id="u_screenwidth" class="form-control" name="u_screenwidth" value="">
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

                            <div class="form-group">
                                <input type="submit" class="btn btn-primary w-100" value="Neues Passwort vergeben" name="" style="background-color:rgb(71, 71, 71); border-color:rgb(71, 71, 71); margin-top:0.35cm;margin-bottom:0.5cm;">
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
