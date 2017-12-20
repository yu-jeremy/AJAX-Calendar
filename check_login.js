/*global $*/

/*
	this function checks if the user is currently logged in
	or not, and shows/hides things accordingly. this is relevant
	when the user logs in, closes the window, and reopens the window
*/

$(document).ready(function() {
	checkLogin();
});

function checkLogin(event) {
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("POST", "check_login.php", true);
	xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
		var isLoggedIn = jsonData.isLoggedIn;
		var username = jsonData.username;
		var id = jsonData.id;
		if(isLoggedIn){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
			// hide login and signup, show welcome and calendar
			populateUserDropdown();
			$(".box").show();
			$(".btngroup").show();
			$("#toggle_event_creator").show();
			$("#user_buttons").hide();
			$("#logout").show();
			$("#tagselector").show();
			$("#upcomingEvents").show();
			$("#user").html("Welcome " + username);
		}else{
			$(".box").hide();
    		$(".btngroup").hide();
    		$("#toggle_event_creator").hide();
    		$("#user_buttons").show();
    		$("#tagselector").hide();
    		$("#upcomingEvents").hide();
    		$("#logout").hide();
		}
	}, false); // Bind the callback to the load event
    xmlHttp.send(null);
}

