#!/bin/sh
# Script install untuk Vercel — JANGAN panggil composer di sini.
# Composer dijalankan otomatis oleh runtime vercel-php saat build PHP.
set -e
echo "Installing Node.js dependencies..."
npm install
