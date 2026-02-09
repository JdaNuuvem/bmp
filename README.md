# Sistema de Cadastro de Leads BMP

Este é um sistema de cadastro de leads moderno e responsivo, desenvolvido em PHP, MySQL, HTML, CSS e JavaScript. Ele apresenta um formulário de captura de leads com design "Premium" (Glassmorphism com tema escuro e dourado), validação de campos, máscaras de entrada e uma área administrativa protegida para visualização e gerenciamento dos leads.

## Funcionalidades

*   **Formulário de Cadastro de Leads:**
    *   Design moderno e responsivo (Glassmorphism, Dark Mode com detalhes em dourado).
    *   Validação de campos em tempo real (JavaScript) e no servidor (PHP).
    *   Máscaras de entrada para Telefone (WhatsApp), CPF, RG e CEP.
    *   Integração com ViaCEP para busca automática de endereços.
    *   Navegação fluida entre os passos do formulário com animações de transição.
*   **Área Administrativa:**
    *   Página de Login segura com autenticação via banco de dados.
    *   Dashboard premium para visualização de leads cadastrados.
    *   Cards de estatísticas (Total de Leads, Leads Hoje, Conversão FGTS).
    *   Tabela de leads responsiva e organizada.
    *   Funcionalidade de Logout.

## Requisitos

Para que o sistema funcione corretamente, seu ambiente de hospedagem web deve atender aos seguintes requisitos:

*   **Servidor Web:** Apache ou Nginx (com suporte a PHP).
*   **PHP:** Versão 7.4 ou superior.
*   **Extensão PDO:** Habilitada para conexão com MySQL (geralmente habilitada por padrão).
*   **Banco de Dados:** MySQL ou MariaDB.

## Instalação e Configuração

Siga os passos abaixo para configurar o aplicativo em seu servidor de hospedagem:

### 1. Upload dos Arquivos

1.  Baixe ou faça upload de todos os arquivos e pastas do projeto (`app.js`, `db.php`, `index.php`, `process.php`, `style.css`, `admin_dashboard.php`, `admin_login.php`, `logout.php`, `database.sql`, e a pasta `BMP/`) para o diretório raiz do seu site na hospedagem (ex: `public_html` ou `htdocs`).

### 2. Configuração do Banco de Dados

1.  **Crie um Banco de Dados:**
    *   Acesse o painel de controle da sua hospedagem (cPanel, DirectAdmin, etc.).
    *   Procure pela seção "Banco de Dados MySQL" (ou similar).
    *   Crie um novo banco de dados (ex: `banco_white`).
    *   Crie um novo usuário para este banco de dados e associe-o ao banco recém-criado, concedendo **todos os privilégios**. Anote o **nome do banco, usuário e senha**, pois você precisará deles no próximo passo.

2.  **Importe a Estrutura e Dados Iniciais:**
    *   Ainda no painel de controle da sua hospedagem, acesse o **phpMyAdmin**.
    *   Selecione o banco de dados que você acabou de criar (`banco_white`).
    *   Clique na aba "Importar".
    *   Clique em "Escolher arquivo" e selecione o arquivo `database.sql` que você fez upload.
    *   Deixe as opções padrão e clique em "Executar".
    *   Isso criará as tabelas `leads` e `admins` e inserirá o usuário administrador padrão.

### 3. Configuração do PHP (`db.php`)

1.  Abra o arquivo `db.php` no diretório do seu projeto (usando um editor de texto ou o gerenciador de arquivos da sua hospedagem).
2.  Edite as seguintes linhas com as informações do seu banco de dados (criadas no passo 2):

    ```php
    // Configurações do Banco de Dados (MySQL / MariaDB)
    $host = 'localhost'; // Geralmente 'localhost', mas verifique com sua hospedagem
    $db   = 'seu_nome_do_banco'; // Ex: 'banco_white'
    $user = 'seu_usuario_do_banco'; // Ex: 'usuario_banco'
    $pass = 'sua_senha_do_banco';   // Ex: 'senha123'
    ```
3.  Salve as alterações no arquivo `db.php`.

## Acessando o Aplicativo

Após seguir os passos de instalação, você poderá acessar o sistema:

*   **Formulário de Cadastro de Leads:**
    *   Acesse: `http://seusite.com/index.php` (substitua `seusite.com` pelo domínio ou IP do seu site).

*   **Página de Login da Área Administrativa:**
    *   Acesse: `http://seusite.com/admin_login.php`

## Credenciais de Acesso Administrativo

*   **Usuário:** `admin`
*   **Senha:** `admin123`

**Recomendação:** Por segurança, é altamente recomendado que você altere a senha do usuário `admin` após o primeiro login ou diretamente no banco de dados. Para alterar a senha no banco de dados, você pode usar o phpMyAdmin e executar um comando SQL como:

```sql
UPDATE admins SET password = '$2y$10$NOVO_HASH_DA_SENHA' WHERE username = 'admin';
```
Você pode gerar um novo hash de senha usando `password_hash('sua_nova_senha', PASSWORD_BCRYPT);` em um script PHP.

## Solução de Problemas Comuns

*   **Erro de Conexão com o Banco de Dados:**
    *   Verifique se as credenciais em `db.php` estão corretas (host, nome do banco, usuário, senha).
    *   Certifique-se de que o banco de dados e o usuário foram criados e associados corretamente.
*   **Página Não Encontrada (404):**
    *   Verifique se os arquivos foram carregados para o diretório correto na hospedagem.
    *   Confira se os nomes dos arquivos (`index.php`, `admin_login.php`, etc.) estão escritos corretamente na URL.
*   **Credenciais de Login Inválidas:**
    *   Verifique se o usuário e a senha estão corretos (`admin` / `admin123`).
    *   Confirme se a tabela `admins` foi importada corretamente no banco de dados.

---

Aproveite seu novo sistema de cadastro de leads!
