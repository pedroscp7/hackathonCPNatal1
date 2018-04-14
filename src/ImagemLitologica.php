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
			$largura_atual = 0;

			// Iniciando Classe para gerar imagem
			$poco = $this->iniciar($largura_total, $profundidade);
			
			foreach($this->blocos as $bloco) {
				$largura = $largura_total;
				$imagem = $this->inserindoBloco(
					$poco, 
					$bloco,
					$profundidade, 
					$largura, 
					$profundidade_atual, 
					$largura_atual);

				$profundidade_atual += ($bloco->getProfundidade() * Bloco::escala());

			}

			// Inserindo a agua
			$profundidade_bloco = $profundidade_atual;
			$pos_x = intVal($largura_total * 0.2);
			$pos_y = 0;
			$path_bloco = $this->geraBloco("agua", $pos_x, $profundidade_bloco);
			$poco = $this->inserir($poco, $path_bloco, intVal($this->largura() * 0.4), 0);			
			unlink($path_bloco);
			$poco->save("../files/" . $this->nome . ".jpg");
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
			$pos_x = $largura_inicial + intval($largura_total * 0.60);
			$imagem = $this->inserir($imagem, $material, $pos_x,  $inicial_profundidade);
			return $imagem;
		}

		public function inserindoBloco($imagem, $bloco, $profundidade,$largura, $profundidade_atual, $largura_atual) {

			// Calculando posições
			$profundidade_bloco = $bloco->getProfundidade() * Bloco::escala();
			$pos_x = intVal($largura);
			$pos_y = $profundidade_atual;

			$path_bloco = $this->geraBloco($bloco->getMaterial(), $pos_x, $profundidade_bloco);

			$imagem = $this->inserir($imagem, $path_bloco, $largura_atual, $profundidade_atual);
			// Deleta imagem gerada
			unlink($path_bloco);

			return $imagem;
		}

		public function geraBloco($material, $largura, $profundidade)
		{
			$nome_bloco = "bloco_" . $material . date("H-m-s");
			$path_bloco = "../path/" . $nome_bloco . ".png";
			$manager = new ImageManager(array('driver' => 'imagick'));
			$path = "../img/" . $material . ".png";
			$bloco = $manager->make($path)->resize($largura, $profundidade);
			$bloco->save($path_bloco);
			return $path_bloco;
		}


		public function getX(){
			return $this->largura();
		}

		public function getY(){
			return $this->calculaProfundidade();
		}

	}