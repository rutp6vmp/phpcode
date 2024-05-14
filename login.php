<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
        }

        .login-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .login-form label {
            margin-bottom: 10px;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .login-form input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-form input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>電子種類清單-登入畫面</h2>

    <form class="login-form" action="login.php" method="post">
        <div>
            <label for="uid">使用者名稱:</label>
            <input type="text" id="uid" name="uid">
        </div>
        <div>
            <label for="passwd">使用者密碼:</label>
            <input type="password" id="passwd" name="passwd">
        </div>
        <div>
            <input type="submit" value="Login">
        </div>
    </form>

    <?php
    // 定義連線參數
    define('DB_HOST', 'localhost');
    define('DB_USER', 'user01');
    define('DB_PASSWORD', 'password');
    define('DB_DATABASE', 'product');

    // 建立資料庫連線
    $DB = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

    // 檢查連線是否成功
    if (!$DB) {
        die('Could not connect: ' . mysqli_error($DB));
    }

    // 設定資料庫編碼
    mysqli_query($DB, "SET NAMES 'utf8'");

    // 檢查表單是否提交
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 接收表單提交的使用者名稱和密碼
        $uid = $_POST['uid'];
        $passwd = $_POST['passwd'];

        // 使用 prepared statement 避免 SQL 注入攻擊
        $stmt = $DB->prepare("SELECT uid FROM login WHERE uid = ? AND passwd = ?");
        $stmt->bind_param("ss", $uid, $passwd);
        $stmt->execute();
        $stmt->store_result();

        // 檢查是否有符合的使用者
        if ($stmt->num_rows > 0) {
            // 登入成功，轉跳到 product3.php
            header("Location: product3.php");
            exit();
        } else {
            // 登入失敗，顯示錯誤訊息
            echo "<p class='error-message'>帳號或密碼錯誤</p>";
        }

        // 關閉 prepared statement
        $stmt->close();
    }

    // 關閉資料庫連線
    $DB->close();
    ?>

</div>

</body>
</html>
