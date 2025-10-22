#!/bin/bash

# Job Tracker - Push to GitHub Script
echo "🚀 Pushing Job Tracker to GitHub..."

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

# Check if git is initialized
if [ ! -d ".git" ]; then
    print_error "Git repository not initialized. Please run 'git init' first."
    exit 1
fi

# Check if there are uncommitted changes
if [ -n "$(git status --porcelain)" ]; then
    print_warning "There are uncommitted changes. Adding them to commit..."
    git add .
    git commit -m "Update: $(date)"
fi

# Check if GitHub CLI is installed
if command -v gh &> /dev/null; then
    print_status "GitHub CLI found. Creating repository..."
    
    # Check if already connected to GitHub
    if gh auth status &> /dev/null; then
        print_status "Already authenticated with GitHub"
    else
        print_warning "Please authenticate with GitHub first:"
        echo "Run: gh auth login"
        exit 1
    fi
    
    # Create repository and push
    gh repo create job-tracker --public --source=. --remote=origin --push
    print_status "Repository created and pushed to GitHub!"
    
else
    print_warning "GitHub CLI not found. Manual setup required:"
    echo ""
    echo "1. Go to https://github.com/new"
    echo "2. Create a new repository named 'job-tracker'"
    echo "3. Copy the repository URL"
    echo "4. Run the following commands:"
    echo ""
    echo "   git remote add origin YOUR_REPOSITORY_URL"
    echo "   git branch -M main"
    echo "   git push -u origin main"
    echo ""
    echo "Or install GitHub CLI:"
    echo "   brew install gh  # macOS"
    echo "   gh auth login"
    echo "   ./push-to-github.sh"
fi

print_status "🎉 Job Tracker is ready for deployment!"
echo ""
echo "📋 Next steps:"
echo "1. Clone the repository on your server:"
echo "   git clone https://github.com/YOUR_USERNAME/job-tracker.git"
echo ""
echo "2. Run with Docker:"
echo "   cd job-tracker"
echo "   ./deploy.sh"
echo ""
echo "3. Access the application:"
echo "   Frontend: http://localhost:3000"
echo "   Backend: http://localhost:8000"
echo "   Nova Admin: http://localhost:8000/nova"
echo ""
echo "🔐 Super Admin Login:"
echo "   Username: sarvar"
echo "   Password: Nsusife123@"
