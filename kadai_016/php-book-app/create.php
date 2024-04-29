<?php
    require_once('db_connect.php');

    if(isset($_POST['submit'])){
        try{
            $pdo = db_connect();

            $sql_insert = '
            INSERT INTO books (book_code, book_name, price, stock_quantity, genre_code)
            VALUE (:book_code, :book_name, :price, :stock_quantity, :genre_code)
            ';

            $stmt_insert = $pdo->prepare($sql_insert);

            $stmt_insert->bindValue(':book_code', $_POST['book_code'], PDO::PARAM_INT);
            $stmt_insert->bindValue(':book_name', $_POST['book_name'], PDO::PARAM_STR);
            $stmt_insert->bindValue(':price', $_POST['price'], PDO::PARAM_INT);
            $stmt_insert->bindValue(':stock_quantity', $_POST['stock_quantity'], PDO::PARAM_INT);
            $stmt_insert->bindValue(':genre_code', $_POST['genre_code'], PDO::PARAM_INT);

            $stmt_insert->execute();

            header("Location: read.php");
        }catch(PDOException $e){
            exit($e->getMessage());
        }
    }

    try{
        $pdo = db_connect();

        $sql_select = 'SELECT genre_code FROM genres';

        $stmt_select = $pdo->query($sql_select);

        $genre_codes = $stmt_select->fetchAll(PDO::FETCH_COLUMN);
    }catch(PDOException $e){
        exit($e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css" type="text/css"/> 
    <title>書籍管理アプリ 書籍登録</title>
</head>
<body>
    <header>
        <nav>
            <a href="index.php">書籍管理アプリ</a>
        </nav>
    </header>
    <main>
        <article class="registration">
            <h1>書籍登録</h1>
            <div class="back">
                <a href="read.php" class="btn">&lt; 戻る</a>
            </div>    
            <form action="create.php" method="post" class="registration-form">
                <div>
                    <label for="book_code">書籍コード</label>
                    <input type="number" id="book_code" name="book_code" min="0" max="100000000" required>

                    <label for="book_name">書籍名</label>
                    <input type="text" id="book_name" name="book_name" maxlength="50" required>
                    
                    <label for="price">単価</label>
                    <input type="number" id="price" name="price" min="0" max="100000000" required>
                    
                    <label for="stock_quantity">在庫数</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" min="0" max="100000000" required>
                    
                    <label for="genre_code">ジャンルコード</label>
                    <select type="number" id="genre_code" name="genre_code" required>
                        <option disabled selected value>選択してください。</option>
                        <?php     
                            foreach($genre_codes as $genre_code){
                                echo("<option value='{$genre_code}'>{$genre_code}</option>");
                            }
                        ?>
                    </select>
                </div>
                <button class="submit-btn" type="submit" name="submit" value="create">登録</button>
            </form>
            
        </article>
    </main>
    <footer>
        <div class="copyright">copyright 書籍管理アプリ all rights reserved.</div>
    </footer>
</body>
</html>