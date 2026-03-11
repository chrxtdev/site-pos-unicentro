#!/bin/bash

# Script de Deploy Rápido (Quick Deploy) - UNICENTROMA
# Este script automatiza o ritual de produção para garantir performance e estabilidade.

echo "🚀 Iniciando Ritual de Deploy..."

# 1. Instalar dependências se necessário
# composer install --optimize-autoloader --no-dev
# npm install && npm run build

# 2. Atualizar Banco de Dados
echo "📦 Rodando migrações..."
php artisan migrate --force

# 3. Ritual de Otimização (Caches)
echo "⚡ Otimizando sistema..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 4. Limpeza de Caches residuais
php artisan cache:clear

# 5. Garantir Permissões
echo "🔑 Ajustando permissões de storage..."
chmod -R 775 storage bootstrap/cache

echo "✅ Deploy concluído com sucesso! O sistema está pronto para produção."
