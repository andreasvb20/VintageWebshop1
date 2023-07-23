function pruefeEmail(){
    var email = $("#u_mail").val();
    $.ajax({
        url: "checkEmail.php",
        type: "post",
        data: {email: email},
        success: function(response){
          if(response == "schonVerwendet"){
            //Labeltext und Inputfeld Rahmen rot färben
            $('#emailwarnung').text("Fehler: die E-Mail Adresse ist schon vergeben!");
            $('#u_mail').css('border', '2px solid red');
          }else{
            //Label und Inputfeld zurückändern
            $('#emailwarnung').text(" ");
            $('#u_mail').css('border', '');
          }
        }
      });
  }
  