<?php
// Check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Check if admin has specific permission
function hasPermission($permission_name) {
    global $conn;
    
    if (!isset($_SESSION['admin_id'])) {
        return false;
    }
    
    $stmt = $conn->prepare("SELECT permission_value FROM admin_permissions WHERE admin_id = ? AND permission_name = ?");
    $stmt->bind_param('is', $_SESSION['admin_id'], $permission_name);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return (bool)$row['permission_value'];
    }
    
    return false;
}

// Log admin activity
function logAdminActivity($action, $details = '') {
    global $conn;
    
    if (!isset($_SESSION['admin_id'])) {
        return false;
    }
    
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $stmt = $conn->prepare("INSERT INTO admin_logs (admin_id, action, details, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param('isss', $_SESSION['admin_id'], $action, $details, $ip_address);
    
    return $stmt->execute();
}

// Get admin details
function getAdminDetails($admin_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT admin_id, admin_name, admin_email, created_at, last_login, is_active FROM admins WHERE admin_id = ?");
    $stmt->bind_param('i', $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    
    return null;
}

// Update admin last login
function updateLastLogin($admin_id) {
    global $conn;
    
    $stmt = $conn->prepare("UPDATE admins SET last_login = NOW() WHERE admin_id = ?");
    $stmt->bind_param('i', $admin_id);
    
    return $stmt->execute();
}

// Generate CSRF token
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Redirect with message
function redirectWithMessage($url, $type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
    header("Location: $url");
    exit();
}

// Get flash message
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

// Check password strength
function isPasswordStrong($password) {
    // At least 8 characters
    if (strlen($password) < 8) {
        return false;
    }
    
    // At least one uppercase letter
    if (!preg_match('/[A-Z]/', $password)) {
        return false;
    }
    
    // At least one lowercase letter
    if (!preg_match('/[a-z]/', $password)) {
        return false;
    }
    
    // At least one number
    if (!preg_match('/[0-9]/', $password)) {
        return false;
    }
    
    // At least one special character
    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        return false;
    }
    
    return true;
} 