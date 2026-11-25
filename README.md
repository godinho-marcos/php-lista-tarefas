# php-lista-tarefas
Projeto para a disciplina APLICAÇÕES PARA A INTERNET do curso SISTEMAS DE INFORMAÇÃO

## Executar com Docker

Com o Docker e o Docker Compose disponíveis, execute na raiz do repositório:

```sh
cp .env.example .env
docker compose up -d --build
```

No PowerShell, use `Copy-Item .env.example .env` para copiar a configuração.

Abra [http://localhost:8080](http://localhost:8080), clique em **Criar conta** e entre com seu e-mail e senha. A página inicial exige login e oferece a opção **Sair**. Se a porta estiver ocupada, altere `APP_PORT` no arquivo `.env`.

O PHP acessa o MySQL na rede interna do Compose. As credenciais do exemplo são para uso local. Na primeira inicialização de um volume novo, o MySQL cria o banco, o usuário da aplicação e as tabelas `usuarios` e `tarefas` a partir de `database/schema.sql`.

Se já subiu os serviços na etapa anterior, execute o comando abaixo após o `docker compose up` para aplicar o esquema no volume existente. O script cria somente as tabelas que faltam.

```sh
docker compose exec db sh -c 'mysql -u "$MYSQL_USER" --password="$MYSQL_PASSWORD" "$MYSQL_DATABASE" < /docker-entrypoint-initdb.d/01-schema.sql'
```

Use `docker compose ps` para consultar o estado dos serviços. Para parar o ambiente, execute `docker compose down`. O volume do MySQL preserva os dados entre execuções.

## Estrutura MVC

- `public/index.php` recebe a requisição e o roteador chama o controlador correspondente.
- `app/Controllers/` coordena o fluxo e carrega a visão. A rota inicial chama `HomeController`, que renderiza `app/Views/home.php`.
- O dashboard e a tela **Gerenciar tarefas** exigem login e exibem apenas as tarefas da conta logada. Em **Gerenciar tarefas**, é possível adicionar, consultar, editar e excluir tarefas. Cada alteração exige um token CSRF, e as consultas de edição e exclusão identificam o registro pelo ID da tarefa e pelo ID do usuário.
- `app/Models/` contém os repositórios que consultam o banco. As tarefas são consultadas pelo identificador do usuário.
- `app/Core/Database.php` usa Singleton para reutilizar uma conexão PDO na mesma requisição. Em `app/Models/`, `RepositoryFactory::make()` fornece essa conexão e delega a construção do repositório ao Factory Method `createRepository()`, implementado pelas fábricas de usuários e tarefas.
