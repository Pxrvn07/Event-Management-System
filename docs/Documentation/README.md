# Event Management System

A comprehensive PHP-based web application for managing events in educational institutions, featuring role-based access for staff and students.

## Features

- **Dual User Roles**: Separate interfaces for staff (event creators) and students (event participants)
- **Event Management**: Create, view, and manage events with image uploads
- **Event Registration**: Students can register for approved events
- **User Authentication**: Secure login system with password hashing
- **Responsive Design**: Modern, mobile-friendly user interface
- **Database Integration**: MySQL database with proper relationships

## Project Structure

```
EventManagementSystem/
├── PHP Backend:
│   ├── db_connection.php          # Centralized database connection
│   ├── create_event.php          # Event creation (Staff)
│   ├── get_approved_events.php   # Fetch approved events
│   ├── get_registered_events.php # Student's registered events
│   ├── get_user_info.php         # User information lookup
│   ├── get_your_events.php       # Staff's created events
│   └── register_event.php        # Event registration (Students)
├── User Interfaces:
│   ├── staff_dashboard.html      # Staff interface
│   ├── student_dashboard.html    # Student interface
│   ├── staff_dashboard.css       # Staff styling
│   └── student_dashboard.css     # Student styling
├── Authentication:
│   ├── Login/
│   │   ├── login.php            # Authentication logic
│   │   ├── login.html           # Login form
│   │   ├── login.css            # Login styling
│   │   └── login.js             # Login functionality
│   └── Register/
│       ├── signup.php           # Registration logic
│       ├── signup.html          # Registration form
│       ├── signup.css           # Registration styling
│       └── signup.js            # Registration functionality
├── Assets:
│   └── uploads/                 # Event brochures/images
├── database_setup.sql           # Database setup script
└── README.md                    # This file
```

## Setup Instructions

### 1. Database Setup

#### **Option A: Automatic Setup (Recommended)**
1. Make sure your web server (XAMPP, MAMP, or built-in PHP server) is running
2. Visit: `http://localhost/EventManagementSystem/setup_database.php`
3. The script will automatically create the database and tables
4. Follow the on-screen instructions

#### **Option B: Manual Setup**
1. Create a MySQL database named `event_management`
2. Run the `database_setup.sql` script to create tables and sample data:
   ```sql
   mysql -u root -p < database_setup.sql
   ```

### 2. Web Server Configuration

1. Place all files in your web server's root directory (e.g., `htdocs` for XAMPP)
2. Ensure PHP and MySQL are properly configured
3. Make sure the `uploads` directory is writable

### 3. Access the Application

- **Database Setup**: `http://localhost/EventManagementSystem/setup_database.php`
- **System Test**: `http://localhost/EventManagementSystem/test_system.php`
- **Login Page**: `http://localhost/EventManagementSystem/Login/login.html`
- **Staff Dashboard**: `http://localhost/EventManagementSystem/staff_dashboard.html`
- **Student Dashboard**: `http://localhost/EventManagementSystem/student_dashboard.html`

### 4. Sample Login Credentials

#### Staff Login:
- Roll Number: `STAFF001`
- Password: `staff123`

#### Student Login:
- Roll Number: `STUD001`
- Password: `student123`

## Recent Fixes Applied

### Database Connection Issues Fixed:
- ✅ Created centralized `db_connection.php` file
- ✅ Updated all PHP files to use centralized connection
- ✅ Added proper error handling with try-catch blocks
- ✅ Fixed table name inconsistencies (`user` → `users`)

### Security Improvements:
- ✅ Added proper error handling for database operations
- ✅ Implemented prepared statements consistently
- ✅ Added connection closing functions
- ✅ Fixed SQL injection vulnerabilities

### File System Issues Fixed:
- ✅ Removed duplicate file `get_approved_events.php.php`
- ✅ Fixed file paths in Login and Register directories
- ✅ Added proper database connection includes

### Code Quality Improvements:
- ✅ Added comprehensive error messages
- ✅ Improved code organization and structure
- ✅ Added proper resource cleanup
- ✅ Enhanced security with input validation

## API Endpoints

### Authentication
- `POST /Login/login.php` - User login
- `POST /Register/signup.php` - User registration

### Event Management
- `POST /create_event.php` - Create new event (Staff only)
- `GET /get_approved_events.php` - Get approved events
- `GET /get_your_events.php` - Get staff's created events
- `GET /get_registered_events.php` - Get student's registered events
- `POST /register_event.php` - Register for an event

### User Management
- `GET /get_user_info.php` - Get user information

## Database Schema

### Users Table
- `id` (Primary Key)
- `full_name`, `email`, `password`, `phone`, `roll_no`
- `created_at` (Timestamp)

### Staff Table
- `id` (Primary Key)
- `staff_name`, `email`, `password`, `designation`, `roll_no`
- `created_at` (Timestamp)

### Events Table
- `event_id` (Primary Key)
- `event_name`, `description`, `date`, `staff_id` (Foreign Key)
- `image_path`, `status` (pending/approved/rejected)
- `created_at` (Timestamp)

### Event Registrations Table
- `id` (Primary Key)
- `event_id` (Foreign Key), `roll_no`, `email`, `phone`
- `status` (registered/cancelled), `registered_at` (Timestamp)

## Troubleshooting

### Common Issues:

1. **Database Connection Error**:
   - Check if MySQL service is running
   - Verify database credentials in `db_connection.php`
   - Ensure database `event_management` exists
   - Try the automatic setup: `setup_database.php`

2. **PHP Database Setup Issues**:
   - Make sure your web server (XAMPP/MAMP/PHP built-in) is running
   - Check PHP error logs if setup fails
   - Ensure PHP has MySQLi extension enabled
   - Try manual setup if automatic setup fails

3. **File Upload Issues**:
   - Check if `uploads` directory exists and is writable
   - Verify file permissions (755 for directories, 644 for files)

4. **Login Issues**:
   - Ensure session support is enabled in PHP
   - Check if cookies are enabled in browser
   - Verify user credentials in database

5. **Permission Errors**:
   - Make sure web server has read access to all files
   - Ensure `uploads` directory has write permissions

### Debug Mode:
To enable detailed error reporting, add this to the top of PHP files:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

## Security Features

- **Password Hashing**: Uses PHP's `password_hash()` and `password_verify()`
- **SQL Injection Prevention**: Prepared statements throughout
- **Input Validation**: Server-side validation for all user inputs
- **Session Management**: Secure session handling with role-based access
- **File Upload Security**: Restricted file types and proper validation

## Future Enhancements

- Email notifications for event updates
- Admin panel for event approval workflow
- Search and filtering functionality
- Event categories and tags
- Calendar integration
- Mobile app development
- Advanced reporting features

## Support

For issues or questions, please check the troubleshooting section or create an issue in the project repository.
