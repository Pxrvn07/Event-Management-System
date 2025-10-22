# File Structure Update Summary

## Overview
The Event Management System has been reorganized into a clean, professional folder structure. All files have been moved to appropriate directories and file paths have been updated accordingly.

## New Folder Structure

```
EventManagementSystem/
├── index.php (NEW - Landing page)
├── 📂 Admin/
│   ├── admin_dashboard.html
│   ├── admin_dashboard.css
│   ├── approve_event.php
│   ├── reject_event.php
│   ├── get_admin_user_info.php
│   ├── get_all_events.php
│   └── get_pending_events.php
├── 📂 Staff/
│   ├── staff_dashboard.html
│   ├── staff_dashboard.css
│   ├── create_event.php
│   ├── edit_event.php
│   ├── get_staff_user_info.php
│   ├── get_coordinated_events.php
│   ├── get_user_coordinated_events.php
│   ├── get_your_events.php
│   └── get_students.php
├── 📂 Student/
│   ├── student_dashboard.html
│   ├── student_dashboard.css
│   ├── get_approved_events.php
│   ├── get_registered_events.php
│   ├── get_user_info.php
│   └── register_event.php
├── 📂 Login/
│   ├── login.html
│   ├── login.css
│   ├── login.js
│   ├── login.php
│   └── a.php
├── 📂 Register/
│   ├── signup.html
│   ├── signup.css
│   ├── signup.js
│   └── signup.php
├── 📂 API/
│   ├── get_event_details.php
│   └── logout.php
├── 📂 Config/
│   ├── email_config.php
│   └── java_email_config.php
├── 📂 Database/
│   ├── database_setup.sql
│   ├── event_management.sql
│   ├── db_connection.php
│   └── setup_database.php
├── 📂 Documentation/
│   ├── README.md
│   ├── SMTP_SETUP.md
│   ├── TODO.md
│   └── PERFORMANCE_OPTIMIZATION.md
├── 📂 tests/
│   ├── test_email.php
│   ├── test_integration.php
│   ├── test_java_email.php
│   ├── test_registration.html
│   ├── test_system.php
│   ├── test_insert.php
│   └── debug_mail.php
├── 📂 scripts/
│   ├── configure_smtp.sh
│   └── start_email_service.sh
├── 📂 assets/ (for images and static files)
├── 📂 uploads/ (for uploaded files)
├── 📂 logs/ (for log files)
└── 📂 java-email-service/ (Java service files)
```

## Key Changes Made

### 1. New Entry Point
- Created `index.php` as the main landing page
- Professional design with links to Login and Register

### 2. File Movements
- **Admin files**: Moved dashboard files and all admin-related PHP files to `Admin/` folder
- **Staff files**: Moved dashboard files and all staff-related PHP files to `Staff/` folder  
- **Student files**: Moved dashboard files and all student-related PHP files to `Student/` folder
- **Config files**: Moved email configuration files to `Config/` folder
- **Database files**: Moved database setup files to `Database/` folder
- **Test files**: Moved all test files to `tests/` folder
- **Script files**: Moved shell scripts to `scripts/` folder
- **Documentation**: Moved documentation files to `Documentation/` folder

### 3. Path Updates
Updated include/require statements in all PHP files:
- `include 'db_connection.php';` → `include '../Database/db_connection.php';`
- `include 'java_email_config.php';` → `include '../Config/java_email_config.php';`

### 4. API Call Updates
Updated fetch() calls in HTML/JS files:
- `fetch('get_event_details.php')` → `fetch('../API/get_event_details.php')`
- `fetch('get_user_info.php')` → `fetch('../Student/get_user_info.php')`
- And many more...

### 5. Navigation Updates
Updated redirect paths in login.js:
- Student dashboard: `../Student/student_dashboard.html`
- Staff dashboard: `../Staff/staff_dashboard.html`  
- Admin dashboard: `../Admin/admin_dashboard.html`

### 6. Fixed Logout
Updated logout.php redirect path:
- From: `./Login/login.html`
- To: `../Login/login.html`

## Benefits

1. **Better Organization**: Role-based separation makes the codebase more maintainable
2. **Professional Structure**: Follows industry best practices for web application structure
3. **Easy Navigation**: Clear folder hierarchy makes finding files intuitive
4. **Clean Root**: No more scattered files in the root directory
5. **Scalability**: Easy to add new features to appropriate folders
6. **Team Collaboration**: Multiple developers can work on different sections without conflicts

## Testing Recommendations

1. Test all login flows (Student, Staff, Admin)
2. Verify dashboard loading for all user types
3. Test event creation, approval, and registration workflows
4. Verify file uploads work correctly with new paths
5. Test logout functionality from all dashboards

## Next Steps

1. Update any hardcoded paths in database records if they exist
2. Test the complete user journey from registration to event management
3. Update any documentation that references old file paths
4. Consider adding a .htaccess file for better URL rewriting if needed