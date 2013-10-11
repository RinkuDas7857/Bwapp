<?php

/*

bWAPP or a buggy web application is a free and open source web application
build to allow security enthusiasts, students and developers to better secure web applications.
It is for educational purposes only.

Please feel free to grab the code and make any improvements you want.
Just say thanks.
https://twitter.com/MME_IT

© 2013 MME BVBA. All rights reserved.

*/

include("security.php");
include("security_level_check.php");
include("functions_external.php");
include("connect_i.php");
include("selections.php");

if($_COOKIE["security_level"] != "2")
{
    
    header("Location: sqli_2.php");
    
}

// Selects all the records
$sql = "select * from movies";

$recordset = $link->query($sql); 

?>
<!DOCTYPE html>
<html>
    
<head>
        
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Architects+Daughter">
<link rel="stylesheet" type="text/css" href="stylesheets/stylesheet.css" media="screen" />
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />

<!--<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>-->
<script src="js/html5.js"></script>

<title>bWAPP - SQL Injection</title>

</head>

<body>
    
<header>

<h1>bWAPP</h1>

<h2>an extremely buggy web application !</h2>

</header>    

<div id="menu">
      
    <table>
        
        <tr>
            
            <td><a href="portal.php">Bugs</a></td>
            <td><a href="password_change.php">Change Password</a></td>
            <td><a href="user_extra.php">Create User</a></td>
            <td><a href="security_level_set.php">Set Security Level</a></td>
            <td><a href="reset.php" onclick="return confirm('All settings will be cleared. Are you sure?');">Reset</a></td>            
            <td><a href="credits.php">Credits</a></td>
            <td><a href="http://itsecgames.blogspot.com" target="_blank">Blog</a></td>
            <td><a href="logout.php" onclick="return confirm('Are you sure you want to leave?');">Logout</a></td>
            <td><font color="red">Welcome <?php echo ucwords($_SESSION["login"])?></font></td>
            
        </tr>
        
    </table>   
   
</div> 

<div id="main">
    
    <h1>SQL Injection (Select)</h1>

    <form action="<?php echo($_SERVER["SCRIPT_NAME"]); ?>" method="GET">
        
        <p>Select a movie:

        <select name="movie">

<?php

            // Fills the 'select' object
            while($row = $recordset->fetch_object())
            {

?>
                <option value="<?php echo $row->id;?>"><?php echo $row->title; ?></option>
<?php

            }

            $recordset->close();

?>

        </select>   

        <button type="submit" name="action" value="go">Go</button>
        
        </p>

    </form>

    <table id="table_yellow">

        <tr height="30" bgcolor="#ffb717" align="center">

            <td width="200"><b>Title</b></td>
            <td width="80"><b>Release</b></td>
            <td width="140"><b>Character</b></td>
            <td width="80"><b>Genre</b></td>
            <td width="80"><b>IMDb</b></td>

        </tr>
<?php

if(isset($_REQUEST["movie"]))
{   

    $id = $_REQUEST["movie"];

    $sql = "SELECT title, release_year, genre, main_character, imdb FROM movies WHERE id =?";

    if ($stmt = $link->prepare($sql)) 
    // if ($stmt = mysqli_prepare($link, $sql))                
    {

        // Binds the parameters for markers
        $stmt->bind_param("s", $id);
        // mysqli_stmt_bind_param($stmt, "s", $id);

        // Executes the query
        $stmt->execute();
        // mysqli_stmt_execute($stmt);

        // Binds the result variables
        $stmt->bind_result($title, $release_year, $genre, $main_character, $imdb);
        // mysqli_stmt_bind_result($stmt, $title, $release_year, $genre, $main_character, $imdb);

        // Stores the result, necessary to count the number of rows
        $stmt->store_result();      
        // mysqli_stmt_store_result($stmt);

        // Prints the number of rows
        // printf("Number of rows: %d.\n", mysqli_stmt_num_rows($stmt));
        // printf("Number of rows: %d.\n", $stmt->num_rows);      

        if($stmt->error)
        // if(mysqli_stmt_error($stmt))
        {        

?>

        <tr height="50">

            <td colspan="5" width="580"><?php die("Error: " . $stmt->error); ?></td>
            <!--
            <td></td>
            <td></td>
            <td></td>
            <td></td> 
            -->                

        </tr>
<?php        

        }

        // Shows the movie details when a valid record exists;
        if($stmt->affected_rows != 0)
        // if(mysqli_stmt_affected_rows($stmt) != 0)  
        {

            // Fetches the values
            $stmt->fetch();
            // mysqli_stmt_fetch($stmt);

            // while ($stmt->fetch()) 
            // {
            //    
            // }

?>

        <tr height="30">

            <td><?php echo $title ?></td>
            <td><?php echo $release_year ?></td>
            <td><?php echo $main_character ?></td>
            <td><?php echo $genre ?></td>
            <td><a href="http://www.imdb.com/title/<?php echo $imdb ?>" target="_blank">Link</a></td>

        </tr>      
<?php     

        }

        else
        {

?>

        <tr height="30">

            <td colspan="5" width="580">No movies were found!</td>
            <!--
            <td></td>
            <td></td>
            <td></td>
            <td></td> 
            -->

        </tr>      
<?php    

        }

        $stmt->close();
        // mysqli_stmt_close($stmt);

    }

}

    else
    {

?>

        <tr height="30">

            <td colspan="5" width="580"></td>
            <!--
            <td></td>
            <td></td>
            <td></td>
            <td></td> 
            -->

        </tr>
<?php

    }

mysqli_close($link);

?>

    </table>    

</div>
    
<div id="side">    
    
    <a href="http://itsecgames.blogspot.com" target="blank_" class="button"><img src="./images/blogger.png"></a>
    <a href="http://be.linkedin.com/in/malikmesellem" target="blank_" class="button"><img src="./images/linkedin.png"></a>
    <a href="http://twitter.com/MME_IT" target="blank_" class="button"><img src="./images/twitter.png"></a>
    <a href="http://www.facebook.com/pages/MME-IT-Audits-Security/104153019664877" target="blank_" class="button"><img src="./images/facebook.png"></a>

</div>     
    
<div id="disclaimer">
          
    <p>bWAPP or a buggy web application is for educational purposes only / © 2013 <b>MME BVBA</b>. All rights reserved.</p>
   
</div>
    
<div id="bee">
    
    <img src="./images/bee_1.png">
    
</div>
    
<div id="security_level">
  
    <form action="<?php echo($_SERVER["SCRIPT_NAME"]);?>" method="POST">
        
        <label>Set your security level:</label><br />
        
        <select name="security_level">
            
            <option value="0">low</option>
            <option value="1">medium</option>
            <option value="2">high</option> 
            
        </select>
        
        <button type="submit" name="form_security_level" value="submit">Set</button>
        <font size="4">Current: <b><?php echo $security_level?></b></font>
        
    </form>   
    
</div>
    
<div id="bug">

    <form action="<?php echo($_SERVER["SCRIPT_NAME"]);?>" method="POST">
        
        <label>Choose your bug:</label><br />
        
        <select name="bug">
   
<?php

// Lists the options from the array 'bugs' (bugs.txt)
foreach ($bugs as $key => $value)
{
    
   $bug = explode(",", trim($value));
   
   // Debugging
   // echo "key: " . $key;
   // echo " value: " . $bug[0];
   // echo " filename: " . $bug[1] . "<br />";
   
   echo "<option value='$key'>$bug[0]</option>";
 
}

?>


        </select>
        
        <button type="submit" name="form_bug" value="submit">Hack</button>
        
    </form>
    
</div>
      
</body>
    
</html>