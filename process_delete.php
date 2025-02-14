<?php
// 删除模型
$deleteId = $_POST['model'];

// 加载现有模型数据
$json = file_get_contents('Data/Model.json');
$data = json_decode($json, true);
$models = $data['models'];

// 过滤掉要删除的模型
$models = array_filter($models, function($model) use ($deleteId) {
    return $model['id'] != $deleteId;
});
$data['models'] = $models;

// 更新默认模型（确保 defaultModel 存在）
$validModels = array_column($models, 'id');
$data['defaultModel'] = ($validModels) ? $validModels[0] : null;

// 保存到 JSON 文件
file_put_contents('Data/Model.json', json_encode($data, JSON_PRETTY_PRINT));

// 跳转回管理页面
header('Location: manage.php');
exit();
?>