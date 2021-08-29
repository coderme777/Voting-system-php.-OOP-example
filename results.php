<?php
    require_once 'model.php';
    
    $variant_id = $_POST['variant_id']?? false;//получаем вариант ответа после голосования пользователя, если его нет то false
    if (!$variant_id) {
        header('Location: index.php'); // если вариантов нет, то редирект на главную
        exit;
    }
    $variant_id = htmlspecialchars($variant_id);
    $my = new Model();
    $res = $my->addVoise($variant_id); //добавляем голос в БД
    $poll = $my->getPollOnVariantId($variant_id);//ищем опрос по варианту ответа
    if ($poll) {//если по варианту ответа опрос найден, то получаем все варианты ответов
        $variants = $my->getVariants($poll['id']);
        
        $ids = [];//собираем в массив все id вариантов ответа
        foreach ($variants as $v) $ids[] = $v['id'];
        
        $voises = $my->getVoters($ids);//теперь получаем все голоса
        
        $summ_data = [];//собираем нужные данные
        foreach($variants as $v) {
            $summ_data[$v['id']] = $v;
            $summ_data[$v['id']]['voises'] = 0; //добавляем в массив еще одно поле с голосами
        }
        $variants = $summ_data;
        foreach ($voises as $v) $variants[$v['variant_id']]['voises']++;//подсчитываем голоса, variant_id берем уже из таблицы voises
    } else {
        header('Location: index.php'); // если вариантов нет, то редирект на главную
        exit;        
    }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style/style.css" rel="stylesheet" type="text/css">
    <title>End voises</title>
</head>
<body>
    <main>
        <h1>Итог голосования</h1>
        <?php foreach($variants as $v) { ?>
            <div>
                <?=$v['title']?>: <i><?=$v['voises']?></i>
            </div>
        <?php }?>
        <a href='index.php'>Перейти на главную</a>
    </main>
</body>
</html>
