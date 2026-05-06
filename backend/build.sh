#!/bin/bash

# Script de build para Render
# Este script se ejecuta automáticamente durante el deploy

echo "🚀 Iniciando build del backend..."

# Instalar dependencias de Composer
echo "📦 Instalando dependencias PHP..."
composer install --no-dev --optimize-autoloader

# Ejecutar migraciones automáticamente
echo "🗄️ Ejecutando migraciones de base de datos..."
php migrations/create_tables.php

echo "✅ Build completado exitosamente!"
