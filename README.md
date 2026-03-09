# Portal Acadêmico | UNICENTRO MA (ERP Educacional)

O **Portal Acadêmico Pós-Graduação** é um sistema completo tipo ERP desenvolvido para a captação, inscrição on-line e gestão acadêmica e financeira dos alunos da UNICENTRO MA.

Ele atende o ciclo completo:
- Captação de leads na Home e Processo de **Inscrição 100% online**.
- **Gestão Financeira Dinâmica** integrada via API (Asaas), suportando Pix, Boleto e Cartão de Crédito com pagamentos, vencimentos e cobranças ativas.
- **Painel do Aluno (Dashboard)** para impressão de comprovantes, visualização de mensalidades em atraso, acesso a Mídia/Comprovantes no cofre e Boletim de Notas Bimestral e Feed de Postagens dos professores.
- **Painel Acadêmico (Docente/Secretaria)** com controle completo via Níveis de Acesso Spatie (ACL), possibilitando gestão do Diário de Classe Eletrônico e Mural de Atividades para publicação de materiais e Links Externos.

---

## 🚀 Tecnologias e Stack Utilizada

* **Backend:** PHP 8.3 + Laravel 11.x
* **Frontend:** Blade Templates, Alpine.js, Tailwind CSS (Design Dinâmico Responsivo)
* **Banco de Dados Relacional:** PostgreSQL / MySQL 8.x
* **Gateways:** API Oficial Asaas Integrada (Post e Webhooks via ngrok em dev)
* **Infra:** Docker Server via Laravel Sail, CloudServo, Ngrok Endpoint

---

## ⚙️ Pré-requisitos
Certifique-se de que sua máquina atende aos requisitos básicos para desenvolvimento local rodando contêineres:
* [Docker Desktop](https://www.docker.com/products/docker-desktop/) rodando e habilitado
* [WSL2](https://learn.microsoft.com/en-us/windows/wsl/install) Ativo (No Windows)
* Composer local instalado (Apenas p/ setup inicial, pois usa o Laravel Sail por baixo).

---

## 🛠️ Instalação LocaL

1. Clone o repositório ou navegue até a pasta base:
   ```bash
   cd /caminho/do/seu/projeto/pos
   ```

2. Instale as dependências usando o composer via imagem temporária do docker (se nao tiver vendor):
   ```bash
   docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v $(pwd):/var/www/html \
       -w /var/www/html \
       laravelsail/php83-composer:latest \
       composer install --ignore-platform-reqs
   ```

3. Copie o arquivo de variáveis de ambiente:
   ```bash
   cp .env.example .env
   ```

4. Suba os containers com Sail:
   ```bash
   ./vendor/bin/sail up -d
   ```

5. Gere a chave da aplicação:
   ```bash
   ./vendor/bin/sail artisan key:generate
   ```

6. Instale os sub-módulos NVM/Frontend:
   ```bash
   ./vendor/bin/sail npm install
   ./vendor/bin/sail npm run build
   ```

7. Rode as Migrations principais e as tabelas obrigatórias via Seeders:
   ```bash
   ./vendor/bin/sail artisan migrate:fresh --seed
   ```

Isso criará uma estrutura zero limpando a base com os 4 papéis Spatie definidos, o 1º Admin Master principal da tabela e a estrutura financeira.

---

## 🔑 Variáveis de Ambiente Cruciais (`.env`)

Mantenha seu `.env` validado com cuidado:

```env
# URL E HTTPS (SEMPRE true EM PROD)
APP_URL=https://seusite.com
FORCE_HTTPS=true

# ASAAS API INTEGRATION
ASAAS_API_KEY="$aact_sua_chave_em_producao_do_asaas"
ASAAS_API_URL="https://sandbox.asaas.com/api/v3" # ou api.asaas.com em rod
ASAAS_WEBHOOK_TOKEN="seutokenaleatorio123"

# DATABASE SAIL PADRÃO (SE LOCAL)
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=pos_laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

---

## 🎭 Estrutura de Permissões / Perfis (ACL)

O sistema utiliza o `spatie/laravel-permission` com as seguintes roles hierarquizadas sendo injetadas globalmente pelo `AdminMiddleware` da plataforma:

| Perfil | Nível Code (Role) | Descrição e Acessos |
|---|---|---|
| **Desenvolvedor / Master** | `admin_master` | Acesso Super-Admin em "Root". Vê todas menus contábeis, as configurações técnicas da loja, gerencia acesso de toda secretaria e vê todas rotinas acadêmicas. |
| **Setor Financeiro** | `financeiro` | Idêntico ao nível superior operacional. Capaz de gerar estornos, criar novas trilhas de boletos em lote e ver Dashboards de lucro total. |
| **Secretariado Comum** | `admin_comum` | Nível seguro focado no Acadêmico da Pós. Pode manipular as descrições dos cursos, turmas e checar alunos (Ler/Gravar/Editar). Não toca no core Asaas de boletos em si. |
| **Professor(a)** | `professor` | Restrito fortemente apenas as `Disciplinas` no qual está `_id` vinculado. Tem acesso a Aba **Diário de Turma** com a Planilha Interativa de Notas (T1, T2, T3) e ao Mural de Atividades da Turma. |

## 💡 Migration Customizadas

Se realizar PULL de versões que atualizem os usuários ou o banco relacional antigo na Azure/Produção:
1. Atualize a base com seus arquivos de migração: `./vendor/bin/sail artisan migrate`. (Se as migrations de Índices de performance ou Tabela de Role forem rodadas, aparecerão como Executadas `Ran`).
2. Recriptografe alunos em base limpa, usando banco do zero com comando custom: `./vendor/bin/sail artisan encrypt:existing-data` -> Roda e anonimiza CPFs antigas já em nuvem!
