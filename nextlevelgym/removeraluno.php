<?php
require_once("conexao.php");

if(isset($_GET["id"])) {
    $id = $_GET["id"];
    $conn->query("DELETE FROM usuarios WHERE id=$id");
}

header("Location: admin.php");
?>
