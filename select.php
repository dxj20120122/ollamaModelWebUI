<?php
// 动态加载 model.json 文件
$json = file_get_contents('Data/Model.json');
$data = json_decode($json, true);

// 生成模型选择链接
$models = $data['models'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>选择模型</title>
    <link rel="stylesheet" href="css/select.style.css">
</head>
<body>
    <div class="main">
        <h3>您好 欢迎来到本地ollama模型Web通话选择页面 请选择您希望使用的模型 如果没有请下载模型并<a style="color:red" href="manage.php">更新模型列表</a>  谢谢</h3>
        <?php foreach ($models as $model): ?>
            <p><a href="index.php?model=<?php echo $model['id']; ?>"><?php echo $model['name']; ?></a></p>
        <?php endforeach; ?>
    </div>
</body>
</html>
