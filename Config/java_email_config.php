<?php
/**
 * Java Email Service Client for PHP
 * This class communicates with the Java email service via HTTP API calls
 */

class JavaEmailService {
    private $java_service_url = 'http://localhost:8081/email-service/api/email';
    private $timeout = 30; // seconds

    /**
     * Send registration confirmation email via Java service
     */
    public function sendRegistrationConfirmation($user_email, $user_name, $event_name, $event_date, $event_description, $event_image = '') {
        try {
            // Prepare email data
            $emailData = array(
                'to_email' => $user_email,
                'user_name' => $user_name,
                'event_name' => $event_name,
                'event_date' => $event_date,
                'event_description' => $event_description,
                'event_image' => $event_image
            );

            // Make HTTP POST request to Java service
            $response = $this->makeHttpRequest('/send-registration-confirmation', $emailData);
            
            if ($response && isset($response['success'])) {
                $this->logEmail($user_email, "Registration Confirmed: " . $event_name, $response['success'] ? 'SUCCESS' : 'FAILED');
                return $response['success'];
            } else {
                $this->logEmail($user_email, "Registration Confirmed: " . $event_name, 'API_ERROR');
                return false;
            }
            
        } catch (Exception $e) {
            error_log("Java Email Service Error: " . $e->getMessage());
            $this->logEmail($user_email, "Registration Confirmed: " . $event_name, 'EXCEPTION: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Test the Java email service
     */
    public function testEmailService($test_email = 'test@example.com') {
        try {
            $response = $this->makeHttpRequest('/test?email=' . urlencode($test_email), null, 'POST');
            return $response;
        } catch (Exception $e) {
            error_log("Java Email Service Test Error: " . $e->getMessage());
            return array('success' => false, 'message' => $e->getMessage());
        }
    }

    /**
     * Check if Java email service is running
     */
    public function checkHealth() {
        try {
            $response = $this->makeHttpRequest('/health', null, 'GET');
            return $response && isset($response['success']) && $response['success'];
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Make HTTP request to Java service
     */
    private function makeHttpRequest($endpoint, $data = null, $method = 'POST') {
        $url = $this->java_service_url . $endpoint;
        
        // Initialize cURL
        $ch = curl_init();
        
        // Set cURL options
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json'
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ));
        
        // Add POST data if provided
        if ($data !== null && $method === 'POST') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        // Execute request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        // Check for cURL errors
        if ($error) {
            throw new Exception("cURL Error: " . $error);
        }
        
        // Check HTTP response code
        if ($httpCode !== 200) {
            throw new Exception("HTTP Error: " . $httpCode . " - " . $response);
        }
        
        // Decode JSON response
        $decodedResponse = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON Decode Error: " . json_last_error_msg());
        }
        
        return $decodedResponse;
    }

    /**
     * Send event registration confirmation email
     */
    public function sendEventRegistrationEmail($eventData) {
        try {
            $emailData = array(
                'to' => $eventData['user_email'],
                'subject' => 'Event Registration Confirmation - ' . $eventData['event_name'],
                'recipientName' => $eventData['user_name'],
                'eventName' => $eventData['event_name'],
                'eventDate' => $eventData['event_date'],
                'venue' => $eventData['venue'],
                'registrationId' => $eventData['registration_id']
            );
            
            $response = $this->makeHttpRequest('/send-registration', $emailData);
            
            // Log the attempt
            $status = ($response && $response['success']) ? 'SUCCESS' : 'FAILED';
            $this->logEmail($eventData['user_email'], 'Event Registration - ' . $eventData['event_name'], $status);
            
            return $response;
        } catch (Exception $e) {
            error_log("Java Email Service Registration Error: " . $e->getMessage());
            $this->logEmail($eventData['user_email'], 'Event Registration - ' . $eventData['event_name'], 'ERROR: ' . $e->getMessage());
            return array('success' => false, 'message' => $e->getMessage());
        }
    }

    /**
     * Log email attempts
     */
    private function logEmail($to_email, $subject, $status) {
        $log_entry = date('Y-m-d H:i:s') . " - [JAVA-SERVICE] Email to: $to_email - Subject: $subject - $status\n";
        
        // Create logs directory if it doesn't exist
        if (!file_exists('logs')) {
            mkdir('logs', 0755, true);
        }
        
        // Write to log file
        file_put_contents('logs/email_log.txt', $log_entry, FILE_APPEND | LOCK_EX);
    }

    /**
     * Get the service URL for debugging
     */
    public function getServiceUrl() {
        return $this->java_service_url;
    }
}

// Function to get user's full name from database (for compatibility)
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