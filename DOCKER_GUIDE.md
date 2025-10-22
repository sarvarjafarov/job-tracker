# 🐳 Docker Deployment Guide

## 🚀 **Quick Start (Recommended)**

### **Option 1: Automated Deployment**
```bash
# Clone the repository
git clone https://github.com/YOUR_USERNAME/job-tracker.git
cd job-tracker

# Run the automated deployment script
./deploy.sh
```

### **Option 2: Manual Docker Setup**

1. **Clone and setup:**
   ```bash
   git clone https://github.com/YOUR_USERNAME/job-tracker.git
   cd job-tracker
   ```

2. **Create environment files:**
   ```bash
   cp backend/env.example backend/.env
   cp frontend/env.example frontend/.env
   ```

3. **Build and start:**
   ```bash
   docker-compose build
   docker-compose up -d
   ```

4. **Setup Laravel:**
   ```bash
   # Wait for MySQL to be ready (30 seconds)
   sleep 30
   
   # Generate app key
   docker-compose exec backend php artisan key:generate
   
   # Run migrations
   docker-compose exec backend php artisan migrate --force
   
   # Seed database
   docker-compose exec backend php artisan db:seed --force
   ```

## 🌐 **Access the Application**

- **Frontend:** http://localhost:3000
- **Backend API:** http://localhost:8000
- **Filament Admin Panel:** http://localhost:8000/admin

### **Login Credentials:**
- **Username:** `sarvar`
- **Password:** `Nsusife123@`

## 🔧 **Docker Commands**

### **Basic Operations**
```bash
# Start all services
docker-compose up -d

# Stop all services
docker-compose down

# View logs
docker-compose logs -f

# Restart services
docker-compose restart

# Rebuild containers
docker-compose build --no-cache
```

### **Individual Services**
```bash
# Start only database
docker-compose up -d mysql

# Start backend only
docker-compose up -d backend

# Start frontend only
docker-compose up -d frontend
```

### **Backend Commands**
```bash
# Access backend container
docker-compose exec backend bash

# Run Laravel commands
docker-compose exec backend php artisan migrate
docker-compose exec backend php artisan db:seed
docker-compose exec backend php artisan tinker
docker-compose exec backend php artisan cache:clear

# Install Composer packages
docker-compose exec backend composer install
```

### **Frontend Commands**
```bash
# Access frontend container
docker-compose exec frontend sh

# Install npm packages
docker-compose exec frontend npm install

# Build for production
docker-compose exec frontend npm run build
```

### **Database Commands**
```bash
# Access MySQL
docker-compose exec mysql mysql -u jobtracker -p job_tracker

# Backup database
docker-compose exec mysql mysqldump -u jobtracker -p job_tracker > backup.sql

# Restore database
docker-compose exec -T mysql mysql -u jobtracker -p job_tracker < backup.sql
```

## 📊 **Monitoring & Debugging**

### **View Logs**
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f backend
docker-compose logs -f frontend
docker-compose logs -f mysql
docker-compose logs -f nginx
```

### **Container Status**
```bash
# Check running containers
docker-compose ps

# Check resource usage
docker stats

# Check container details
docker-compose exec backend php artisan --version
```

### **Debug Issues**
```bash
# Check if containers are running
docker-compose ps

# Check container logs
docker-compose logs backend
docker-compose logs mysql

# Access container shell
docker-compose exec backend bash
docker-compose exec mysql bash
```

## 🛠️ **Development Mode**

For development with hot reloading:

1. **Start only database:**
   ```bash
   docker-compose up -d mysql
   ```

2. **Run backend locally:**
   ```bash
   cd backend
   composer install
   cp env.example .env
   php artisan key:generate
   php artisan migrate
   php artisan serve
   ```

3. **Run frontend locally:**
   ```bash
   cd frontend
   npm install
   npm start
   ```

## 🔒 **Production Deployment**

### **Environment Configuration**
Update these files for production:

**backend/.env:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
DB_PASSWORD=your_secure_password
```

**frontend/.env:**
```env
REACT_APP_API_URL=https://your-domain.com/api
```

### **SSL Setup**
1. Add SSL certificates to `nginx/ssl/`
2. Update `nginx/nginx.conf` for HTTPS
3. Update environment variables for HTTPS URLs

### **Database Backup**
```bash
# Create backup
docker-compose exec mysql mysqldump -u jobtracker -p job_tracker > backup_$(date +%Y%m%d_%H%M%S).sql

# Restore backup
docker-compose exec -T mysql mysql -u jobtracker -p job_tracker < backup_file.sql
```

## 🚨 **Troubleshooting**

### **Common Issues**

1. **Port conflicts:**
   ```bash
   # Check if ports are in use
   lsof -i :3000
   lsof -i :8000
   lsof -i :3306
   
   # Kill processes using ports
   sudo kill -9 $(lsof -t -i:3000)
   sudo kill -9 $(lsof -t -i:8000)
   ```

2. **Permission issues:**
   ```bash
   # Fix Laravel storage permissions
   docker-compose exec backend chmod -R 775 storage bootstrap/cache
   docker-compose exec backend chown -R www-data:www-data storage bootstrap/cache
   ```

3. **Database connection issues:**
   ```bash
   # Check MySQL logs
   docker-compose logs mysql
   
   # Restart MySQL
   docker-compose restart mysql
   
   # Check database connection
   docker-compose exec backend php artisan tinker
   ```

4. **Container won't start:**
   ```bash
   # Check container logs
   docker-compose logs container_name
   
   # Rebuild container
   docker-compose build --no-cache container_name
   
   # Remove and recreate
   docker-compose down
   docker-compose up -d
   ```

5. **Memory issues:**
   ```bash
   # Check Docker memory usage
   docker system df
   
   # Clean up unused resources
   docker system prune -a
   ```

### **Reset Everything**
```bash
# Stop and remove all containers
docker-compose down -v

# Remove all volumes
docker volume prune

# Remove all images
docker image prune -a

# Rebuild and start
docker-compose build --no-cache
docker-compose up -d
```

## 📈 **Scaling**

### **Horizontal Scaling**
```yaml
# In docker-compose.yml
services:
  backend:
    deploy:
      replicas: 3
  frontend:
    deploy:
      replicas: 2
```

### **Load Balancing**
The nginx configuration already includes load balancing for multiple backend instances.

## 🔧 **Customization**

### **Change Ports**
Update `docker-compose.yml`:
```yaml
services:
  backend:
    ports:
      - "8080:8000"  # Change 8000 to 8080
  frontend:
    ports:
      - "3001:3000"  # Change 3000 to 3001
```

### **Add Environment Variables**
```yaml
services:
  backend:
    environment:
      - CUSTOM_VAR=value
```

### **Mount Volumes**
```yaml
services:
  backend:
    volumes:
      - ./custom:/var/www/html/custom
```

## 📋 **Health Checks**

### **Check Application Health**
```bash
# Check if services are responding
curl http://localhost:3000
curl http://localhost:8000/api

# Check database connection
docker-compose exec backend php artisan tinker
```

### **Monitor Resources**
```bash
# Check container resource usage
docker stats

# Check disk usage
docker system df
```

## 🎯 **Next Steps**

1. **Customize the application** for your needs
2. **Set up monitoring** with tools like Prometheus
3. **Configure CI/CD** with GitHub Actions
4. **Add SSL certificates** for production
5. **Set up backups** for database and files
6. **Configure logging** for production monitoring

Your Job Tracker application is now ready for production deployment! 🚀
