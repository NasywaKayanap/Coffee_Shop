<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

header('Content-Type: application/json');

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Get all products
        $query = "SELECT * FROM products";
        $result = getConnection()->query($query);
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        break;
        
    case 'POST':
        // Add new product
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        // Implementation
        break;
}