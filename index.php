<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>ollama 模型通话Web UI   本地ollama模型通话Web UI 请确保 PHP 环境正常 建议下载PHPstudy小皮面板 简易好用  ollama 运行 Model.json更新</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="highlight/styles/vs2015.css">
    <script src="highlight/highlight.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>
    <div class="container">
        <p><h1>本地ollama模型通话Web UI 请确保 PHP 环境正常  ollama 运行  模型列表更新<a href="select.php">👆点击选择模型</a> | <a href="manage.php">管理模型</a></h1></p>

        <div id="chat-box">
            <!-- 对话内容将显示在这里 -->
        </div>
        <form id="chat-form">
            <textarea id="user-input" rows="2" placeholder="请输入您的消息..." required></textarea>
            <button type="submit">发送</button>
        </form>
        <div id="thinking-animation" class="thinking-animation" style="display: none;">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
        <div id="error-message" class="error" style="display: none;"></div>
    </div>

    <script src="js/chat.js"></script>

</body>
</html>
