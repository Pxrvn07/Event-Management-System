# 📧 Java Email Service for Event Management System

A robust Java-based email service using Spring Boot and JavaMail API, designed to handle email notifications for the Sathyabama Event Management System.

## 🚀 Features

- **Professional Email Templates**: Beautiful HTML emails with Sathyabama branding
- **REST API**: Easy integration with PHP application via HTTP calls
- **Reliable Delivery**: Uses JavaMail API with SMTP configuration
- **Error Handling**: Comprehensive logging and error management
- **Cross-Origin Support**: CORS configured for seamless PHP integration
- **Test Endpoints**: Built-in testing capabilities

## 📋 Prerequisites

- **Java 11 or higher**
- **Maven 3.6+**
- **SMTP Server Access** (Gmail, Outlook, or any SMTP provider)

## 🔧 Installation & Setup

### 1. Install Java and Maven

**macOS (using Homebrew):**
```bash
brew install openjdk@11 maven
```

**Windows:**
- Download Java from [AdoptOpenJDK](https://adoptopenjdk.net/)
- Download Maven from [Apache Maven](https://maven.apache.org/download.cgi)

### 2. Configure Email Settings

Edit `src/main/resources/application.properties`:

```properties
# For Gmail (recommended for testing)
spring.mail.host=smtp.gmail.com
spring.mail.port=587
spring.mail.username=your-email@gmail.com
spring.mail.password=your-app-password
spring.mail.properties.mail.smtp.auth=true
spring.mail.properties.mail.smtp.starttls.enable=true

# Update sender information
app.email.from=events@sathyabama.ac.in
app.email.from-name=Sathyabama Event Management System
```

**For Gmail:**
1. Enable 2-Factor Authentication
2. Generate an App Password: [Google Account Security](https://myaccount.google.com/security)
3. Use the App Password (not your regular password)

### 3. Build and Run

```bash
# Navigate to the java-email-service directory
cd java-email-service

# Make the run script executable
chmod +x run.sh

# Build and start the service
./run.sh
```

The service will start on `http://localhost:8081/email-service`

## 🔍 API Endpoints

### Health Check
```http
GET /email-service/api/email/health
```

### Send Registration Confirmation
```http
POST /email-service/api/email/send-registration-confirmation
Content-Type: application/json

{
    "to_email": "student@example.com",
    "user_name": "John Doe",
    "event_name": "Tech Conference 2025",
    "event_date": "2025-12-15",
    "event_description": "Amazing tech conference with latest innovations",
    "event_image": "path/to/image.jpg"
}
```

### Test Email
```http
POST /email-service/api/email/test?email=test@example.com
```

## 🧪 Testing

1. **Start the Java service** using `./run.sh`
2. **Open the test page** in your browser: `http://localhost/EventManagementSystem/test_java_email.php`
3. **Check service health** and send test emails
4. **Register for an event** through the student dashboard to test full integration

## 🔗 PHP Integration

The PHP application uses `java_email_config.php` to communicate with the Java service:

```php
// Example usage in PHP
include 'java_email_config.php';

$javaEmailService = new JavaEmailService();
$success = $javaEmailService->sendRegistrationConfirmation(
    'student@example.com',
    'John Doe',
    'Tech Conference 2025',
    '2025-12-15',
    'Event description',
    'image_path.jpg'
);
```

## 📊 Monitoring & Logs

- **Application Logs**: Check console output when running the service
- **Email Logs**: View `logs/email_log.txt` for email sending history
- **Health Endpoint**: Monitor service availability via `/api/email/health`

## ⚙️ Configuration Options

### SMTP Providers

**Gmail:**
```properties
spring.mail.host=smtp.gmail.com
spring.mail.port=587
```

**Outlook/Hotmail:**
```properties
spring.mail.host=smtp-mail.outlook.com
spring.mail.port=587
```

**Custom SMTP:**
```properties
spring.mail.host=your-smtp-server.com
spring.mail.port=587
spring.mail.username=your-username
spring.mail.password=your-password
```

### Development Mode

For development without actual email sending, you can use [MailHog](https://github.com/mailhog/MailHog):

```properties
spring.mail.host=localhost
spring.mail.port=1025
```

## 🛠️ Troubleshooting

### Common Issues

1. **"Connection refused"**
   - Ensure the Java service is running on port 8081
   - Check firewall settings

2. **"Authentication failed"**
   - Verify SMTP credentials
   - For Gmail, use App Password instead of regular password

3. **"CORS errors"**
   - CORS is pre-configured for localhost
   - For production, update `WebConfig.java`

4. **"Java not found"**
   - Install Java 11+ and ensure it's in your PATH
   - Run `java -version` to verify

### Debug Steps

1. Check Java service health: `curl http://localhost:8081/email-service/api/email/health`
2. Review application logs in the terminal
3. Check email logs in `logs/email_log.txt`
4. Use the test page: `http://localhost/EventManagementSystem/test_java_email.php`

## 🔄 Production Deployment

For production deployment:

1. **Update email configuration** with production SMTP settings
2. **Configure proper CORS** for your domain
3. **Use environment variables** for sensitive configuration
4. **Set up monitoring** and health checks
5. **Consider using a proper email service** like SendGrid, Mailgun, or AWS SES

## 📈 Benefits of Java Email Service

- **Reliability**: JavaMail API is robust and well-tested
- **Performance**: Better handling of concurrent email requests
- **Scalability**: Can be deployed as a microservice
- **Maintainability**: Separate concerns from PHP application
- **Advanced Features**: Rich email formatting, attachments, templates
- **Professional**: Enterprise-grade email delivery

## 🆘 Support

If you encounter any issues:

1. Check the troubleshooting section above
2. Review the application logs
3. Test with the provided endpoints
4. Ensure all prerequisites are met

---

**Happy emailing!** 📧✨