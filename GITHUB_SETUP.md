# 🚀 GitHub Setup & Docker Deployment Guide

## 📤 **Step 1: Push to GitHub**

### **Option A: Using GitHub CLI (Recommended)**

1. **Install GitHub CLI:**
   ```bash
   # macOS
   brew install gh
   
   # Ubuntu/Debian
   curl -fsSL https://cli.github.com/packages/githubcli-archive-keyring.gpg | sudo dd of=/usr/share/keyrings/githubcli-archive-keyring.gpg
   echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/githubcli-archive-keyring.gpg] https://cli.github.com/packages stable main" | sudo tee /etc/apt/sources.list.d/github-cli.list > /dev/null
   sudo apt update
   sudo apt install gh
   ```

2. **Login to GitHub:**
   ```bash
   gh auth login
   ```

3. **Create and push repository:**
   ```bash
   gh repo create job-tracker --public --source=. --remote=origin --push
   ```

### **Option B: Manual GitHub Setup**

1. **Go to GitHub.com** and create a new repository named `job-tracker`

2. **Add remote and push:**
   ```bash
   git remote add origin https://github.com/YOUR_USERNAME/job-tracker.git
   git branch -M main
   git push -u origin main
   ```

## 🐳 **Step 2: Docker Deployment**

### **Prerequisites**
- Docker installed
- Docker Compose installed

### **Quick Start with Docker**

1. **Clone the repository:**
   ```bash
   git clone https://github.com/YOUR_USERNAME/job-tracker.git
   cd job-tracker
   ```

2. **Run the automated deployment:**
   ```bash
   ./deploy.sh
   ```

### **Manual Docker Setup**

1. **Create environment files:**
   ```bash
   # Backend environment
   cp backend/env.example backend/.env
   
   # Frontend environment
   cp frontend/env.example frontend/.env
   ```

2. **Update backend .env file:**
   ```env
   APP_NAME="Job Tracker"
   APP_ENV=production
   APP_KEY=
   APP_DEBUG=false
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=job_tracker
   DB_USERNAME=jobtracker
   DB_PASSWORD=jobtracker123
   ```

3. **Update frontend .env file:**
   ```env
   REACT_APP_API_URL=http://localhost:8000/api
   ```

4. **Build and start services:**
   ```bash
   docker-compose build
   docker-compose up -d
   ```

5. **Run Laravel setup:**
   ```bash
   # Generate application key
   docker-compose exec backend php artisan key:generate
   
   # Run migrations
   docker-compose exec backend php artisan migrate --force
   
   # Seed database
   docker-compose exec backend php artisan db:seed --force
   
   # Clear caches
   docker-compose exec backend php artisan config:cache
   docker-compose exec backend php artisan route:cache
   docker-compose exec backend php artisan view:cache
   ```

## 🌐 **Access the Application**

After successful deployment, you can access:

- **Frontend:** http://localhost:3000
- **Backend API:** http://localhost:8000
- **Laravel Nova Admin:** http://localhost:8000/nova

### **Super Admin Login:**
- **Username:** `sarvar`
- **Password:** `Nsusife123@`

## 🔧 **Docker Commands**

### **Basic Commands**
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

### **Backend Commands**
```bash
# Access backend container
docker-compose exec backend bash

# Run Laravel commands
docker-compose exec backend php artisan migrate
docker-compose exec backend php artisan db:seed
docker-compose exec backend php artisan tinker

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

## 📊 **Monitoring & Logs**

### **View Logs**
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f backend
docker-compose logs -f frontend
docker-compose logs -f mysql
```

### **Container Status**
```bash
docker-compose ps
```

### **Resource Usage**
```bash
docker stats
```

## 🔒 **Production Deployment**

### **Environment Variables**
Update the following for production:

```env
# Backend .env
APP_ENV=production
APP_DEBUG=false
DB_PASSWORD=your_secure_password

# Frontend .env
REACT_APP_API_URL=https://your-domain.com/api
```

### **SSL Configuration**
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
   ```

2. **Permission issues:**
   ```bash
   # Fix Laravel storage permissions
   docker-compose exec backend chmod -R 775 storage bootstrap/cache
   ```

3. **Database connection issues:**
   ```bash
   # Check MySQL logs
   docker-compose logs mysql
   
   # Restart MySQL
   docker-compose restart mysql
   ```

4. **Container won't start:**
   ```bash
   # Check container logs
   docker-compose logs container_name
   
   # Rebuild container
   docker-compose build --no-cache container_name
   ```

### **Reset Everything**
```bash
# Stop and remove all containers
docker-compose down -v

# Remove all volumes
docker volume prune

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

## 🎯 **Next Steps**

1. **Customize the application** for your specific needs
2. **Add SSL certificates** for production
3. **Set up monitoring** with tools like Prometheus
4. **Configure CI/CD** with GitHub Actions
5. **Add more features** like email notifications, file uploads, etc.

Your Job Tracker application is now ready for production deployment! 🚀
