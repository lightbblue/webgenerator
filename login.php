<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>webgenerator Joaquin Stachioni</title>
</head>
<body>
	<h1>webgenerator Joaquin Stachioni</h1>
	<form action="login.php" method="post">
		Email: <input type="email" name="correo" required><br><br>
		Contraseña: <input type="password" name="contra" required><br><br>
		<a href="./register.php">Registrarse</a><br><br>
		<input type="submit" name="submit" value="Ingresar"><br><br>
	</form>
	<?php  
		session_start();
		if(isset($_SESSION['id'])){
			header("location:panel.php");
		}
		$ingresobtn = filter_input(INPUT_POST, 'submit');
		$data = "mysql:host=localhost;dbname=webgenerator";
	$conexion = new PDO($data, 'adm_webgenerator', 'webgenerator2024');

		if($ingresobtn){
			$consulta = $conexion->prepare("SELECT * FROM `usuarios` WHERE `email` = :email");
			$consulta->execute(array(
				':email' => $_POST['correo']
		    ));
			$datos = $consulta->fetch(PDO::FETCH_ASSOC);
			if(isset($datos['email'])){
				if($datos['password']!=$_POST['contra']){
					echo "Contraseña Incorrecta";
				}
				else{
					$_SESSION['id']=$datos['idUsuario'];
					header("location:panel.php");
				}
			}
			else{
				echo "Correo Incorrecto";
			}
		}
	?>
</body>
</html>