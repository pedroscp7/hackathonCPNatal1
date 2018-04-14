<?php
	
	require_once "../src/ImagemLitologica.php";

	$gerador = new ImagemLitologica();
	$gerador->addBloco("calcario_preto", 7);
	$gerador->addBloco("cascalho", 10);
	$gerador->addBloco("siltito_amarelado", 5);
	$gerador->addBloco("arenito_avermelhado", 5);
	$gerador->addBloco("solo_amarelado", 1);	

	$gerador->criar();
	echo $gerador->verificarImagem() ? "Sucesso! Imagem criado: {$gerador->getNome()}" : "Erro!";
	echo "\n";