<?php
header("Content-Type: application/json");
include 'db.php';

// Allow only specific request methods
$method = $_SERVER['REQUEST_METHOD'];

// Read raw data from the request body and decode it as a JSON array
// This allows handling of JSON payloads sent via various HTTP methods (e.g., POST, PUT, DELETE)
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        handleGet();
        break;
    case 'POST':
        handlePost($input);
        break;
    case 'PUT':
        handlePut($input);
        break;
    case 'DELETE':
        handleDelete($input);
        break;
    default:
        http_response_code(405);
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

function handleGet()
{
    $db = Db::getInstance();
    $sql = "SELECT * FROM users";
    $result = $db->query($sql);

    if ($result) {
        echo json_encode($result);
    } else {
        // Set the HTTP response status code to 500 (Internal Server Error)
        // This indicates that an error occurred on the server side while processing the request
        http_response_code(500);
        $db->logError("Failed to retrieve users.");
        echo json_encode(['message' => 'An error occurred while retrieving users.']);
    }
}

function handlePost($input)
{
    if (empty($input['name']) || empty($input['email'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Name and email are required']);
        return;
    }

    $db = Db::getInstance();
    $sql = "INSERT INTO users (name, email) VALUES (?, ?)";
    $insertId = $db->insert($sql, "ss", [$input['name'], $input['email']]);

    if ($insertId) {
        http_response_code(201);
        echo json_encode(['message' => 'User created successfully', 'id' => $insertId]);
    } else {
        http_response_code(500);
        $db->logError("Failed to create user with data: " . json_encode($input));
        echo json_encode(['message' => 'Failed to create user']);
    }
}

function handlePut($input)
{
    if (empty($input['id']) || empty($input['name']) || empty($input['email'])) {
        http_response_code(400);
        echo json_encode(['message' => 'ID, name, and email are required']);
        return;
    }

    $db = Db::getInstance();
    $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $affectedRows = $db->modify($sql, "ssi", [$input['name'], $input['email'], $input['id']]);

    if ($affectedRows > 0) {
        echo json_encode(['message' => 'User updated successfully']);
    } elseif ($affectedRows === 0) {
        http_response_code(404);
        echo json_encode(['message' => 'No user was updated. User not found or data unchanged.']);
    } else {
        http_response_code(500);
        $db->logError("Failed to update user with ID: " . $input['id']);
        echo json_encode(['message' => 'Failed to update user']);
    }
}

function handleDelete($input)
{
    if (empty($input['id'])) {
        http_response_code(400);
        echo json_encode(['message' => 'ID is required']);
        return;
    }

    $db = Db::getInstance();
    $sql = "DELETE FROM users WHERE id = ?";
    $affectedRows = $db->modify($sql, "i", [$input['id']]);

    if ($affectedRows > 0) {
        echo json_encode(['message' => 'User deleted successfully']);
    } elseif ($affectedRows === 0) {
        http_response_code(404);
        echo json_encode(['message' => 'User not found.']);
    } else {
        http_response_code(500);
        $db->logError("Failed to delete user with ID: " . $input['id']);
        echo json_encode(['message' => 'Failed to delete user']);
    }
}
