<?php
	require_once "../src/ImagemLitologica.php";

	$litologica = new ImagemLitologica();

	if(empty($_POST["blocos"])) {
		$blocos = '[{"comprimento":"10","material":"arenito_amarelo"},{"comprimento":"10","material":"arenito_avermelhado"},{"comprimento":"10","material":"argila_acinzentada"},{"comprimento":"10","material":"argila_avermelhada"},{"comprimento":"10","material":"calcario_preto"},{"comprimento":"10","material":"folhelho_amarronzado"},{"comprimento":"10","material":"siltito_amarelado"},{"comprimento":"10","material":"solo_amarelado"}]';
	} else {
		$blocos = $_POST["blocos"];
	}

	$blocos = json_decode($blocos);

	foreach($blocos as $bloco) {
		$litologica->addBloco($bloco->material, $bloco->comprimento);
	}

	$litologica->criar();

	$x = $litologica->getX();
	$y = $litologica->getY();

	$img = $litologica->getNome();
	echo "<img src='/files/{$img}.jpg' width='{$x}' height='{$y}' />";

