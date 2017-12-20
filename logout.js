/* global $ */

/*

on logout, we want to hide the calendar
hide add event button
hide logout button
hide month title and month traversal buttons 

*/
function logoutAjax(event){

	var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("POST", "process_logout.php", true); 
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
    xmlHttp.send(null); // Send the data
    xmlHttp.addEventListener("load", function(event){
    	console.log("Logged out.");
		//Show login, hide signup, welcome and calendar 
		$("#user_buttons").show();
		$("#logout").hide();
		$("#user").html("");
		$(".box").hide();
		$(".btngroup").hide();
		$("#tagselector").hide();
		$("#toggle_event_creator").hide();
		$("#event").hide();
		$("#upcomingEvents").hide();
	}, false); // Bind the callback to the load event
}

function deleteAccount(event) {
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("POST", "deleteAccount.php", true); 
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
    xmlHttp.send(null); // Send the data
    xmlHttp.addEventListener("load", function(event){
		//Show login, hide signup, welcome and calendar 
		$("#user_buttons").show();
		$("#logout").hide();
		$("#user").html("");
		$(".box").hide();
		$("#upcomingEvents").hide();
		$(".btngroup").hide();
		$("#tagselector").hide();
		$("#toggle_event_creator").hide();
		$("#event").hide();
		alert("Your account has been deleted.");
	}, false); // Bind the callback to the load event
}


document.getElementById("logoutbtn").addEventListener("click", logoutAjax, false); // Bind the AJAX call to button click
document.getElementById("deleteAccount").addEventListener("click", deleteAccount, false); // Bind the AJAX call to button click