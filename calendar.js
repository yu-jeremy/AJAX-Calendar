/* global $ */
/* global Month */
/* global ddate */
/* global adate */
/* global bdate */
/* global sstring */
/* global tempdate */
/* global selectedTag */
/* global returnedObject */

// initializing global variables
var currentMonth = new Month(2017, 9); // October 2017
var selectedCell = null;
var ddate = "";
var adate = "";
var sstring = "";
var tempdate = "";
var bdate = "";
var selectedTag = "all";


// all the things we want to do with the document is ready
$(document).ready(function() {
  
  // update the calendar and all dynamic content
  changeMonthTitle();
  updateCalendar();
  getEvents();
  tagger();
  
  // change month when previous button is pressed
  $("#prev_month_btn").click(function() {
    currentMonth = currentMonth.prevMonth();
    changeMonthTitle();
    updateCalendar();
  });
  
  // Change the month when the "next" button is pressed
  $("#next_month_btn").click(function() {
    currentMonth = currentMonth.nextMonth(); 
    changeMonthTitle();
    updateCalendar(); // Whenever the month is updated, we'll need to re-render the calendar in HTML
  });
  
  $("#login_form").hide();
  $("#loginbtn").click(function () {
    $("#login_form").toggle();
  });
  
  $("#register_form").hide();
  $("#registerbtn").click(function() {
    $("#register_form").toggle();
  });
  
  $("#event").hide();
  $("#toggle_event_creator").click(function () {
    $("#event").toggle();
  });
  
  // adding AM and PM to the time selector when creating an event
  $("#hour").append("<option value=None></option>");
  for (var i = 1; i <= 24; i++) {
    if (i >= 1 && i < 10) {
      $("#hour").append("<option value=0" + i + ">" + i + " AM</option>");
    } else if (i >= 10 && i < 12) {
      $("#hour").append("<option value=" + i + ">" + i + " AM</option>");
    } else if (i == 12) {
      $("#hour").append("<option value=12>12 PM</option>");
    } else if (i > 12 && i < 24) {
      $("#hour").append("<option value=" + i + ">" + (i - 12) + " PM</option>");
    } else if (i == 24) {
      $("#hour").append("<option value=24>12 AM</option>");
    }
  }
  
  // adding minute options to time selector
  $("#minute").append("<option value=None></option>");
  for (var j = 0; j <= 59; j++) {
    if (j < 10) {
      $("#minute").append("<option value=0" + j + ">" + j + "</option>");
    } else {
      $("#minute").append("<option value=" + j + ">" + j + "</option>");
    }
  }
});

// when the tag radio button is changed, refresh the calendar using displayAll()
$(document).on("change","input[type=radio]",function(){
    var ac=$('[name="taggers"]:checked').val();
    //console.log(ac);
    selectedTag = ac;
    displayAll();
});

// adding event listeners to many buttons
document.getElementById("new_event_btn").addEventListener("click", addEventAjax, false); // Bind the AJAX call to button click
document.getElementById("IDtoDelete_button").addEventListener("click", deleteEventAjax, false); // Bind the AJAX call to button click
document.getElementById("IDtoEdit_button").addEventListener("click", editEventAjax, false); // Bind the AJAX call to button click
document.getElementById("omega").addEventListener("click", share, false);


/*
	this function displays events on the calendar
	it's responsible for displaying events for any tag that is currently selected
	it is quite similar to the updateCalendar function
*/
function displayAll() {
  var weeks = currentMonth.getWeeks();
  var todayy = new Date();
  var datee = todayy.getFullYear()+'-'+(todayy.getMonth()+1)+'-'+todayy.getDate();
  console.log(datee);
  
  var tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate() + 1);
  var dateee = tomorrow.getFullYear()+'-'+(tomorrow.getMonth()+1)+'-'+tomorrow.getDate();
  //console.log(dateee);
  var upcomingEventsPlace = document.getElementById("upcomingEvents");
  upcomingEventsPlace.innerHTML = "";
	upcomingEventsPlace.innerHTML = "<strong>Upcoming Events: </strong>";
 
 // cycle through each day
  for (var w in weeks) {
    var days = weeks[w].getDates();
    for (var d in days) {
      //console.log(w +"d" +days[d].getDate())
      var mmonth = (currentMonth.month+1);
      mmonth = ('0' + mmonth).slice(-2);
      var yyear = currentMonth.year;
      var aday = days[d].getDate();
      var nday = aday;
      aday = ('0' + aday).slice(-2);
      bdate = yyear + "-" + mmonth + "-" + aday;
      sstring = w +"d" +days[d].getDate();
      
      // account for rollover dates
      if (w == 0 && days[d].getDate() > 20) {
        bdate = yyear + "-" + (parseInt(mmonth)-1) + "-" + aday;
      } else if (w >= 4 && days[d].getDate() < 10) {
    	if (parseInt(mmonth) == 12) {
      		bdate = (parseInt(yyear)+1) + "-" + "01" + "-" + aday;
      	} else {
      		bdate = yyear + "-" + (parseInt(mmonth)+1) + "-" + aday;
      	}
      } else {
        bdate = yyear + "-" + (mmonth) + "-" + aday;
      }
      
      // conditionals for upcoming events creative portion
      var todayTrue = false;
      if (datee == bdate) {
      	todayTrue = true;
      	//console.log(todayTrue);
      }
      var tomorrowTrue = false;
      if (dateee == bdate) {
      	tomorrowTrue = true;
      	//console.log(tomorrowTrue);
      }
      
      // list all the events that are happening for a day on the specific cell
      (function(id, theday, tuday, tumorrow) {
        var dataString = "date=" + encodeURIComponent(bdate) + "&tag=" + encodeURIComponent(selectedTag);
        var xmlHttp = new XMLHttpRequest();
        //console.log(selectedTag);
        xmlHttp.open("POST", "getEvents.php",true);
        xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        xmlHttp.send(dataString);
        xmlHttp.addEventListener("load", function(event){
          var jsonData = JSON.parse(event.target.responseText);
          
          
          var putPlace = document.getElementById(id);
          putPlace.innerHTML = theday;
          putPlace.innerHTML += "<ul class=todays_events>";
          
          if (jsonData.success === false) {
            alert("Something went wrong with the query.");
          } else if (jsonData.exist) {
          	//console.log("exists");
            var putevent = jsonData.events;
            for (var i in putevent) {
              var eventname = putevent[i];
              var str = eventname.time + " ";
              if (eventname.time === "") {
              	if ((parseInt((str).substring(0,2))) >= 12) {
              		(str).replace(":00 ", "PM");
              	} else {
              		(str).replace(":00 ", "AM");
              	}
                putPlace.innerHTML += '<li class=event_text>' + eventname.eventname + '</li>';
              } else {
                putPlace.innerHTML += '<li class=event_text>' + str + ":" + eventname.eventname + '</li>';
              }
              if (tuday === true || tumorrow === true) {
          		upcomingEventsPlace.innerHTML += "[" + eventname.eventname + "] ";
        	  }
            }
            putPlace.innerHTML += "</ul>";
          }
        }, false);
      })(sstring, nday, todayTrue, tomorrowTrue);
    }
  }
}

/* 
	this is a function that when the user clicks on a cell in the calendar
	relevant information is send to the function panel on the left
	which allows the user to create, edit, and delete events
*/
function updateCalendar() {
  var weeks = currentMonth.getWeeks();
  $("tr").remove(".dayNums");
  for (var w in weeks) {
    var days = weeks[w].getDates();
    //console.log(w);
    $("#calendar tbody").append("<tr class=dayNums></tr>");
    for (var d in days) {
      $("#calendar").find("tbody tr").eq(w).append("<td id=" + w +"d" +days[d].getDate() + " class=cell>" + "</td>"); // + days[d].getDate() + 
      $(".cell").css("vertical-align", "top");
      
      var mmonth = (currentMonth.month+1);
      mmonth = ('0' + mmonth).slice(-2);
      var yyear = currentMonth.year;
      var aday = days[d].getDate();
      aday = ('0' + aday).slice(-2);
      adate = yyear + "-" + mmonth + "-" + aday;
      
      $("#" + w + "d" + days[d].getDate()).click(function() {
      	toggleEventBox(this, "#" + w + "d", yyear, mmonth);
      });
    }
  }
  displayAll();
}

/*
 * Called by updateCalendar() to toggle the event "page"
 * depending on if the user clicked a different cell or
 * the same cell
*/
function toggleEventBox(cell, id, year, month) {
	var this_cell = cell;
    var dday = cell.getAttribute('id').split('d')[1];
    dday = ('0' + dday).slice(-2);
    
    var getw = cell.getAttribute('id').split('d')[0];
    console.log(month);
    console.log(getw);
    // fixing rollover dates here
    if (getw == 0 && dday > 20) {
      ddate = year + "-" + (parseInt(month)-1) + "-" + dday;
    } else if (getw >= 4 && dday < 10) {
    	if (month == 12) {
      		ddate = (parseInt(year)+1) + "-" + "01" + "-" + dday;
      	} else {
      		ddate = year + "-" + (parseInt(month)+1) + "-" + dday;
      	}

    } else {
      ddate = year + "-" + (month) + "-" + dday;
    }
        
    //ddate = yyear + "-" + mmonth + "-" + dday;
    if (selectedCell == this_cell && $("#event").is(":visible")) {
      $("#event").hide("1000");
    } else if (selectedCell != this_cell && $("#event").is(":visible")) {
      getEvents();
      selectedCell = this_cell;
      var gug = document.getElementById("event_adder_title");
      gug.innerHTML = "Date Selected:  <strong>" + ddate + "</strong>";
	  $("#event").show("1000");
    } else {
      getEvents();
      selectedCell = this_cell;
      var gig = document.getElementById("event_adder_title");
      gig.innerHTML = "Date Selected:  <strong>" + ddate + "</strong>";
      $("#event").show("1000");
    }
}


/*
 * Selects and displays the appropriate month label
 * depending on the current month's numerical value
*/
function changeMonthTitle() {
  var thisMonth = "";
  if (currentMonth.month === 0) {
    thisMonth += "January";
  } else if (currentMonth.month == 1) {
    thisMonth += "February";
  } else if (currentMonth.month == 2) {
    thisMonth += "March";
  } else if (currentMonth.month == 3) {
    thisMonth += "April";
  } else if (currentMonth.month == 4) {
    thisMonth += "May";
  } else if (currentMonth.month == 5) {
    thisMonth += "June";
  } else if (currentMonth.month == 6) {
    thisMonth += "July";
  } else if (currentMonth.month == 7) {
    thisMonth += "August";
  } else if (currentMonth.month == 8) {
    thisMonth += "September";
  } else if (currentMonth.month == 9) {
    thisMonth += "October";
  } else if (currentMonth.month == 10) {
    thisMonth += "November";
  } else if (currentMonth.month == 11) {
    thisMonth += "December";
  }
  document.getElementById("currentMonth").textContent = thisMonth + " " + currentMonth.year;
}


/*
 * Populates the drop-down menu that is used to 
 * share an event withanother user 
*/
function populateUserDropdown(event) {
	
	var xmlHttp = new XMLHttpRequest(); 
	xmlHttp.open("POST", "fillUserDropdown.php", true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.send(null);
	xmlHttp.addEventListener("load", function(event) {
  	var jsonData = JSON.parse(event.target.responseText);
  	var eventsParent = document.getElementById("userList");
  	eventsParent.innerHTML = "<strong>LIST OF USERNAMES</strong>";
  	eventsParent.innerHTML += "<ul>";
  	if (jsonData.exist) {  
  	  var users = jsonData.users;
      for (var user in users) {
        var username = users[user];
        eventsParent.innerHTML += "<li>" + username.username + "</li>";
      }
  	} else {
  	  alert("Failed to get users from database. Querying went wrong!");
  	}
  	eventsParent.innerHTML += "</ul>";
  }, false); 
}

/*
	function to share an event that belongs to you
	to another user
*/
function share(event) {
	var event_id = document.getElementById("alpha").value;
	var name = document.getElementById("beta").value;
  var dataString = "event_id=" + encodeURIComponent(event_id) +  "&name=" + encodeURIComponent(name);
  
  var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("POST", "share.php", true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.send(dataString); 
	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText); 
		if (jsonData.success) {
			alert("Event shared!");
		} else {
			alert("Could not share event.");
		}
		
	}, false); // Bind the callback to the load event
}


/*
 * Posts data to process_event_create.php and responds
 * with a callback function that either displays the newly
 * added event (using updateCalendar() and getEvents()) or
 * tells the user that adding this event wasn't successful
*/
function addEventAjax(event){
	
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("POST", "getCSRFToken.php",true);
    xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xmlHttp.send(null);
    xmlHttp.addEventListener("load", function(event){
	    var jsonData = JSON.parse(event.target.responseText);
	    var token = jsonData.token;
	    console.log(token);
	    
	    var eventname = document.getElementById("eventname").value;
		var description = document.getElementById("description").value;
		var tag = document.getElementById("tag").value;
		
		var hour_dropdown = document.getElementById("hour");
		var minute_dropdown = document.getElementById("minute");
		
		// somewhere here 
	
		var hour = hour_dropdown[hour_dropdown.selectedIndex].value; 
		var minute = minute_dropdown[minute_dropdown.selectedIndex].value; 
		var time = hour + ":" + minute + ":00";
		
		// Make a URL-encoded string for passing POST data:
		var dataString = "eventname=" + encodeURIComponent(eventname) + "&ddate=" + encodeURIComponent(ddate)+ "&description=" + encodeURIComponent(description)+ "&tag=" + encodeURIComponent(tag)+ "&time=" + encodeURIComponent(time)+ "&token=" + encodeURIComponent(token);
		var xmlHttp = new XMLHttpRequest(); 
		xmlHttp.open("POST", "process_event_create.php", true);
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		xmlHttp.send(dataString); 
		xmlHttp.addEventListener("load", function(event){
			var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
			if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
				alert("Your event has been added. " + jsonData.eventname + " " + jsonData.description + " " + jsonData.date + " " + jsonData.authorid + " " + jsonData.tag);
			}else{
				alert("Failed to add event: " + jsonData.message);
			}
		}, false); // Bind the callback to the load event
		updateCalendar();
		getEvents();
		tagger();
	    
	    
    }, false);
}


/*
	given an event's ID, edit an event by entering relevant information
	you can only get ID's for your own events to edit
*/
function editEventAjax(event) {
	
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("POST", "getCSRFToken.php",true);
    xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xmlHttp.send(null);
    xmlHttp.addEventListener("load", function(event){
	    var jsonData = JSON.parse(event.target.responseText);
	    var token = jsonData.token;
	    console.log(token);
	     
		  var id = document.getElementById("IDtoEdit").value;
		  var tag = document.getElementById("edittag").value;
		  var eventname = document.getElementById("edittitle").value;
		  var description = document.getElementById("editdescription").value;
		  var day = document.getElementById("editday").value;
		  day = ('0' + day).slice(-2);
		  var month = document.getElementById("editmonth").value;
		  month = ('0' + month).slice(-2);
		  var year = document.getElementById("edityear").value;
		  var date = year + "-" + month + "-" + day;
		  //console.log(date);
		  
		  var dataString = "id=" + encodeURIComponent(id) + "&eventname=" + encodeURIComponent(eventname) + "&description=" + encodeURIComponent(description)+ "&date=" + encodeURIComponent(date)+ "&token=" + encodeURIComponent(token)+ "&tag=" + encodeURIComponent(tag);
		  
		  var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
			xmlHttp.open("POST", "process_event_edit.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
			xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
			xmlHttp.send(dataString); // Send the data
			// this is the incoming jason data from the process.php file
			
			// up until here everythign is run properly
			xmlHttp.addEventListener("load", function(event){
				var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
				if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
					alert("Your event has been edited. " + jsonData.eventname + " " + jsonData.description);
				}else{
					alert("Failed to add event.");
				}
			}, false); // Bind the callback to the load event
			
			updateCalendar();
			getEvents();
			tagger();
    }, false);
}

/*
	get all the events that are happening on a specific day
	and display this information on the left panel
*/
function getEvents(event) {
	var localTag = "all";
  var dataString = "date=" + encodeURIComponent(ddate) + "&tag=" + encodeURIComponent(localTag);
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.open("POST", "getEvents.php",true);
  xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  xmlHttp.addEventListener("load", function(event){
    var jsonData = JSON.parse(event.target.responseText);
    var eventsParent = document.getElementById("existingEvents");
    eventsParent.innerHTML = "<strong>EXISTING EVENTS: (id || title) </strong>";
    eventsParent.innerHTML += "<ul>";
    if (jsonData.sucess === false) {
      alert("Something went wrong with the query.");
    } else if (jsonData.exist) {
      var events = jsonData.events;
      for (var i in events) {
        var eventname = events[i];
        eventsParent.innerHTML += '<li>' + eventname.id + " || " + eventname.eventname + '<ul>' + eventname.description + '</ul></li>';
      }
    } else {
      //alert("Something else went wrong.");
    }
    eventsParent.innerHTML += "</ul>";
  }, false);
  xmlHttp.send(dataString);
}

/*
 * Function that handles deletion of events,
 * sends data to deleteEvent.php and responds with
 * a callback function to display results of deletion
*/ 
function deleteEventAjax(event){
	
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("POST", "getCSRFToken.php",true);
    xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xmlHttp.send(null);
    xmlHttp.addEventListener("load", function(event){
	    var jsonData = JSON.parse(event.target.responseText);
	    var token = jsonData.token;
	    console.log(token);
	  var id = document.getElementById("IDtoDelete").value;
	  var dataString = "id=" + encodeURIComponent(id) + "&token=" + encodeURIComponent(token);
	  var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
		xmlHttp.open("POST", "deleteEvent.php", true); 
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	  xmlHttp.send(dataString); // Send the data
	  xmlHttp.addEventListener("load", function(event){
	    var jsonData = JSON.parse(event.target.responseText); 
	    if(jsonData.eventDeleted){
	      alert("Event deleted successfully.");
	    }else{
	      alert(jsonData.message);
	    }
	  }, false);
	  updateCalendar();
	  getEvents();
	  tagger();
    }, false);
}


/*
 * Displays tags for each user, which allow users to
 * "group" events and display only the events that
 * have that tag using a radio button group
*/
function tagger() {
	
	var place = document.getElementById("tagselector");
	var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("POST", "getTags.php", true); 
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.send(null); // Send the data
	xmlHttp.addEventListener("load", function(event){
  	
  	// works until here
  	//console.log("tick2");
    var jsonData = JSON.parse(event.target.responseText); 
    //console.log("tick2");
    console.log("tagger");
    if (jsonData.success) {
      var tags = jsonData.tagg;
      //console.log(tags);
      place.innerHTML = "<div class='taggerremove' name='tagger' id='tagger'>";
      place.innerHTML += "<input type='radio' name='taggers' value='all' checked> All Events <br>";
      //console.log("placeone");
      for (var i in tags) {
        var tag = tags[i];
        if (tag.tag !== null && tag.tag != "" && tag.tag != "all") {
        	place.innerHTML += "<input type='radio' name='taggers' value='" + tag["tag"] + "'> " + tag["tag"] +"<br>";
        }
      }
      /*
      var radioV = $('input[name="taggers"]:checked').val();
		console.log(radioV);
		*/
    } else {
    	place.innerHTML = "";
    }
      place.innerHTML += "</div>";
  }, false);
  
}





