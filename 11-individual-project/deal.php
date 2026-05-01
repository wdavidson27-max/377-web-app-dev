<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'ok' => false,
        'message' => 'POST required.',
    ]);
    exit;
}

$seatNumber = (int) ($_POST['seat_number'] ?? 0);

if ($seatNumber < 1 || $seatNumber > 8) {
    http_response_code(422);
    echo json_encode([
        'ok' => false,
        'message' => 'A valid seat is required.',
    ]);
    exit;
}

$deck = [];
$suits = ['Spades', 'Hearts', 'Diamonds', 'Clubs'];
$ranks = ['A', 'K', 'Q', 'J', '10', '9', '8', '7', '6', '5', '4', '3', '2'];

foreach ($suits as $suit) {
    foreach ($ranks as $rank) {
        $deck[] = $rank . ' of ' . $suit;
    }
}

shuffle($deck);
array_pop($deck);
array_pop($deck);

echo json_encode([
    'ok' => true,
    'seatNumber' => $seatNumber,
    'cardCount' => 2,
]);
