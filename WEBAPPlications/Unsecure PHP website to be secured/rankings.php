<?php

	include "connection.php";

	$query="select * from users where usrtype=0 order by num_toys desc limit 10";

	$elves_rs=mysqli_query($db,$query);



	if(!$elves_rs)

	{

		echo "<font color=red size=10>Error: Invalid SQL Query</font>";

		die($query);

	}

?>



<html>

<head>

	<title>Elf Rankings</title>

    	<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">

	<link href="css/main.css" rel="stylesheet">

</head>



<body>

<?php require_once("menu.php"); ?>

<div id = "heading">

	<h1>Elf Rankings</h1>

</div>



	<center>

	<table class=table>

	<caption><font color='rgba(0,0,0,0.7)' size=4><b><i>Top Elves Today</i></b></font></caption>

	<tr><th width="50px">&nbsp;</th><th width="84px">&nbsp;</th><th>Name</th><th>#toys</th><th>Last Toy</th></tr>

<?php

	$cnt = 1;

	while($elf=mysqli_fetch_assoc($elves_rs))

	{

		echo '<tr>';

		echo '<td class="align-middle">'.$cnt.'</td>';

		echo '<td class="align-middle"><img class="img-icon" src="imgs/profile/60x60-'.$elf['username'].'.png" /></td>';

		echo '<td class="align-middle"><a href="elf.php?id='.$elf['id'].'">'.$elf['full_name'].'</a></td>';

		echo '<td class="align-middle">'.$elf['num_toys'].'</td>';

		echo '<td class="align-middle">'.$elf['email'].'</td>';

		echo '</tr>';

		$cnt++;

	}

?>

</table>

</center>

</body>

</html>

