#!/bin/bash

# NI Drip Central - Docker Quick Start Script

echo "🚀 Starting NI Drip Central Docker Services..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker first."
    exit 1
fi

# Start Docker services
echo "📦 Starting MySQL and Mailpit containers..."
docker-compose up -d

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
sleep 10

# Check if .env exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    php artisan key:generate
fi

# Check if MySQL configuration is enabled
if grep -q "^DB_CONNECTION=sqlite" .env; then
    echo "🔄 Switching to MySQL configuration..."
    sed -i.bak 's/^DB_CONNECTION=sqlite/# DB_CONNECTION=sqlite/' .env
    sed -i.bak 's/^DB_DATABASE=.*$/# DB_DATABASE=database\/database.sqlite/' .env
    sed -i.bak 's/^# DB_CONNECTION=mysql/DB_CONNECTION=mysql/' .env
    sed -i.bak 's/^# DB_HOST=127.0.0.1/DB_HOST=127.0.0.1/' .env
    sed -i.bak 's/^# DB_PORT=3306/DB_PORT=3306/' .env
    sed -i.bak 's/^# DB_DATABASE=ni_drip_central/DB_DATABASE=ni_drip_central/' .env
    sed -i.bak 's/^# DB_USERNAME=ni_drip_user/DB_USERNAME=ni_drip_user/' .env
    sed -i.bak 's/^# DB_PASSWORD=ni_drip_password/DB_PASSWORD=ni_drip_password/' .env
    rm .env.bak
fi

# Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate --seed

echo "✅ Docker services started successfully!"
echo ""
echo "📊 Services:"
echo "   - Application: http://localhost:8000"
echo "   - Mailpit Web UI: http://localhost:8025"
echo "   - MySQL: localhost:3306"
echo ""
echo "🔐 Admin Login:"
echo "   - Email: admin@electronicsmart.com"
echo "   - Password: password123"
echo ""
echo "🚀 Start Laravel development server:"
echo "   php artisan serve"