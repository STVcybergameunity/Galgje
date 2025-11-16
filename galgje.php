
<style>
  form {
    margin-bottom: 20px;
  }
  label {
    display: block;
    margin-top: 10px;
  }
  input[type="text"] {
    width: 50px;
    padding: 5px;
    margin-top: 5px;
  }
  input[type="submit"] {
    margin-top: 15px;
    padding: 10px 20px;
  }
</style>

<?php

// reset
if (isset($_POST['reset'])) {
  setcookie("woord", "", -1);
  setcookie("gegokte_letters", "", -1);
  setcookie("fout_count", "", -1);
  header("Location: galgje.php");
  return;
}

require "mannetje.php";

// gegokte letters ophalen uit cookie
if (isset(($_COOKIE['gegokte_letters']))) {
  $gegokte_letters = $_COOKIE['gegokte_letters'];
} else {
  $gegokte_letters = "";
}

// Lijst van mogelijke woorden
$woorden = array(
  "kikker",
  "plofvis",
  "schaap",
  "walvis",
  "kalkoen",
  "schildpad",
  "vogel",
  "inktvis",
  "octopus",
  "pijlstaartrog",
  "penguin"
);

// woord ophalen uit cookie. of nieuw woord kiezen
if (isset($_COOKIE['woord'])) {
  $woord = $_COOKIE['woord'];
} else {
  $woord = $woorden[array_rand($woorden)];
}
setcookie("woord", $woord, time() + 3600);

// woord in letters splitsen
$woord_letters = str_split($woord);

// nieuwe letter toevoegen aan gegokte letters
$gok_letter = $_POST['letter'] ?? '';
$gok_letter = strtolower($gok_letter);
echo "Je gokte de letter: " . $gok_letter . "<br>";

$gegokte_letters .= $gok_letter;

setcookie("gegokte_letters", $gegokte_letters, time() + 3600);

// Vind en sla goede en foute letters op
$goede_letters = array();
$foute_letters = array();
// Check welke letters goed of fout zijn
foreach (str_split($gegokte_letters) as $letter) {
  if (!str_contains($woord, $letter)) {
    $foute_letters[] = $letter;
  }
  if (str_contains($woord, $letter)) {
    $goede_letters[] = $letter;
  }
}
// Sorteer de letters
sort($goede_letters);
sort($foute_letters);

// Tel de aantallen
$aantal_fout = count($foute_letters);
$aantal_goed = count($goede_letters);
$totale_score = $aantal_goed*2 - $aantal_fout;

// laat goede en foute letters zien, en aantallen
echo "Goede letters:  (aantal: " . $aantal_goed . ") " . implode(", ", array_unique($goede_letters)) . "<br>";
echo "Foute letters:  (aantal: " . $aantal_fout . ") " . implode(", ", array_unique($foute_letters)) . "<br>";
echo "Totale Score: " . $totale_score . " <br>";

// Als je te veel fouten hebt gemaakt ben je af
if ($aantal_fout == 6) {
  echo "<h2>Helaas, je hebt verloren! Het woord was '$woord'.</h2>";
}

// Als alle goede letters zijn geraden heb je gewonnen
$aantal_unieke_letters = count(array_unique($woord_letters));
if ($aantal_goed == $aantal_unieke_letters) {
  echo "<h2>Gefeliciteerd! Je hebt het woord '$woord' geraden! Je hebt $totale_score punten! </h2>";
}


echo "<p>--------------------------------- Maak uw gok ------------------------------------------</p>";

// Als we nog niet klaar of af zijn
// Show gokformulier en huidige wood.
if ($aantal_fout < 6 && $aantal_unieke_letters > $aantal_goed) {
  include "gokformulier.php";
  // Voor elk correct gegokte letter, echo de letter, anders een _
  foreach ($woord_letters as $letter) {
    if (str_contains($gegokte_letters, $letter)) {
      echo $letter . " ";
    } else {
      echo "_ ";
    }
  }
  // Show mannetje
  echo $mike[$aantal_fout];
}

include "resetformulier.php";