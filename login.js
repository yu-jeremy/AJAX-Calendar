/* global $ */
/* global updateCalendar */
/* global tagger */
/* global populateUserDropdown */

/*
	this function logs somebody in and shows/hides
	relevant information as well as updates the calendar
*/

function loginAjax(event){

	var username = document.getElementById("login_user").value;
	var password = document.getElementById("login_pwd").value;

	// Make a URL-encoded string for passing POST data:
	var dataString = "login_user=" + encodeURIComponent(username) + "&login_pwd=" + encodeURIComponent(password);

	var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("POST", "process_login.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
		if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
			alert("You've been Logged In!");
			$("div").remove(".taggerremove");
			console.log(jsonData.token);
			$("#login_form").hide();
			$("#register_form").hide();
			$("#user_buttons").hide();
			$("#logout").show();
			$("#tagselector").show();
			$(".box").show();
			$(".btngroup").show();
			$("#toggle_event_creator").show();
			$("#upcomingEvents").show();
			$("#user").html("Welcome " + jsonData.username + "!");
			populateUserDropdown();
			updateCalendar();
			tagger();
		}else{
			alert("You were not logged in."+jsonData.message);
		}
	}, false); // Bind the callback to the load event

	xmlHttp.send(dataString); // Send the data
}

document.getElementById("login_submit").addEventListener("click", loginAjax, false); // Bind the AJAX call to button click