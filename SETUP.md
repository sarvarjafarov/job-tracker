# Job Tracker - Complete Setup Guide

## 🚀 Quick Start

This guide will help you set up the complete Job Tracker application with Laravel backend and React frontend.

## 📋 Prerequisites

Before starting, ensure you have the following installed:

### Required Software
- **PHP 8.1+** with extensions: BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML
- **Composer** (PHP dependency manager)
- **Node.js 16+** and **NPM**
- **MySQL 8.0+** or **PostgreSQL 13+**
- **Git**

### Installation Commands

#### macOS (using Homebrew)
```bash
# Install Homebrew (if not already installed)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Install PHP and Composer
brew install php composer

# Install Node.js
brew install node

# Install MySQL
brew install mysql
brew services start mysql
```

#### Ubuntu/Debian
```bash
# Update package list
sudo apt update

# Install PHP and extensions
sudo apt install php8.1 php8.1-cli php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Install MySQL
sudo apt install mysql-server
```

## 🗄️ Database Setup

1. **Create Database**
   ```sql
   CREATE DATABASE job_tracker;
   ```

2. **Create User (Optional)**
   ```sql
   CREATE USER 'jobtracker'@'localhost' IDENTIFIED BY 'your_password';
   GRANT ALL PRIVILEGES ON job_tracker.* TO 'jobtracker'@'localhost';
   FLUSH PRIVILEGES;
   ```

## 🔧 Backend Setup (Laravel)

1. **Navigate to backend directory**
   ```bash
   cd backend
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Environment Configuration**
   ```bash
   cp env.example .env
   ```

4. **Update .env file with your database credentials**
   ```env
   APP_NAME="Job Tracker"
   APP_ENV=local
   APP_KEY=
   APP_DEBUG=true
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=job_tracker
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Run Database Migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed Database (Creates super admin account)**
   ```bash
   php artisan db:seed
   ```

8. **Start Laravel Server**
   ```bash
   php artisan serve
   ```

   The API will be available at: `http://localhost:8000`

## ⚛️ Frontend Setup (React)

1. **Navigate to frontend directory**
   ```bash
   cd frontend
   ```

2. **Install Node.js dependencies**
   ```bash
   npm install
   ```

3. **Environment Configuration**
   ```bash
   cp env.example .env
   ```

4. **Update .env file**
   ```env
   REACT_APP_API_URL=http://localhost:8000/api
   ```

5. **Start React Development Server**
   ```bash
   npm start
   ```

   The frontend will be available at: `http://localhost:3000`

## 🔐 Super Admin Account

The database seeder creates a super admin account with these credentials:

- **Username:** `sarvar`
- **Password:** `Nsusife123@`
- **Role:** `super_admin`

## 🧪 Testing the Application

### 1. Backend API Testing

Test the API endpoints using curl or Postman:

```bash
# Test API health
curl http://localhost:8000

# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"username": "sarvar", "password": "Nsusife123@"}'

# Get applications (requires token)
curl -X GET http://localhost:8000/api/applications \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 2. Frontend Testing

1. Open `http://localhost:3000`
2. Click "Sign in to your account"
3. Use the super admin credentials:
   - Username: `sarvar`
   - Password: `Nsusife123@`
4. Explore the dashboard and features

## 📁 Project Structure

```
job-tracker/
├── backend/                 # Laravel API
│   ├── app/
│   │   ├── Http/Controllers/
│   │   ├── Models/
│   │   └── Providers/
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── routes/
│   └── config/
├── frontend/               # React Application
│   ├── src/
│   │   ├── components/
│   │   ├── pages/
│   │   ├── services/
│   │   ├── contexts/
│   │   └── types/
│   └── public/
└── README.md
```

## 🚀 Production Deployment

### Backend (Laravel)

1. **Set up production environment**
   ```bash
   # Update .env for production
   APP_ENV=production
   APP_DEBUG=false
   DB_HOST=your_production_db_host
   DB_PASSWORD=your_production_password
   ```

2. **Optimize for production**
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

### Frontend (React)

1. **Build for production**
   ```bash
   npm run build
   ```

2. **Serve static files**
   ```bash
   # Using serve package
   npm install -g serve
   serve -s build
   ```

## 🔧 Development Tools

### Recommended VS Code Extensions
- PHP Intelephense
- Laravel Extension Pack
- ES7+ React/Redux/React-Native snippets
- Tailwind CSS IntelliSense
- TypeScript Importer

### Useful Commands

```bash
# Laravel
php artisan migrate:fresh --seed    # Reset database with seeders
php artisan tinker                   # Interactive PHP shell
php artisan route:list              # List all routes

# React
npm run build                       # Build for production
npm test                           # Run tests
npm run eject                      # Eject from Create React App
```

## 🐛 Troubleshooting

### Common Issues

1. **Composer not found**
   ```bash
   # Install Composer globally
   curl -sS https://getcomposer.org/installer | php
   sudo mv composer.phar /usr/local/bin/composer
   ```

2. **Node.js not found**
   ```bash
   # Install Node.js via NodeSource
   curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
   sudo apt-get install -y nodejs
   ```

3. **Database connection issues**
   - Check MySQL service is running
   - Verify database credentials in .env
   - Ensure database exists

4. **CORS issues**
   - Check API URL in frontend .env
   - Verify Laravel CORS configuration

## 📞 Support

If you encounter issues:

1. Check the logs:
   - Laravel: `storage/logs/laravel.log`
   - React: Browser console

2. Verify all prerequisites are installed

3. Ensure all services are running:
   - MySQL/PostgreSQL
   - Laravel server (port 8000)
   - React dev server (port 3000)

## 🎉 Success!

Once everything is set up, you should have:

- ✅ Laravel API running on `http://localhost:8000`
- ✅ React frontend running on `http://localhost:3000`
- ✅ Database with super admin account
- ✅ Full authentication system
- ✅ Job application tracking
- ✅ Company management
- ✅ User profile management

Happy coding! 🚀
