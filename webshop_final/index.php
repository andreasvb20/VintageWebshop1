<?php
  include_once 'php_include/header1.php';
  if (!isset($_SESSION))
  {
    session_start();
  }
?>

        <!-- Start main-->
        <main id="main-site">
          <!-- Begrüßung des Users -->
          <?php
            if (isset($_SESSION['u_id'])){
              echo"<br><center><h6>Herzlich Willkommen ".$_SESSION['u_anrede']." ".$_SESSION['u_lname'].". Sie waren zuletzt am ".$_SESSION['u_lastlogin']." online.</h6></center>";
            }else{
              echo"<br><center><h6>Bitte melden Sie sich an!</h6></center>";
            }
          ?>
          <!-- Begrüßung des Users -->

            <!--Owl Carousel-->
            <section id="banner-area">
                <div class="owl-carousel owl-theme">
                    <div class="item">
                        <img src="images/c1.JPG" alt="Banner1">
                    </div>
                    <div class="item">
                        <img src="images/c2.JPG" alt="Banner2">
                    </div>
                    <div class="item">
                        <img src="images/c3.JPG" alt="Banner3">
                    </div>
                </div>
            </section>
            <!--Owl Carousel-->

        <br><br><br>
        </main>
        <!-- End main-->

<?php
  include_once 'php_include/footer1.php';
?>