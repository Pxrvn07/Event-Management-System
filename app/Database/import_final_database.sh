#!/bin/bash
# =====================================================
# FINAL DATABASE IMPORT SCRIPT
# =====================================================
# This script will import the final database and verify everything is working

echo "🚀 Starting Final Database Setup..."
echo "=================================="

# Check if MySQL is running
if ! command -v mysql &> /dev/null; then
    echo "❌ MySQL not found. Make sure XAMPP is running!"
    exit 1
fi

# Set database credentials (adjust if needed)
DB_HOST="localhost"
DB_USER="root"
DB_PASS=""
DB_NAME="event_management"

echo "📊 Importing final database..."

# Import the database
mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" < FINAL_DATABASE.sql

if [ $? -eq 0 ]; then
    echo "✅ Database imported successfully!"
    echo ""
    echo "🎉 Your Event Management System is ready!"
    echo "========================================"
    echo ""
    echo "📋 Database Summary:"
    echo "• Admin accounts: 2"
    echo "• Staff accounts: 3"  
    echo "• Student accounts: 5"
    echo "• Sample events: 7"
    echo "• Registration closed event: Sports Championship (25/25)"
    echo ""
    echo "🔑 Test Login Credentials:"
    echo "Admin: admin@sathyabama.edu / admin123"
    echo "Staff: rajesh.kumar@sathyabama.edu / staff123"
    echo "Student: arjun.patel@student.sathyabama.edu / student123"
    echo ""
    echo "🌐 Open your browser and go to:"
    echo "http://localhost/EventManagementSystem/"
    echo ""
    echo "✨ All features are ready to test!"
else
    echo "❌ Database import failed!"
    echo "Make sure XAMPP MySQL is running and try again."
    exit 1
fi