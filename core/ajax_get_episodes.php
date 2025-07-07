<?php
//
// ::::    ::::    <y_bin_551>    :::::::::       :::       :::::::::
// +:+:+: :+:+:+  :+:    :+:  :+:    :+:    :+: :+:     :+:    :+:
// +:+ +:+:+ +:+  +:+         +:+    +:+   +:+   +:+    +:+    +:+
// +#+  +:+  +#+  +#+         +#+    +:+  +#++:++#++:   +#++:++#+
// +#+       +#+  +#+         +#+    +#+  +#+     +#+   +#+    +#+
// #+#       #+#  #+#    #+#  #+#    #+#  #+#     #+#   #+#    #+#
// ###       ###   ########   #########   ###     ###   #########
//
// File Name:   ajax_get_episodes.php
// File Purpose: Fetches and returns season and episode data for a show as JSON.
// Last Change:  June 24, 2025
//

// -- Initialization and Security --
header('Content-Type: application/json');
require_once(realpath(__DIR__ . '/config.php'));

$showId = (int)($_GET['show_id'] ?? 0);
if ($showId === 0) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid show ID']);
    exit();
}
// -- End of Initialization --


// -- Database Fetch and JSON Output --
try {
    // Step 1: Fetch all seasons for the show
    $seasonStmt = $pdo->prepare("SELECT season_number, name, poster_path FROM seasons WHERE show_id = ? ORDER BY season_number ASC");
    $seasonStmt->execute([$showId]);
    $seasonsData = $seasonStmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);

    // Step 2: Fetch all episodes for the show
    $episodeStmt = $pdo->prepare("SELECT id, season_number, episode_number, title FROM episodes WHERE show_id = ? ORDER BY season_number ASC, episode_number ASC");
    $episodeStmt->execute([$showId]);
    $episodesData = $episodeStmt->fetchAll(PDO::FETCH_ASSOC);

    // Step 3: Combine them into a structured response
    $response = [];
    foreach ($seasonsData as $seasonNum => $seasonInfo) {
        $response[$seasonNum] = [
            'name' => $seasonInfo[0]['name'], // Get name from the first (and only) row for that season
            'poster_path' => $seasonInfo[0]['poster_path'],
            'episodes' => []
        ];
    }

    foreach ($episodesData as $episode) {
        // If a season for this episode exists in our structure, add the episode to it
        if (isset($response[$episode['season_number']])) {
            $response[$episode['season_number']]['episodes'][] = $episode;
        }
    }

    echo json_encode($response);

} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    // In a real app, log the error instead of echoing it
    echo json_encode(['error' => 'Database query failed.']);
}
// -- End of Database Fetch --

?>
