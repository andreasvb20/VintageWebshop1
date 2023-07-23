
function updateLabel() {
    // Schicke Request an PHP Script
    $.ajax({
        url: 'getUserNumber.php',
        success: function(result) {
            // Update das Label mit dem Rückgabewert für die UserAnzahl onlin
            $('#userAnzahl').text("Aktuell online: " + result);
        }
    });
}

// Wiederhole updateLabel Funktion jede Sekunde
setInterval(updateLabel, 500);