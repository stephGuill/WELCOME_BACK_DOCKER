<?php
// Force la r\u00e9ponse en JSON (Content-Type pour que le client sache parser la r\u00e9ponse)
header('Content-Type: application/json');

// Chemin absolu vers le fichier de stockage des r\u00e9sultats (m\u00eame dossier que ce script)
$file = __DIR__ . '/results.json';
// Lit le corps brut de la requ\u00eate HTTP POST (payload JSON envoy\u00e9 par le jeu)
$input = file_get_contents('php://input');
// D\u00e9code le JSON en tableau PHP associatif
$data = json_decode($input, true);

// Validation : si le d\u00e9codage \u00e9choue ou que la donn\u00e9e n'est pas un tableau, renvoie 400 Bad Request
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON payload']);
    exit;
}

// Si le fichier n'existe pas encore, on le cr\u00e9e avec un tableau vide
if (!file_exists($file)) {
    file_put_contents($file, json_encode([], JSON_PRETTY_PRINT));
}

// Lit les r\u00e9sultats existants depuis le fichier JSON
$existing = json_decode(file_get_contents($file), true);
// S\u00e9curit\u00e9 : si le fichier \u00e9tait corrompu ou vide, repart d'un tableau vide
if (!is_array($existing)) {
    $existing = [];
}

// Ajoute le nouveau r\u00e9sultat au tableau (null coalescing ?? pour les cl\u00e9s optionnelles)
$existing[] = [
    'winner' => $data['winner'] ?? 'unknown',   // gagnant : 'X', 'O' ou 'draw'
    'playedAt' => $data['playedAt'] ?? date('c'), // horodatage ISO 8601
    'board' => $data['board'] ?? []              // \u00e9tat final du plateau (9 cases)
];

// R\u00e9\u00e9crit le fichier JSON avec le nouveau r\u00e9sultat ajout\u00e9 (JSON_PRETTY_PRINT = lisible)
file_put_contents($file, json_encode($existing, JSON_PRETTY_PRINT));

// Renvoie une confirmation avec le nombre total de parties enregistr\u00e9es
echo json_encode(['ok' => true, 'saved' => count($existing)]);
