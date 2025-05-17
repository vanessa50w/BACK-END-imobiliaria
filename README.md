# Projeto Imobiliária - Sistema de Reservas

## Descrição
Este projeto é um sistema básico de reservas para uma imobiliária, que permite aos usuários visualizar imóveis, reservar, editar e deletar reservas. O backend é feito em PHP usando PDO para conexão com banco de dados MySQL, e a interface é feita com HTML/CSS.

## Funcionalidades Principais
- Visualizar lista de imóveis disponíveis
- Reservar imóvel com dados pessoais (nome, email, data da reserva)
- Visualizar as reservas feitas
- Editar e deletar reservas (CRUD completo)
- Interface amigável com HTML e CSS
- Backend seguro usando PHP com PDO para acesso ao banco de dados MySQL

## Tecnologias Utilizadas
- PHP
- PDO (PHP Data Objects)
- MySQL
- HTML / CSS

## Estrutura do Projeto
- `/model` - Classes PHP que representam os dados (Reserva.php, ReservaDAO.php)
- `/controller` - Controladores que gerenciam a lógica de negócios e chamadas do usuário (se houver)
- `/view` - Arquivos HTML/CSS para a interface do usuário
- `/config` - Arquivos de configuração (ex: Conexao.php para conexão com o banco)

## Como Executar
1. Configure seu banco de dados MySQL e importe o script SQL para criar a tabela `reservas`.
2. Atualize as configurações de conexão em `Conexao.php`.
3. Coloque os arquivos no servidor local (XAMPP, WAMP ou similar).
4. Acesse via navegador o sistema para visualizar, criar, editar e deletar reservas.