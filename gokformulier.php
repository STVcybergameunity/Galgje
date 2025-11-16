
<?php
echo <<<FORM
<form method="post" action="galgje.php">
  <label for="letter">Voer een letter in:</label>
  <input type="text" id="letter" name="letter" maxlength="1" pattern="[A-Za-z]" required autofocus>
  <input type="text" id="woord" name="woord" value="$woord" hidden>
  <input type="submit" value="Gok!">
</form>
FORM;
?>
