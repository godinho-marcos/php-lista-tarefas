# php-lista-tarefas
Projeto para a disciplina APLICAÇÕES PARA A INTERNET do curso SISTEMAS DE INFORMAÇÃO

## Executar com Docker

Com o Docker e o Docker Compose disponíveis, execute na raiz do repositório:

```sh
cp .env.example .env
docker compose up -d --build
```

No PowerShell, use `Copy-Item .env.example .env` para copiar a configuração.

Abra [http://localhost:8080](http://localhost:8080). Nesta etapa, a página exibe uma mensagem que confirma a execução do PHP. Se a porta estiver ocupada, altere `APP_PORT` no arquivo `.env`.

O MySQL é acessado pelo PHP na rede interna do Compose. As credenciais do exemplo são para uso local. O banco e o usuário são criados na primeira inicialização do volume.

Use `docker compose ps` para consultar o estado dos serviços. Para parar o ambiente, execute `docker compose down`. O volume do MySQL preserva os dados entre execuções.
