<?php require("php/layout_head.php"); ?>

<!-- 版身開始 -->
<div class="bodyArea">
    <div class="body-L">
        <!-- 選單開始 -->
        <?php require("php/layout_sel.php"); ?>
        <!-- 選單結束 -->
    </div><!-- body-L End -->
    <div class="body-R">
        <!-- 版身內容開始 -->
        <?php
        require("php/cmsdb.php");

        // Prevent SQL injection by using prepared statements
        if (!isset($_GET['cat'])) {
            $sql = "SELECT * FROM products ORDER BY model_year DESC";
            $stmt = $conn->prepare($sql);
        } else {
            $cat = $_GET['cat'];
            $sql = "SELECT * FROM products WHERE category_id = ? ORDER BY model_year DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $cat);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        ?>
        <table class="tab2">
            <!-- 表格標題 -->
            <tr class="tab2tit">
                <?php
                // 取得所有欄位的欄位資訊
                while ($fieldinfo = $result->fetch_field()) {
                    echo "<th>" . htmlspecialchars($fieldinfo->name) . "</th>"; // 使用 htmlspecialchars 避免 XSS 攻擊
                }
                ?>
                <th width="50">修改</th>
                <th width="50">刪除</th>
            </tr>
            <!-- 列出產品資料表內容開始 -->
            <?php
            if ($result->num_rows > 0) {
                // 每筆記錄的輸出資料
                while ($row = $result->fetch_assoc()) { // 使用 fetch_assoc 取得關聯陣列，可以直接使用欄位名稱存取資料
                    echo "<tr>";
                    // 列出各欄位值
                    foreach ($row as $key => $value) {
                        if (in_array(strtolower(substr($value, -4)), [".jpg", ".png", ".gif", "jpeg"])) { // 圖片欄
                            $img = "productimg/" . htmlspecialchars($value); // 使用 htmlspecialchars 避免 XSS 攻擊
                            echo '<td><img src="' . $img . '" width="50" height="50"></td>';
                        } else {
                            echo '<td>' . htmlspecialchars($value) . '</td>'; // 使用 htmlspecialchars 避免 XSS 攻擊
                        }
                    }
                    echo "<td><a title='修改' class='btn-vi' href='edit_product.php?id=" . htmlspecialchars($row['id']) . "'>修改</a></td>";
                    echo "<td><a title='刪除' class='btn-del' onclick='alertDel(" . htmlspecialchars($row['id']) . ");MsgYesNoOn();'>刪除</a></td>";
                    echo "</tr>";
                }
            }
            $stmt->close();
            $conn->close();
            ?>
            <!-- 列出產品資料表內容結束 -->
        </table>
        <!-- 版身內容結束 -->
    </div><!-- body-R End -->
</div><!-- bodyArea End -->
<!-- 版身結束 -->

<?php
### 訊息視窗 ###
if (isset($_GET['Msg'])) {
    if ($_GET['Msg'] == 1) {
        echo "
        <script>
        $(document).ready(function(){
            MsgAlertOn();
            $('.MsgTxt').text('資料已完成新增。');
        });
        </script>";
    }
    if ($_GET['Msg'] == 2) {
        echo "
        <script>
        $(document).ready(function(){
            MsgAlertOn();
            $('.MsgTxt').text('指定的資料已刪除。');
        });
        </script>";
    }
}

require("php/layout_footer.php");
?>
