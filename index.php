<?php
    require_once 'model.php';
    $id = $_GET['id']?? 1;//если в глобальном массиве нет id, то id = 1;
    $my = new Model();
    $poll = $my->getPoll(htmlspecialchars($id));//получаем опрос и фильтруем id
    if ($poll) $variants = $my->getVariants($poll['id']);//если опрос существует, то выводим его варианты

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style/style.css" rel="stylesheet" type="text/css">
    <title>System of a voises</title>
</head>
<body>
    <main>
        <h1>Система голосования</h1>
        <?php if($poll) { ?>
        <form action='results.php' method='post' name='poll'>
            <h2><?=$poll['title']?></h2>
            <?php foreach ($variants as $v) {?> 
                <div>
                    <?=$v['title']?>: <input type='radio' name='variant_id' value='<?=$v['id']?>'>
                </div>
            <?php }?>
                <br>
                <input type='submit' name='poll' value='Проголосовать'>            
        </form>
        <?php } else { 
            echo '<p>Такого опроса не существует!</p>';
        } ?>
            
       
    </main>
</body>
</html>