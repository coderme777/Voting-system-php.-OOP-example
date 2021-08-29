<?php
    class Model
    {
        private $pdo;
            
        public function __construct() {
            try {
                $this->pdo = new PDO('mysql:host=localhost;dbname=voise_syst','user','123');
            } catch (PDOException $e) {
                echo 'Ошибка при подключении к БД';
            }
        }
    
        public function getPoll($id) { //получаем опрос по id
            $query = 'SELECT * FROM `polls` WHERE `id`=?'; 
            $poll = $this->pdo->prepare($query);//подготовка запроса
            $poll->execute([$id]);//результат выполнения запроса
            return $poll->fetch(PDO::FETCH_ASSOC);
        }
        
        public function getVariants($poll_id) { //получаем варианты ответов опроса
            $query = 'SELECT * FROM `variants` WHERE `poll_id`=?';
            $variants = $this->pdo->prepare($query);
            $variants->execute([$poll_id]);
            return $variants->fetchAll(PDO::FETCH_ASSOC);
        }
        
        public function getVoters($variant_ids) {//функция получения голосов для варианта ответа
            $par = str_repeat('?,', count($variant_ids) - 1).'?';//параметры
            $query = "SELECT * FROM voises WHERE `variant_id` IN ($par)";
            $voises = $this->pdo->prepare($query);
            $voises->execute($variant_ids);
            return $voises->fetchAll(PDO::FETCH_ASSOC);
        }
        
        public function addVoise($variant_id) {//ф-я добавления голоса(запись в БД)
            $query = 'INSERT INTO `voises` (`variant_id`) VALUES(?)';
            $voises = $this->pdo->prepare($query);
        return $voises->execute([$variant_id]);    
        }
   
        public function getPollOnVariantId($variant_id) {//получаем опрос зная $variant_id
            $query = 'SELECT * FROM `polls` WHERE `id` = (SELECT `poll_id` FROM `variants` WHERE `id` = ?)';
            $poll = $this->pdo->prepare($query);
            $poll->execute([$variant_id]);
            return $poll->fetch();
        }
   }  
?>