<?php
// 添加新模型
$modelId = $_POST['id'];
$modelName = $_POST['name'];

// 加载现有模型数据
$json = file_get_contents('Data/Model.json');
$data = json_decode($json, true);
$models = $data['models'];

// 添加新模型到模型列表
$models[] = [
    'id' => $modelId,
    'name' => $modelName
];

// 更新默认模型
$data['defaultModel'] = $modelId;
$data['models'] = $models;

// 保存到 JSON 文件
file_put_contents('Data/Model.json', json_encode($data, JSON_PRETTY_PRINT));

// 跳转回管理页面
header('Location: manage.php');
exit();
?>