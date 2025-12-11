<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$github_token = 'YOUR_GITHUB_TOKEN';
$repo = 'username/domiking-configs';

// Lấy config theo user ID
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $url = "https://api.github.com/repos/{$repo}/contents/users/{$user_id}.json";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: token {$github_token}",
        "User-Agent: DomiKing-Extension"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $data = json_decode($response, true);
    
    if (isset($data['content'])) {
        echo base64_decode($data['content']);
    } else {
        // Fallback về config mặc định
        $default_url = "https://api.github.com/repos/{$repo}/contents/users/default.json";
        curl_setopt($ch, CURLOPT_URL, $default_url);
        $default_response = curl_exec($ch);
        $default_data = json_decode($default_response, true);
        
        if (isset($default_data['content'])) {
            echo base64_decode($default_data['content']);
        } else {
            echo json_encode(['error' => 'Config not found']);
        }
    }
    
    curl_close($ch);
}
?>
