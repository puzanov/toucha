<?
require_once "HttpClient.php";

$config = yaml_parse(file_get_contents("config.yml"));
$online_users = json_decode(HttpClient::quickGet($config["get_online_users_url"]));
foreach($online_users as $user) {
  if ($user->sex == 0) {
    $men[] = $user;
  }
}
foreach ($config["girls"] as $girl) {
  $client = new HttpClient($config["login_host"]);
  $client->post('/login.php', array(
      'login' => $girl["login"],
      'password' => $girl["password"],
      'sub' => 'Войти'
  ));
  $cookies = $client->getCookies();
  $client = new HttpClient($config["mir_host"]);
  $client->setCookies($cookies);
  $man_login = $men[rand(0, count($men)-1)]->login;
  echo "{$girl["login"]} is visiting $man_login\n";
  $client->get("/personal.php?user=$man_login");
  $client->getStatus();
}  
?>
