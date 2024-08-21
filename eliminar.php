<?php
	$dominio = filter_input(INPUT_GET,"dominio");
	shell_exec("rm -r $dominio");
	shell_exec("rm -r $dominio.zip");
	$data = "mysql:host=localhost;dbname=webgenerator";
	$conexion = new PDO($data, 'adm_webgenerator', 'webgenerator2024');
	$consulta = $conexion->prepare("DELETE FROM `webs` WHERE `dominio` = :dominio");
	$consulta->execute(array(
		':dominio' => $dominio
	));
	header("location:panel.php");
?>