# TIME BLUBOX
vandemberg Lima
Patricia Regis
Diego Marcelino

# Biblioteca para gerar imagem Litologica

## Rodar composer install
Importar o código para o seu projeto
Rodar o teste web: Vá na pasta do código e rode: php -S localhost:80
Acesse a url: localhost/teste
Rodar o teste por CLI vá a pasta teste do projeto e rode: PHP teste-cli.php

# Imagens geradas
Todas as imagens geradas ficam salvas em path_do_projeto/files


# Funções abertas da biblioteca

Contrutor vazio!

getX(): largura da imagem
getY(): altura da imagem
addBloco(tipo_do_material: string, profunidade_material)
criar(): gera a imagem
verificaImagem(): verifica se a imagem foi criada. (retorna um true ou false)
getNome(): Pega o nome da imagem que foi ferada. (Um hash do timestamp)
