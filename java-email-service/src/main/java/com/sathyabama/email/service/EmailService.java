package com.sathyabama.email.service;

import com.sathyabama.email.model.EmailRequest;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.mail.javamail.JavaMailSender;
import org.springframework.mail.javamail.MimeMessageHelper;
import org.springframework.stereotype.Service;
import org.thymeleaf.TemplateEngine;
import org.thymeleaf.context.Context;

import javax.mail.internet.MimeMessage;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.logging.Logger;

/**
 * Service class for sending emails
 */
@Service
public class EmailService {
    
    private static final Logger logger = Logger.getLogger(EmailService.class.getName());
    
    @Autowired
    private JavaMailSender mailSender;
    
    @Autowired
    private TemplateEngine templateEngine;
    
    @Value("${app.email.from}")
    private String fromEmail;
    
    @Value("${app.email.from-name}")
    private String fromName;
    
    /**
     * Send event registration confirmation email
     */
    public boolean sendRegistrationConfirmation(EmailRequest emailRequest) {
        try {
            logger.info("📧 Attempting to send registration confirmation to: " + emailRequest.getToEmail());
            
            // In development mode, just log the email instead of sending
            if (isDevelopmentMode()) {
                logger.info("📝 DEVELOPMENT MODE - Email content logged instead of sent:");
                logger.info("To: " + emailRequest.getToEmail());
                logger.info("Subject: Registration Confirmed: " + emailRequest.getEventName());
                logger.info("Event: " + emailRequest.getEventName());
                logger.info("User: " + emailRequest.getUserName());
                logger.info("Date: " + emailRequest.getEventDate());
                logger.info("✅ Email logged successfully (development mode)");
                return true;
            }
            
            // Create email message
            MimeMessage message = mailSender.createMimeMessage();
            MimeMessageHelper helper = new MimeMessageHelper(message, true, "UTF-8");
            
            // Set email properties
            helper.setFrom(fromEmail, fromName);
            helper.setTo(emailRequest.getToEmail());
            helper.setSubject("Registration Confirmed: " + emailRequest.getEventName());
            
            // Generate HTML content
            String htmlContent = generateEmailTemplate(emailRequest);
            helper.setText(htmlContent, true);
            
            // Send email
            mailSender.send(message);
            
            logger.info("✅ Email sent successfully to: " + emailRequest.getToEmail());
            return true;
            
        } catch (Exception e) {
            logger.severe("❌ Failed to send email to: " + emailRequest.getToEmail() + 
                         ". Error: " + e.getMessage());
            // In development, still return true to not break the flow
            if (isDevelopmentMode()) {
                logger.info("📝 Development mode: Treating failed email as success");
                return true;
            }
            e.printStackTrace();
            return false;
        }
    }
    
    /**
     * Check if running in development mode
     */
    private boolean isDevelopmentMode() {
        String activeProfiles = System.getProperty("spring.profiles.active");
        return activeProfiles != null && activeProfiles.contains("dev");
    }
    
    /**
     * Generate HTML email template
     */
    private String generateEmailTemplate(EmailRequest emailRequest) {
        // Parse date for better formatting
        String formattedDate = formatEventDate(emailRequest.getEventDate());
        
        return "<!DOCTYPE html>\n" +
                "<html lang=\"en\">\n" +
                "<head>\n" +
                "    <meta charset=\"UTF-8\">\n" +
                "    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n" +
                "    <title>Event Registration Confirmation</title>\n" +
                "    <style>\n" +
                "        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }\n" +
                "        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }\n" +
                "        .header { background: linear-gradient(135deg, #831339, #a81746); color: white; padding: 40px 30px; text-align: center; }\n" +
                "        .header h1 { margin: 0 0 10px 0; font-size: 28px; font-weight: 700; }\n" +
                "        .header p { margin: 0; font-size: 16px; opacity: 0.95; }\n" +
                "        .content { padding: 40px 30px; }\n" +
                "        .content h2 { color: #831339; margin-top: 0; font-size: 24px; }\n" +
                "        .event-details { background: #f8f9fa; padding: 25px; border-radius: 10px; margin: 25px 0; border-left: 4px solid #831339; }\n" +
                "        .event-details h3 { color: #831339; margin-top: 0; font-size: 20px; font-weight: 600; }\n" +
                "        .detail-item { margin: 15px 0; }\n" +
                "        .detail-label { font-weight: 600; color: #555; display: inline-block; min-width: 80px; }\n" +
                "        .detail-value { color: #333; }\n" +
                "        .instructions { background: #e8f4ff; padding: 20px; border-radius: 8px; margin: 25px 0; }\n" +
                "        .instructions h3 { color: #2563eb; margin-top: 0; }\n" +
                "        .instructions ul { padding-left: 20px; }\n" +
                "        .instructions li { margin: 8px 0; }\n" +
                "        .important-note { background: #fff3cd; padding: 15px; border-radius: 6px; border-left: 4px solid #ffc107; margin: 20px 0; }\n" +
                "        .footer { background: #f8f9fa; padding: 25px 30px; text-align: center; color: #666; border-top: 1px solid #e9ecef; }\n" +
                "        .footer p { margin: 5px 0; font-size: 14px; }\n" +
                "        .emoji { font-size: 1.2em; }\n" +
                "    </style>\n" +
                "</head>\n" +
                "<body>\n" +
                "    <div class=\"container\">\n" +
                "        <div class=\"header\">\n" +
                "            <h1><span class=\"emoji\">🎉</span> Registration Confirmed!</h1>\n" +
                "            <p>Welcome to " + emailRequest.getEventName() + "</p>\n" +
                "        </div>\n" +
                "        \n" +
                "        <div class=\"content\">\n" +
                "            <h2>Dear " + emailRequest.getUserName() + ",</h2>\n" +
                "            \n" +
                "            <p>Congratulations! You have successfully registered for the following event:</p>\n" +
                "            \n" +
                "            <div class=\"event-details\">\n" +
                "                <h3>" + emailRequest.getEventName() + "</h3>\n" +
                "                <div class=\"detail-item\">\n" +
                "                    <span class=\"detail-label\"><span class=\"emoji\">📅</span> Date:</span>\n" +
                "                    <span class=\"detail-value\">" + formattedDate + "</span>\n" +
                "                </div>\n" +
                "                <div class=\"detail-item\">\n" +
                "                    <span class=\"detail-label\"><span class=\"emoji\">📝</span> Description:</span>\n" +
                "                    <div class=\"detail-value\" style=\"margin-top: 8px;\">" + 
                (emailRequest.getEventDescription() != null && !emailRequest.getEventDescription().trim().isEmpty() 
                    ? emailRequest.getEventDescription() 
                    : "Exciting event details will be shared soon!") + "</div>\n" +
                "                </div>\n" +
                "            </div>\n" +
                "            \n" +
                "            <div class=\"instructions\">\n" +
                "                <h3><span class=\"emoji\">📋</span> What's Next?</h3>\n" +
                "                <ul>\n" +
                "                    <li>Mark your calendar for " + formattedDate + "</li>\n" +
                "                    <li>Keep this email as your registration confirmation</li>\n" +
                "                    <li>Watch for additional event updates and instructions</li>\n" +
                "                    <li>Contact event coordinators if you have any questions</li>\n" +
                "                    <li>Arrive on time and be prepared for an amazing experience!</li>\n" +
                "                </ul>\n" +
                "            </div>\n" +
                "            \n" +
                "            <div class=\"important-note\">\n" +
                "                <p><strong><span class=\"emoji\">⚠️</span> Important:</strong> Please bring a valid ID for event check-in.</p>\n" +
                "            </div>\n" +
                "            \n" +
                "            <p>We look forward to seeing you at the event!</p>\n" +
                "            \n" +
                "            <p>Best regards,<br>\n" +
                "            <strong>Sathyabama Event Management Team</strong></p>\n" +
                "        </div>\n" +
                "        \n" +
                "        <div class=\"footer\">\n" +
                "            <p>This is an automated confirmation email. Please do not reply to this message.</p>\n" +
                "            <p>For questions, contact the event coordinators through the student portal.</p>\n" +
                "            <p>&copy; " + new SimpleDateFormat("yyyy").format(new Date()) + " Sathyabama Institute of Science and Technology</p>\n" +
                "        </div>\n" +
                "    </div>\n" +
                "</body>\n" +
                "</html>";
    }
    
    /**
     * Format event date for display
     */
    private String formatEventDate(String dateString) {
        try {
            SimpleDateFormat inputFormat = new SimpleDateFormat("yyyy-MM-dd");
            SimpleDateFormat outputFormat = new SimpleDateFormat("EEEE, MMMM d, yyyy");
            Date date = inputFormat.parse(dateString);
            return outputFormat.format(date);
        } catch (Exception e) {
            return dateString; // Return original if parsing fails
        }
    }
}