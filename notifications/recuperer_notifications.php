<?php

session_start();
require_once(__DIR__.'/../bdd/creation_bdd.php');
$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT message, created_at FROM notifications WHERE user_id = ? AND is_read = ".($type == 'tous' ? '1 OR is_read = 0' : ($type == 'lues' ? 1 : 0))." ORDER BY created_at DESC");
$stmt->execute([$userId]);
echo json_encode(['notifications' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);