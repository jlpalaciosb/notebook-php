<?php
	require $_SERVER["DOCUMENT_ROOT"] . "/include/utilidades.php";

	session_start();
	if (!isset($_SESSION['diario_user_logged'])) { #debe iniciar sesión
		header("Location: /login/index.php");
		exit();
	}

	$dateuserDb = $ownerDb = $contentDb = "";
	
	if ($_SERVER['REQUEST_METHOD'] == 'GET') hacerEnGet();
	else if ($_SERVER['REQUEST_METHOD'] == 'POST') hacerEnPost();
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<title>Editar</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="/bootstrap-3.3.7/dist/css/bootstrap.css">
	<link rel="stylesheet" href="/style.css">
	<link rel="shortcut icon" type="image/png" href="/res/diarioapp.png"/>
	<meta charset="UTF-8">
	<script>
        var textarea = null;
        window.addEventListener("load", function() {
            textarea = window.document.querySelector("textarea");
            textarea.addEventListener("keypress", function() {
                if(textarea.scrollTop != 0){
                    textarea.style.height = textarea.scrollHeight + 2 +"px";
                }
            }, false);
            textarea.style.height = textarea.scrollHeight + 2 +"px";
        }, false);
    </script>
</head>
<body>
	<div class="container">
		<!--Encabezado?-->
		<?php include $_SERVER["DOCUMENT_ROOT"] . "/include/encabezado.php"; ?>

		<h1 class="outside">
			<?php echo legible_dateuser($dateuserDb) ?>
		</h1>
		<form class="cuadro" method="post" action="/diarios/editar.php">
			<input style="display:none;" type="text" name="dateuser" value="<?php echo $_GET["dateuser"]; ?>">
			<div class="form-group">
				<label for="content">Edite este diario</label>
				<textarea name="content" class="form-control"><?php echo $contentDb ?></textarea>
			</div>
			<button type="submit" class="btn btn-primary">Guardar</button>
		</form>
	</div>
</body>
</html>

<?php
	function hacerEnGet() {
		try {
			if (isset($_GET["date"]) && !empty($_GET["date"])) {
				$_GET["dateuser"] = $_GET["date"] . "-" . $_SESSION["diario_user_logged"]; //esto es un parche

				$dateuserPar = $_GET["dateuser"];

				$conn = null;
				$conn = new PDO("pgsql:host=localhost;dbname=diariodb", "postgres", "12345");
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$stmt = $conn->prepare("SELECT * FROM diarios WHERE dateuser=:a");
				$stmt->bindParam(':a', $dateuserPar);

				$stmt->execute();
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
				if (empty($result)) {
					throw new Exception("No se encontró registro en la base de datos");
				}

				$GLOBALS["dateuserDb"] = $result[0]['dateuser'];
				$GLOBALS["ownerDb"] = $result[0]['owner'];
				$GLOBALS["contentDb"] = $result[0]['content'];

				$GLOBALS["contentDb"] = openssl_decrypt($GLOBALS["contentDb"], "AES-128-CBC",
										 $_SESSION['user_password_md5'],
										 0, '0000000000000000');	//parche desencriptacion

				if ($GLOBALS["ownerDb"] != $_SESSION['diario_user_logged']) {
					throw new Exception("No tienes permiso");
				}
			
				$conn = null;
			} else {
				throw new Exception("Error de parametro get");
			}
		} catch(Exception $e) {
		    echo '<p class="error">Error: ' . $e->getMessage() . "</p>";
		    exit();
		}
	}

	function hacerEnPost() {
		try {
			if(!isset($_POST["content"]) || !isset($_POST["dateuser"]) || empty($_POST["dateuser"])) {
				throw new Exception("Error de parametro post", 1);
			}
			if (tengoPermiso()) {
				$conn = null;
				$conn = new PDO("pgsql:host=localhost;dbname=diariodb", "postgres", "12345");
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$stmt = $conn->prepare("UPDATE diarios SET content=:a WHERE dateuser=:b");
				
				$_POST["content"] = openssl_encrypt($_POST["content"], "AES-128-CBC",
													$_SESSION['user_password_md5'],
													0, '0000000000000000');			//parche encriptación
				
				$stmt->bindParam(':a', $_POST["content"]);
				$stmt->bindParam(':b', $_POST["dateuser"]);
				$stmt->execute();

				header("Location: /diarios/ver.php?date=" . substr($_POST["dateuser"], 0, 10));
				exit();
			} else {
				echo "No tienes permiso";
				exit();
			}
		} catch(Exception $e) {
		    echo '<p class="error">Error: ' . $e->getMessage() . "</p>";
		    exit();
		}
	}

	function tengoPermiso() {
		$permiso = false;

		try {
			$conn = null;
			$conn = new PDO("pgsql:host=localhost;dbname=diariodb", "postgres", "12345");
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$stmt = $conn->prepare("SELECT * FROM diarios WHERE dateuser=:a");
			$stmt->bindParam(':a', $_POST["dateuser"]);

			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if (empty($result)) {
				throw new Exception("No se encontró el registro a editar en la base de datos");
			}

			if ($result[0]['owner'] == $_SESSION['diario_user_logged']) {
				$permiso = true;
			}
		
			$conn = null;
		} catch(Exception $e) {
		    echo '<p class="error">Error: ' . $e->getMessage() . "</p>";
		    exit();
		}

		return $permiso;
	}
?>