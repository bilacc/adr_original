<?php
declare(strict_types=1);

namespace App\Upload;

use Exception;

require_once('../../../lib/functions.php'); // Update path if using autoloader

class UploadHandler {
    private const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'application/pdf']; // Customize
    private const MAX_SIZE = 5 * 1024 * 1024; // 5MB
    private const STORE_FOLDER = '../../../upload_data/tmp'; // Ideally, move outside web root

    public function handleUpload(string $type): void {
        if (empty($_FILES[$type])) {
            throw new Exception('No file uploaded.');
        }

        $file = $_FILES[$type];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Upload error: ' . $file['error']);
        }

        if ($file['size'] > self::MAX_SIZE) {
            throw new Exception('File too large.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!in_array($mime, self::ALLOWED_TYPES, true)) {
            throw new Exception('Invalid file type.');
        }

        // Secure filename with UUID
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $safeName = uniqid('', true) . '.' . strtolower($ext);
        $targetPath = realpath(self::STORE_FOLDER) . DIRECTORY_SEPARATOR . $safeName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception('Failed to move file.');
        }

        // Store in session (or DB for better persistence)
        if (!isset($_SESSION[$type])) {
            $_SESSION[$type] = ['cnt' => 0];
        }
        $_SESSION[$type][$_SESSION[$type]['cnt']] = ['name' => $safeName, 'mime' => $mime, 'original_name' => $file['name']];
        $_SESSION[$type]['cnt']++;
    }
}

try {
    (new UploadHandler())->handleUpload(key($_FILES ?? []));
    // Optional: Echo success for AJAX
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    error_log($e->getMessage());
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}