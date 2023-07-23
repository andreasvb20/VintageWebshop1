<?php

function component($pro_name, $pro_preis, $pro_image, $pro_id, $pro_groesse, $pro_farbe){
    //Produkt auf Produktseite anzeigen
    $element = "
    
    <div class=\"col-md-3 col-sm-6 my-3 my-md-0\">
                <form action=\"productadd.php\" method=\"post\">
                    <div class=\"card shadow\">
                        <div>
                            <img src=\"$pro_image\" alt=\"Image1\" class=\"img-fluid card-img-top\">
                        </div>
                        <div class=\"card-body\">
                            <h5 class=\"card-title\">$pro_name</h5>
                            <p class=\"card-text\">
                                <h6> Größe: $pro_groesse </h6>
                                <h6> Farbe: $pro_farbe <h6>
                            </p>
                            <h5>
                                <span class=\"price\">$pro_preis €</span>
                            </h5>

                            <button type=\"submit\" class=\"btn btn-warning my-3\" name=\"add\">hinzufügen<i class=\"fas fa-shopping-cart\"></i></button>
                            <label for=\"w_menge_add\">Menge: </label>
                            <input type='number' name='w_menge_add' value=1 min=\"1\" style=\"width:1.7cm\" required>
                            <input type='hidden' name='pro_id' value='$pro_id'>
                        </div>
                    </div>
                </form>
            </div>
    ";
    echo $element;
}

function cartElement($pro_image, $pro_name, $pro_preis, $pro_id, $pro_groesse, $w_menge){
    //Rabatt bestimmen
    if ($w_menge >= 10){
        $rabatt = 20;
        $rabattstring = " - 20%";
        $rabattpreisstring = $pro_preis - ($rabatt/100) * $pro_preis." €";
    }else if($w_menge >= 5){
        $rabatt = 10;
        $rabattstring = " - 10%";
        $rabattpreisstring = $pro_preis - ($rabatt/100) * $pro_preis." €";
    }else{
        $rabatt = 0;
        $rabattstring = "";
        $rabattpreisstring = "";
    }
    $totalrabatt = ($rabatt/100) * $pro_preis * $w_menge;
    $_SESSION['totalrabatt'] += $totalrabatt;


    //Produkt in Warenkorb anzeigen
    $element = "
    
                <form action=\"productchange.php\" method=\"post\" class=\"cart-items\">
                    <div class=\"border rounded\">
                        <div class=\"row bg-white\">
                            <div class=\"col-md-3 pl-0\">
                                <img src=$pro_image alt=\"Image1\" class=\"img-fluid\">
                            </div>
                            <div class=\"col-md-6\">
                                <h5 class=\"pt-2\">$pro_name</h5>
                                <small class=\"text-secondary\">Größe: $pro_groesse</small>
                                <h5 class=\"pt-2\">$pro_preis €  <i style=\"color: green;\">$rabattstring</i></h5>
                                <h5 class=\"pt-2\" style=\"font-weight:700; font-size:25px\">$rabattpreisstring</h5>
                                <input type=\"submit\" value=\"entfernen\" class=\"btn btn-danger mx-2 form-control\" name=\"action\" style=\"width:3cm;\"></input>
                            </div>
                            <div class=\"col-md-3 py-5\">
                                <div>
                                    <input type=\"submit\" class=\"form-control\" value=\"+\" name=\"action\" style=\"border-radius: 50%; border: 2px solid black; background-color:gold; font-size:22px; font-weight:500; width: 40%;\"></input>
                                    <label class=\"control-label\" style=\"padding-left: 0.5cm; padding-left: 0.5cm; border-color:black; border-width:1px; border-style: solid;\" value=\"\">$w_menge</label>
                                    <input type=\"submit\" class=\"form-control\" value=\"-\" name=\"action\" style=\"border-radius: 50%; border: 2px solid black; background-color:darkred; font-size:24px; font-weight:500; color: white; width: 40%;\"></input>
                                    <input type=\"hidden\" id=\"currentproid\" class=\"form-control\" name=\"currentproid\" value=$pro_id>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
    
    ";
    echo  $element;
}

function billElement($pro_id, $pro_name, $pro_preis, $w_menge ){

    $element = "
    
    <tr>
        <td>".$pro_id."</td>
        <td>".$pro_name."</td>
        <td>".$pro_preis." €</td>
        <td>".$w_menge."</td>
        <td>".$w_menge * $pro_preis." €</td>
   </tr>
    
    ";
    echo $element;
}

function mailBillElement($pro_id, $pro_name, $pro_preis, $w_menge ){

    $element = "
    
    <tr>
        <td>".$pro_id."</td>
        <td>".$pro_name."</td>
        <td>".$pro_preis." €</td>
        <td>".$w_menge."</td>
        <td>".$w_menge * $pro_preis." €</td>
   </tr>
    
    ";
    return $element;
}

function billElement123($pro_id, $pro_name, $pro_preis, $w_menge ){

    $element = "
    
    <tr>
        <td>".$pro_id."</td>
        <td>".$pro_name."</td>
        <td>".$w_menge."</td>
        <td>".$pro_preis." €</td>
        <td>".$w_menge * $pro_preis." €</td>
   </tr>
    
    ";
    return $element;
}

function orderElement($bes_id, $bes_datum, $bes_versand, $bes_total, $bes_totalrabatt){

    //Anzeigen einer früheren Bestellung
    //Datenbankabfrage aller alten Produkte aus Tabelle Posten
    $con = new mysqli("localhost", "root","", "webshop1");
    if ($con->connect_error) {
        die("Failed to connect : ".$con->connect_error);
    }
    $query1 = $con->prepare("SELECT * FROM posten WHERE bes_idf =? ");
    $query1->bind_param("i", $bes_id);
    $query1->execute();
    $query1_result = $query1->get_result();
    $query1->close();

    //Anzeigen jedes einzelnen Produkt in der Bestellung
    
    $tabellenstring = "";
    while ($row1 = mysqli_fetch_assoc($query1_result)){
        //Datenbankabfrage der Produktdetails aus Tabelle Produkt
        $query2 = $con->prepare("SELECT * FROM produkt WHERE pro_id =? ");
        $query2->bind_param("i",$row1['pro_idf']);
        $query2->execute();
        $query2_result = $query2->get_result();
        $query2->close();
        $product_data = mysqli_fetch_assoc($query2_result);
            
        $bill = billElement123($product_data['pro_id'], $product_data['pro_name'], $product_data['pro_preis'], $row1['posten_menge']);
        $tabellenstring = $tabellenstring.$bill;
    }

    $element = '

<form action="buyagain.php" method="post">
    <br>
    <h6>Bestelldatum: '.$bes_datum.'<h6>
    <h6>Bestellnummer: '.$bes_id.'</h6><br>
    <table width="100%" border="1">
    <tr style="border-width: 2px; border-color:black;">
        <th>Art-Nr</th>
        <th>Bezeichnung</th>
        <th>Menge</th>
        <th>Einzelpreis</th>
        <th>Gesamt</th>
    </tr>

        '.$tabellenstring.'
        
    <tr style="border-top: solid;">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <th>Summe:</th>
        <th>'.$bes_total.' €</th>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <th>-Rabatt:</th>
        <td>'.$bes_totalrabatt.' €</td>
    </tr>
    <tr style="border-bottom: solid;">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <th>+Lieferkosten:</th>
        <td>'.$bes_versand.' €</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <th>Gesamtbetrag:</th>
        <th>'.$bes_total-$bes_totalrabatt+$bes_versand.' €</th>
    </tr>
    </table>
    <div class="form-group">
        <br>
        <input type="hidden" id="bestellid" class="form-control" name="bestellid" value='.$bes_id.'>
        <input type="submit" class="btn btn-primary w-100" value="gleiche Bestellung nochmal" name="" style="background-color:gold; border-color:black; margin-top:0.35cm;margin-bottom:0.5cm; color:black; font-size:23px; font-weight:700; border-width: 3px;">
        <br><br><br><br>
    </div>
</form>

    ';
    echo $element;

}

?>















