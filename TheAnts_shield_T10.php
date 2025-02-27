<?php 
// Evolution costs for each step (完整1-20級)
$evolution_costs = [
    '特化口鉗' => [280, 310, 360, 450, 570, 730, 940, 1210, 1550, 1970, 2480, 3110, 3860, 4770, 5840, 7110, 8590, 10320, 12320, 14620],
    '特化甲殼' => [280, 310, 360, 450, 570, 730, 940, 1210, 1550, 1970, 2480, 3110, 3860, 4770, 5840, 7110, 8590, 10320, 12320, 14620],
    '巨大上顎' => [320, 350, 420, 510, 650, 840, 1080, 1390, 1770, 2250, 2840, 3550, 4420, 5450, 6680, 8120, 9820, 11790, 14080, 16710],
    '硬化甲殼' => [320, 350, 420, 510, 650, 840, 1080, 1390, 1770, 2250, 2840, 3550, 4420, 5450, 6680, 8120, 9820, 11790, 14080, 16710],
    '近衛蟻迅捷攻擊II' => [360, 400, 470, 580, 730, 940, 1210, 1560, 1990, 2530, 3190, 4000, 4970, 6130, 7510, 9140, 11050, 13270, 15840, 18790],
    '耐酸蟻軀' => [400, 440, 520, 640, 820, 1050, 1350, 1730, 2210, 2810, 3550, 4440, 5520, 6180, 8340, 10150, 12270, 14740, 17600, 20900],
    '高級樹葉介質' => [730, 930, 1290, 1820, 2540, 3480, 4640, 6060, 7730, 9690, 11950, 14530, 17450, 20720, 24360],
    '近衛蟻高級異變' => [730, 930, 1290, 1820, 2540, 3480, 4640, 6060, 7730, 9690, 11950, 14530, 17450, 20720, 24360],
    '正面強攻' => [480, 520, 640, 760, 980, 1270, 1640, 2090, 2650, 3370, 4270, 5340, 6640, 8170, 10030, 12170, 14740, 17680, 21120, 25120],
    '奮力抵抗' => [480, 520, 640, 760, 980, 1270, 1640, 2090, 2650, 3370, 4270, 5340, 6640, 8170, 10030, 12170, 14740, 17680, 21120, 25120],
    '近衛蟻高級治癒' => [520, 560, 700, 820, 1060, 1380, 1780, 2270, 2870, 3650, 4630, 5790, 7200, 8850, 10870, 13180, 15970, 19150, 22880, 27230],
    '特化口鉗II' => [560, 600, 760, 880, 1140, 1490, 1920, 2450, 3090, 3930, 4490, 6240, 7760, 9530, 11710, 14190, 17200, 20620, 24640, 29340],
    '特化甲殼II' => [560, 600, 760, 880, 1140, 1490, 1920, 2450, 3090, 3930, 4490, 6240, 7760, 9530, 11710, 14190, 17200, 20620, 24640, 29340],
    '致命撕咬' => [600, 640, 820, 940, 1220, 1600, 2060, 2630, 3310, 4210, 5350, 6690, 8320, 10210, 12560, 15200, 18430, 22090, 26400, 31450],
    '近衛蟻蟻軀強化' => [640, 680, 880, 1000, 1300, 1710, 2200, 2810, 3530, 4490, 5710, 7140, 8880, 10890, 13390, 16210, 19660, 23560, 28160, 33560],
    '解鎖10級近衛蟻' => [58140]
];

define('DAILY_FISHBONE', 7000); // 每日魚骨獎勵（正常狀態下）

// 分組設定：從上而下的進化分組
$groups = [
    1 => ['特化口鉗', '特化甲殼'],
    2 => ['巨大上顎', '硬化甲殼'],
    3 => ['近衛蟻迅捷攻擊II'],
    4 => ['耐酸蟻軀'],
    5 => ['高級樹葉介質', '近衛蟻高級異變'],
    6 => ['正面強攻', '奮力抵抗'],
    7 => ['近衛蟻高級治癒'],
    8 => ['特化口鉗II', '特化甲殼II'],
    9 => ['致命撕咬'],
    10 => ['近衛蟻蟻軀強化'],
    11 => ['解鎖10級近衛蟻']
];

/*
  運算邏輯：
  - 根據玩家在各組中選擇的進度，找出最高組號（active group），
    也就是所有分組中，至少有一項目選擇不為 0 的最高組號。
  - 對於該 active group 之前的所有組，視為已完成（成本 = 0）。
  - 對於該 active group，依玩家所選進度計算剩餘成本。
  - 對於該 active group 之後的所有組，視為尚未開始（進度 = 0，全數投入）。
  同時計算明細，以分組整理。
*/
function calculate_remaining_fishbones($progress) {
    global $evolution_costs, $groups;
    $total_cost = 0;
    $details = [];
    
    // 判斷最高組號（active group）
    $activeGroup = 0;
    foreach ($groups as $groupNum => $items) {
        foreach ($items as $item) {
            $userProgress = isset($progress[$item]) ? intval($progress[$item]) : 0;
            if ($userProgress > 0 && $groupNum > $activeGroup) {
                $activeGroup = $groupNum;
                break;
            }
        }
    }
    if ($activeGroup == 0) {
        $activeGroup = 1;
    }
    
    // 依組別計算成本與明細
    foreach ($groups as $groupNum => $items) {
        $groupCost = 0;
        $groupDetail = [];
        foreach ($items as $item) {
            $fullLevel = count($evolution_costs[$item]);
            if ($groupNum < $activeGroup) {
                $cost = 0;
            } elseif ($groupNum == $activeGroup) {
                $userProgress = isset($progress[$item]) ? intval($progress[$item]) : 0;
                $cost = 0;
                for ($i = $userProgress; $i < $fullLevel; $i++) {
                    $cost += $evolution_costs[$item][$i];
                }
            } else {
                $cost = array_sum($evolution_costs[$item]);
            }
            $groupDetail[$item] = $cost;
            $groupCost += $cost;
        }
        $details[$groupNum] = $groupDetail;
        $total_cost += $groupCost;
    }
    // 正常計算：每日獎勵7000魚骨
    $days_needed = ceil($total_cost / DAILY_FISHBONE);
    $unlock_date = date('Y-m-d', strtotime("+{$days_needed} days"));
    
    // 建築日計算：每日如果為周一，獲得1400魚骨；其他日子則獲得7000魚骨
    $remaining = $total_cost;
    $building_days = 0;
    $timestamp = time();
    while ($remaining > 0) {
        $dow = date('w', $timestamp); // 1 表示周一 (0 為周日)
        if ($dow == 1) {
            $yield = 1400;
        } else {
            $yield = 7000;
        }
        $remaining -= $yield;
        $building_days++;
        $timestamp = strtotime("+1 day", $timestamp);
    }
    $unlock_date_building = date('Y-m-d', strtotime("+{$building_days} days"));
    
    return [$total_cost, $days_needed, $unlock_date, $building_days, $unlock_date_building, $details];
}

// 處理 POST 請求：回傳總魚骨需求、預計天數、預計解鎖日期、建築日預計天數、建築日預計解鎖日期與分組明細
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Content-Type: application/json");
    $progress = $_POST['progress'] ?? [];
    $progress = array_map('intval', $progress);
    list($fishbone_needed, $days_needed, $unlock_date, $building_days, $unlock_date_building, $group_details) = calculate_remaining_fishbones($progress);
    echo json_encode([
        'fishbone' => $fishbone_needed,
        'days' => $days_needed,
        'unlock_date' => $unlock_date,
        'days_building' => $building_days,
        'unlock_date_building' => $unlock_date_building,
        'group_details' => $group_details
    ]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>近衛蟻T10進化計算</title>
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
    <script>
        // updateForm()：當玩家變更下拉選單時，若數值不為 0，則加入 .changed 類別
        function updateForm() {
            const selects = document.querySelectorAll('select.progress-select');
            selects.forEach(select => {
                if (parseInt(select.value) > 0) {
                    select.parentElement.classList.add('changed');
                } else {
                    select.parentElement.classList.remove('changed');
                }
            });
        }
        window.addEventListener('DOMContentLoaded', () => {
            const selects = document.querySelectorAll('select.progress-select');
            selects.forEach(select => {
                select.addEventListener('change', updateForm);
            });
        });
        async function calculate() {
            let formData = new FormData(document.getElementById('evoForm'));
            let response = await fetch(window.location.href, {
                method: 'POST',
                body: formData
            });
            if (!response.ok) {
                document.getElementById('result').innerText = "計算錯誤，請重試";
                return;
            }
            let result = await response.json();
            let output = `<br><hr><br><strong>總所需魚骨：</strong> ${result.fishbone}<br>`;
            // 以表格呈現兩種計算方式的預計解鎖天數與日期
            output += `<table class="detail-table">
                        <tr>
                            <th>計算方式</th>
                            <th>預計解鎖天數</th>
                            <th>預計解鎖日期</th>
                        </tr>
                        <tr>
                            <td>最強區域戰 (SvS) 每日皆得 9 貝<br><font color="red">(7000魚骨/天)</font></td>
                            <td>${result.days} 天</td>
                            <td>${result.unlock_date}</td>
                        </tr>
                        <tr>
                            <td>建築日僅得 6 貝，其餘天數 9 貝<br><font color="red">(周一：1400魚骨/天，周二至周日：7000魚骨/天)</font></td>
                            <td>${result.days_building} 天</td>
                            <td>${result.unlock_date_building}</td>
                        </tr>
                      </table><br>`;
            // 顯示進化項目明細
            let groupDetails = result.group_details;
            if (groupDetails && Object.keys(groupDetails).length > 0) {
                output += `<table class="detail-table">
                    <tr>
                        <th>進度列</th>
                        <th>進化項目</th>
                        <th>所需魚骨</th>
                    </tr>`;
                for (let group in groupDetails) {
                    for (let item in groupDetails[group]) {
                        output += `<tr>
                            <td>${group}</td>
                            <td>${item}</td>
                            <td>${groupDetails[group][item]}</td>
                        </tr>`;
                    }
                }
                output += `</table>`;
            }
            document.getElementById('result').innerHTML = output;
        }
        // resetForm()：重置表單與清空結果，並清除所有 .changed 樣式
        function resetForm() {
            document.getElementById('evoForm').reset();
            document.getElementById('result').innerHTML = "";
            const tds = document.querySelectorAll('td');
            tds.forEach(td => td.classList.remove('changed'));
        }
    </script>
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
        <h2>射手蟻T10進化計算</h2>
        <p class="instruction">
            請選擇您目前最後一項進化進度。<br>
            <font color="red"><strong>注意：</strong>當出現分支(平行進化項目)時，請調整兩邊的進化進度(進度為0則無須調整)。</font>
        </p>
        <form id="evoForm" onsubmit="event.preventDefault(); calculate();">
            <table>
                <tr>
                    <th>進度列</th>
                    <th>左分支</th>
                    <th></th>
                    <th>右分支</th>
                </tr>
                <?php foreach ($groups as $group_num => $items): ?>
                    <tr class="group-row" data-group="<?= $group_num ?>">
                        <td><?= $group_num ?></td>
                        <?php if (count($items) == 2): ?>
                            <!-- 兩個項目：左、右 -->
                            <td>
                                <label><?= $items[0] ?></label>
                                <?php $costs = $evolution_costs[$items[0]]; ?>
                                <select name="progress[<?= $items[0] ?>]" class="progress-select" data-max="<?= count($costs) ?>">
                                    <?php for ($i = 0; $i <= count($costs); $i++): ?>
                                        <option value="<?= $i ?>">(<?= $i ?>/<?= count($costs) ?>)</option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                            <td></td>
                            <td>
                                <label><?= $items[1] ?></label>
                                <?php $costs = $evolution_costs[$items[1]]; ?>
                                <select name="progress[<?= $items[1] ?>]" class="progress-select" data-max="<?= count($costs) ?>">
                                    <?php for ($i = 0; $i <= count($costs); $i++): ?>
                                        <option value="<?= $i ?>">(<?= $i ?>/<?= count($costs) ?>)</option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                        <?php else: ?>
                            <!-- 只有一個項目：放在中 -->
                            <td></td>
                            <td>
                                <label><?= $items[0] ?></label>
                                <?php $costs = $evolution_costs[$items[0]]; ?>
                                <select name="progress[<?= $items[0] ?>]" class="progress-select" data-max="<?= count($costs) ?>">
                                    <?php for ($i = 0; $i <= count($costs); $i++): ?>
                                        <option value="<?= $i ?>">(<?= $i ?>/<?= count($costs) ?>)</option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                            <td></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
            <button type="submit">計算</button>
            <button type="reset" onclick="resetForm();">重置</button>
        </form>
        <p id="result"></p>
    </div>
</body>
</html>
