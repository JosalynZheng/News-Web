<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
	<title>MainPage</title>
	<link type="text/css" rel="stylesheet" href="StyleSheet.css">
</head>

<body>
<h2 style="text-align:center">News</h2>
<?php 
//distinguish user or unuser
//check if user has logined in
if(isset($_SESSION['user_id']))
	$user_id = $_SESSION['user_id'];
else
	$user_id = 0;
//echo $user_id;
//0 means unlogged 
$token = $_SESSION['token'];
if($user_id == 0)
{?>

<div style="text-align:center" >

<a href="mainpage.php">Home</a>
<a href="search.php">Search</a>
<a href="login.php">Login</a>
<a href="register.php">Register</a>

</div>



<?php
}	
//for logined 
else
{?>
<div class = "fa">

	<form action = 'addStory.php' class="centered" method = 'POST'>
            <input type='hidden' name='token' value="<?php echo $_SESSION['token'];?>" />         
            <input type='submit' class="centered" name = 'editcomment' value='Add Your Story'>
    </form>
</div>
<div style="text-align:center" > 
<a href="mainpage.php">Home</a>
<a href="ownPage.php">Own</a>
<a href="likedNews.php">Liked</a>
<a href="search.php">Search</a>
<a href="logout.php">Logout</a>

</div>

<?php } 
//tags
$social = "SOCIAL";
$science = "SCIENCE";
$health = "HEALTH";
$politic = "POLITIC";
$other = "OTHER";
?>


<?php

echo 
'<div style="text-align:center" >  

<a href = "tag.php?tagname=' . $social .'">Social</a>
<a href="tag.php?tagname=' . $science . '">Science</a>
<a href="tag.php?tagname=' . $health . '">Health</a>
<a href="tag.php?tagname=' . $politic . '">Politic</a>
<a href="tag.php?tagname=' . $other . '">Other</a>

</div>';

function add($user_id, $sql){
	//enter succeed 
	require 'database.php';

	$stmt = $mysqli->prepare($sql);

	if(!$stmt){
		
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	
	$stmt->execute();
	
	$stmt->bind_result($title, $content, $date, $tag, $story_id, $userid, $image);
			
		echo "<ul>\n";
		while($stmt->fetch()){
			echo '<li><a href = "storypage.php?story_id=' . $story_id . '">' . $title . '</a></li>';
			printf("\t<li>%s %s %s</li>\n",				
				htmlspecialchars($content),
				htmlspecialchars($date),
				htmlspecialchars($tag)
			);
			if(strlen($image) > 0){
				echo '<p style="text-align: center"><img src="' . $image .'" alt = "" height="100" width="200"></p>';
			}
			if($user_id != 0 && $user_id == $userid){
				echo "
				<li><form action = 'deletestory.php' method = 'POST'>
				<input type='hidden' name='token' value='" . $_SESSION['token'] . "' />
				<input type='hidden' name='story_id' value='" . $story_id . "' />
				<input type='submit' value='Delete'></li>
				</form>
				";
			}
			
		}
		echo "</ul>\n";

}


$sql = "select title, content, stories.date, tag, story_id, user_id, image from stories";
add($user_id, $sql);

?>




</body>
</html>