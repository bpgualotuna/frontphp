#!/bin/bash

echo "🚀 Build del proyecto completo (Frontend + Backend)"

# 1. Instalar dependencias del frontend
echo "📦 Instalando dependencias del frontend..."
npm install

# 2. Compilar el frontend
echo "🔨 Compilando frontend React..."
npm run build

# 3. Copiar el frontend compilado al directorio público del backend
echo "📁 Copiando frontend compilado a backend/public..."
rm -rf backend/public/assets
rm -f backend/public/index.html
cp -r dist/* backend/public/

# 4. Instalar dependencias del backend
echo "📦 Instalando dependencias del backend..."
cd backend
composer install --no-dev --optimize-autoloader

# 5. Ejecutar migraciones
echo "🗄️ Ejecutando migraciones..."
php migrations/create_tables.php

echo "✅ Build completado!"
