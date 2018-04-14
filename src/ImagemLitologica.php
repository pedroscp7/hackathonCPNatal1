<?php
		
	// Importante classes chamadas pelo composer
	require '../vendor/autoload.php';
	require '../src/bloco.php';
	
	use Dompdf\Dompdf;
	use Intervention\Image\ImageManager;
	
	class ImagemLitologica {

		private $blocos = [];

		public function __construct()
		{
			$this->nome = md5(date("H:m:s"));
		}

		public function addBloco($material, $profundidade)
		{
			// Tamanho da lista de blocos
			$tamanho = count($this->blocos);

			if($tamanho > 0) {

				// Pega último bloco
				$index = $tamanho - 1;
				$ultimo_bloco = $this->blocos[$index];
				
				if($ultimo_bloco->getMaterial() == $material) {
					// soma a profundidade com o bloco anterior e salva no array
					$soma_profundidade = $ultimo_bloco->getProfundidade() + $profundidade;
					$this->blocos[$index] = new Bloco($material, $soma_profundidade);
				} else {
					// adiciona novo bloco ao array
					$this->blocos[] = new Bloco($material, $profundidade);
				}

			} else {
				// Adiciona novo bloco ao array
				$bloco = new BLoco($material, $profundidade);
				$this->blocos[] = $bloco;
			}
			
		}

		public function criarImagem()
		{	

			$profundidade = $this->calculaProfundidade();
			$profundidade_atual = 0;
			$profundidade_bloco = Bloco::escala();
			$largura_total = $this->largura();
			$largura_inicial = 0;

			$orientacao = "top-left";
			$manager = new ImageManager(array('driver' => 'imagick'));
			$image = $manager->make('../img/background.png')->resize($largura_total, $profundidade);

			foreach($this->blocos as $bloco) {
				// Pega o tipo do material
				$material = $bloco->getMaterial();

				// Define onde começa e onde termina o bloco
				$inicial_profundidade = $profundidade_atual;
				$fim_profundidade = $inicial_profundidade + ($bloco->getProfundidade() * $profundidade_bloco);
				$profundidade_atual = $fim_profundidade;

				$largura_primeira = intval($largura_total * 0.45);
				while($inicial_profundidade <= $fim_profundidade) {

					$largura_inicial = 0;
					while($largura_inicial <= $largura_primeira) {

						if($largura_inicial < $largura_total * 0.40){
							$image->insert("../img/" . $material . ".png", $orientacao, $largura_inicial, $inicial_profundidade);
						}
						
						if(($largura_inicial + intval($largura_total * 0.25)) < intval($largura_total * 0.55)) {
							$image->insert("../img/" . "agua" . ".png", $orientacao, $largura_inicial + intval($largura_total * 0.30),  $inicial_profundidade);
						}

						$image->insert("../img/" . $material . ".png", $orientacao, $largura_inicial + intval($largura_total * 0.60),  $inicial_profundidade);
						$largura_inicial += 5;
					}
					$inicial_profundidade += $profundidade_bloco;
				}
			}

			$image->save("../files/" . $this->nome . ".jpg");
		}

		public function verificarImagem()
		{
			return file_exists("../files/" . $this->nome . ".jpg");
		}

		public function getNome()
		{
			return $this->nome;
		}

		private function calculaProfundidade()
		{
			$profundidade_total = 0;
			foreach($this->blocos as $bloco) {
				$profundidade_total += $bloco->getProfundidade();
			}
			return $profundidade_total * Bloco::escala();
		}

		private function largura() 
		{
			return 20 * Bloco::escala();
		}

	}