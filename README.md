# Portal Acadêmico | UNICENTRO MA

Sistema de gestão acadêmica e financeira para pós-graduação.

## 🚀 Como Iniciar (Deploy/Dev)

1. **Subir containers:**
   ```bash
   ./vendor/bin/sail up -d
   ```

2. **Setup inicial (primeira vez):**
   ```bash
   ./vendor/bin/sail artisan migrate --seed
   ```

3. **Criar primeiro Administrador:**
   ```bash
   ./vendor/bin/sail artisan app:create-admin
   ```

## 🔑 Configurações (.env)

Campos essenciais para o funcionamento:

- `ASAAS_API_KEY`: Chave da API do Asaas.
- `ASAAS_API_URL`: URL da API (Sandbox ou Produção).
- `ASAAS_WEBHOOK_TOKEN`: Token para validação de webhooks.
custom: `./vendor/bin/sail artisan encrypt:existing-data` -> Roda e anonimiza CPFs antigas já em nuvem!
