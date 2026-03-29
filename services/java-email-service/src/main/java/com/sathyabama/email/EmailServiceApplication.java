package com.sathyabama.email;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;

/**
 * Main Spring Boot application class for the Email Service
 * This service provides REST API endpoints for sending emails
 * from the Event Management System
 */
@SpringBootApplication
public class EmailServiceApplication {

    public static void main(String[] args) {
        System.out.println("🚀 Starting Sathyabama Email Service...");
        SpringApplication.run(EmailServiceApplication.class, args);
        System.out.println("✅ Email Service is running on http://localhost:8081");
    }
}