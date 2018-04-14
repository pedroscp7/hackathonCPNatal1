<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title> Teste Litologia API </title>
	<link rel="stylesheet" type="text/css" href="/teste/bootstrap/css/bootstrap.css" />
</head>
<body>
	<header>
		<center><h1> Teste geraçãoo de imagem litologica</h1></center>
	</header>
	<main class="container">
		<form class="" method="POST", action="/teste/index-form.php">
			<input type="hidden" name="blocos" id="blocos" value="[]">
			<div class="form-group">
				<label> Adicionar material </label>
				<select class="form-control" id="material">
					<option class="form-control" value="agua"> Água </option>
					<option class="form-control" value="arenito_amarelo"> Arenito Amarelo </option>
					<option class="form-control" value="arenito_avermelhado"> Arenito Avermelhado </option>
					<option class="form-control" value="argila_acinzentada"> Argila Acinzetada </option>
					<option class="form-control" value="argila_avermelhada"> Argila Avermelhada </option>
					<option class="form-control" value="calcario_preto"> Calcario Preto </option>
					<option class="form-control" value="folhelho_amarronzado"> Folhelho Amerronzado </option>
					<option class="form-control" value="siltito_amarelado"> Siltito Amarelado </option>
					<option class="form-control" value="solo_amarelado"> Solo Amarelado </option>
				</select>
			</div>

			<div class="form-group">
				<label>Comprimento (em metros)</label>
				<input class="form-control" type="number" id="comprimento" />
			</div>

			<div class="form-group">
				<input class="btn btn-default" type="button" id="adicionar" value="Adicionar">
			</div>

			<div class="form-group">
				<input class="btn btn-primary" type="submit" value="Gerar Imagem" />
			</div>
		</form>

		<table class="table">
			<thead>
				<tr>
					<th>Material</th>
					<th>Comprimento</th>
				</tr>
			</thead>
			<tbody id="table-blocos">
			</tbody>
		</table>

		<div>
	</main>
	<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
	<script type="text/javascript">
		$("#adicionar").on("click", function(event){
			
			event.preventDefault();
			
			let comprimento = $("#comprimento").val();
			let material = $("#material").val();
			let obj = $("#blocos").val();

			console.log(comprimento);
			console.log(material);
			console.log();

			let blocos = JSON.parse(obj);
			bloco = {
				comprimento: comprimento,
				material: material
			};

			blocos.push(bloco);

			$("#blocos").val(JSON.stringify(blocos));

			let html = `
				<tr>
					<td>${bloco.comprimento}</td>
					<td>${bloco.material}</td>
				</tr>
			`;

			$("#table-blocos").append(html);

		});

	</script>
</body>
</html>