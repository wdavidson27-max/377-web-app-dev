<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/library.php';

start_app_session();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'ok' => false,
        'message' => 'Login required.',
    ]);
    exit;
}

$connection = get_connection();
$userId = (int) $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $winnings = max(0, (int) ($_POST['winnings'] ?? 0));
    $losses = max(0, (int) ($_POST['losses'] ?? 0));

    $statement = $connection->prepare(
        'UPDATE users SET total_winnings = total_winnings + ?, total_losses = total_losses + ? WHERE id = ?'
    );
    $statement->bind_param('iii', $winnings, $losses, $userId);
    $statement->execute();
}

$statement = $connection->prepare('SELECT total_winnings, total_losses FROM users WHERE id = ?');
$statement->bind_param('i', $userId);
$statement->execute();
$result = $statement->get_result();
$stats = $result->fetch_assoc();

echo json_encode([
    'ok' => true,
    'total_winnings' => (int) ($stats['total_winnings'] ?? 0),
    'total_losses' => (int) ($stats['total_losses'] ?? 0),
]);
