# Job Tracker Application

A comprehensive job application tracking system built with Laravel backend and modern frontend.

## Features

- **User Management**: Registration, login, and role-based access control
- **Job Application Tracking**: Track applications, status updates, and progress
- **Company Management**: Store and manage company information
- **Interview Scheduling**: Schedule and track interviews
- **Notes System**: Add private and public notes to applications
- **Analytics Dashboard**: Track application success rates and metrics
- **CMS Integration**: Laravel Nova for administrative tasks

## Technology Stack

### Backend
- Laravel 10+ (PHP 8.1+)
- MySQL/PostgreSQL database
- Laravel Sanctum for API authentication
- Laravel Nova for CMS

### Frontend
- React.js with TypeScript
- Tailwind CSS for styling
- Axios for API communication

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js and NPM
- MySQL or PostgreSQL

### Backend Setup

1. **Install PHP dependencies:**
   ```bash
   cd backend
   composer install
   ```

2. **Environment Configuration:**
   ```bash
   cp env.example .env
   ```
   
   Update the `.env` file with your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=job_tracker
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

3. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

4. **Run Migrations:**
   ```bash
   php artisan migrate
   ```

5. **Seed Database:**
   ```bash
   php artisan db:seed
   ```

6. **Start Development Server:**
   ```bash
   php artisan serve
   ```

### Super Admin Account

The seeder creates a super admin account with the following credentials:
- **Username:** sarvar
- **Password:** Nsusife123@
- **Role:** super_admin

## API Endpoints

### Authentication
- `POST /api/register` - Register new user
- `POST /api/login` - Login user
- `POST /api/logout` - Logout user
- `GET /api/me` - Get current user
- `PUT /api/profile` - Update user profile
- `PUT /api/change-password` - Change password

### Applications
- `GET /api/applications` - List applications
- `POST /api/applications` - Create application
- `GET /api/applications/{id}` - Get application
- `PUT /api/applications/{id}` - Update application
- `DELETE /api/applications/{id}` - Delete application

### Companies
- `GET /api/companies` - List companies
- `POST /api/companies` - Create company
- `GET /api/companies/{id}` - Get company
- `PUT /api/companies/{id}` - Update company
- `DELETE /api/companies/{id}` - Delete company

## Database Schema

### Core Tables
- `users` - User accounts with roles
- `companies` - Company information
- `jobs` - Job postings/positions
- `applications` - Job applications
- `interviews` - Interview scheduling
- `application_notes` - Application notes

### User Roles
- `super_admin` - Full system access
- `admin` - Administrative access
- `user` - Standard user access

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
