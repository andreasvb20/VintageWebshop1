<?php
  if (!isset($_SESSION))
  {
    session_start();
  }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>webshop1</title>
         <!-- Bootstrap CDN-->
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="css/registration1.css">
        <link rel="stylesheet" href="css/all.min.css">
        <!-- Font Awesome-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- Rubik Font-->
        <link href='https://fonts.googleapis.com/css?family=Rubik' rel='stylesheet'>
        <!-- owl carousel-->
        <link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
        <link rel="stylesheet" type="text/css" href="css/owl.theme.default.min.css">
        
        <style>
            .color-primary{
            color: rgba(11, 108, 140, 0.643);
            }
            .color-primary-bg{
            background: rgba(11, 108, 140, 0.643);
            }
            .color-second{
            color: rgba(141, 141, 141, 0.643);
            }
            .color-second-bg{
            background: black;
            }
            .color-yellow{
            color: rgb(255, 233, 37);
            }
            .color-yellow-bg{
            background: rgb(255, 233, 37);
            }
        </style>
    </head>

    <body>

        <!-- Kopfbereich-->
        <header>
            <div class="strip d-flex justify-content-between px-4 py1 bg-dark">
                <p class="font-rale font-size-12 text-black-50 m-0"></p>
                <div class="font-rale font-size-14">
                    <p></p>
                </div>
            </div>

            <!-- statischer Navigator-->
            <nav class="navbar navbar-expand-lg navbar-dark color-second-bg">
                <div class="container-fluid">
                  <a class="navbar-brand" href="index.php">
                    <img src="images/coconut.PNG" alt="coconut" style="height:1.55cm; width:1.5cm;">
                    coco.bello  
                  </a>
                  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="border-color: white;">
                    <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                      <li class="nav-item">
                        <a class="nav-link" style="color:black; width:0.7cm;"></a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="productpage.php" style="color:white;">Alle Artikel</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" style="color:black; width:0.7cm;"></a>
                      </li>
                      <li class="nav-item">
                          <div class="topnav">
                            <!-- Suche in der Artikelübersicht -->
                            <form action="searchproductpage.php" method="post">
                              <input type="text" name="suchtext" placeholder="Suchbegriff" style="height:1.1cm;">
                              <button class="btn btn-outline-success my-2 my-sm-0" type="submit" name="submit_suche" style="color:white; border-color:white; height:1.1cm;">Suchen</button>
                            </form>
                          </div>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" style="color:black; width:1cm;"></a>
                      </li>
                      <li class="nav-item">
                        <form action="#" class="font-size-14 font-rale">
                          <button type="button" onclick="location.href='cart.php'" class="btn btn-primary" style="background-color:white; scale:88%;">
                              <img src="images/warenkorb.PNG" alt="Warenkorb" style="height:0.8cm; width:1.2cm;">
                              <?php
                              if (isset($_SESSION['korbmenge'])){
                              ?>
                                <label id="warenkorb_anzeige1" name="warenkorb_anzeige" style="color:black; font-size:18px; font: weight 800px; background-color:gold;" ><?php echo $_SESSION['korbmenge']; ?></label>
                              <?php
                              }else{
                              ?>
                                <label id="warenkorb_anzeige1" name="warenkorb_anzeige" style="color:black; font-size:18px; font: weight 800px; background-color:gold;" >0</label>
                              <?php
                              }
                              ?>
                            </button>
                        </form>
                      </li>
                        <?php
                          if (isset($_SESSION['u_id'])){
                        ?>
                          <li class="nav-item">
                            <a class="nav-link" style="color:black; width:0.7cm;"></a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" href="oldorders.php" style="color:white;">Frühere Bestellungen</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" style="color:black; width:0.7cm;"></a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" href="php_include/logout1.php" style="color:white;">Abmelden</a>
                          </li>
                        <?php
                          }else{
                        ?>
                          <li class="nav-item">
                            <a class="nav-link" style="color:black; width:0.7cm;"></a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" href="login2.php" style="color:white;">Anmelden</a>
                          </li>
                        <?php
                          }
                        ?>
                          <li class="nav-item">
                            <a class="nav-link" style="color:black; width:0.7cm;"></a>
                          </li>
                                            
                          <li class="nav-item">
                            <label id="userAnzahl" class="nav-label" style="color:white;">Aktuell online:</label>
                          </li>                   
                    </ul>
                  </div>
                </div>
              </nav>
              
        </header>
        <!-- Kopfbereich-->
