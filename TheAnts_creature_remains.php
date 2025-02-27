<?php 

?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>魚骨獲取途徑</title>
    <style>
        /* Nav bar CSS */
        nav {
            background-color: #ffccdd;
            padding: 10px 0;
            margin-bottom: 20px;
        }
        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }
        nav li {
            margin: 0 15px;
        }
        nav a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        nav a:hover {
            color: #ff6699;
        }
        body {
            background-color: #ffe6f2;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff0f6;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 10px;
            border: 1px solid #f5c6cb;
            text-align: center;
        }
        th {
            background-color: #f8d7da;
        }
        /* 將 select 內文字置中 */
        select {
            width: 100%;
            padding: 5px;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            background-color: #fff;
            text-align: center;
            text-align-last: center;
        }
        /* 若該儲存格有更改的數值，更改背景顏色 */
        .changed {
            background-color: #AD5A5A;
            color: white;
        }
        button {
            background-color: #ff99cc;
            border: none;
            color: white;
            padding: 10px 20px;
            margin-top: 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #ff80b3;
        }
        .instruction {
            margin-bottom: 20px;
            color: #333;
            font-size: 14px;
        }
        .detail-table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }
        .detail-table th, .detail-table td {
            border: 1px solid #f5c6cb;
            padding: 8px;
        }
        .detail-table th {
            background-color: #f8d7da;
        }
    </style>
</head>
<body>
    <!-- Nav Bar -->
    <nav>
        <ul>
            <li><a href="http://192.168.0.41/xampp/TheAnts_shooter_t10.php">射手蟻T10進化計算</a></li>
            <li><a href="http://192.168.0.41/xampp/TheAnts_carrier_t10.php">運輸蟻T10進化計算</a></li>
            <li><a href="http://192.168.0.41/xampp/TheAnts_shield_t10.php">近衛蟻T10進化計算</a></li>
            <li><a href="http://192.168.0.41/xampp/TheAnts_creature_remains.php">魚骨獲得方式</a></li>
        </ul>
    </nav>
    <div class="container">
        <h2>魚骨獲得方式</h2>
        <img width="50%" src="buy_creature_remains.png" alt="Creature Remains">
    </div>
</body>
</html>
