<?php
// process.php

// 手动加载 Parsedown 类
require 'tool/Parsedown.php';

$parsedown = new Parsedown();

// 获取原始 POST 数据
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// 输入验证
if (!isset($data['prompt'])) {
    http_response_code(400);
    echo json_encode(['error' => '提示不能为空。或请求超时。请稍后再试。']);
    exit();
}

$prompt = trim($data['prompt']);
$model = isset($data['model']) ? $data['model'] : $data['model'];
$temperature = isset($data['temperature']) ? floatval($data['temperature']) : 0.7;
$max_tokens = isset($data['max_tokens']) ? intval($data['max_tokens']) : 4096;

// 构建 API 请求的 URL    如自定义 API 地址 或 API 服务地址不正确，请修改此处
$url = getenv('OLLAMA_API_URL') ?: 'http://localhost:11434/v1/completions';

// 准备 POST 数据
$postData = array(
    "model" => $model,
    "prompt" => $prompt,
    "temperature" => $temperature,
    "max_tokens" => $max_tokens
);

// 初始化 cURL 会话   如果没有开启 cURL 功能 请在php.ini 中将 cURL 的值变成1
$ch = curl_init($url);

// 设置 cURL 选项
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Accept: application/json'
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// 执行请求并获取响应
$response = curl_exec($ch);

// 检查是否有错误发生
if ($response === false) {
    $error = curl_error($ch);
    http_response_code(500);
    echo json_encode(['error' => "cURL 错误: " . htmlspecialchars($error)]);
} else {
    // 解析 JSON 响应
    $result = json_decode($response, true);
    if (isset($result['choices'][0]['text'])) {
        // 将 Markdown 转换为 HTML
        $htmlResponse = $parsedown->text($result['choices'][0]['text']);

        // 去掉多余的 <p> 标签
        $htmlResponse = preg_replace('/<p>(.*?)<\/p>/', '$1', $htmlResponse);

        // 将代码块的标签包裹为 <pre><code> 以支持高亮
        $htmlResponse = preg_replace(
            '/```(.*?)```/s',
            '<pre><code>$1</code></pre>',
            $htmlResponse
        );

        // 返回 HTML 内容
        echo json_encode(['text' => $htmlResponse]);
    } else {
        if (isset($result['error'])) {
            http_response_code(400);
            echo json_encode(['error' => "错误信息: " . htmlspecialchars($result['error']['message'])]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => "未知的错误。"]);
        }
    }
}

// 关闭 cURL 会话
curl_close($ch);
?>