<?php
	
	require_once "../src/ImagemLitologica.php";

	$gerador = new ImagemLitologica();
	$gerador->addBloco("arenito_amarelo", 7);
	$gerador->addBloco("arenito_avermelhado", 10);
	$gerador->addBloco("argila_acinzentada", 5);
	$gerador->addBloco("argila_acinzentada", 5);
	$gerador->addBloco("calcario_preto", 1);	

	$gerador->criarImagem();
	echo $gerador->verificarImagem() ? "Sucesso! Imagem criado: {$gerador->getNome()}" : "Erro!";
	echo "\n";