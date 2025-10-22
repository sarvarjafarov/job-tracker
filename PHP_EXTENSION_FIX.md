# 🔧 PHP Extension Fix Guide

## 🚨 **Issue: Missing PHP intl Extension**

The error you're seeing is because Filament requires the `intl` PHP extension, which is missing from your system.

## 🛠️ **Solution Options**

### **Option 1: Enable intl Extension (Recommended)**

#### **For macOS with Homebrew:**
```bash
# Check your PHP version
php --version

# Install intl extension
brew install php@8.1
brew link php@8.1

# Or if using a different PHP version
brew install php@8.2
brew link php@8.2
```

#### **For Ubuntu/Debian:**
```bash
# Install intl extension
sudo apt-get install php8.1-intl

# Or for PHP 8.2
sudo apt-get install php8.2-intl
```

#### **For CentOS/RHEL:**
```bash
# Install intl extension
sudo yum install php-intl

# Or for newer versions
sudo dnf install php-intl
```

### **Option 2: Use Docker (Easiest)**

Since you're using Docker, the easiest solution is to update the Dockerfile to include the intl extension:

```dockerfile
# In backend/Dockerfile, add this line after installing PHP extensions
RUN docker-php-ext-install intl
```

### **Option 3: Temporary Workaround**

If you want to proceed without fixing the extension immediately:

```bash
# Run composer with ignore platform requirements
composer install --ignore-platform-req=ext-intl
```

## 🐳 **Docker Solution (Recommended)**

Let me update the Dockerfile to include the intl extension:
