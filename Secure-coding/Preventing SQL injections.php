<?php  
1_ Fixing SQLi by Data Type Validation: 



$query="select * from users where id=".$_GET['id']; #in this query we can see the id parameter is not being checked whether it is an integer or not! To avoid injections, we can convert whatever the user inputs in the id parameter to an integer. 

$elves_rs=mysqli_query($db,$query);
///////////////////


$query="select * from users where id=".intval($_GET['id']); #as you can see here we added intval() function to avoid injections
$elves_rs=mysqli_query($db,$query);

/*
intval() function will take a string and try to convert it into an integer. If no valid integer is found on the string, it will return 0, which is also an integer. To clarify this, let's look at some values and how they would be converted: 

intval("123") = 123
intval("tryhackme") = 0 # as you can see here it took a string and turned it to an integer 0
intval("123tryhackme") = 123 # here it changed the string to 123 
*/

/*
Notice that for most data types, you will be able to make something similar. If you expect to receive a float number, you can use floatval() function just the same. Even if values are not numeric but follow some specific structure, you could implement your own validators to ensure that data conforms with a given format. Think, for example, of a parameter used to send IP addresses. You could quickly implement a simple function to check if the IP is well formed and opt not to run the SQL query if it isn't.
*/
/////////////////////////////////////////////////////////////////////////

2_ Fixing SQLi Using Prepared Statements: 

/*PLEASE CHECK TRY HACK ME Advent Of Cyber Day 16 if you want more references.

Prepared statements allow you to separate the syntax of your SQL sentence from the actual parameters used on your WHERE clause. Instead of building a single string by concatenation, you will first describe the structure of your SQL query and use placeholders to indicate the position of your query's parameters. You will then bind the parameters to the prepared statement in a separate function call. 
*/

$query="select * from toys where name like '%".$_GET['q']."%' or description like '%".$_GET['q']."%'";
$toys_rs=mysqli_query($db,$query);

/* "The above code is a code that performs search in a website"

Every time a search is done, it gets sent to search-toys.php via the q parameter. If you ask the elves to recheck the application right now, Elf Exploit should have a way to take advantage of a vulnerability in that parameter. 

Here, the q parameter gets concatenated twice into the same SQL sentence. Notice that in both cases, the data in q is wrapped around single quotes, which is how you represent a string in SQL. The problem with having PHP build the query is that the database has no other option but to trust what it is being given. If an attacker somehow injects SQL, PHP will blindly concatenate the injected payload into the query string, and the database will execute it.
*/

/*
First, we will modify our initial query by replacing any parameter['%".$_GET['q']."%'] with a placeholder indicated with a question mark (?). This will tell the database we want to run a query that takes two parameters as inputs. The query will then be passed to the mysqli_prepare() function instead of our usual mysqli_query(). mysqli_prepare() will not run the query yet but will indicate to the database to prepare the query with the given syntax. This function will return a prepared statement.
*/

$query="select * from toys where name like ? or description like ?";
$stmt = mysqli_prepare($db, $query);

/*
To execute our query, MySQL needs to know the value to put on each placeholder we defined before. We can use the mysqli_stmt_bind_param() function to attach variables to each placeholder. This function requires you to send the following function parameters:

The first parameter should be a reference to the prepared statement to which to bind the variables. 

The second parameter is a string composed of one letter per placeholder to be bound, where letters indicate each variable's data type. Since we want to pass two strings, we put "ss" in the second parameter, where each "s" represents a string-typed variable. You can also use the letters "i" for integers or "d" for floats. You can check the full list in PHP's documentation.

After that, you will need to pass the variables themselves. You must add as many variables as placeholders defined with ? in your query, which in our case, are two. Notice that, in our example, both parameters have the same content, but in other cases, it may not be so.

The resulting code for this would be as follows:
*/
$q = "%".$_GET['q']."%";
mysqli_stmt_bind_param($stmt, 'ss', $q, $q);/*bind param from the name we can tell that this function combines the sql initilized query and combines it with any variables, here we used 'ss' to indicate that the query takes 2 strings, then we followed it with the 2 strings variables taken from user input.*/

/*Once we have created a statement and bound the required parameters, we will execute the prepared statement using mysqli_stmt_execute(), which receives the statement $stmt as its only parameter.*/

mysqli_stmt_execute($stmt);

/*
Finally, when a statement has been executed, we can retrieve the corresponding result set using the mysqli_stmt_get_result(), passing the statement as the only parameter. We'll assign the result set to the $toys_rs variable as in the original code.
*/

$toys_rs=mysqli_stmt_get_result($stmt);

//Our final resulting code should look like this:

$q = "%".$_GET['q']."%";
$query="select * from toys where name like ? or description like ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, 'ss', $q, $q);
mysqli_stmt_execute($stmt);
$toys_rs=mysqli_stmt_get_result($stmt);

?>
