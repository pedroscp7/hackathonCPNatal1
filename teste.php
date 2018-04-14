<?php
	
	require_once "Gerador.php";

	$gerador = new Gerador();
	$gerador->addMaterial("arenito_amarelo", 7 * 5);
	$gerador->addMaterial("arenito_avermelhado", 10 * 5);
	$gerador->addMaterial("argila_acinzentada", 5 * 5);
	$gerador->addMaterial("argila_acinzentada", 5 * 5);
	$gerador->addMaterial("calcario_preto", 1 * 5);	

	$gerador->criarImagem();
	echo $gerador->verificarImagem() ? "Sucesso! Imagem criado: {$gerador->getNome()}" : "Erro!";
	echo "\n";