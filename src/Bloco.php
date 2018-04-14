<?php

	class Bloco 
	{

		private $material;
		private $profundidade;

		private $tipos_materiais = [
			"agua",
			"arenito_amarelo",
			"arenito_avermelhado",
			"argila_acinzentada",
			"argila_avermelhada",
			"calcario_preto",
			"folhelho_amarronzado",
			"siltito_amarelado",
			"solo_amarelado",
		];

		function __construct($material, $profundidade)
		{
			
			// Verifica se existe material
			if(!in_array($material, $this->tipos_materiais)) {
				throw new Exception("Material não existe");
			}

			$this->material = $material;
			$this->profundidade = $profundidade;
		}

		public function getMaterial()
		{
			return $this->material;
		}

		public function getProfundidade() 
		{
			return $this->profundidade;
		}

	}