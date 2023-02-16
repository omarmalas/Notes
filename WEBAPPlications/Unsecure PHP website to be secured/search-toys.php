<?php

include "connection.php";



$query="select * from toys where name like '%".$_GET['q']."%' or description like '%".$_GET['q']."%'";

$toys_rs=mysqli_query($db,$query);



if(!$toys_rs)

{

	echo "<font color=red size=10>Error: Invalid SQL Query</font>";

	die($query);

}



?>



<html>

<head>

	<title>Toys Found</title>

    	<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">

	<link href="css/main.css" rel="stylesheet">

</head>



<body>

<?php require_once("menu.php"); ?>

<div id = "heading">

	<h1>Toy Search</h1>

</div>



	<caption><font color='rgba(0,0,0,0.7)' size=4><b><i>Your search results:</i></b></font></caption>

	<center>

	<table class=table>

	<tr><th width="84px">&nbsp;</th><th>Name</th><th>#toys</th></tr>

<?php

	while($toy=mysqli_fetch_assoc($toys_rs))

	{

		echo '<tr>';

		echo '<td class="align-middle"><img class="img-icon" src="imgs/toys/'.$toy['name'].'.png" /></td>';

		echo '<td class="align-middle"><a href="toy.php?id='.$toy['id'].'">'.$toy['name'].'</a></td>';

		echo '<td class="align-middle">'.$toy['description'].'</td>';

		echo '</tr>';

	}

?>

</table>

</center>

</body>

</html>

