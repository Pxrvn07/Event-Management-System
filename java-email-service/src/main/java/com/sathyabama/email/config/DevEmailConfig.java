package com.sathyabama.email.config;

import org.springframework.boot.autoconfigure.condition.ConditionalOnProperty;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.context.annotation.Profile;
import org.springframework.mail.javamail.JavaMailSender;
import org.springframework.mail.javamail.JavaMailSenderImpl;

/**
 * Email configuration for development environment
 */
@Configuration
@Profile("dev")
public class DevEmailConfig {
    
    @Bean
    public JavaMailSender javaMailSender() {
        JavaMailSenderImpl mailSender = new JavaMailSenderImpl();
        
        // Mock SMTP settings for development
        mailSender.setHost("localhost");
        mailSender.setPort(2525);
        mailSender.setUsername("test@sathyabama.edu");
        mailSender.setPassword("testpassword");
        
        // Disable authentication for development
        mailSender.getJavaMailProperties().put("mail.smtp.auth", "false");
        mailSender.getJavaMailProperties().put("mail.smtp.starttls.enable", "false");
        mailSender.getJavaMailProperties().put("mail.debug", "true");
        
        return mailSender;
    }
}