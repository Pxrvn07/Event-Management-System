package com.sathyabama.email.controller;

import com.sathyabama.email.model.EmailRequest;
import com.sathyabama.email.model.EmailResponse;
import com.sathyabama.email.service.EmailService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.logging.Logger;

/**
 * REST API Controller for email operations
 */
@RestController
@RequestMapping("/api/email")
@CrossOrigin(origins = {"http://localhost", "http://localhost:80", "http://localhost:8080"})
public class EmailController {
    
    private static final Logger logger = Logger.getLogger(EmailController.class.getName());
    
    @Autowired
    private EmailService emailService;
    
    /**
     * Health check endpoint
     */
    @GetMapping("/health")
    public ResponseEntity<EmailResponse> health() {
        return ResponseEntity.ok(EmailResponse.success("Email service is running"));
    }
    
    /**
     * Send event registration confirmation email
     */
    @PostMapping("/send-registration-confirmation")
    public ResponseEntity<EmailResponse> sendRegistrationConfirmation(@RequestBody EmailRequest emailRequest) {
        try {
            logger.info("📧 Received email request: " + emailRequest.toString());
            
            // Validate required fields
            if (emailRequest.getToEmail() == null || emailRequest.getToEmail().trim().isEmpty()) {
                return ResponseEntity.badRequest()
                    .body(EmailResponse.failure("Email address is required"));
            }
            
            if (emailRequest.getEventName() == null || emailRequest.getEventName().trim().isEmpty()) {
                return ResponseEntity.badRequest()
                    .body(EmailResponse.failure("Event name is required"));
            }
            
            if (emailRequest.getUserName() == null || emailRequest.getUserName().trim().isEmpty()) {
                emailRequest.setUserName("Student"); // Default fallback
            }
            
            // Send email
            boolean success = emailService.sendRegistrationConfirmation(emailRequest);
            
            if (success) {
                logger.info("✅ Email sent successfully to: " + emailRequest.getToEmail());
                return ResponseEntity.ok(
                    EmailResponse.success("Email sent successfully to " + emailRequest.getToEmail())
                );
            } else {
                logger.warning("❌ Failed to send email to: " + emailRequest.getToEmail());
                return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR)
                    .body(EmailResponse.failure("Failed to send email"));
            }
            
        } catch (Exception e) {
            logger.severe("💥 Error processing email request: " + e.getMessage());
            e.printStackTrace();
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR)
                .body(EmailResponse.failure("Internal server error: " + e.getMessage()));
        }
    }
    
    /**
     * Test email endpoint for development
     */
    @PostMapping("/test")
    public ResponseEntity<EmailResponse> testEmail(@RequestParam(defaultValue = "test@example.com") String email) {
        try {
            EmailRequest testRequest = new EmailRequest(
                email,
                "Test User",
                "Sample Tech Conference 2025",
                "2025-12-15",
                "This is a test event for email functionality testing. Join us for an amazing conference!",
                null
            );
            
            boolean success = emailService.sendRegistrationConfirmation(testRequest);
            
            if (success) {
                return ResponseEntity.ok(
                    EmailResponse.success("Test email sent successfully to " + email)
                );
            } else {
                return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR)
                    .body(EmailResponse.failure("Failed to send test email"));
            }
            
        } catch (Exception e) {
            logger.severe("Error sending test email: " + e.getMessage());
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR)
                .body(EmailResponse.failure("Error: " + e.getMessage()));
        }
    }
}