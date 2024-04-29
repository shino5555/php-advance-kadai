<?php
    require_once('db_connect.php');


    try{
        $pdo = db_connect();

        if (isset($_GET['order'])){
            $order = $_GET['order'];
        }else{
            $order = NULL;
        }

        if(isset($_GET['keyword'])){
            $keyword = $_GET['keyword'];
        }else{
            $keyword = NULL;
        }

        if($order==='desc'){
            $sql_select = 'SELECT * FROM books WHERE book_name LIKE :keyword ORDER BY updated_at DESC';
        }else{
            $sql_select = 'SELECT * FROM books WHERE book_name LIKE :keyword ORDER BY updated_at ASC';
        }

        $stmt_select = $pdo->prepare($sql_select);

        $partial_match = "%{$keyword}%";

        $stmt_select->bindValue(':keyword', $partial_match, PDO::PARAM_STR);

        $stmt_select->execute();

        $books = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

        if(isset($_GET['delete'])){
            $sql_delete = 'DELETE FROM books WHERE id = :id';

            $stmt_delete = $pdo->prepare($sql_delete);

            $stmt_delete->bindValue(':id', $_GET['delete'], PDO::PARAM_INT);

            $stmt_delete->execute();

            header("Location: read.php");
        }

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
    <title>書籍管理アプリ 書籍一覧</title>
</head>
<body>
    <header>
        <nav>
            
            <a href="index.php">書籍管理アプリ</a>
        </nav>
    </header>
    <main>
        <article class="books">
            <h1>書籍一覧</h1>
             <div class="success">
                <?php
                    if(isset($_GET['message'])){
                        echo $_GET['message'];
                    }
                ?>
          </div>  
            <div class="books-ui">
                <div>
                    <a href="read.php?order=desc&keyword=<?= $keyword ?>">
                        <img src="img/desc.png" class="sort-img" alt="降順に並び替え">
                    </a>
                    <a href="read.php?order=asc&keyword=<?= $keyword ?>">
                        <img src="img/asc.png" class="sort-img" alt="昇順に並び替え">
                    </a>
                    <form action="read.php" method="$_GET" class="search-form">
                        <input type="hidden" name="order" value="<?= $order ?>">
                        <input type="text" placeholder="書籍名で検索" name="keyword" class="search-box" value="<?= $keyword ?>">
                    </form>
                </div>
                <a href="create.php" class="btn">商品登録</a>
            </div>      
            <table class="books-table">
                <tr>
                    <th>書籍コード</th>
                    <th>書籍名</th>
                    <th>単価</th>
                    <th>在籍数</th>
                    <th>ジャンルコード</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
                <?php
                    foreach($books as $book){
                        $table_row =" 
                        <tr>
                            <td>{$book['book_code']}</td>
                            <td>{$book['book_name']}</td>
                            <td>{$book['price']}</td>
                            <td>{$book['stock_quantity']}</td>
                            <td>{$book['genre_code']}</td>
                            <td>
                                <a href='update.php?id={$book['id']}'><img src='img/edit.png' class='edit-icon'></a>
                            </td>
                            <td>
                                <a href='read.php?delete={$book['id']}'><img src='img/delete.png' class='delete-icon'></a>
                            </td>
                        </tr>";
                        echo($table_row);
                    }
                ?>
            </table>
        </article>
    </main>
    <footer>
        <div class="copyright">copyright 書籍管理アプリ all rights reserved.</div>
    </footer>
</body>
</html>