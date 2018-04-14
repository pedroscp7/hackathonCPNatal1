<?php
		
	// Importante classes chamadas pelo composer
	require 'vendor/autoload.php';
	
	use Dompdf\Dompdf;
	use Intervention\Image\ImageManager;
	
	class Gerador {

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

		private $propriedades = [];

		public function __construct($diametro = 0, $volume = 0)
		{
			// $this->pdf = new Dompdf();
			$this->materiais = [];
			$this->nome = md5(date("H:m:s"));
			$this->propriedades["diametro"] = $diametro;
			$this->propriedades["volume"] = $volume;
		}

		public function addMaterial($tipo, $profundidade)
		{
			// Verifica se existe material
			if(!in_array($tipo, $this->tipos_materiais)) {
				throw new Exception("Material não existe");
			}

			// Tamanho da lista de materiais
			$tamanho = count($this->materiais);

			if($tamanho > 0) {

				// Pega último material
				$index = $tamanho - 1;
				$ultimo_material = $this->materiais[$index];
				
				if($ultimo_material["tipo"] == $tipo) {
					// soma a profundidade com o material anterior e salva no array
					$soma_profundidade = $ultimo_material["profundidade"] + $profundidade;
					$this->materiais[$index] = ["tipo" => $tipo, "profundidade" => $profundidade];
				} else {
					// adiciona novo material ao array
					$this->materiais[] = ["tipo" => $tipo, "profundidade" => $profundidade];
				}

			} else {
				// Adiciona novo mateiral ao array
				$this->materiais[] = ["tipo" => $tipo, "profundidade" => $profundidade];
			}
			
		}

		public function criarImagem()
		{	

			$profundidade = $this->calculaAltura();
			$profundidade_atual = 0;
			$profundidade_bloco = 5;
			$largura_total = $this->largura();
			$largura_inicial = 0;

			$orientacao = "top-left";
			$manager = new ImageManager(array('driver' => 'imagick'));
			$image = $manager->make('./img/background.png')->resize($largura_total, $profundidade);

			foreach($this->materiais as $material) {
				// Define bloco
				$bloco = $material["tipo"];

				// Define onde começa e onde termina o material
				$inicial_profundidade = $profundidade_atual;
				$fim_profundidade = $inicial_profundidade + ($material["profundidade"] * $profundidade_bloco);
				$profundidade_atual = $fim_profundidade;

				$largura_primeira = intval($largura_total * 0.45);
				while($inicial_profundidade <= $fim_profundidade) {

					$largura_inicial = 0;
					while($largura_inicial <= $largura_primeira) {

						if($largura_inicial < $largura_total * 0.40){
							$image->insert("./img/" . $bloco . ".png", $orientacao, $largura_inicial, $inicial_profundidade);
						}
						
						if(($largura_inicial + intval($largura_total * 0.25)) < intval($largura_total * 0.55)) {
							$image->insert("./img/" . "agua" . ".png", $orientacao, $largura_inicial + intval($largura_total * 0.30),  $inicial_profundidade);
						}

						$image->insert("./img/" . $bloco . ".png", $orientacao, $largura_inicial + intval($largura_total * 0.60),  $inicial_profundidade);
						$largura_inicial += 5;
					}
					$inicial_profundidade += $profundidade_bloco;
				}
			}

			$image->save("./files/" . $this->nome . ".jpg");
		}

		public function verificarImagem()
		{
			return file_exists("./files/" . $this->nome . ".jpg");
		}

		public function getNome()
		{
			return $this->nome;
		}

		private function calculaAltura()
		{
			$comprimento_total = 0;
			foreach($this->materiais as $material) {
				$comprimento_total += $material["profundidade"];
			}
			return $comprimento_total * 5;
		}

		private function largura() {
			return 500;
		}

	}