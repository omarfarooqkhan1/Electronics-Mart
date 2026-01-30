# NI Drip Central - E-commerce Platform

A modern, full-featured e-commerce platform built with Laravel 11, featuring a comprehensive admin panel and RESTful API for managing products, orders, and customers.

## 🚀 Features

### Admin Panel
- **Modern Dashboard** with real-time statistics and analytics
- **Product Management** with image uploads, specifications, and inventory tracking
- **Category Management** with hierarchical organization
- **Order Management** with 5-state workflow (processing → confirmed → shipped → delivered → cancelled)
- **Customer Management** with detailed profiles and order history
- **Settings Panel** for system configuration
- **Authentication** with secure login and password reset

### API Features
- **RESTful API** with comprehensive endpoints
- **JWT Authentication** using Laravel Sanctum
- **Product Catalog** with search, filtering, and pagination
- **Shopping Cart** with persistent storage
- **Order Processing** with email notifications
- **User Management** with email verification

### Technical Features
- **Database Flexibility** - SQLite (development) and MySQL (production) support
- **Email Testing** with Mailpit integration
- **Docker Support** for easy development setup
- **Modern UI** with Tailwind CSS and responsive design
- **File Storage** with Laravel's storage system
- **Comprehensive Validation** and error handling

## 🛠 Technology Stack

- **Backend**: Laravel 11 (PHP 8.3+)
- **Database**: SQLite (dev) / MySQL 8.0 (prod)
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Icons**: Lucide Icons
- **Email**: Mailpit (development)
- **Authentication**: Laravel Sanctum
- **File Storage**: Laravel Storage
- **Development**: Docker Compose

## 📋 Requirements

- PHP 8.3 or higher
- Composer
- Node.js & NPM
- SQLite (default) or MySQL
- Docker & Docker Compose (optional)

## 🚀 Quick Start

### Option 1: Standard Setup (SQLite)

1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd ni-drip-central
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**:
   ```bash
   touch database/database.sqlite
   php artisan migrate --seed
   ```

5. **Storage setup**:
   ```bash
   php artisan storage:link
   ```

6. **Start development server**:
   ```bash
   php artisan serve
   npm run dev
   ```

### Option 2: Docker Setup (MySQL + Mailpit)

1. **Start Docker services**:
   ```bash
   docker-compose up -d
   ```

2. **Update environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   
   Then uncomment MySQL configuration in `.env` and comment out SQLite.

3. **Run migrations**:
   ```bash
   php artisan migrate --seed
   ```

4. **Start Laravel**:
   ```bash
   php artisan serve
   ```

## 🔧 Configuration

### Database Configuration

**SQLite (Default)**:
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

**MySQL (Docker)**:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ni_drip_central
DB_USERNAME=ni_drip_user
DB_PASSWORD=ni_drip_password
```

### Email Configuration

**Development (Mailpit)**:
```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_FROM_ADDRESS="noreply@nidripcentral.com"
MAIL_FROM_NAME="NI Drip Central"
```

## 🏗 Architecture

### Database Schema

**Core Entities**:
- `users` - Customer and admin accounts
- `categories` - Product categories with hierarchical support
- `products` - Product catalog with specifications and pricing
- `images` - Polymorphic image storage
- `orders` - Order management with 5-state workflow
- `order_items` - Order line items with pricing snapshots
- `carts` - Shopping cart persistence
- `cart_items` - Cart line items
- `addresses` - Customer shipping addresses

### API Endpoints

**Authentication**:
- `POST /api/register` - User registration
- `POST /api/login` - User login
- `POST /api/logout` - User logout
- `POST /api/verify-email` - Email verification

**Products**:
- `GET /api/products` - List products with filtering
- `GET /api/products/{id}` - Get product details
- `GET /api/categories` - List categories

**Cart & Orders**:
- `GET /api/cart` - Get user's cart
- `POST /api/cart/add` - Add item to cart
- `PUT /api/cart/update/{id}` - Update cart item
- `DELETE /api/cart/remove/{id}` - Remove cart item
- `POST /api/orders` - Create order
- `GET /api/orders` - List user orders
- `GET /api/orders/{id}` - Get order details

### Admin Panel Structure

**Dashboard** (`/admin`):
- Real-time statistics
- Recent orders and activities
- Quick actions

**Product Management** (`/admin/products`):
- Product listing with search and filters
- Create/edit products with image uploads
- Inventory management
- Brand selection with predefined options

**Order Management** (`/admin/orders`):
- Order listing with status filters
- Order details and status updates
- Customer information
- Order timeline tracking

**Category Management** (`/admin/categories`):
- Category listing and management
- Create/edit categories
- Category activation/deactivation

**Settings** (`/admin/settings`):
- Admin profile management
- System configuration
- Email settings

## 🎨 UI/UX Features

### Modern Design
- **Responsive Layout** - Mobile-first design
- **Dark/Light Theme** - Consistent color scheme
- **Smooth Animations** - CSS transitions and transforms
- **Interactive Elements** - Hover effects and loading states

### User Experience
- **Auto-filtering** - Real-time search and filter application
- **Password Toggle** - Show/hide password functionality
- **Direct Actions** - Quick logout and action buttons
- **Form Validation** - Real-time validation with error messages

### Brand Identity
- **NI Drip Central Branding** - Consistent logo and colors
- **Gradient Accents** - Modern gradient color scheme
- **Professional Typography** - Inter and Playfair Display fonts

## 🔐 Security Features

- **CSRF Protection** - All forms protected
- **SQL Injection Prevention** - Eloquent ORM usage
- **XSS Protection** - Input sanitization
- **Authentication** - Secure login with rate limiting
- **Authorization** - Role-based access control
- **Password Hashing** - Bcrypt encryption

## 📧 Email System

### Development
- **Mailpit Integration** - Local email testing
- **Web Interface** - http://localhost:8025
- **SMTP Server** - Port 1025

### Email Templates
- Order confirmation emails
- Order status updates
- Email verification
- Password reset notifications

## 🐳 Docker Services

### MySQL Database
- **Container**: ni_drip_central_mysql
- **Port**: 3306
- **Persistent Storage**: Docker volume

### Mailpit
- **Container**: ni_drip_central_mailpit
- **SMTP Port**: 1025
- **Web UI**: http://localhost:8025

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

## 📝 API Documentation

The API follows RESTful conventions with JSON responses. All authenticated endpoints require a Bearer token obtained from the login endpoint.

### Authentication Flow
1. Register or login to get access token
2. Include token in Authorization header: `Bearer {token}`
3. Access protected endpoints

### Response Format
```json
{
    "success": true,
    "data": {},
    "message": "Success message"
}
```

## 🚀 Deployment

### Production Setup
1. Set `APP_ENV=production` in `.env`
2. Configure production database
3. Set up proper mail configuration
4. Run `php artisan config:cache`
5. Run `php artisan route:cache`
6. Run `php artisan view:cache`

### Environment Variables
- Set strong `APP_KEY`
- Configure production database
- Set up email service (SMTP/SES)
- Configure file storage (S3/local)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests
5. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 🆘 Support

For support and questions:
- Create an issue on GitHub
- Check the documentation
- Review the API endpoints

---

**NI Drip Central** - Modern E-commerce Platform © 2024