#!/bin/bash

echo "🔧 SMTP Configuration Script for Java Email Service"
echo "=================================================="

# Check if application.properties exists
PROPS_FILE="java-email-service/src/main/resources/application.properties"

if [ ! -f "$PROPS_FILE" ]; then
    echo "❌ Error: application.properties not found at $PROPS_FILE"
    exit 1
fi

echo "📧 Gmail SMTP Configuration"
echo "1. Make sure you have a Gmail App Password ready"
echo "2. Go to: https://myaccount.google.com/apppasswords"
echo ""

# Prompt for email
echo -n "Enter your Gmail address: "
read gmail_address

# Prompt for app password
echo -n "Enter your 16-digit App Password (e.g., 'abcd efgh ijkl mnop'): "
read -s app_password
echo ""

# Validate inputs
if [ -z "$gmail_address" ] || [ -z "$app_password" ]; then
    echo "❌ Error: Both email and password are required"
    exit 1
fi

# Create backup
cp "$PROPS_FILE" "$PROPS_FILE.backup"
echo "📋 Created backup: $PROPS_FILE.backup"

# Update configuration
sed -i.tmp "s/spring.mail.username=YOUR_GMAIL_ADDRESS@gmail.com/spring.mail.username=$gmail_address/" "$PROPS_FILE"
sed -i.tmp "s/spring.mail.password=YOUR_16_DIGIT_APP_PASSWORD/spring.mail.password=$app_password/" "$PROPS_FILE"
sed -i.tmp "s/app.email.from=events@sathyabama.ac.in/app.email.from=$gmail_address/" "$PROPS_FILE"

# Clean up temp file
rm "$PROPS_FILE.tmp"

echo "✅ SMTP configuration updated successfully!"
echo ""
echo "🔄 Next steps:"
echo "1. Restart the Java email service:"
echo "   cd java-email-service"
echo "   mvn spring-boot:run"
echo ""
echo "2. Test the configuration:"
echo "   Visit: http://localhost/EventManagementSystem/test_java_email.php"
echo ""
echo "📄 Configuration file updated: $PROPS_FILE"