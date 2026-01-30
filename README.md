# Electronics Mart - Laravel Backend

Modern Laravel API backend for Electronics Mart e-commerce platform with authentication-only access.

## 🚀 Quick Start

```bash
# Install dependencies
composer install

# Configure environment
cp .env.example .env

# Generate application key
php artisan key:generate

# Create SQLite database
touch database/database.sqlite

# Run migrations and seed data
php artisan migrate --seed

# Start development server
php artisan serve
```

The API will be available at `http://localhost:8000`

## 🛠️ Tech Stack

- **Laravel 11** with PHP 8.2+
- **SQLite** database (default) / MySQL (optional)
- **Laravel Sanctum** for API authentication
- **Laravel Mail** with Mailpit for development
- **Local File Storage** for product images
- **Modern Admin Dashboard** with Tailwind CSS + DaisyUI

## 🧩 Key Features

### Core Functionality
- **Authentication-Only API** - All endpoints require user authentication
- **Product Management** - Electronics catalog with categories and brands
- **Shopping Cart** - Persistent cart with price preservation
- **Order Management** - 5-state order system (processing → confirmed → shipped → delivered → cancelled)
- **Admin Dashboard** - Modern web interface for store management

### Technical Features
- **JWT Authentication** with Laravel Sanctum
- **Price Preservation** - Cart and order prices locked at time of addition
- **Tax-Inclusive Pricing** - Simplified European-style pricing
- **Free Shipping** - No shipping calculations required
- **Email Notifications** - Order confirmations and updates
- **Modern UI** - Responsive admin dashboard with Tailwind CSS

## 🔧 Environment Configuration

### Database (SQLite - Default)
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### Database (MySQL - Alternative)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=electronics_mart
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Mail Configuration (Development)
```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_FROM_ADDRESS="noreply@electronicsmart.com"
MAIL_FROM_NAME="Electronics Mart"
```

### Application Settings
```env
APP_NAME="Electronics Mart"
APP_URL=http://localhost:8000
APP_TIMEZONE=UTC
CURRENCY=EUR
```

## 📦 Installation Guide

### 1. Clone and Install
```bash
git clone <repository-url>
cd electronics-mart-backend
composer install
```

### 2. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Database Setup (SQLite)
```bash
# Create SQLite database file
touch database/database.sqlite

# Run migrations and seed sample data
php artisan migrate --seed
```

### 4. Alternative Database Setup (MySQL)
If you prefer MySQL, update your `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=electronics_mart
DB_USERNAME=root
DB_PASSWORD=your_password
```

Then run:
```bash
php artisan migrate --seed
```

### 5. Start Development Server
```bash
php artisan serve
```

## 🏗️ Development Commands

```bash
# Database operations
php artisan migrate              # Run migrations
php artisan migrate:fresh --seed # Fresh database with sample data
php artisan db:seed             # Seed sample data only

# Cache management
php artisan cache:clear         # Clear application cache
php artisan config:clear        # Clear configuration cache
php artisan view:clear          # Clear compiled views

# Development tools
php artisan route:list          # List all routes
php artisan tinker             # Interactive shell
```

## 🗂️ Project Structure

```
app/
├── Http/Controllers/
│   ├── Api/                   # API controllers
│   └── Admin/                 # Admin dashboard controllers
├── Models/                    # Eloquent models
├── Mail/                      # Email templates
└── Http/Resources/            # API resources

database/
├── migrations/                # Database schema
├── seeders/                   # Sample data
└── database.sqlite           # SQLite database file

resources/views/admin/         # Admin dashboard views
routes/
├── api.php                   # API routes
└── web.php                   # Web routes (admin)
```

## 🔌 API Endpoints

### Authentication
- `POST /api/register` - User registration
- `POST /api/login` - User login
- `GET /api/user` - Get authenticated user
- `POST /api/logout` - User logout

### Products (Authenticated)
- `GET /api/products` - List products with filters
- `GET /api/products/{id}` - Get product details
- `GET /api/products/brands` - Get available brands
- `GET /api/categories` - List categories

### Shopping Cart (Authenticated)
- `GET /api/cart` - Get cart contents
- `POST /api/cart` - Add item to cart
- `PUT /api/cart/{id}` - Update cart item
- `DELETE /api/cart/{id}` - Remove cart item
- `DELETE /api/cart` - Clear cart

### Orders (Authenticated)
- `GET /api/orders` - List user orders
- `POST /api/orders` - Create order (checkout)
- `GET /api/orders/{id}` - Get order details
- `POST /api/orders/validate-checkout` - Validate cart before checkout

### Admin Dashboard (Web)
- `/admin/login` - Admin login
- `/admin/dashboard` - Dashboard overview
- `/admin/products` - Product management
- `/admin/categories` - Category management
- `/admin/orders` - Order management
- `/admin/settings` - System settings

## 🔒 Authentication

All API endpoints (except registration and login) require authentication using Laravel Sanctum tokens:

```bash
# Login to get token
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"customer@example.com","password":"password123"}'

# Use token in subsequent requests
curl -X GET http://localhost:8000/api/products \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## 🛒 Sample Data

The application includes comprehensive sample data:

### Categories
- Refrigerators, Air Conditioners, Washing Machines
- Televisions, Microwaves, Dishwashers
- Water Heaters, Small Appliances

### Products
- 12+ sample electronics products with realistic pricing in EUR
- Product images, specifications, and brand information
- Featured products and stock quantities

### Users
- **Admin**: `admin@electronicsmart.com` / `password123`
- **Customer**: `customer@example.com` / `password123`

## 💰 Pricing System

- **Tax-Inclusive Pricing** - All prices include VAT/tax
- **Euro Currency** - Prices displayed in EUR (€)
- **Free Shipping** - No shipping costs calculated
- **Price Preservation** - Cart and order prices locked when items added

## 📧 Email System

Development email testing with Mailpit:

1. **Install Mailpit** (optional):
   ```bash
   # macOS
   brew install mailpit
   
   # Or download from https://github.com/axllent/mailpit
   ```

2. **Start Mailpit**:
   ```bash
   mailpit
   ```

3. **View emails** at `http://localhost:8025`

## 🎨 Admin Dashboard

Modern admin interface built with:
- **Tailwind CSS** - Utility-first CSS framework
- **DaisyUI** - Component library
- **Alpine.js** - Lightweight JavaScript framework
- **Lucide Icons** - Beautiful icon set

Features:
- Responsive design for all devices
- Dark/light theme support
- Real-time statistics
- Intuitive product and order management

## 🧪 Testing the API

Use the included Postman collection (`Electronics Mart API Collection.json`):

1. Import the collection into Postman
2. Set the base URL to `http://localhost:8001/api` (or your server URL)
3. Use the "Customer Login" request to authenticate
4. Test other endpoints with automatic token management

## 🚀 Production Deployment

### Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Use MySQL/PostgreSQL for production
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-secure-password

# Configure production mail service
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-smtp-user
MAIL_PASSWORD=your-smtp-password
```

### Deployment Commands
```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Set proper permissions
chmod -R 755 storage bootstrap/cache
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

**Electronics Mart** - Modern e-commerce platform built with Laravel 11