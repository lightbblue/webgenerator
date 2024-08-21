<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>webgenerator Joaquin Stachioni</title>
</head>
<body>
	<?php
		session_start();
		if(!isset($_SESSION['id'])){
			header("location:login.php");
		}
		$data = "mysql:host=localhost;dbname=webgenerator";
	$conexion = new PDO($data, 'adm_webgenerator', 'webgenerator2024');
  		$consulta = $conexion->prepare("SELECT * FROM `usuarios` WHERE `idUsuario` = :id");
		$consulta->execute(array(
		':id' => $_SESSION['id']
    	));
    	$datos = $consulta->fetch(PDO::FETCH_ASSOC);
    	$id = $datos['idUsuario'];
	?>
	<h1>Bienvenido a tu panel</h1>
	<a href="./logout.php">Cerrar sesión de <?=$id?></a><br><br>
	<form action="panel.php" method="post">
		Generar Web de:<br><input type="text" name="nombre" required><br><br>
		<input type="submit" name="submit" value="Crear Web"><br><br>
	</form>
	<?php  
		$crearbtn = filter_input(INPUT_POST, 'submit');
		if($crearbtn){
			$nombre = $_POST['nombre'];
			$dominio = $_SESSION['id'].$nombre;
			$consulta = $conexion->prepare("SELECT * FROM `webs` WHERE `dominio` = :dominio");
			$consulta->execute(array(
				':dominio' => $dominio
	    	));
	    	$datos = $consulta->fetch(PDO::FETCH_ASSOC);
	    	if (isset($datos['idWeb'])) {
	    		echo "El dominio ya existe";
	    	}
	    	else{
	    		$consulta = $conexion->prepare("INSERT INTO `webs` VALUES (NULL,:idUsuario,:dominio,:fecha)");
				$consulta->execute(array(
					':idUsuario' => $_SESSION['id'],
					':dominio' => $dominio,
					':fecha' => date('Y-m-d')
		    	));
		    	shell_exec("./wix.sh $dominio");
		    	shell_exec("zip -r $dominio.zip $dominio");
	    	}
		}
		echo "<br><br><table border='solid 1px black'><tr><th colspan='4'>WEBS DEL USUARIO</th></tr><tr><td>DOMINIO</td><td>DESCARGAR</td><td>ELIMINAR</td><td>CREACION</td></tr>";
		$consulta = $conexion->prepare("SELECT * FROM `webs` WHERE `idUsuario` = :idUsuario");
		$consulta->execute(array(
			':idUsuario' => $_SESSION['id'],
		));
		$datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
		if (isset($datos[0])) {
			foreach ($datos as $key => $value) {
				echo "<tr><td>Ir a <a href='./".$value['dominio']."/index.php'>".$value['dominio']."</a></td><td><a href='".$value['dominio'].".zip'>Descargar Web</a></td><td><a href='./eliminar.php?dominio=".$value['dominio']."'>Eliminar</a></td><td>".$value['fechaCreacion']."</td></tr>";
			}
			echo "</table><br><br>";
		}
		$consulta = $conexion->prepare("SELECT * FROM `usuarios` WHERE `email` = 'admin@server.com'");
		$consulta->execute();
    	$datos = $consulta->fetch(PDO::FETCH_ASSOC);
    	if (isset($datos['idUsuario'])){
    		if ($datos['idUsuario']==$_SESSION['id']) {
	    		$consulta = $conexion->prepare("SELECT * FROM `webs`");
				$consulta->execute();
		    	$datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
	    		echo "<table border='solid 1px black'><tr><th colspan='4'>TODAS LAS WEBS</th></tr><tr><td>IDWEB</td><td>IDUSUARIO</td><td>DOMINIO</td><td>CREACION</td></tr>";
	    		foreach ($datos as $key => $value) {
    				echo "<tr><td>".$value['idWeb']."</td><td>".$value['idUsuario']."</td><td>".$value['dominio']."</td><td>".$value['fechaCreacion']."</td></tr>";
	    		}
	    		echo "</tr></table><br><br>";
	    	}	
    	}
	?>
</body>
</html>