# Projeto Veículo Já!

Sistema web para compra e venda de veículos, desenvolvido como projeto para a disciplina de Programação para Internet.

## Tecnologias utilizadas

- PHP 7.4+
- MySQL 5.7+
- HTML5
- CSS3
- JavaScript

## Estrutura do projeto

```
projeto_veiculos/
├── css/             # Arquivos de estilo
├── images/          # Imagens do sistema
├── includes/        # Arquivos de inclusão (cabeçalho, rodapé, configurações)
├── js/              # Arquivos JavaScript
├── uploads/         # Diretório para upload de imagens de veículos
└── sql/             # Scripts SQL para criação do banco de dados
```

## Funcionalidades

### Usuários não logados
- Visualizar veículos disponíveis
- Filtrar veículos por marca, modelo e cidade
- Visualizar detalhes de veículos
- Cadastrar-se no sistema
- Fazer login

### Usuários logados
- Todas as funcionalidades de usuários não logados
- Manifestar interesse em veículos
- Gerenciar seus próprios anúncios (criar, editar, excluir)
- Gerenciar interesses manifestados
- Visualizar interesses recebidos em seus anúncios

## Configuração e Instalação

1. Clone o repositório ou extraia os arquivos para o diretório do seu servidor web (ex: htdocs, www, etc)
2. Importe o arquivo SQL localizado em `/sql/database.sql` para criar o banco de dados e as tabelas necessárias
3. Configure os dados de conexão com o banco de dados no arquivo `/includes/config.php`
4. Certifique-se de que o diretório `/uploads` possui permissões de escrita
5. Acesse o sistema pelo navegador (ex: http://localhost/projeto_veiculos)

## Usuário de teste

Para fins de teste, o sistema já possui um usuário cadastrado:
- E-mail: joao@example.com
- Senha: 123456

## Autores

Este projeto foi desenvolvido como trabalho final da disciplina de Programação para Internet. 