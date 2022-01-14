<?php
include '../functions/functions.php';
include "../functions/connect.php";
include "../functions/create_tables.php";

if ($_POST['label']=='control_new_login'){
  $query = mysqli_query($link, "SELECT user_id FROM drugg_users WHERE user_login='".mysqli_real_escape_string($link, $_POST['login'])."'");
    if(mysqli_num_rows($query) > 0)
    {
        //Пользователь с таким логином уже существует в базе данных
        echo json_encode(array('login_have' => true ));
    }

}
if ($_POST['label']=='register_new_user'){ 
  $err = [];
      // проверяем, не сущестует ли пользователя с таким именем
    $query = mysqli_query($link, "SELECT user_id FROM drugg_users WHERE user_login='".mysqli_real_escape_string($link, $_POST['login'])."'");
    if(mysqli_num_rows($query) > 0)
    {
        $err[] = "login_have";//Пользователь с таким логином уже существует в базе данных
        echo json_encode(array('login_have' => true, 'saved'=>false ));
    }

     if(count($err) == 0)
    {

        $login = $_POST['login'];
        // Убераем лишние пробелы и делаем двойное хеширование
        $password = md5(md5(trim($_POST['password'])));

        mysqli_query($link,"INSERT INTO drugg_users SET user_login='".$login."', user_password='".$password."'");
        echo json_encode(array('login_have' => false, 'saved'=> true));
    }

}

if ($_POST['label']=='enter_log_pass'){
	  $login=$_POST['login'];
	   $password=$_POST['password'];

	   // Вытаскиваем из БД запись, у которой логин равняеться введенному
    $query = mysqli_query($link,"SELECT user_id, user_password FROM drugg_users WHERE user_login='".mysqli_real_escape_string($link,$login)."' LIMIT 1");
    
    $data = mysqli_fetch_assoc($query);
    
    // Сравниваем пароли
    if($data['user_password'] === md5(md5($password))) {
    	//echo "пароли совпали";

    	  // Генерируем случайное число и шифруем его
        $hash = md5(generateCode(10));

        // Записываем в БД новый хеш авторизации и IP
        @mysqli_query($link, "UPDATE drugg_users SET user_hash='".$hash."' "." WHERE user_id='".$data['user_id']."'");
          
         echo json_encode(array('user_hash' => $hash,'user_login' => $login ,'wrong_log_pass'=>false));

    } else { echo json_encode(array('wrong_log_pass' => true ));}
}

 if ($_POST['label'] == 'check_user_hash') {
 	$login = $_POST['user_login'];
 	 $query = mysqli_query($link,"SELECT user_hash FROM drugg_users WHERE user_login='".mysqli_real_escape_string($link,$login)."' LIMIT 1");
      $data = mysqli_fetch_assoc($query);
       if ($data['user_hash'] == $_POST['user_hash']) {
       	echo json_encode(array('user_hash_match'=>true,'user_login'=>$login));
       } else {echo json_encode(array('user_hash_match'=>false));}
 }

?>