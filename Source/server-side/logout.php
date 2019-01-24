<?php
	//This file is used for a user to logout of the current session
	//all session data is wiped with session_destroy()
	session_start();
	session_destroy();

	//after wiping session, redirect user to login screen
	echo "<script>
				alert('You have successfully logged out');
				window.location.href='../index.html';
				</script>";

?>