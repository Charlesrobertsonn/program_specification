<?php
// Database Connection
$host = "localhost";
$user = "root"; // Change if necessary
$password = ""; // Change if necessary
$database = "program_specification";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database Connection Failed."]));
}

// Set JSON Header
header('Content-Type: application/json');

// Get Lesson ID & Template ID from POST
$lesson_id = isset($_POST['lesson_id']) ? intval($_POST['lesson_id']) : 0;
$template_id = isset($_POST['template_id']) ? intval($_POST['template_id']) : 0;

if (!$lesson_id || !$template_id) {
    echo json_encode(["success" => false, "message" => "Invalid lesson or template selection."]);
    exit;
}

// Define Default Content for Each Template
$template_data = [
    1 => [ 'title' => 'Sound Deck Lesson', 'instructions' => 'Listen to the sounds.', 'audio_url' => json_encode(["audio/s.mp3", "audio/m.mp3"]), 'image_url' => json_encode(["images/s.png", "images/m.png"]) ],
    2 => [ 'title' => 'Phonetic Focus', 'instructions' => 'Learn phonetics.', 'video_url' => 'https://www.youtube.com/embed/video_id', 'word_list' => json_encode(["cat", "bat", "rat"]) ],
    3 => [ 'title' => 'Key Focus Revision', 'instructions' => 'Review key spelling words.', 'word_list' => json_encode(["bat", "mat", "fan"]), 'audio_url' => json_encode(["audio/bat.mp3", "audio/mat.mp3"]) ],
    4 => [ 'title' => 'Syllabication', 'instructions' => 'Break words into syllables.', 'video_url' => 'https://www.youtube.com/embed/syllabication_video', 'syllables' => json_encode([["word" => "Rabbit", "parts" => ["Rab", "bit"]]]) ],
    5 => [ 'title' => 'Read-Spell-Focus', 'instructions' => 'Read words three times.', 'word_list' => json_encode(["cat", "bat", "rat"]), 'audio_url' => json_encode(["audio/cat.mp3", "audio/bat.mp3"]) ],
    6 => [ 'title' => 'Read Rally', 'instructions' => 'Practice reading aloud.', 'word_list' => json_encode(["Dip", "Lip", "Tip"]), 'quiz' => json_encode([["question" => "What does Tim do?", "options" => ["Sips", "Jumps"], "answer" => "Sips"]]) ],
    7 => [ 'title' => 'Spell Bound', 'instructions' => 'Complete the sentences.', 'word_bank' => json_encode(["brown", "dog", "red"]) ],
    8 => [ 'title' => 'Comic', 'instructions' => 'Read and explore.', 'comic_pages' => json_encode([["text" => "Tam has a cat.", "image" => "comic1.png"], ["text" => "The cat is on the mat.", "image" => "comic2.png"]]) ]
];

// Validate Template
if (!isset($template_data[$template_id])) {
    echo json_encode(["success" => false, "message" => "Invalid template ID."]);
    exit;
}

// Insert Content
$conn->begin_transaction();
try {
    foreach ($template_data[$template_id] as $key => $value) {
        $stmt = $conn->prepare("INSERT INTO lesson_content (lesson_id, template_id, content_key, content_value) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("iiss", $lesson_id, $template_id, $key, $value);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        $stmt->close();
    }
    $conn->commit();
    echo json_encode(["success" => true, "message" => "Lesson content added successfully!"]);
} catch (Exception $e) {
    $conn->rollback();
    error_log("Database Error: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Error adding lesson content."]);
}

// Close Connection
$conn->close();
?>
