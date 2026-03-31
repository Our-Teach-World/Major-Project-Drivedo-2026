# EduShare Laravel 12 - Setup & Installation Guide

## Project Structure

```
drive-in-laravel/
├── app/
│   ├── Models/
│   │   ├── Auth.php (User model with role-based auth)
│   │   ├── Admin.php (Admin model)
│   │   └── Upload.php (File uploads model)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php (Login/Register)
│   │   │   ├── AdminController.php (Admin panel)
│   │   │   ├── TeacherController.php (Teacher features)
│   │   │   ├── StudentController.php (Student features)
│   │   │   └── ChatController.php (AI chatbot with NVIDIA API)
│   │   └── Middleware/
│   │       ├── AuthTeacher.php
│   │       ├── AuthStudent.php
│   │       └── AuthAdmin.php
├── database/
│   ├── migrations/ (auth, admin, uploads tables)
│   └── seeders/ (AdminSeeder with default credentials)
├── resources/
│   └── views/ (All Blade templates - White/Black theme)
│       ├── index.blade.php
│       ├── about.blade.php
│       ├── contact.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   └── registration-success.blade.php
│       ├── teacher/
│       │   └── dashboard.blade.php
│       ├── student/
│       │   └── dashboard.blade.php
│       └── admin/
│           ├── login.blade.php
│           ├── dashboard.blade.php
│           └── users.blade.php
├── routes/
│   └── web.php (All application routes)
└── config/
    └── auth.php (Configured for Auth model)
```

## Setup Instructions

### 1. Ensure MySQL is Running
```bash
# Start XAMPP MySQL Server
# or verify: mysql -u root
```

### 2. Navigate to Project Directory
```bash
cd c:\xampp\htdocs\drivedo\htdocs\drive-in-laravel
```

### 3. Install Dependencies (if needed)
```bash
composer install
```

### 4. Run Database Migrations
```bash
php artisan migrate
```

### 5. Seed Default Admin User
```bash
php artisan db:seed --class=AdminSeeder
```

### 6. Create Storage Symlink for file uploads
```bash
php artisan storage:link
```

### 7. Start Development Server
```bash
php artisan serve
```

Access the application at: `http://localhost:8000`

## Credentials

### Admin Login
- **URL**: http://localhost:8000/admin/login
- **Username**: admin
- **Password**: admin123

### Test User Registration
1. Go to http://localhost:8000/register
2. Create a test account as "student" or "teacher"
3. If teacher: Wait for admin approval
4. If student: Can login immediately

## Key Features

### 1. **Authentication System**
- User registration with role selection (student/teacher)
- BCRYPT password hashing
- Role-based access control
- Approval workflow for teachers

### 2. **Teacher Dashboard**
- Profile management (name and image)
- File upload (10MB limit)
- Automatic file categorization:
  - Documents (PDF, DOCX, XLSX, PPTX, TXT)
  - Images (PNG, JPG, JPEG, etc.)
  - Audio (MP3, WAV, etc.)
  - Video (MP4, MKV, etc.)
- PDF text extraction for RAG search
- File list with upload timestamps

### 3. **Student Dashboard**
- Browse available teachers
- View folders by file type
- Search and download files
- Real-time file listing

### 4. **Admin Dashboard**
- User statistics (pending, approved, teachers, students)
- User management panel
- Approve pending teacher accounts
- Delete users
- Export user data to CSV

### 5. **AI Chat with RAG**
- NVIDIA API integration
- Retrieval-Augmented Generation (RAG)
- Search through uploaded PDF content
- Context-aware responses

## Database Schema

### auth table
```sql
- id (primary key)
- username (unique, varchar 50)
- password (bcrypt hashed)
- role (enum: 'student', 'teacher')
- status (enum: 'pending', 'approved', 'rejected')
- created_at, updated_at
```

### admin table
```sql
- id (primary key)
- username (unique, varchar 50)
- password (bcrypt hashed)
- created_at, updated_at
```

### uploads table
```sql
- id (primary key)
- user_id (foreign key → auth.id)
- filename (varchar 255)
- filepath (varchar 255)
- extracted_text (LONGTEXT, full-text indexed)
- uploaded_at, created_at, updated_at
```

## API Endpoints

### Chat Endpoint
- **POST** `/api/chat`
- **Body**:
  ```json
  {
    "message": "What is in the uploaded documents?",
    "history": [],
    "contextFiles": []
  }
  ```

### Student File Access
- **GET** `/api/student/files?action=teachers`
- **GET** `/api/student/files?action=folders&teacher=<name>`
- **GET** `/api/student/files?action=files&teacher=<name>&folder=<type>`

## Styling

All pages use a **clean white and black theme**:
- White background (#ffffff)
- Black text (#000000)
- Black borders (#000000)
- Simple, modern design
- Bootstrap-style responsive layout

## Troubleshooting

### Database Connection Failed
- Ensure MySQL is running
- Check `.env` file for correct database credentials:
  ```
  DB_HOST=127.0.0.1
  DB_DATABASE=drivedo
  DB_USERNAME=root
  DB_PASSWORD=
  ```

### Upload Not Working
- Ensure `storage/` directory is writable
- Run: `chmod -R 755 storage/`

### Views Not Found
- Ensure view files are in `resources/views/`
- Try: `php artisan view:clear`

### Authentication Issues
- Clear session: `php artisan tinker` then `Session::flush()`
- Check middleware configuration in `bootstrap/app.php`

## File Structure for Uploads

```
storage/uploads/
└── <username_sanitized>/
    ├── profile.jpg
    ├── name.txt
    ├── documents/
    │   └── file.pdf
    ├── images/
    │   └── photo.jpg
    ├── audio/
    │   └── lecture.mp3
    └── video/
        └── tutorial.mp4
```

## Environment Variables (.env)

Key variables to check:
```
APP_NAME=EduShare
APP_ENV=production/local
APP_KEY=base64:xxx...
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=drivedo
DB_USERNAME=root
DB_PASSWORD=

NVIDIA_API_KEY=nvapi-...
```

## Performance Tips

1. **Optimize PDF Extraction**: Large PDFs take time to parse
2. **Database Indexing**: FULLTEXT index on `uploads.extracted_text`
3. **File Storage**: Move `storage/` to a fast disk for large deployments
4. **Session Driver**: Currently database, switch to redis for scaling

## Security Notes

1. ✅ BCRYPT password hashing enabled
2. ✅ CSRF tokens on all forms
3. ✅ SQL injection prevention (prepared statements)
4. ✅ Role-based access control
5. ✅ File type validation on upload
6. ⚠️ Disable DEBUG mode in production
7. ⚠️ Use HTTPS in production
8. ⚠️ Secure NVIDIA API key (use environment variables)

## Support

For issues or questions, refer to:
- Laravel Documentation: https://laravel.com/docs
- Project Database: Original `database.sql` in parent directory
- Controllers: See implementation details in `app/Http/Controllers/`

---
**Status**: Complete and Ready for Testing
**Version**: Laravel 12
**Last Updated**: 2025-03-26
