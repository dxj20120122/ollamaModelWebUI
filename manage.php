<?php
// 加载模型数据
$json = file_get_contents('Data/Model.json');
$data = json_decode($json, true);
$models = $data['models'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>模型管理</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            transition: all 0.5s ease-in-out;
            box-sizing: border-box;
        }

        /* 全局样式 */
        body {
            font-family: Arial, sans-serif;
            background-color: #000; /* 主体为黑色 */
            color: #fff; /* 文字为白色 */
            margin: 0;
            padding: 20px;
        }

        /* 标题样式 */
        h1, h2 {
            color: #fff;
            margin: 20px 0;
        }

        h1 p a , h1 p{
            color: #fff;
            text-decoration: underline;
            font-weight: 400;
            font-size: 24px;
            height: 80px;
            width: 100%;
            line-height: 80px;
        }

        a:hover{
            font-size: 26px;
            font-weight: 800;
        }

        /* 表单样式 */
        form {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #1a1a1a; /* 深灰色背景 */
            border-radius: 5px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #444;
            border-radius: 4px;
            background-color: #222;
            color: #fff;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #444; /* 按钮背景色 */
            color: #fff;
            cursor: pointer;
        }

        button:hover {
            background-color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 10px;
            text-align: left;
            background-color: #1a1a1a; /* 表格深灰色背景 */
        }

        th {
            background-color: #333; /* 表头深灰色背景 */
        }

        /* 响应式调整 */
        @media (max-width: 600px) {
            form {
                padding: 15px;
            }

            input[type="text"],
            select {
                margin-bottom: 12px;
            }

            button {
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>
    <h1>模型管理 <p><a href="index.php">返回主页</a></p></h1>

    <!-- 显示模型列表 -->
    <h2>模型列表</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>名称</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($models as $model): ?>
                <tr>
                    <td><?php echo $model['id']; ?></td>
                    <td><?php echo $model['name']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- 添加模型 -->
    <h2>添加模型</h2>
    <form action="process_add.php" method="post">
        <label for="id">ID:</label>
        <input type="text" id="id" name="id" required><br><br>
        <label for="name">名称:</label>
        <input type="text" id="name" name="name" required><br><br>
        <button type="submit">添加模型</button>
    </form>

    <!-- 删除模型 -->
    <h2>删除模型</h2>
    <form action="process_delete.php" method="post">
        <label for="model">选择模型:</label>
        <select id="model" name="model" required>
            <?php foreach ($models as $model): ?>
                <option value="<?php echo $model['id']; ?>"><?php echo $model['name']; ?></option>
            <?php endforeach; ?>
        </select><br><br>
        <button type="submit">删除模型</button>
    </form>
</body>
</html>