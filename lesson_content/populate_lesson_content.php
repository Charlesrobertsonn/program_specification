<?php
require '../db.php'; // Ensure this file connects to the database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lesson_id = intval($_POST['lesson_id']);
    $template_id = intval($_POST['template_id']);

    if (!$lesson_id || !$template_id) {
        die("Invalid input. Lesson ID and Template ID are required.");
    }

    // Template ID to content mapping
    $template_content = [
        1 => [ // Sound Deck
            ['content_key' => 'title', 'content_value' => 'Sound Deck'],
            ['content_key' => 'instructions', 'content_value' => 'Listen to the sounds and repeat'],
            ['content_key' => 'video_url', 'content_value' => 'sound_deck_video.mp4'],
            ['content_key' => 'image_url', 'content_value' => 'sound_deck_image.png']
        ],
        2 => [ // Phonetic Focus
            ['content_key' => 'title', 'content_value' => 'Phonetic Focus'],
            ['content_key' => 'instructions', 'content_value' => 'Learn the phonetic sounds'],
            ['content_key' => 'video_url', 'content_value' => 'phonetic_focus_video.mp4'],
            ['content_key' => 'image_url', 'content_value' => 'phonetic_focus_image.png']
        ],
        3 => [ // Key Focus Revision
            ['content_key' => 'title', 'content_value' => 'Key Focus Revision'],
            ['content_key' => 'instructions', 'content_value' => 'Review words and practice'],
            ['content_key' => 'image_url', 'content_value' => 'key_focus_image.png']
        ],
        4 => [ // Syllabication
            ['content_key' => 'title', 'content_value' => 'Syllabication'],
            ['content_key' => 'instructions', 'content_value' => 'Divide words into syllables'],
            ['content_key' => 'video_url', 'content_value' => 'syllabication_video.mp4']
        ],
        5 => [ // Read Spell Focus
            ['content_key' => 'title', 'content_value' => 'Read Spell Focus'],
            ['content_key' => 'instructions', 'content_value' => 'Listen and spell the words'],
            ['content_key' => 'image_url', 'content_value' => 'read_spell_focus_image.png']
        ],
        6 => [ // Read Rally
            ['content_key' => 'title', 'content_value' => 'Read Rally'],
            ['content_key' => 'instructions', 'content_value' => 'Timed reading practice'],
            ['content_key' => 'video_url', 'content_value' => 'read_rally_video.mp4']
        ],
        7 => [ // Spell Bound
            ['content_key' => 'title', 'content_value' => 'Spell Bound'],
            ['content_key' => 'instructions', 'content_value' => 'Complete the sentences'],
            ['content_key' => 'image_url', 'content_value' => 'spell_bound_image.png']
        ],
        8 => [ // Comic
            ['content_key' => 'title', 'content_value' => 'Interactive Comic'],
            ['content_key' => 'instructions', 'content_value' => 'Read the comic and answer questions'],
            ['content_key' => 'image_url', 'content_value' => 'comic_image.png']
        ]
    ];

    if (!isset($template_content[$template_id])) {
        die("Invalid Template ID.");
    }

    // Prepare and execute database insertion
    $stmt = $conn->prepare("INSERT INTO lesson_content (lesson_id, template_id, content_key, content_value) VALUES (?, ?, ?, ?)");

    foreach ($template_content[$template_id] as $content) {
        $stmt->bind_param("iiss", $lesson_id, $template_id, $content['content_key'], $content['content_value']);
        $stmt->execute();
    }

    echo "Lesson content populated successfully!";
}
?>