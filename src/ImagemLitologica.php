<?php
		
	// Importante classes chamadas pelo composer
	require '../vendor/autoload.php';
	require '../src/bloco.php';
	
	use Dompdf\Dompdf;
	use Intervention\Image\ImageManager;
	
	class ImagemLitologica {

		private $blocos = [];
		private $orientacao = "top-left";
		private $ultimo_material;

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

		private function iniciar($largura_total, $profundidade)
		{
			$manager = new ImageManager(array('driver' => 'imagick'));
			$imagem = $manager->make('../img/background.png')->resize($largura_total, $profundidade);
			return $imagem;
		}

		private function inserir($imagem, $path, $pos_x, $pos_y)
		{
			$pos_x = intVal($pos_x);
			$pos_y = intVal($pos_y);
			$path = "../img/" . $path . ".png";

			$imagem->insert($path, $this->orientacao, $pos_x, $pos_y);
			
			return $imagem;
		}

		public function criar()
		{	

			// Propriedades
			$profundidade = $this->calculaProfundidade();
			$profundidade_atual = 0;
			$profundidade_bloco = Bloco::escala();
			$largura_total = $this->largura();
			$largura_inicial = 0;

			// Iniciando Classe para gerar imagem
			$imagem = $this->iniciar($largura_total, $profundidade);

			// Adiciona títulos
			$imagem = $this->legenda($imagem,$largura_total,$profundidade);
			// $path = $this->gerandoBloco();

			// inserir imagem gerada código 
			// 20%

			
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

						// Adiciona Poco
						$imagem = $this->poco($imagem,
											$largura_inicial,
											$largura_total,
											$material, 
											$inicial_profundidade);

						$largura_inicial += Bloco::escala();
					}

					$inicial_profundidade += $profundidade_bloco;
				}
			}

			$imagem->save("../files/" . $this->nome . ".jpg");
		}

		public function verificarImagem()
		{
			return file_exists("../files/" . $this->nome . ".jpg");
		}

		public function getNome()
		{
			return $this->nome;
		}

		public function getPath() {
			return "../files/" . $this->nome . ".jpg";
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
			return 3 * Bloco::escala() * 3;
		}

		public function poco($imagem, $largura_inicial, $largura_total, $material, $inicial_profundidade) {
			
			$largura_inicial_poco = $largura_inicial + ($largura_total * 0.2);
			$largura_final_poco = $largura_total * 0.6;
			$inicial_profundidade = $inicial_profundidade + (Bloco::escala() * 0.1);

			if($largura_inicial < $largura_total * 0.40){							
				$imagem = $this->inserir($imagem, $material, $largura_inicial, $inicial_profundidade);
			}
			
			// Adiciona agua
			if(($largura_inicial + intval($largura_total * 0.25)) < intval($largura_total * 0.55)) {
				$pos_x  = $largura_inicial + intval($largura_total * 0.30);
				$imagem  = $this->inserir($imagem, "agua", $pos_x,  $inicial_profundidade);
			}

			// Adiciona imagem da direita
			$pox_x = $largura_inicial + intval($largura_total * 0.60);
			$imagem = $this->inserir($imagem, $material, $pox_x,  $inicial_profundidade);
			return $imagem;
		}

		public function legenda($imagem, $largura_total,$profundidade_total) {

			$imagem = $this->inserir($imagem, "escala", $largura_total * 0.1, $profundidade_total * 0.1);
			$imagem = $this->inserir($imagem, "reservatorio", $this->orientacao, $largura_total * 0.4, $profundidade_total * 0.1);
			$imagem = $this->inserir($imagem, 'escala2', $this->orientacao, $largura_total * 0.7, $profundidade_total * 0.1);
			$imagem = $this->inserir($imagem, 'litologia', $this->orientacao, $largura_total * 0.7, $profundidade_total * 0.1);

			return $imagem;
		}

	}