<HTML XMLns="http://www.w3.org/1999/xHTML"> 
<body>
<H1>Members of the Hawthorn Bowling Club</H1>
<br/>
<?php 
  $file = "./data/bowlers.txt";
  if(!file_exists($file))
    echo "No registered member found!";
  else {
    $bowlers=file($file);
    echo "<table border='1'><th>Name</th><th>Phone</th>";
    for($i=0;$i<count($bowlers);$i++) {
      $curBowler = explode(",",$bowlers[$i]);
      echo "<tr><td>".$curBowler[0]."</td>";
      echo "<td>".$curBowler[1]."</td></tr>";
    }
    echo "</table>";
  }
?>
</body> 
</HTML>
