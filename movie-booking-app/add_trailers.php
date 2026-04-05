<?php
require_once 'config/db.php';
$conn = getDBConnection();

try {
    // Add trailer_url column if it doesn't exist
    $stmt = $conn->query("SHOW COLUMNS FROM movies LIKE 'trailer_url'");
    if ($stmt->rowCount() == 0) {
        $conn->exec("ALTER TABLE movies ADD COLUMN trailer_url VARCHAR(500) NULL AFTER backdrop_url");
        echo "Column 'trailer_url' added successfully.\n";
    }

    // Update trailer URLs (using embed format)
    $trailers = [
        'Leo' => 'https://www.youtube.com/embed/Po3jStA673E',
        'Jailer' => 'https://www.youtube.com/embed/Y5BeWdODPqo',
        'Vikram' => 'https://www.youtube.com/embed/OKBMCL-frPU',
        'Dune: Part Two' => 'https://www.youtube.com/embed/Way9Dexny3w',
        'Oppenheimer' => 'https://www.youtube.com/embed/bK6ldnjE3Y0',
        'The Batman' => 'https://www.youtube.com/embed/mqqft2x_Aa4'
    ];

    foreach ($trailers as $title => $url) {
        $stmt = $conn->prepare("UPDATE movies SET trailer_url = ? WHERE title LIKE ?");
        $stmt->execute([$url, "%$title%"]);
        echo "Updated trailer for $title.\n";
    }

    echo "Migration complete!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
