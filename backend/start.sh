#!/bin/bash

# Script de inicio para Render
# Este script inicia el servidor PHP

echo "🚀 Iniciando servidor PHP..."

# Iniciar servidor PHP en el puerto proporcionado por Render
cd public
php -S 0.0.0.0:${PORT:-8080}
