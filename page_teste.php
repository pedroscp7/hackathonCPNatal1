!<!DOCTYPE html>
<html>
<head>
	<title> Página de teste da Geração de imagens</title>
</head>
<body>
	<header>
		<h1> Oi, como você vai? </h1>
	</header>
	<main>
		<h2> Adicione novos materiais e seus comprimentos </h2>
		<form id="form" method="POST" action="./page_teste_post.php">
			
			<div>
				<label> Material (A, B ou C)</label>
				<input type="text" name="material" />
			</div>

			<div>
				<label> Comprimento (metros)</label>
				<input type="text" name="comprimento" />
			</div>

			<div>
				<input type="submit" name="enviar" value="Adicionar" />
			</div>
		</form>
	</main>
	<script>
		console.log("--- hello, that is my test! MOTHERFUCK! YO!");

		document.getElementById("#form").onclick(function(event) {
			event.preventDefault();
			console.log("-- salvando informações --");
		});
	</script>
</body>
</html>