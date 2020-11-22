<?php
//logout.php
setcookie("type", "", time()-3600);

header('Location: ' . $_SERVER['HTTP_REFERER']);

?>