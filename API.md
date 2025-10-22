# Job Tracker API Documentation

## 🔗 **Base URL**
```
http://localhost:8000/api
```

## 🔐 **Authentication**

All protected endpoints require a Bearer token in the Authorization header:
```
Authorization: Bearer YOUR_JWT_TOKEN
```

## 📋 **API Endpoints**

### **Authentication Endpoints**

#### **POST** `/register`
Register a new user account.

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "username": "johndoe",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response:**
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "username": "johndoe",
    "role": "user",
    "is_active": true
  },
  "token": "jwt_token_here"
}
```

#### **POST** `/login`
Login with username/email and password.

**Request Body:**
```json
{
  "username": "johndoe",
  "password": "password123"
}
```

**Response:**
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "username": "johndoe",
    "role": "user",
    "is_active": true
  },
  "token": "jwt_token_here"
}
```

#### **POST** `/logout`
Logout and invalidate current token.

**Response:**
```json
{
  "message": "Logout successful"
}
```

#### **GET** `/me`
Get current authenticated user information.

**Response:**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "username": "johndoe",
    "role": "user",
    "is_active": true
  }
}
```

### **Dashboard Endpoints**

#### **GET** `/dashboard/stats`
Get dashboard statistics.

**Response:**
```json
{
  "total_applications": 25,
  "status_breakdown": {
    "applied": 5,
    "under_review": 8,
    "interviewed": 3,
    "offer_received": 2,
    "rejected": 7
  },
  "monthly_applications": {
    "2024-01": 5,
    "2024-02": 8,
    "2024-03": 12
  },
  "top_companies": [
    {"name": "Google", "count": 3},
    {"name": "Microsoft", "count": 2}
  ],
  "recent_applications": [...]
}
```

#### **GET** `/dashboard/success-rate`
Get application success rate.

**Response:**
```json
{
  "success_rate": 15.5,
  "total_applications": 25,
  "successful_applications": 4
}
```

### **Application Endpoints**

#### **GET** `/applications`
Get paginated list of applications.

**Query Parameters:**
- `page` (optional): Page number
- `status` (optional): Filter by status
- `company_id` (optional): Filter by company
- `search` (optional): Search term

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "company_id": 1,
      "job_id": 1,
      "status": "applied",
      "applied_date": "2024-01-15",
      "notes": "Applied through company website",
      "resume_url": "https://example.com/resume.pdf",
      "cover_letter_url": "https://example.com/cover.pdf",
      "salary_expectation": 75000,
      "source": "Company Website",
      "company": {
        "id": 1,
        "name": "Google",
        "website": "https://google.com"
      },
      "job": {
        "id": 1,
        "title": "Software Engineer",
        "location": "Mountain View, CA"
      }
    }
  ],
  "current_page": 1,
  "last_page": 3,
  "per_page": 15,
  "total": 25
}
```

#### **POST** `/applications`
Create a new application.

**Request Body:**
```json
{
  "company_id": 1,
  "job_id": 1,
  "status": "applied",
  "applied_date": "2024-01-15",
  "notes": "Applied through company website",
  "resume_url": "https://example.com/resume.pdf",
  "cover_letter_url": "https://example.com/cover.pdf",
  "salary_expectation": 75000,
  "source": "Company Website"
}
```

#### **GET** `/applications/{id}`
Get specific application details.

#### **PUT** `/applications/{id}`
Update application.

#### **DELETE** `/applications/{id}`
Delete application.

### **Company Endpoints**

#### **GET** `/companies`
Get paginated list of companies.

**Query Parameters:**
- `page` (optional): Page number
- `search` (optional): Search term
- `industry` (optional): Filter by industry

#### **POST** `/companies`
Create a new company.

**Request Body:**
```json
{
  "name": "Google",
  "website": "https://google.com",
  "email": "careers@google.com",
  "phone": "+1-650-253-0000",
  "address": "1600 Amphitheatre Parkway, Mountain View, CA",
  "description": "Technology company specializing in search and cloud services",
  "industry": "Technology",
  "size": "10000+",
  "logo_url": "https://example.com/google-logo.png"
}
```

#### **GET** `/companies/{id}`
Get specific company details.

#### **PUT** `/companies/{id}`
Update company.

#### **DELETE** `/companies/{id}`
Delete company.

### **Interview Endpoints**

#### **GET** `/applications/{application_id}/interviews`
Get interviews for an application.

#### **POST** `/applications/{application_id}/interviews`
Create a new interview.

**Request Body:**
```json
{
  "interview_date": "2024-01-20",
  "interview_time": "14:00",
  "type": "video",
  "location": "Zoom Meeting",
  "interviewer_name": "Jane Smith",
  "interviewer_email": "jane@company.com",
  "notes": "Technical interview for software engineer position",
  "status": "scheduled"
}
```

#### **GET** `/interviews/{id}`
Get specific interview details.

#### **PUT** `/interviews/{id}`
Update interview.

#### **DELETE** `/interviews/{id}`
Delete interview.

### **Application Notes Endpoints**

#### **GET** `/applications/{application_id}/notes`
Get notes for an application.

#### **POST** `/applications/{application_id}/notes`
Create a new note.

**Request Body:**
```json
{
  "note": "Had a great conversation with the hiring manager",
  "is_private": false
}
```

#### **GET** `/notes/{id}`
Get specific note details.

#### **PUT** `/notes/{id}`
Update note.

#### **DELETE** `/notes/{id}`
Delete note.

### **Job Endpoints**

#### **GET** `/jobs`
Get paginated list of jobs.

**Query Parameters:**
- `page` (optional): Page number
- `company_id` (optional): Filter by company
- `search` (optional): Search term

## 📊 **Status Values**

### **Application Statuses**
- `applied` - Applied
- `under_review` - Under Review
- `phone_screening` - Phone Screening
- `interview_scheduled` - Interview Scheduled
- `interviewed` - Interviewed
- `technical_interview` - Technical Interview
- `final_interview` - Final Interview
- `offer_received` - Offer Received
- `offer_accepted` - Offer Accepted
- `offer_declined` - Offer Declined
- `rejected` - Rejected
- `withdrawn` - Withdrawn

### **Interview Types**
- `phone` - Phone Interview
- `video` - Video Interview
- `in-person` - In-Person Interview
- `technical` - Technical Interview
- `hr` - HR Interview
- `final` - Final Interview

### **Interview Statuses**
- `scheduled` - Scheduled
- `completed` - Completed
- `cancelled` - Cancelled
- `rescheduled` - Rescheduled

## 🔒 **Error Responses**

### **401 Unauthorized**
```json
{
  "message": "Unauthenticated."
}
```

### **403 Forbidden**
```json
{
  "message": "Unauthorized"
}
```

### **422 Validation Error**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### **404 Not Found**
```json
{
  "message": "No query results for model [App\\Models\\Application] 1"
}
```

### **500 Server Error**
```json
{
  "message": "Server Error"
}
```

## 🧪 **Testing the API**

### **Using cURL**

#### Login Example:
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"username": "sarvar", "password": "Nsusife123@"}'
```

#### Get Applications Example:
```bash
curl -X GET http://localhost:8000/api/applications \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### **Using Postman**
1. Import the collection
2. Set the base URL to `http://localhost:8000/api`
3. Use the login endpoint to get a token
4. Set the Authorization header with Bearer token
5. Test other endpoints

## 📝 **Rate Limiting**

- **API Rate Limit**: 60 requests per minute per user
- **Headers**: Rate limit information is included in response headers
- **Exceeded**: Returns 429 status code when limit exceeded

## 🔧 **Development Notes**

- All timestamps are in UTC
- Dates are in YYYY-MM-DD format
- Times are in HH:MM format (24-hour)
- Pagination uses Laravel's default pagination
- Search is case-insensitive
- All endpoints support JSON content type
