<?php
require '../db.php'; // Ensure this file connects to the database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lesson_id = intval($_POST['lesson_id']);
    $template_id = intval($_POST['template_id']);

    if (!$lesson_id || !$template_id) {
        echo json_encode(["success" => false, "message" => "Invalid input. Lesson ID and Template ID are required."]);
        exit;
    }

    // Template-specific content keys
    $template_content = [
        1 => [ // Sound Deck
            ['content_key' => 'title', 'content_value' => 'Sound Deck'],
            ['content_key' => 'instructions', 'content_value' => 'Listen to my voice and sound them out!'],
            ['content_key' => 'letter', 'content_value' => 's'],
            ['content_key' => 'image_url', 'content_value' => 'images/s.png'],
            ['content_key' => 'audio_url', 'content_value' => 'audio/s.mp3']
        ],
        2 => [ // Key Focus Revision
            ['content_key' => 'title', 'content_value' => 'Spelling Practice'],
            ['content_key' => 'instructions', 'content_value' => 'Press play to hear the word, then type the correct spelling.'],
            ['content_key' => 'word_list', 'content_value' => json_encode(["bat", "mat", "fan", "rat"])],
            ['content_key' => 'audio_urls', 'content_value' => json_encode(["audio/bat.mp3", "audio/mat.mp3", "audio/fan.mp3", "audio/rat.mp3"])]
        ],
        3 => [ // Phonetic Focus
            ['content_key' => 'title', 'content_value' => 'Phonetic Focus: Short "a" Sound'],
            ['content_key' => 'instructions', 'content_value' => 'Learn the Short "a" Sound'],
            ['content_key' => 'video_url', 'content_value' => 'https://www.youtube.com/embed/your_video_id_here'],
            ['content_key' => 'words_to_read', 'content_value' => json_encode(["cat", "bat", "map", "van", "jam", "wax", "rat"])],
            ['content_key' => 'audio_urls', 'content_value' => json_encode(["audio/cat.mp3", "audio/bat.mp3", "audio/map.mp3"])]
        ],
        4 => [ // Syllabication
            ['content_key' => 'title', 'content_value' => 'Syllabication Focus - VC/CV Rule'],
            ['content_key' => 'instructions', 'content_value' => 'Learn how to divide words into syllables'],
            ['content_key' => 'video_url', 'content_value' => 'https://www.youtube.com/embed/your_video_id_here'],
            ['content_key' => 'words_to_syllabicate', 'content_value' => json_encode([
                ["word" => "Basket", "syllables" => ["Bas", "ket"]],
                ["word" => "Puppet", "syllables" => ["Pup", "pet"]]
            ])]
        ],
        5 => [ // Read Spell Focus
            ['content_key' => 'title', 'content_value' => 'Read-Spell-Focus'],
            ['content_key' => 'instructions', 'content_value' => 'Read each row three times. Click the word if you\'re stuck.'],
            ['content_key' => 'words', 'content_value' => json_encode(["cat", "bat", "rat", "hat", "pen", "ten"])],
            ['content_key' => 'cheat_sheet', 'content_value' => json_encode(["prefixes" => ["pre", "un", "re"], "sounds" => ["sh", "ch", "th"], "suffixes" => ["ing", "ed", "ly"]])]
        ],
        6 => [ // Read Rally
            ['content_key' => 'title', 'content_value' => 'Read Rally - CVC2 Reading Activity'],
            ['content_key' => 'instructions', 'content_value' => 'Practice reading aloud. Click on words and sentences to hear pronunciation.'],
            ['content_key' => 'words', 'content_value' => json_encode(["Dip", "Lip", "Sip", "Tip", "Hip", "Rip"])],
            ['content_key' => 'sentences', 'content_value' => json_encode(["Tim sips his drink.", "Max has a big bag."])],
            ['content_key' => 'timer', 'content_value' => 'true']
        ],
        7 => [ // Spell Bound
            ['content_key' => 'title', 'content_value' => 'Spell Bound - Spelling & Sentence Review'],
            ['content_key' => 'instructions', 'content_value' => 'Complete the passages and sentences correctly.'],
            ['content_key' => 'cloze_passages', 'content_value' => json_encode([
                ["sentence" => "The __ fox jumps over the lazy __.", "answers" => ["brown", "dog"]]
            ])],
            ['content_key' => 'word_bank', 'content_value' => json_encode(["brown", "dog", "red", "tree", "cat", "street"])]
        ],
        8 => [ // Comic
            ['content_key' => 'title', 'content_value' => 'Interactive Comic Book'],
            ['content_key' => 'comic_pages', 'content_value' => json_encode([
                ["page" => 1, "panels" => [["text" => "Tam has a cat.", "image" => "images/tam_cat.png"]]]
            ])],
            ['content_key' => 'quiz', 'content_value' => json_encode([
                "question" => "What did Sab do to the cat?",
                "options" => ["Fed the cat", "Chased the cat", "Petted the cat"],
                "answer" => "Petted the cat"
            ])]
        ]
    ];

    if (!isset($template_content[$template_id])) {
        die(json_encode(["success" => false, "message" => "Invalid Template ID."]));
    }

    $stmt = $conn->prepare("INSERT INTO lesson_content (lesson_id, template_id, content_key, content_value) VALUES (?, ?, ?, ?)");
    foreach ($template_content[$template_id] as $content) {
        $stmt->bind_param("iiss", $lesson_id, $template_id, $content['content_key'], $content['content_value']);
        $stmt->execute();
    }

    echo json_encode(["success" => true, "message" => "Lesson content populated successfully!"]);
}
?>
