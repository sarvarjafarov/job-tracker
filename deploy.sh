#!/bin/bash

# Job Tracker Deployment Script
echo "🚀 Starting Job Tracker Deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    print_error "Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    print_error "Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

print_status "Docker and Docker Compose are installed"

# Create necessary directories
mkdir -p nginx/ssl
mkdir -p backend/storage/logs
mkdir -p backend/storage/framework/cache
mkdir -p backend/storage/framework/sessions
mkdir -p backend/storage/framework/views

print_status "Created necessary directories"

# Set permissions for Laravel storage
chmod -R 775 backend/storage
chmod -R 775 backend/bootstrap/cache

print_status "Set Laravel storage permissions"

# Copy environment files if they don't exist
if [ ! -f backend/.env ]; then
    cp backend/env.example backend/.env
    print_status "Created backend .env file"
fi

if [ ! -f frontend/.env ]; then
    cp frontend/env.example frontend/.env
    print_status "Created frontend .env file"
fi

# Build and start services
print_status "Building Docker containers..."
docker-compose build

print_status "Starting services..."
docker-compose up -d

# Wait for MySQL to be ready
print_status "Waiting for MySQL to be ready..."
sleep 30

# Run Laravel migrations and seeders
print_status "Running Laravel migrations..."
docker-compose exec backend php artisan migrate --force

print_status "Running Laravel seeders..."
docker-compose exec backend php artisan db:seed --force

print_status "Clearing Laravel caches..."
docker-compose exec backend php artisan config:cache
docker-compose exec backend php artisan route:cache
docker-compose exec backend php artisan view:cache

print_status "🎉 Deployment completed successfully!"

echo ""
echo "📋 Access Information:"
echo "🌐 Frontend: http://localhost:3000"
echo "🔧 Backend API: http://localhost:8000"
echo "👑 Laravel Nova Admin: http://localhost:8000/nova"
echo ""
echo "🔐 Super Admin Credentials:"
echo "Username: sarvar"
echo "Password: Nsusife123@"
echo ""
echo "📊 To view logs: docker-compose logs -f"
echo "🛑 To stop services: docker-compose down"
echo "🔄 To restart services: docker-compose restart"
