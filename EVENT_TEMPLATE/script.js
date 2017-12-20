function addEventAjax(event){
    
	var eventname = document.getElementById("eventname").value; // Get the username from the form
	var authorid = document.getElementById("authorid").value; // Get the username from the form

	// Make a URL-encoded string for passing POST data:
	var dataString = "eventname=" + encodeURIComponent(eventname) + "&authorid=" + encodeURIComponent(authorid);
	
	var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("POST", "process.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
	xmlHttp.send(dataString); // Send the data
	// this is the incoming jason data from the process.php file
	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
		if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
			
			// JEREMY LOOK HERE WHAT'S WRONG////
			$("#result").html(jsonData.eventname);
			
			//alert("Yay! " + jsonData.message);
		}else{
			alert("Booo. "+jsonData.message);
		}
	}, false); // Bind the callback to the load event
}

document.getElementById("newevent").addEventListener("click", addEventAjax, false); // Bind the AJAX call to button click