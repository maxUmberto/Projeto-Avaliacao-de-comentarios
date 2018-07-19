<?php
//inicia a sessão
session_start();
unset($_SESSION['dados'], $_SESSION['comentario']);
session_destroy();
//<iframe src="https://www.instagram.com/accounts/logout/">
header("Location: index.php");
?>
