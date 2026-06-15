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

$playerCount = (int) ($_POST['player_count'] ?? 0);

if ($playerCount < 1 || $playerCount > 8) {
    http_response_code(422);
    echo json_encode([
        'ok' => false,
        'message' => 'A valid player count is required.',
    ]);
    exit;
}

$suits = [
    ['symbol' => '♠', 'color' => 'black'],
    ['symbol' => '♥', 'color' => 'red'],
    ['symbol' => '♦', 'color' => 'red'],
    ['symbol' => '♣', 'color' => 'black'],
];
$ranks = ['A', 'K', 'Q', 'J', '10', '9', '8', '7', '6', '5', '4', '3', '2'];
$deck = [];

foreach ($suits as $suit) {
    foreach ($ranks as $rank) {
        $deck[] = [
            'rank' => $rank,
            'suit' => $suit['symbol'],
            'color' => $suit['color'],
        ];
    }
}

shuffle($deck);
$deals = [];

for ($i = 0; $i < $playerCount; $i++) {
    $deals[] = [array_pop($deck), array_pop($deck)];
}

$flop = [array_pop($deck), array_pop($deck), array_pop($deck)];
$turn = array_pop($deck);
$river = array_pop($deck);

echo json_encode([
    'ok' => true,
    'deals' => $deals,
    'flop' => $flop,
    'turn' => $turn,
    'river' => $river
]);
