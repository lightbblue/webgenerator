<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>webgenerator Joaquin Stachioni</title>
</head>
<body>
	<h1>Registrarse es simple</h1>
	<form action="register.php" method="post">
		Email: <input type="email" name="correo" required><br><br>
		Contraseña: <input type="password" name="contra" required><br><br>
		Repetir Contraseña: <input type="password" name="contra2" required><br><br>
		<input type="submit" name="submit" value="Registrarse"><br><br>
	</form>
	<?php  
		session_start();
		if(isset($_SESSION['id'])){
			header("location:panel.php");
		}
		$registrobtn = filter_input(INPUT_POST, 'submit');
		$data = "mysql:host=localhost;dbname=webgenerator";
	$conexion = new PDO($data, 'adm_webgenerator', 'webgenerator2024');

		if($registrobtn){
			if($_POST['contra']==$_POST['contra2']){
				$consulta = $conexion->prepare("SELECT * FROM `usuarios` WHERE `email` = :email");
				$consulta->execute(array(
				':email' => $_POST['correo']
		    	));
		    	$datos = $consulta->fetch(PDO::FETCH_ASSOC);
		    	if(isset($datos['email'])){
		    		echo "El usuario ya se encuentra registrado<br>";
		    	}
		    	else{
		    		$consulta = $conexion->prepare("INSERT INTO `usuarios` VALUES (NULL,:email,:pass,:fecha)");
					$consulta->execute(array(
						':email' => $_POST['correo'],
						':pass' => $_POST['contra'],
						':fecha' => date('Y-m-d')
			    	));
			    	echo "Registro Exitoso";
			    	header("location:login.php");
		    	}
			}
			else{
				echo "Las contraseñas no coinciden";
			}
		}
	?>
</body>
</html>