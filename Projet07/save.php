<?php
header('Content-Type: application/json');

$file = __DIR__ . '/results.json';
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON payload']);
    exit;
}

if (!file_exists($file)) {
    file_put_contents($file, json_encode([], JSON_PRETTY_PRINT));
}

$existing = json_decode(file_get_contents($file), true);
if (!is_array($existing)) {
    $existing = [];
}

$existing[] = [
    'winner' => $data['winner'] ?? 'unknown',
    'playedAt' => $data['playedAt'] ?? date('c'),
    'board' => $data['board'] ?? []
];

file_put_contents($file, json_encode($existing, JSON_PRETTY_PRINT));

echo json_encode(['ok' => true, 'saved' => count($existing)]);
