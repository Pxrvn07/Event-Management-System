#!/bin/bash

# =================================================================
# EMAIL CONFIGURATION SETUP SCRIPT
# =================================================================

echo "🔧 Setting up Email Service for Real Email Sending"
echo "=================================================="
echo ""

echo "📧 To send real emails, you need to configure Gmail App Password:"
echo ""
echo "1. Go to: https://myaccount.google.com/security"
echo "2. Enable 2-Step Verification if not already enabled"
echo "3. Go to: https://myaccount.google.com/apppasswords"
echo "4. Generate a new App Password for 'Mail'"
echo "5. Copy the 16-character password (e.g., 'abcd efgh ijkl mnop')"
echo ""

echo "📝 Current email configuration:"
echo "Email: mpraveen.k2007@gmail.com"
echo "Password: [NEEDS TO BE SET]"
echo ""

read -p "Do you want to set the App Password now? (y/n): " answer

if [[ $answer =~ ^[Yy]$ ]]; then
    echo ""
    read -s -p "Enter your Gmail App Password (16 characters): " app_password
    echo ""
    
    # Update the configuration file
    sed -i.bak "s/your-app-password-here/$app_password/g" src/main/resources/application.properties
    sed -i.bak "s/your-app-password-here/$app_password/g" src/main/resources/application-prod.properties
    
    echo "✅ App Password configured successfully!"
    echo ""
    echo "🚀 To start the email service with real email sending:"
    echo "   mvn clean package -DskipTests"
    echo "   java -Dspring.profiles.active=prod -jar target/email-service-1.0.0.jar"
    echo ""
else
    echo ""
    echo "⚠️  Email service will run in development mode (emails logged only)"
    echo "   To enable real emails later, edit:"
    echo "   - src/main/resources/application.properties"
    echo "   - src/main/resources/application-prod.properties"
    echo "   Replace 'your-app-password-here' with your Gmail App Password"
    echo ""
fi

echo "🔍 Gmail App Password Setup Instructions:"
echo "https://support.google.com/accounts/answer/185833"