<?php
	session_start();
	$id = $_SESSION['id'];
	$dominio = filter_input(INPUT_GET,"dominio");
	$result = $id.$dominio;
	shell_exec("zip $result.zip $result");
	$data = "mysql:host=localhost;dbname=webgenerator";
	$conexion = new PDO($data, 'adm_webgenerator', 'webgenerator2024');
	$consulta = $conexion->prepare("DELETE FROM `webs` WHERE `dominio` = :dominio");
	$consulta->execute(array(
		':dominio' => $dominio
	));
	header("location:panel.php");
?>