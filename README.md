# FAN IT - Fullstack Developer Test

A comprehensive book management web application built with Laravel, Livewire, and PostgreSQL.

## Features

### Authentication System (20 Points)
- ✅ **Login Screen**: Secure user authentication with email and password
- ✅ **Registration Screen**: User registration with email verification
- ✅ **Forgot Password**: Password reset via email
- ✅ **Email Verification**: Account verification through email
- ✅ **Change Password**: Secure password change in profile section

### Home Page (10 Points)
- ✅ **User Dashboard**: Display user details and email verification status
- ✅ **Statistics Overview**: Show user activity and system statistics

### User Management (20 Points)
- ✅ **User List**: Display all registered users with verification status
- ✅ **Filter Users**: Filter by email verification status
- ✅ **Search Users**: Search users by name or email with real-time results

### Book Management (30 Points)
- ✅ **CRUD Operations**: Complete Create, Read, Update, Delete functionality
- ✅ **Book Properties**: Title, author, description, thumbnail, and rating (1-5)
- ✅ **File Upload**: Secure thumbnail upload with validation
- ✅ **User Ownership**: Users can only manage their own books

### Landing Page (10 Points)
- ✅ **Public Book Display**: Show all books without authentication required
- ✅ **Multiple Filters**: Filter by author, date uploaded, and rating
- ✅ **Pagination**: Efficient pagination for large datasets
- ✅ **Responsive Design**: Mobile-friendly interface

### Testing Suite (10 Points)
- ✅ **Unit Tests**: Critical backend logic testing
- ✅ **Integration Tests**: End-to-end functionality testing
- ✅ **API Tests**: RESTful API endpoint testing

## Technology Stack

- **Backend**: Laravel 11.x with Livewire 3.x
- **Database**: PostgreSQL
- **Authentication**: Laravel Sanctum for API, built-in auth for web
- **Frontend**: Blade templates with Tailwind CSS
- **File Storage**: Local storage with public disk
- **Testing**: PHPUnit with Laravel testing utilities

## System Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18.x
- PostgreSQL >= 13.x
- Git

## Installation Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/yourname_fdtest.git
cd yourname_fdtest
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

Create a PostgreSQL database and update your `.env` file:

```env
# Database Configuration
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=yourname_fdtest
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

# Application Configuration
APP_NAME="Book Management System"
APP_ENV=local
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost:8000

# Mail Configuration (for email verification)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Session Configuration
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache Configuration
CACHE_STORE=database

# Queue Configuration (optional)
QUEUE_CONNECTION=database
```

### 5. Database Migration and Seeding

```bash
# Run database migrations
php artisan migrate

# Seed the database with sample data (optional)
php artisan db:seed

# Create storage link for file uploads
php artisan storage:link
```

### 6. Build Frontend Assets

```bash
# Development build
npm run dev

# Or for production
npm run build
```

### 7. Start the Development Server

```bash
# Start Laravel development server
php artisan serve

# In another terminal, start Vite dev server (if using npm run dev)
npm run dev
```

Your application will be available at `http://localhost:8000`

## Default User Account

After running the seeders, you can log in with:
- **Email**: test@example.com
- **Password**: password

## Testing

### Run All Tests

```bash
# Run the complete test suite
php artisan test

# Run with coverage (requires Xdebug)
php artisan test --coverage

# Run specific test categories
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

### Run Specific Test Files

```bash
# Authentication tests
php artisan test tests/Feature/Auth/UserAuthenticationTest.php

# Book management tests
php artisan test tests/Feature/BookManagementTest.php

# API integration tests
php artisan test tests/Feature/Api/ApiIntegrationTest.php
```

## API Documentation

### Authentication Endpoints

```bash
# Register new user
POST /api/v1/register
Content-Type: application/json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}

# Login user
POST /api/v1/login
Content-Type: application/json
{
    "email": "john@example.com",
    "password": "password123"
}

# Logout user
POST /api/v1/logout
Authorization: Bearer {token}
```

### Book Management Endpoints

```bash
# Get all books (public)
GET /api/v1/books?author=John&rating=5&per_page=12

# Get specific book
GET /api/v1/books/{id}

# Create new book (authenticated)
POST /api/v1/books
Authorization: Bearer {token}
Content-Type: multipart/form-data
{
    "title": "Book Title",
    "author": "Author Name",
    "description": "Book description",
    "rating": 4,
    "thumbnail": [file]
}

# Update book (authenticated, owner only)
PUT /api/v1/books/{id}
Authorization: Bearer {token}

# Delete book (authenticated, owner only)
DELETE /api/v1/books/{id}
Authorization: Bearer {token}

# Get user's books
GET /api/v1/my-books
Authorization: Bearer {token}
```

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/              # API Controllers
│   └── Middleware/           # Custom Middleware
├── Livewire/                 # Livewire Components
│   ├── Auth/                # Authentication components
│   └── Profile/             # Profile management
├── Models/                  # Eloquent Models
└── Mail/                    # Mail classes

database/
├── factories/               # Model Factories
├── migrations/              # Database Migrations
└── seeders/                # Database Seeders

resources/
├── views/
│   ├── livewire/           # Livewire Blade templates
│   ├── layouts/            # Layout templates
│   └── emails/             # Email templates

tests/
├── Feature/                # Integration tests
└── Unit/                   # Unit tests
```

## Security Features

- ✅ **Password Hashing**: Bcrypt encryption for all passwords
- ✅ **CSRF Protection**: Built-in Laravel CSRF protection
- ✅ **SQL Injection Prevention**: Eloquent ORM with parameter binding
- ✅ **File Upload Security**: Validated file types and sizes
- ✅ **Rate Limiting**: API and authentication rate limiting
- ✅ **Input Validation**: Comprehensive server-side validation
- ✅ **Authentication Middleware**: Route protection
- ✅ **Email Verification**: Secure account verification

## Performance Optimizations

- ✅ **Database Indexing**: Optimized queries with proper indexes
- ✅ **Eager Loading**: Efficient relationship loading
- ✅ **Pagination**: Memory-efficient data display
- ✅ **Caching**: Database query caching
- ✅ **File Storage**: Optimized file handling

## Third-Party Libraries Used

### Backend Dependencies
- **Laravel Sanctum**: API authentication
- **Livewire**: Full-stack framework for Laravel
- **Intervention Image**: Image processing (if needed)

### Frontend Dependencies
- **Tailwind CSS**: Utility-first CSS framework
- **Alpine.js**: Lightweight JavaScript framework
- **Heroicons**: Beautiful hand-crafted SVG icons

### Development Dependencies
- **Laravel Pint**: Code style fixer
- **PHPUnit**: Testing framework
- **Mockery**: Mocking framework for tests

## Troubleshooting

### Common Issues

**1. Database Connection Error**
```bash
# Check PostgreSQL service
sudo systemctl status postgresql

# Verify database exists
psql -U postgres -l
```

**2. Storage Permission Issues**
```bash
# Fix storage permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

**3. Missing Application Key**
```bash
php artisan key:generate
```

**4. Node.js Build Errors**
```bash
# Clear npm cache and reinstall
rm -rf node_modules package-lock.json
npm install
```

### Environment-Specific Notes

**Development Environment:**
- Debug mode enabled
- Detailed error messages
- Hot module replacement with Vite

**Production Environment:**
- Set `APP_DEBUG=false`
- Use `npm run build` for optimized assets
- Configure proper mail server
- Set up SSL certificates
- Use Redis for caching and sessions

## Deployment

### Production Deployment Steps

```bash
# 1. Clone repository
git clone https://github.com/yourusername/yourname_fdtest.git

# 2. Install dependencies (production only)
composer install --no-dev --optimize-autoloader

# 3. Set environment
cp .env.example .env
# Configure production settings

# 4. Generate key and migrate
php artisan key:generate
php artisan migrate --force

# 5. Build assets
npm ci --only=production
npm run build

# 6. Configure web server (Apache/Nginx)
# 7. Set proper permissions
# 8. Configure SSL
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Write tests for new functionality
4. Ensure all tests pass
5. Submit a pull request

## License

This project is created for FAN IT technical assessment.

## Support

For technical issues or questions about this implementation, please contact:
- **Email**: your.email@example.com
- **GitHub**: https://github.com/yourusername/yourname_fdtest

---

**Happy Coding! 🚀**