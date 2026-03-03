<?php

/**
 * VaaGa Academy - OTP SMS & Email Diagnostic Script
 * 
 * This script tests MSG91 SMS API connectivity and Brevo email SMTP configuration.
 * 
 * Usage:
 *   Command Line: php scripts/test_otp_email.php
 *   Browser: Copy to public folder and access via URL
 */

// Bootstrap Laravel
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Detect if running in browser or CLI
$isCli = php_sapi_name() === 'cli';
$lineBreak = $isCli ? "\n" : "<br>";
$headingStart = $isCli ? "" : "<h2>";
$headingEnd = $isCli ? "" : "</h2>";
$boldStart = $isCli ? "" : "<strong>";
$boldEnd = $isCli ? "" : "</strong>";
$preStart = $isCli ? "" : "<pre>";
$preEnd = $isCli ? "" : "</pre>";

if (!$isCli) {
    header('Content-Type: text/html; charset=utf-8');
    echo "<!DOCTYPE html><html><head><title>OTP & Email Diagnostic</title></head><body>";
    echo "<h1>🔧 VaaGa Academy OTP & Email Diagnostic</h1><hr>";
} else {
    echo "==========================================\n";
    echo "🔧 VaaGa Academy OTP & Email Diagnostic\n";
    echo "==========================================\n\n";
}

$errors = [];
$warnings = [];
$success = [];

/**
 * Helper function to mask sensitive strings
 */
function maskString($string, $visibleStart = 4, $visibleEnd = 4) {
    if (empty($string)) return 'NOT SET';
    $length = strlen($string);
    if ($length <= $visibleStart + $visibleEnd) return $string;
    return substr($string, 0, $visibleStart) . str_repeat('*', $length - $visibleStart - $visibleEnd) . substr($string, -$visibleEnd);
}

/**
 * Helper to output results
 */
function output($message, $type = 'info') {
    global $isCli, $lineBreak;
    $prefix = '';
    if ($type === 'success') $prefix = $isCli ? "✓ " : "✅ ";
    elseif ($type === 'error') $prefix = $isCli ? "✗ " : "❌ ";
    elseif ($type === 'warning') $prefix = $isCli ? "⚠ " : "⚠️ ";
    elseif ($type === 'info') $prefix = $isCli ? "ℹ " : "ℹ️ ";
    echo $prefix . $message . $lineBreak;
}

// ============================================================================
// SECTION 1: Environment Check
// ============================================================================
echo $headingStart . "1. Environment Check" . $headingEnd . $lineBreak;

echo $boldStart . "PHP Version: " . $boldEnd . phpversion() . $lineBreak;
echo $boldStart . "Laravel Version: " . $boldEnd . \Illuminate\Foundation\Application::VERSION . $lineBreak;
echo $boldStart . "Environment: " . $boldEnd . config('app.env') . $lineBreak;
echo $boldStart . "App URL: " . $boldEnd . config('app.url') . $lineBreak;

// Check required PHP extensions
$requiredExtensions = ['curl', 'openssl', 'mbstring', 'json'];
$missingExtensions = [];
foreach ($requiredExtensions as $ext) {
    if (!extension_loaded($ext)) {
        $missingExtensions[] = $ext;
    }
}

if (empty($missingExtensions)) {
    output("All required PHP extensions are installed: " . implode(', ', $requiredExtensions), 'success');
    $success[] = "PHP Extensions";
} else {
    output("Missing PHP extensions: " . implode(', ', $missingExtensions), 'error');
    $errors[] = "Missing PHP extensions: " . implode(', ', $missingExtensions);
}

// Check if running from browser (security warning)
if (!$isCli) {
    output("WARNING: Running from browser may expose sensitive information!", 'warning');
    $warnings[] = "Running from browser";
}

echo $lineBreak;

// ============================================================================
// SECTION 2: MSG91 SMS Configuration Test
// ============================================================================
echo $headingStart . "2. MSG91 SMS Configuration Test" . $headingEnd . $lineBreak;

// Get MSG91 configuration
$msg91Config = config('services.msg91');
$msg91Enabled = $msg91Config['enabled'] ?? false;
$msg91AuthKey = $msg91Config['authkey'] ?? null;
$msg91TemplateId = $msg91Config['template_id'] ?? null;
$msg91SenderId = $msg91Config['sender_id'] ?? null;
$msg91ApiUrl = $msg91Config['api_url'] ?? 'https://control.msg91.com/api/v5/flow';

echo $boldStart . "MSG91 Enabled: " . $boldEnd . ($msg91Enabled ? 'Yes' : 'No') . $lineBreak;
echo $boldStart . "Auth Key: " . $boldEnd . maskString($msg91AuthKey) . $lineBreak;
echo $boldStart . "Template ID: " . $boldEnd . maskString($msg91TemplateId, 6, 6) . $lineBreak;
echo $boldStart . "Sender ID: " . $boldEnd . ($msg91SenderId ?: 'NOT SET') . $lineBreak;
echo $boldStart . "API URL: " . $boldEnd . $msg91ApiUrl . $lineBreak;

// Check if MSG91 is properly configured
if (!$msg91Enabled) {
    output("MSG91 is disabled in configuration", 'warning');
    $warnings[] = "MSG91 is disabled";
} elseif (empty($msg91AuthKey) || $msg91AuthKey === '401284ApGWjkfa66b6263aP1') {
    // Check if using default/demo key
    if ($msg91AuthKey === '401284ApGWjkfa66b6263aP1') {
        output("Using default MSG91 Auth Key - should be changed in production!", 'warning');
        $warnings[] = "Using default MSG91 Auth Key";
    } else {
        output("MSG91 Auth Key is not configured", 'error');
        $errors[] = "MSG91 Auth Key not configured";
    }
} else {
    output("MSG91 Auth Key is configured", 'success');
}

// Test MSG91 API Connectivity (without actually sending SMS)
echo $lineBreak . $boldStart . "Testing MSG91 API Connectivity..." . $boldEnd . $lineBreak;

try {
    $ch = curl_init();
    
    // Set up a minimal test request (we'll check balance/health endpoint)
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://control.msg91.com/api/v5/balance',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => [
            'accept: application/json',
            'authkey: ' . $msg91AuthKey,
        ],
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    if ($curlError) {
        output("CURL Error: " . $curlError, 'error');
        $errors[] = "MSG91 CURL Error: " . $curlError;
    } else {
        $responseData = json_decode($response, true);
        
        if ($httpCode === 200) {
            output("MSG91 API connection successful (HTTP 200)", 'success');
            $success[] = "MSG91 API Connection";
            
            if (isset($responseData['status'])) {
                echo "  Status: " . $responseData['status'] . $lineBreak;
            }
        } elseif ($httpCode === 401) {
            output("MSG91 API returned 401 - Authentication failed. Check your Auth Key.", 'error');
            $errors[] = "MSG91 Authentication failed";
        } elseif ($httpCode === 403) {
            output("MSG91 API returned 403 - Access forbidden. Check your account status.", 'error');
            $errors[] = "MSG91 Access forbidden";
        } elseif ($httpCode === 0) {
            output("Could not connect to MSG91 API. Check your internet connection.", 'error');
            $errors[] = "MSG91 API unreachable";
        } else {
            output("MSG91 API returned HTTP " . $httpCode, 'warning');
            $warnings[] = "MSG91 API HTTP " . $httpCode;
        }
        
        if (!empty($responseData)) {
            echo $preStart . "Response: " . json_encode($responseData, JSON_PRETTY_PRINT) . $preEnd . $lineBreak;
        }
    }
} catch (Exception $e) {
    output("Exception testing MSG91: " . $e->getMessage(), 'error');
    $errors[] = "MSG91 Exception: " . $e->getMessage();
}

// Test SMS Template Configuration
echo $lineBreak . $boldStart . "Checking SMS Template Configuration..." . $boldEnd . $lineBreak;
if (empty($msg91TemplateId)) {
    output("MSG91 Template ID is not configured", 'error');
    $errors[] = "MSG91 Template ID not configured";
} else {
    output("Template ID is configured: " . maskString($msg91TemplateId, 6, 6), 'success');
}

if (empty($msg91SenderId)) {
    output("MSG91 Sender ID is not configured (using default)", 'warning');
    $warnings[] = "MSG91 Sender ID not configured";
} else {
    output("Sender ID is configured: " . $msg91SenderId, 'success');
}

echo $lineBreak;

// ============================================================================
// SECTION 3: Email Configuration Test
// ============================================================================
echo $headingStart . "3. Email (Brevo/SMTP) Configuration Test" . $headingEnd . $lineBreak;

// Get mail configuration
$mailMailer = config('mail.default');
$mailConfig = config('mail.mailers.' . $mailMailer);
$fromAddress = config('mail.from.address');
$fromName = config('mail.from.name');

echo $boldStart . "Mail Driver: " . $boldEnd . $mailMailer . $lineBreak;
echo $boldStart . "From Address: " . $boldEnd . ($fromAddress ?: 'NOT SET') . $lineBreak;
echo $boldStart . "From Name: " . $boldEnd . ($fromName ?: 'NOT SET') . $lineBreak;

// Check SMTP configuration if using SMTP
if ($mailMailer === 'smtp') {
    echo $lineBreak . $boldStart . "SMTP Configuration:" . $boldEnd . $lineBreak;
    
    $smtpHost = $mailConfig['host'] ?? 'NOT SET';
    $smtpPort = $mailConfig['port'] ?? 'NOT SET';
    $smtpEncryption = $mailConfig['encryption'] ?? 'NOT SET';
    $smtpUsername = $mailConfig['username'] ?? null;
    $smtpPassword = $mailConfig['password'] ?? null;
    
    echo "  Host: " . $smtpHost . $lineBreak;
    echo "  Port: " . $smtpPort . $lineBreak;
    echo "  Encryption: " . $smtpEncryption . $lineBreak;
    echo "  Username: " . maskString($smtpUsername) . $lineBreak;
    echo "  Password: " . (empty($smtpPassword) ? 'NOT SET' : '********') . $lineBreak;
    
    // Check for Brevo configuration
    $isBrevo = (strpos($smtpHost, 'brevo') !== false || strpos($smtpHost, 'sendinblue') !== false);
    if ($isBrevo) {
        output("Detected Brevo SMTP configuration", 'info');
    }
    
    // Validate SMTP settings
    if (empty($smtpHost) || $smtpHost === 'smtp.mailgun.org') {
        if ($smtpHost === 'smtp.mailgun.org') {
            output("Using default SMTP host (smtp.mailgun.org) - should be configured!", 'warning');
            $warnings[] = "Using default SMTP host";
        } else {
            output("SMTP host is not configured", 'error');
            $errors[] = "SMTP host not configured";
        }
    } else {
        output("SMTP host is configured", 'success');
    }
    
    if (empty($smtpUsername)) {
        output("SMTP username is not configured", 'error');
        $errors[] = "SMTP username not configured";
    } else {
        output("SMTP username is configured", 'success');
    }
    
    if (empty($smtpPassword)) {
        output("SMTP password is not configured", 'error');
        $errors[] = "SMTP password not configured";
    } else {
        output("SMTP password is configured", 'success');
    }
    
    // Test SMTP Connection
    echo $lineBreak . $boldStart . "Testing SMTP Connection..." . $boldEnd . $lineBreak;
    
    try {
        $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
            $smtpHost,
            $smtpPort,
            $smtpEncryption === 'tls'
        );
        
        if (!empty($smtpUsername)) {
            $transport->setUsername($smtpUsername);
        }
        if (!empty($smtpPassword)) {
            $transport->setPassword($smtpPassword);
        }
        
        $transport->setTimeout(10);
        
        // Try to establish connection
        $transport->start();
        
        if ($transport->isStarted()) {
            output("SMTP connection successful!", 'success');
            $success[] = "SMTP Connection";
            $transport->stop();
        } else {
            output("Could not establish SMTP connection", 'error');
            $errors[] = "SMTP connection failed";
        }
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
        if (strpos($errorMsg, 'Authentication failed') !== false) {
            output("SMTP Authentication failed - check username/password", 'error');
            $errors[] = "SMTP Authentication failed";
        } elseif (strpos($errorMsg, 'Connection refused') !== false || strpos($errorMsg, 'Connection timed out') !== false) {
            output("SMTP Connection failed - check host/port settings", 'error');
            $errors[] = "SMTP Connection failed";
        } else {
            output("SMTP Error: " . $errorMsg, 'error');
            $errors[] = "SMTP Error: " . $errorMsg;
        }
    }
} elseif ($mailMailer === 'log') {
    output("Email is set to 'log' driver - emails will be written to log files only", 'warning');
    $warnings[] = "Using log mail driver";
} elseif ($mailMailer === 'array') {
    output("Email is set to 'array' driver - emails will not be actually sent", 'warning');
    $warnings[] = "Using array mail driver";
} else {
    output("Using mail driver: " . $mailMailer, 'info');
}

// Check from address
if (empty($fromAddress) || $fromAddress === 'hello@example.com') {
    output("From address is not properly configured", 'warning');
    $warnings[] = "From address not configured";
} else {
    output("From address is configured: " . $fromAddress, 'success');
}

echo $lineBreak;

// ============================================================================
// SECTION 4: Additional Environment Variables Check
// ============================================================================
echo $headingStart . "4. Additional Environment Variables" . $headingEnd . $lineBreak;

$envChecks = [
    'MSG91_AUTHKEY' => env('MSG91_AUTHKEY'),
    'MSG91_OTP_TEMPLATE_ID' => env('MSG91_OTP_TEMPLATE_ID'),
    'MSG91_SENDER_ID' => env('MSG91_SENDER_ID'),
    'MSG91_ENABLED' => env('MSG91_ENABLED'),
    'MAIL_MAILER' => env('MAIL_MAILER'),
    'MAIL_HOST' => env('MAIL_HOST'),
    'MAIL_PORT' => env('MAIL_PORT'),
    'MAIL_USERNAME' => env('MAIL_USERNAME'),
    'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS'),
];

foreach ($envChecks as $key => $value) {
    $displayValue = ($value === null) ? 'null' : (($value === '') ? '(empty string)' : $value);
    
    // Mask sensitive values
    if (strpos($key, 'AUTHKEY') !== false || strpos($key, 'USERNAME') !== false || strpos($key, 'PASSWORD') !== false) {
        $displayValue = maskString($value);
    }
    
    echo $key . ": " . $displayValue . $lineBreak;
}

echo $lineBreak;

// ============================================================================
// SECTION 5: Summary
// ============================================================================
echo $headingStart . "5. Diagnostic Summary" . $headingEnd . $lineBreak;

echo $boldStart . "Successful Checks: " . $boldEnd . count($success) . $lineBreak;
if (!empty($success)) {
    foreach ($success as $s) {
        output($s, 'success');
    }
}

echo $lineBreak . $boldStart . "Warnings: " . $boldEnd . count($warnings) . $lineBreak;
if (!empty($warnings)) {
    foreach ($warnings as $w) {
        output($w, 'warning');
    }
} else {
    echo "None" . $lineBreak;
}

echo $lineBreak . $boldStart . "Errors: " . $boldEnd . count($errors) . $lineBreak;
if (!empty($errors)) {
    foreach ($errors as $e) {
        output($e, 'error');
    }
} else {
    echo "None" . $lineBreak;
}

// Overall status
echo $lineBreak . "==========================================" . $lineBreak;
if (empty($errors) && empty($warnings)) {
    output("✅ All checks passed! OTP SMS and Email are properly configured.", 'success');
    $exitCode = 0;
} elseif (empty($errors)) {
    output("⚠️ All critical checks passed with " . count($warnings) . " warning(s).", 'warning');
    $exitCode = 0;
} else {
    output("❌ " . count($errors) . " error(s) found. Please review and fix the issues above.", 'error');
    $exitCode = 1;
}
echo "==========================================" . $lineBreak;

// Recommendations
echo $lineBreak . $headingStart . "Recommendations:" . $headingEnd . $lineBreak;

if (in_array('MSG91 Authentication failed', $errors) || in_array('MSG91 Auth Key not configured', $errors)) {
    echo "• Update your .env file with a valid MSG91 Auth Key from https://msg91.com/" . $lineBreak;
}

if (in_array('SMTP Authentication failed', $errors) || in_array('SMTP username not configured', $errors)) {
    echo "• For Brevo SMTP: Get your SMTP credentials from https://app.brevo.com/settings/keys/smtp" . $lineBreak;
    echo "  - Username is typically your Brevo account email" . $lineBreak;
    echo "  - Password is the SMTP key (not your account password)" . $lineBreak;
}

if (in_array('MSG91 Template ID not configured', $errors) || in_array('MSG91 Sender ID not configured', $warnings)) {
    echo "• Create an SMS template in MSG91 dashboard and set MSG91_OTP_TEMPLATE_ID in .env" . $lineBreak;
    echo "• Set MSG91_SENDER_ID to your approved 6-character sender ID" . $lineBreak;
}

if ($mailMailer === 'log' || $mailMailer === 'array') {
    echo "• To actually send emails, change MAIL_MAILER to 'smtp' in your .env file" . $lineBreak;
}

if (!$isCli) {
    echo $lineBreak . "<hr><em>For security, remove this script from public access after testing.</em>";
    echo "</body></html>";
}

exit($exitCode);
