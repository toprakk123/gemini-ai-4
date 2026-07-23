<?php
// Toprak AI - Gemini AQ Key Uyumlu index.php

$apiKey = "AQ.Ab8RN6ItWJP08IivJRn9GA4C7Cgp3li80_H6Og0S0YzdPu_Skw"; // AQ ile başlayan anahtarını buraya yapıştır
$prompt = isset($_POST['prompt']) ? $_POST['prompt'] : '';
$responseOutput = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($prompt)) {
    // Google'ın güncel generativelanguage endpoint'i (v1beta veya v1)
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent";

    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $prompt]
                ]
            ]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey // AQ key'ler bu header ile çalışır
    ]);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        $jsonResult = json_decode($result, true);
        $responseOutput = $jsonResult['candidates'][0]['content']['parts'][0]['text'] ?? 'Yanıt işlenemedi.';
    } else {
        $responseOutput = "Hata Kodu: {$httpCode} - Yanıt: " . htmlspecialchars($result);
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Toprak AI - Gemini AQ</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; margin: 0; padding: 50px; display: flex; justify-content: center; }
        .container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 600px; }
        textarea { width: 100%; height: 100px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; resize: none; margin-bottom: 10px; }
        button { background: #4F46E5; color: #fff; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background: #4338CA; }
        .result { margin-top: 20px; background: #f9fafb; padding: 15px; border-left: 4px solid #4F46E5; white-space: pre-wrap; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Toprak AI (Gemini AQ)</h2>
        <form method="POST">
            <textarea name="prompt" placeholder="Gemini'ye bir şeyler sor..."><?php echo htmlspecialchars($prompt); ?></textarea>
            <br>
            <button type="submit">Gemini ile Üret</button>
        </form>

        <?php if (!empty($responseOutput)): ?>
            <div class="result">
                <strong>Yapay Zeka Yanıtı:</strong><br>
                <?php echo nl2br(htmlspecialchars($responseOutput)); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
