# Agenda de Estudos

Projeto da ACQA de Aplicações para a Internet. Cada pessoa cria sua conta e organiza tarefas por disciplina, prazo e status.

## Executar com Docker

Pré-requisitos: Docker em execução, Docker Compose e Git. Clone o repositório e abra a pasta `php-lista-tarefas`. O Docker instala o PHP, o Apache e o MySQL dentro dos contêineres.

Na raiz do repositório, execute:

```sh
cp .env.example .env
docker compose up -d --build --wait
```

No PowerShell, use `Copy-Item .env.example .env` para copiar a configuração.

Abra [http://localhost:8080](http://localhost:8080), clique em **Criar conta** e entre com seu e-mail e senha. A página inicial exige login e oferece a opção **Sair**. Se a porta estiver ocupada, altere `APP_PORT` no arquivo `.env`.

O PHP acessa o MySQL na rede interna do Compose. As credenciais do exemplo são para uso local. Na primeira inicialização de um volume novo, o MySQL cria o banco, o usuário da aplicação e as tabelas `usuarios` e `tarefas` a partir de `database/schema.sql`.

Se já subiu os serviços na etapa anterior, execute o comando abaixo após o `docker compose up` para aplicar o esquema no volume existente. O script cria somente as tabelas que faltam.

```sh
docker compose exec db sh -c 'mysql -u "$MYSQL_USER" --password="$MYSQL_PASSWORD" "$MYSQL_DATABASE" < /docker-entrypoint-initdb.d/01-schema.sql'
```

Use `docker compose ps` para consultar o estado dos serviços. Para parar o ambiente, execute `docker compose down`. O volume do MySQL preserva os dados entre execuções.

## Roteiro de verificação

1. Abra a página inicial. Ela redireciona para o login enquanto você não estiver autenticado.
2. Em **Criar conta**, cadastre um nome, um e-mail e uma senha fictícios. Faça login com esses dados.
3. No **Dashboard**, abra **Gerenciar tarefas**, clique em **Adicionar tarefa** e preencha título, disciplina e status. O prazo é opcional.
4. Confirme que a tarefa aparece no dashboard. Em **Gerenciar tarefas**, edite o status, salve e depois exclua a tarefa para verificar o CRUD completo.
5. Use uma segunda conta para confirmar que sua lista é independente. Ao clicar em **Sair**, o sistema volta para o login.

## Estrutura MVC

- `public/index.php` recebe a requisição e o roteador chama o controlador correspondente.
- `app/Controllers/` coordena o fluxo e carrega a visão. A rota inicial chama `HomeController`, que renderiza `app/Views/home.php`.
- O dashboard e a tela **Gerenciar tarefas** exigem login e exibem apenas as tarefas da conta logada. Em **Gerenciar tarefas**, é possível adicionar, consultar, editar e excluir tarefas. Cada alteração exige um token CSRF, e as consultas de edição e exclusão identificam o registro pelo ID da tarefa e pelo ID do usuário.
- `app/Models/` contém os repositórios que consultam o banco. As tarefas são consultadas pelo identificador do usuário.
- [`app/Views/`](app/Views/) contém os formulários e as páginas em HTML. O estilo compartilhado está em [`public/assets/style.css`](public/assets/style.css).

## Padrões de projeto

- **Singleton.** [`Database::connection()`](app/Core/Database.php) cria a conexão PDO na primeira chamada e reutiliza o mesmo objeto durante a requisição.
- **Factory Method.** [`RepositoryFactory::make()`](app/Models/RepositoryFactory.php) obtém a conexão e delega a criação do repositório ao método `createRepository()`. [`UserRepositoryFactory`](app/Models/UserRepositoryFactory.php) cria o repositório de usuários, e [`TaskRepositoryFactory`](app/Models/TaskRepositoryFactory.php) cria o de tarefas.

No cadastro, o controlador guarda somente o hash bcrypt da senha. O login confere a senha com `password_verify()` e inicia a sessão autenticada. Os repositórios usam consultas parametrizadas ao MySQL.

## Capturas da ACQA

As capturas mostram o sistema em execução com dados fictícios.

- [Tela de login](docs/capturas/01-login.jpg)
- [Tela de cadastro](docs/capturas/02-cadastro.jpg)
- [Dashboard com lista de tarefas](docs/capturas/03-dashboard.jpg)
- [Formulário de adição de tarefa](docs/capturas/04-adicionar-tarefa.jpg)
