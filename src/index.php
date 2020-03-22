<?php

$redis = new Redis();
$redis->connect($_ENV["REDIS_HOST"], 6379) or die("Cannot connect to redis");

if(isset($_GET["reset"])){
    $redis->flushdb();
    header("Location: /");
    exit();
}
if(isset($_GET["votefor"])){
  if($_GET["votefor"]==$_ENV["COLOR1"] || $_GET["votefor"]==$_ENV["COLOR2"]){
    $redis->incr($_GET["votefor"]);
    header("Location: /");
    exit();
  }
  else {
    echo "Vous ne pouvez pas voter pour une couleur qui n'existe pas !";
    exit();
  }
}

$redis->incr('display');

?><html>
<head>
  <style>
  td { text-align: center;}
  </style>
</head>
<body>

<center>
  <table>
    <tr>
      <td>Couleur : </td>
      <td style="width:100px;height:50px;background: #<?php echo $_ENV["COLOR1"]; ?>"></td>
      <td style="width:100px;height:50px;background: #<?php echo $_ENV["COLOR2"]; ?>"></td>
    </tr>
    <tr>
      <td>Votes:</td>
      <td><?php echo $redis->get($_ENV["COLOR1"])?:0; ?></td>
      <td><?php echo $redis->get($_ENV["COLOR2"])?:0; ?></td>
    </tr>
    <tr>
      <td></td>
      <td><a href="/?votefor=<?php echo $_ENV["COLOR1"]; ?>">Voter</a></td>
      <td><a href="/?votefor=<?php echo $_ENV["COLOR2"]; ?>">Voter</a></td>
    </tr>
  </table>

  <br><br>
  <a href="/?reset">Remettre les compteurs &agrave; z&eacute;ro</a>
  <br><br>
  <em>Page vue <?php echo $redis->get('display')?:0; ?> fois</em>
  <br>
  <em><?php echo file_get_contents("info.txt"); ?></em>
</center>
</body>
</html>
