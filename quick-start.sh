#!/bin/bash

# Job Tracker - Quick Start Script
echo "🚀 Job Tracker Quick Start..."

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

echo "Choose your deployment method:"
echo "1) Docker (Recommended - handles PHP extensions automatically)"
echo "2) Local development (Requires PHP setup)"
echo "3) API only (No admin panel)"
echo ""
read -p "Enter your choice (1-3): " choice

case $choice in
    1)
        print_status "Starting Docker deployment..."
        
        # Check if Docker is installed
        if ! command -v docker &> /dev/null; then
            print_error "Docker is not installed. Please install Docker first."
            exit 1
        fi
        
        if ! command -v docker-compose &> /dev/null; then
            print_error "Docker Compose is not installed. Please install Docker Compose first."
            exit 1
        fi
        
        # Build and start services
        print_status "Building Docker containers..."
        docker-compose build
        
        print_status "Starting services..."
        docker-compose up -d
        
        # Wait for MySQL
        print_status "Waiting for MySQL to be ready..."
        sleep 30
        
        # Setup Laravel
        print_status "Setting up Laravel..."
        docker-compose exec backend php artisan key:generate
        docker-compose exec backend php artisan migrate --force
        docker-compose exec backend php artisan db:seed --force
        
        print_status "🎉 Deployment completed!"
        echo ""
        echo "📋 Access Information:"
        echo "🌐 Frontend: http://localhost:3000"
        echo "🔧 Backend API: http://localhost:8000"
        echo "👑 Admin Panel: http://localhost:8000/admin"
        echo ""
        echo "🔐 Login Credentials:"
        echo "Username: sarvar"
        echo "Password: Nsusife123@"
        ;;
        
    2)
        print_status "Setting up local development..."
        
        # Check PHP
        if ! command -v php &> /dev/null; then
            print_error "PHP is not installed. Please install PHP 8.1+ first."
            exit 1
        fi
        
        # Check Composer
        if ! command -v composer &> /dev/null; then
            print_error "Composer is not installed. Please install Composer first."
            exit 1
        fi
        
        # Backend setup
        print_status "Setting up backend..."
        cd backend
        composer install --ignore-platform-req=ext-intl
        cp env.example .env
        php artisan key:generate
        php artisan migrate
        php artisan db:seed
        
        print_status "Backend setup complete!"
        print_warning "Start backend server: php artisan serve"
        
        # Frontend setup
        print_status "Setting up frontend..."
        cd ../frontend
        
        if ! command -v npm &> /dev/null; then
            print_error "NPM is not installed. Please install Node.js and NPM first."
            exit 1
        fi
        
        npm install
        print_status "Frontend setup complete!"
        print_warning "Start frontend server: npm start"
        ;;
        
    3)
        print_status "Setting up API-only version..."
        
        cd backend
        composer install --ignore-platform-req=ext-intl
        cp env.example .env
        php artisan key:generate
        php artisan migrate
        php artisan db:seed
        
        print_status "API setup complete!"
        print_warning "Start API server: php artisan serve"
        echo ""
        echo "📋 API Endpoints:"
        echo "🔧 API Base: http://localhost:8000/api"
        echo "📊 Dashboard: http://localhost:8000/api/dashboard/stats"
        echo "👥 Applications: http://localhost:8000/api/applications"
        echo "🏢 Companies: http://localhost:8000/api/companies"
        ;;
        
    *)
        print_error "Invalid choice. Please run the script again."
        exit 1
        ;;
esac

echo ""
print_status "🎉 Job Tracker is ready!"
echo ""
echo "📚 Documentation:"
echo "- README.md - Project overview"
echo "- API.md - API documentation"
echo "- DOCKER_GUIDE.md - Docker deployment guide"
echo "- SIMPLE_DEPLOYMENT.md - Alternative deployment options"
