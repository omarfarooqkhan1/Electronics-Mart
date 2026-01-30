#!/bin/bash

# Stop and remove existing container if it exists
docker stop electronics-mart-mysql 2>/dev/null || true
docker rm electronics-mart-mysql 2>/dev/null || true

# Run MySQL container for development
docker run -d \
  --name electronics-mart-mysql \
  -e MYSQL_ROOT_PASSWORD=root_password \
  -e MYSQL_DATABASE=electronics_mart_dev \
  -e MYSQL_USER=electronics_mart_user \
  -e MYSQL_PASSWORD=electronics_mart_password \
  -p 3306:3306 \
  -v electronics_mart_mysql_data:/var/lib/mysql \
  mysql:8.0

echo "MySQL container started!"
echo "Connection details:"
echo "Host: 127.0.0.1"
echo "Port: 3306"
echo "Database: electronics_mart_dev"
echo "Username: electronics_mart_user"
echo "Password: electronics_mart_password"
echo "Root password: root_password"