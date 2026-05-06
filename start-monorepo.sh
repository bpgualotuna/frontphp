#!/bin/bash

echo "🚀 Iniciando servidor PHP..."

# Iniciar servidor PHP desde backend/public
cd backend/public
php -S 0.0.0.0:${PORT:-8080}
