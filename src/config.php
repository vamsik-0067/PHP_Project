<?php
declare(strict_types=1);

session_start();

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $host = getenv('DB_HOST') ?: 'db';
        $name = getenv('DB_NAME') ?: 'devtrack';
        $user = getenv('DB_USER') ?: 'devtrack';
        $pass = getenv('DB_PASSWORD') ?: 'devtrack_pass';
        $pdo = new PDO(
            "mysql:host={$host};dbname={$name};charset=utf8mb4",
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }
    return $pdo;
}

function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function logged_in(): bool {
    return isset($_SESSION['user']);
}

function require_login(): void {
    if (!logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function user(): array {
    return $_SESSION['user'] ?? [];
}

function redirect(string $url): never {
    header("Location: {$url}");
    exit;
}
