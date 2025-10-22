<?php
/**
 * Email Configuration and Sending Functions
 * This file handles email sending for event registration confirmations
 */

class EmailService {
    private $smtp_host = 'localhost';
    private $smtp_port = 587;
    private $smtp_username = '';
    private $smtp_password = '';
    private $from_email = 'events@sathyabama.ac.in';
    private $from_name = 'Sathyabama Event Management System';

    /**
     * Send email using PHP's mail() function
     * For production, consider using PHPMailer with proper SMTP configuration
     */
    public function sendEmail($to_email, $to_name, $subject, $html_body, $plain_body = '') {
        // For development/testing - log email instead of sending
        if ($this->isTestMode()) {
            return $this->logEmailInsteadOfSending($to_email, $subject, $html_body);
        }
        
        // Set email headers
        $headers = array();
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'From: ' . $this->from_name . ' <' . $this->from_email . '>';
        $headers[] = 'Reply-To: ' . $this->from_email;
        $headers[] = 'Return-Path: ' . $this->from_email;
        $headers[] = 'X-Mailer: PHP/' . phpversion();

        // Send email
        $success = mail($to_email, $subject, $html_body, implode("\r\n", $headers));
        
        // Log email attempt (useful for debugging)
        $this->logEmail($to_email, $subject, $success);
        
        return $success;
    }

    /**
     * Check if we're in test mode (no proper mail server configured)
     */
    private function isTestMode() {
        // Check if sendmail is available and working
        $sendmail_path = ini_get('sendmail_path');
        return empty($sendmail_path) || $sendmail_path === '/usr/sbin/sendmail -t -i';
    }

    /**
     * For development - save email content to file instead of sending
     */
    private function logEmailInsteadOfSending($to_email, $subject, $html_body) {
        $timestamp = date('Y-m-d_H-i-s');
        $filename = "logs/email_{$timestamp}_" . preg_replace('/[^a-zA-Z0-9]/', '_', $to_email) . ".html";
        
        // Create logs directory if it doesn't exist
        if (!file_exists('logs')) {
            mkdir('logs', 0755, true);
        }
        
        $email_content = "
<!DOCTYPE html>
<html>
<head>
    <title>Email Preview - {$subject}</title>
    <style>
        .email-preview-header { 
            background: #f0f0f0; 
            padding: 20px; 
            border-bottom: 2px solid #831339; 
            margin-bottom: 20px;
        }
        .email-preview-info { 
            background: #e8f4ff; 
            padding: 15px; 
            border-radius: 8px; 
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class='email-preview-header'>
        <h1>📧 Email Preview</h1>
        <p><strong>This email would have been sent to:</strong> {$to_email}</p>
        <p><strong>Subject:</strong> {$subject}</p>
        <p><strong>Generated at:</strong> " . date('Y-m-d H:i:s') . "</p>
    </div>
    
    <div class='email-preview-info'>
        <p><strong>📝 Note:</strong> This is a preview of the email that would be sent. The actual email content is shown below.</p>
        <p><strong>💡 To enable real email sending:</strong> Configure SMTP settings in email_config.php or set up a mail server.</p>
    </div>
    
    <hr>
    
    {$html_body}
</body>
</html>";
        
        $success = file_put_contents($filename, $email_content);
        
        // Log the preview creation
        $this->logEmail($to_email, $subject, $success ? 'PREVIEW_CREATED' : 'PREVIEW_FAILED');
        
        return $success !== false;
    }

    /**
     * Generate event registration confirmation email HTML
     */
    public function generateRegistrationEmail($user_name, $event_name, $event_date, $event_description, $event_image = '') {
        $formatted_date = date('F j, Y', strtotime($event_date));
        
        $html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #831339, #a81746); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #ffffff; padding: 30px; border: 1px solid #ddd; }
        .event-details { background: #f9f9f9; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .footer { background: #f5f5f5; padding: 20px; text-align: center; border-radius: 0 0 8px 8px; color: #666; }
        .button { display: inline-block; padding: 12px 24px; background: #831339; color: white; text-decoration: none; border-radius: 6px; margin: 10px 0; }
        .logo { max-width: 150px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Registration Confirmed!</h1>
            <p>Welcome to ' . htmlspecialchars($event_name) . '</p>
        </div>
        
        <div class="content">
            <h2>Dear ' . htmlspecialchars($user_name) . ',</h2>
            
            <p>Congratulations! You have successfully registered for the following event:</p>
            
            <div class="event-details">
                <h3 style="color: #831339; margin-top: 0;">' . htmlspecialchars($event_name) . '</h3>
                <p><strong>📅 Date:</strong> ' . $formatted_date . '</p>
                <p><strong>📝 Description:</strong></p>
                <p>' . nl2br(htmlspecialchars($event_description)) . '</p>
            </div>
            
            <h3>What\'s Next?</h3>
            <ul>
                <li>Mark your calendar for ' . $formatted_date . '</li>
                <li>Keep this email as your registration confirmation</li>
                <li>Watch for additional event updates and instructions</li>
                <li>Contact event coordinators if you have any questions</li>
            </ul>
            
            <p><strong>Important:</strong> Please bring a valid ID for event check-in.</p>
            
            <p>We look forward to seeing you at the event!</p>
            
            <p>Best regards,<br>
            <strong>Sathyabama Event Management Team</strong></p>
        </div>
        
        <div class="footer">
            <p>This is an automated confirmation email. Please do not reply to this message.</p>
            <p>For questions, contact the event coordinators through the student portal.</p>
            <p>&copy; ' . date('Y') . ' Sathyabama Institute of Science and Technology</p>
        </div>
    </div>
</body>
</html>';
        
        return $html;
    }

    /**
     * Log email sending attempts for debugging
     */
    private function logEmail($to_email, $subject, $success) {
        if ($success === 'PREVIEW_CREATED') {
            $status = 'PREVIEW_CREATED';
        } elseif ($success === 'PREVIEW_FAILED') {
            $status = 'PREVIEW_FAILED';
        } else {
            $status = $success ? 'SUCCESS' : 'FAILED';
        }
        
        $log_entry = date('Y-m-d H:i:s') . " - Email to: $to_email - Subject: $subject - $status\n";
        
        // Create logs directory if it doesn't exist
        if (!file_exists('logs')) {
            mkdir('logs', 0755, true);
        }
        
        // Write to log file
        file_put_contents('logs/email_log.txt', $log_entry, FILE_APPEND | LOCK_EX);
    }

    /**
     * Send registration confirmation email
     */
    public function sendRegistrationConfirmation($user_email, $user_name, $event_name, $event_date, $event_description, $event_image = '') {
        $subject = "Registration Confirmed: " . $event_name;
        $html_body = $this->generateRegistrationEmail($user_name, $event_name, $event_date, $event_description, $event_image);
        
        return $this->sendEmail($user_email, $user_name, $subject, $html_body);
    }
}

// Function to get user's full name from database
function getUserFullName($roll_no, $conn) {
    try {
        $stmt = $conn->prepare("SELECT full_name FROM users WHERE roll_no = ? LIMIT 1");
        $stmt->bind_param("s", $roll_no);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return $user['full_name'] ?: 'Student';
        }
        
        return 'Student';
    } catch (Exception $e) {
        return 'Student';
    }
}
?>