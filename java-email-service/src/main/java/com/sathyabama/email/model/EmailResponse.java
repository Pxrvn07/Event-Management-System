package com.sathyabama.email.model;

/**
 * Response model for email sending operations
 */
public class EmailResponse {
    
    private boolean success;
    private String message;
    private String emailId;
    private long timestamp;
    
    // Default constructor
    public EmailResponse() {
        this.timestamp = System.currentTimeMillis();
    }
    
    // Constructor for success response
    public EmailResponse(boolean success, String message) {
        this();
        this.success = success;
        this.message = message;
    }
    
    // Constructor with email ID
    public EmailResponse(boolean success, String message, String emailId) {
        this(success, message);
        this.emailId = emailId;
    }
    
    // Static factory methods for common responses
    public static EmailResponse success(String message) {
        return new EmailResponse(true, message);
    }
    
    public static EmailResponse success(String message, String emailId) {
        return new EmailResponse(true, message, emailId);
    }
    
    public static EmailResponse failure(String message) {
        return new EmailResponse(false, message);
    }
    
    // Getters and Setters
    public boolean isSuccess() {
        return success;
    }
    
    public void setSuccess(boolean success) {
        this.success = success;
    }
    
    public String getMessage() {
        return message;
    }
    
    public void setMessage(String message) {
        this.message = message;
    }
    
    public String getEmailId() {
        return emailId;
    }
    
    public void setEmailId(String emailId) {
        this.emailId = emailId;
    }
    
    public long getTimestamp() {
        return timestamp;
    }
    
    public void setTimestamp(long timestamp) {
        this.timestamp = timestamp;
    }
}