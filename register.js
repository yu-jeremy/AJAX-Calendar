/* global $ */

/*
	this function registers a new user
*/


function registerAjax(event){

  var firstname = document.getElementById("firstname").value;
  var lastname = document.getElementById("lastname").value;
  var username = document.getElementById("reg_user").value;
  var password = document.getElementById("reg_pwd").value;


	// Make a URL-encoded string for passing POST data:
	var dataString = "firstname=" + encodeURIComponent(firstname) + "&lastname=" + encodeURIComponent(lastname) + "&reg_user=" + encodeURIComponent(username) + "&reg_pwd=" + encodeURIComponent(password);

	var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("POST", "process_register.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
	xmlHttp.send(dataString); // Send the data
	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
		if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
			alert("You were registered!");
			$("#register_form").hide();
		}else{
			alert("You were not registered!"+jsonData.message);
		}
	}, false); // Bind the callback to the load event
}


document.getElementById("reg_submit").addEventListener("click", registerAjax, false); // Bind the AJAX call to button click