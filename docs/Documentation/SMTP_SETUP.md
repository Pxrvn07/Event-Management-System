# 📧 SMTP Setup Guide for Java Email Service

## 🔧 Quick Setup Instructions

### Step 1: Create Gmail App Password

1. **Go to your Google Account**: https://myaccount.google.com/
2. **Navigate to Security** → **2-Step Verification** (enable if not already)
3. **Go to App Passwords**: https://myaccount.google.com/apppasswords
4. **Generate App Password**:
   - Select app: "Mail"
   - Select device: "Other (custom name)" → type "Sathyabama Event System"
   - Click **Generate**
   - **COPY the 16-digit password** (like: `abcd efgh ijkl mnop`)

### Step 2: Update Configuration

Edit the file: `java-email-service/src/main/resources/application.properties`

Replace these lines:
```properties
spring.mail.username=YOUR_GMAIL_ADDRESS@gmail.com
spring.mail.password=YOUR_16_DIGIT_APP_PASSWORD
```

With your actual credentials:
```properties
spring.mail.username=your.email@gmail.com
spring.mail.password=abcd efgh ijkl mnop
```

**Important**: Use the 16-digit App Password, NOT your regular Gmail password!

### Step 3: Update Sender Email (Optional)

Change the "from" email address:
```properties
app.email.from=your.email@gmail.com
app.email.from-name=Sathyabama Event Management System
```

### Step 4: Restart the Service

1. Stop the current Java service (Ctrl+C if running in terminal)
2. Start it again:
   ```bash
   cd java-email-service
   mvn spring-boot:run
   ```

### Step 5: Test Email Delivery

Visit: http://localhost/EventManagementSystem/test_java_email.php

## 🛡️ Security Best Practices

1. **Never commit credentials** to version control
2. **Use environment variables** for production:
   ```properties
   spring.mail.username=${GMAIL_USERNAME}
   spring.mail.password=${GMAIL_APP_PASSWORD}
   ```

3. **Consider other SMTP providers** for production:
   - SendGrid (recommended for production)
   - Amazon SES
   - Mailgun

## 🔍 Troubleshooting

### Common Issues:

1. **"Authentication failed"**
   - ✅ Make sure 2-Step Verification is enabled
   - ✅ Use App Password, not regular password
   - ✅ Check if App Password was copied correctly

2. **"Connection timeout"**
   - ✅ Check internet connection
   - ✅ Try port 465 with SSL instead of 587
   - ✅ Check firewall settings

3. **"Less secure app access"**
   - ✅ Modern Gmail requires App Passwords (not "less secure apps")

### Alternative Configuration (if 587 doesn't work):

```properties
spring.mail.host=smtp.gmail.com
spring.mail.port=465
spring.mail.username=your.email@gmail.com
spring.mail.password=your-app-password
spring.mail.properties.mail.smtp.auth=true
spring.mail.properties.mail.smtp.ssl.enable=true
spring.mail.properties.mail.smtp.ssl.trust=smtp.gmail.com
```

## ✅ Success Indicators

After setup, you should see:
- Health check: ✅ "Email service is running"
- Test email: ✅ "Test email sent successfully"
- Event registration: ✅ Emails delivered to users

## 🚀 Ready for Production

For production deployment, consider:
1. Using environment variables for credentials
2. Setting up dedicated SMTP service (SendGrid/SES)
3. Implementing email templates
4. Adding email queue for high volume

---
**Need help?** Check the logs in the Java service terminal for detailed error messages.