#!/bin/bash

echo "======================================"
echo "PROBANDO API DE SIVARSOCIAL"
echo "======================================"
echo ""

# Colores para mejor visualización
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

API_URL="http://localhost:8000/api"

echo -e "${YELLOW}1. Probando endpoint de test...${NC}"
curl -s -X GET "${API_URL}/test" | jq '.'
echo ""

echo -e "${YELLOW}2. Probando registro de usuario...${NC}"
REGISTER_RESPONSE=$(curl -s -X POST "${API_URL}/auth/register" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Usuario Test API",
    "username": "apitester",
    "email": "api@test.com",
    "password": "12345678"
  }')

echo "$REGISTER_RESPONSE" | jq '.'

# Extraer token si el registro fue exitoso
TOKEN=$(echo "$REGISTER_RESPONSE" | jq -r '.data.token // empty')
echo ""

if [ ! -z "$TOKEN" ]; then
    echo -e "${GREEN}✓ Token obtenido: ${TOKEN:0:20}...${NC}"
    echo ""
    
    echo -e "${YELLOW}3. Probando endpoint de usuario autenticado...${NC}"
    curl -s -X GET "${API_URL}/auth/me" \
      -H "Authorization: Bearer $TOKEN" \
      -H "Accept: application/json" | jq '.'
    echo ""
    
    echo -e "${YELLOW}4. Probando obtener posts...${NC}"
    curl -s -X GET "${API_URL}/posts" \
      -H "Authorization: Bearer $TOKEN" \
      -H "Accept: application/json" | jq '.'
    echo ""
    
    echo -e "${YELLOW}5. Probando búsqueda de usuarios...${NC}"
    curl -s -X GET "${API_URL}/users/search?q=test" \
      -H "Authorization: Bearer $TOKEN" \
      -H "Accept: application/json" | jq '.'
    echo ""
else
    echo -e "${RED}✗ No se pudo obtener token, probando login con usuario existente...${NC}"
    echo ""
    
    echo -e "${YELLOW}3. Probando login...${NC}"
    echo "Ingresa email de un usuario existente:"
    read -p "Email: " USER_EMAIL
    echo "Ingresa la contraseña:"
    read -s -p "Password: " USER_PASSWORD
    echo ""
    
    LOGIN_RESPONSE=$(curl -s -X POST "${API_URL}/auth/login" \
      -H "Content-Type: application/json" \
      -d "{
        \"email\": \"$USER_EMAIL\",
        \"password\": \"$USER_PASSWORD\"
      }")
    
    echo "$LOGIN_RESPONSE" | jq '.'
    
    TOKEN=$(echo "$LOGIN_RESPONSE" | jq -r '.data.token // empty')
    
    if [ ! -z "$TOKEN" ]; then
        echo -e "${GREEN}✓ Login exitoso${NC}"
        echo ""
        
        echo -e "${YELLOW}4. Probando endpoint de usuario autenticado...${NC}"
        curl -s -X GET "${API_URL}/auth/me" \
          -H "Authorization: Bearer $TOKEN" \
          -H "Accept: application/json" | jq '.'
        echo ""
    else
        echo -e "${RED}✗ Login fallido${NC}"
    fi
fi

echo -e "${GREEN}======================================"
echo "PRUEBAS COMPLETADAS"
echo -e "======================================${NC}"