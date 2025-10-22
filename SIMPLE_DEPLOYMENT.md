# 🚀 Simple Deployment Options

## 🎯 **Quick Start (No Admin Panel)**

If you want to get started quickly without dealing with PHP extensions, here are your options:

### **Option 1: Docker Deployment (Recommended)**

```bash
# 1. Clone the repository
git clone https://github.com/YOUR_USERNAME/job-tracker.git
cd job-tracker

# 2. Run with Docker (includes intl extension fix)
docker-compose build
docker-compose up -d

# 3. Wait for MySQL to be ready
sleep 30

# 4. Setup Laravel
docker-compose exec backend php artisan key:generate
docker-compose exec backend php artisan migrate --force
docker-compose exec backend php artisan db:seed --force
```

### **Option 2: Local Development (Without Admin Panel)**

```bash
# 1. Install dependencies without Filament
cd backend
composer install --ignore-platform-req=ext-intl

# 2. Setup environment
cp env.example .env
php artisan key:generate

# 3. Setup database
php artisan migrate
php artisan db:seed

# 4. Start Laravel server
php artisan serve

# 5. In another terminal, start React
cd frontend
npm install
npm start
```

### **Option 3: Use Alternative Admin Panel**

Instead of Filament, you can use a simple admin interface or API-only approach.

## 🌐 **Access Points**

After deployment:

- **Frontend:** http://localhost:3000
- **Backend API:** http://localhost:8000
- **API Documentation:** http://localhost:8000/api

## 🔐 **Login Credentials**

- **Username:** `sarvar`
- **Password:** `Nsusife123@`

## 📋 **API Endpoints Available**

Even without the admin panel, you have full API access:

```bash
# Test API endpoints
curl http://localhost:8000/api/applications
curl http://localhost:8000/api/companies
curl http://localhost:8000/api/dashboard/stats
```

## 🛠️ **Troubleshooting**

### **If Docker doesn't work:**
```bash
# Check Docker is running
docker --version
docker-compose --version

# Check containers
docker-compose ps
```

### **If local setup fails:**
```bash
# Check PHP version
php --version

# Install required extensions
brew install php@8.1  # macOS
sudo apt install php8.1-intl  # Ubuntu
```

## 🎯 **Next Steps**

1. **Choose your deployment method** above
2. **Test the application** with the provided credentials
3. **Add admin panel later** when you have time to fix PHP extensions
4. **Customize** the application for your needs

The core functionality works perfectly without the admin panel! 🚀
