package com.sathyabama.email.model;

import com.fasterxml.jackson.annotation.JsonProperty;

/**
 * Data model for email registration request
 */
public class EmailRequest {
    
    @JsonProperty("to_email")
    private String toEmail;
    
    @JsonProperty("user_name")
    private String userName;
    
    @JsonProperty("event_name")
    private String eventName;
    
    @JsonProperty("event_date")
    private String eventDate;
    
    @JsonProperty("event_description")
    private String eventDescription;
    
    @JsonProperty("event_image")
    private String eventImage;
    
    // Default constructor
    public EmailRequest() {}
    
    // Constructor with all fields
    public EmailRequest(String toEmail, String userName, String eventName, 
                       String eventDate, String eventDescription, String eventImage) {
        this.toEmail = toEmail;
        this.userName = userName;
        this.eventName = eventName;
        this.eventDate = eventDate;
        this.eventDescription = eventDescription;
        this.eventImage = eventImage;
    }
    
    // Getters and Setters
    public String getToEmail() {
        return toEmail;
    }
    
    public void setToEmail(String toEmail) {
        this.toEmail = toEmail;
    }
    
    public String getUserName() {
        return userName;
    }
    
    public void setUserName(String userName) {
        this.userName = userName;
    }
    
    public String getEventName() {
        return eventName;
    }
    
    public void setEventName(String eventName) {
        this.eventName = eventName;
    }
    
    public String getEventDate() {
        return eventDate;
    }
    
    public void setEventDate(String eventDate) {
        this.eventDate = eventDate;
    }
    
    public String getEventDescription() {
        return eventDescription;
    }
    
    public void setEventDescription(String eventDescription) {
        this.eventDescription = eventDescription;
    }
    
    public String getEventImage() {
        return eventImage;
    }
    
    public void setEventImage(String eventImage) {
        this.eventImage = eventImage;
    }
    
    @Override
    public String toString() {
        return "EmailRequest{" +
                "toEmail='" + toEmail + '\'' +
                ", userName='" + userName + '\'' +
                ", eventName='" + eventName + '\'' +
                ", eventDate='" + eventDate + '\'' +
                ", eventDescription='" + eventDescription + '\'' +
                ", eventImage='" + eventImage + '\'' +
                '}';
    }
}