<?php
/* если нет таблиц в базе, то генерируются эти*/



$check= "CREATE TABLE IF NOT EXISTS `drugg_users` (
  `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT primary key,
  `user_login` varchar(30) NOT NULL,
  `user_password` varchar(32) NOT NULL,
  `user_hash` varchar(32) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;";
$res = mysqli_query($link, $check);
/*end = если нет таблиц, то генерирует эти*/
?>