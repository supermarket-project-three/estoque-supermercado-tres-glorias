<?php 

    session_start();
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: /estoque-supermercado-tres-glorias/src/app/views/login.php");
        exit();
    }
?>