# 📋 Task Manager API

API RESTful para gerenciar tarefas com Laravel, Docker e testes automatizados.

---

## 🚀 Quick Start

### 1️⃣ Clonar & Entrar

```bash
git clone https://github.com/Lucas-0802/task-manager-api.git
cd task-manager-api
```

### 2️⃣ Configurar Ambiente

```bash
cp .env.example .env
```

### 3️⃣ Levantar com Docker

```bash
docker compose up -d
```

**Pronto!** As migrations já rodaram automaticamente. A API está em <a href="http://localhost:80" target="_blank">`http://localhost:80`</a> clique para baixar o JSON com a collection.

---

## 📚 API Endpoints

| Método   | Endpoint          | Ação             |
| -------- | ----------------- | ---------------- |
| `GET`    | `/api/tasks`      | Listar tarefas   |
| `GET`    | `/api/tasks/{id}` | Buscar tarefa    |
| `POST`   | `/api/tasks`      | Criar tarefa     |
| `PUT`    | `/api/tasks/{id}` | Atualizar tarefa |
| `DELETE` | `/api/tasks/{id}` | Deletar tarefa   |

### Exemplo: Criar Tarefa

```bash
curl -X POST <a href="http://localhost/api/tasks" target="_blank">http://localhost/api/tasks</a> \
  -H "Content-Type: application/json" \
  -d '{"title":"Minha tarefa","description":"Descrição"}'
```

---

## 🧪 Testes

```bash
# Todos os testes
docker compose exec laravel.test ./vendor/bin/phpunit --testdox

# Apenas testes unitários
docker compose exec laravel.test ./vendor/bin/phpunit tests/Unit/ --testdox

# Apenas integração
docker compose exec laravel.test ./vendor/bin/phpunit tests/Integration/ --testdox

# Apenas E2E
docker compose exec laravel.test ./vendor/bin/phpunit tests/Feature/ --testdox
```
