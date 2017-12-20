<?php 
    ini_set("session.cookie_httponly", 1);
    session_start();
    require("db_connect.php");
    $previous_ua = @$_SESSION['useragent'];
    $current_ua = $_SERVER['HTTP_USER_AGENT'];
    
    if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
    	die("Session hijack detected");
    }else{
    	$_SESSION['useragent'] = $current_ua;
    }
?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>AJAX Calendar</title>
    <!-- own CSS -->
    <link rel="stylesheet" href="calendar.css">
</head>
<body>
    
    
    
    <div class="container">
        <div class="smallcontainer">
            <div id="user"></div>
            <div id="user_buttons">
                <input id="loginbtn" type="button" name="login" value="Login">
                <input id="registerbtn" type="button" name="register" value="Register An Account">
            </div>
            
            <!-- 
                main panel
                - see existing events 
                - create event
                - edit events
                - delete events
            -->
            
          
          <!-- login form is only displayed when the user is not logged in -->
            <div id="login_form">
                <div class="user_data">Login</div>
                <div class="user_data">
                  <label for="login_user">Username:</label>
                  <input type="text" id="login_user" name="login_user" placeholder="Enter Username here">
                </div>
                <div class="user_data">
                    <label for="login_pwd">Password:</label>
                    <input type="password" id="login_pwd" name="login_pwd" placeholder="Enter Password here">
                </div>
                <div class="user_data">
                    <input type="submit" id="login_submit" name="login_submit">
                </div>
            </div>
            <div id="register_form">
                <div class="user_data">Register A User</div>
                <div class="user_data">
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" placeholder="Enter first name here">
                </div>
                <div class="user_data">
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" placeholder="Enter last name here">
                </div>
                <div class="user_data">
                    <label for="reg_user">Username:</label>
                    <input type="text" id="reg_user" name="reg_user" placeholder="Enter Username here">
                </div>
                <div class="user_data">
                    <label for="reg_pwd">Password:</label>
                    <input type="password" id="reg_pwd" name="reg_pwd" placeholder="Enter Password here">
                </div>
                <div class="user_data">
                    <input type="submit" id="reg_submit" name="reg_submit">
                </div>
            </div>
            <div id="logout">
                <button id="logoutbtn">Log Out</button>
                <button id="deleteAccount">Delete Account</button>
            </div>
            <br>
            
            <div id="tagselector">
               
            </div>
            <br>
            <div id="event">
                <div class="event_data">
                    <div id="event_adder_title"></div>
                </div>
                <div class="event_data" id="existingEvents"></div>
                <div class="event_data">
                    <br/>
                    <strong>CREATE A NEW EVENT BELOW</strong>
                </div>
                <div class="event_data">
                    <label for="eventname">Event Name:</label>
                    <input id="eventname" type="text" name="eventname">
    
                </div>
                <div class="event_data">
                    <label for="description">Event Description:</label>
                    <input id="description" type="text" name="description">
                </div>
                <div class="event_data">
                    <label for="hour">Event Time:</label>
                    <select id="hour" name="hour">
                      <option value="" disabled selected>Hour</option>
                    </select>
                    <select id="minute" name="minute">
                      <option value="" disabled selected>Minute</option>
                    </select>
                </div>
                <div class="event_data">
                    <label for="tag">Optional Tag:</label>
                    <input id="tag" type="text" name="tag">
                </div>
                <div class="event_data">
                    <button id="new_event_btn">Create</button>
                </div>
                <br/>
                <div class="event_data">
                    <strong>DELETE AN EVENT BELOW</strong>
                </div>
                <div class="event_data">
                    <label for="IDtoDelete">Event ID: </label>
                    <input id="IDtoDelete" type="text" name="IDtoDelete">
                    
                </div>
                <div class="event_data">
                    <button id="IDtoDelete_button">Delete</button>
                </div>
                <br/>
                <div class="event_data">
                    <strong>EDIT AN EVENT BELOW</strong>
                </div>
                <div class="event_data">
                    <label for="IDtoEdit">Event ID: </label>
                    <input id="IDtoEdit" type="text" name="IDtoEdit">
                </div>
                <div class="event_data">
                    <label for="edittitle">New Event Title: </label>
                    <input id="edittitle" type="text" name="edittitle">
                </div>
                <div class="event_data">
                    <label for="edittag">New Event Tag: </label>
                    <input id="edittag" type="text" name="edittag">
                </div>
                <div class="event_data">
                    <label for="editdescription">New Description: </label>
                    <input id="editdescription" type="text" name="editdescription">
                </div>
                <div class="event_data">
                    <label for="editday">New Day: </label>
                    <input id="editday" type="text" name="editday">
                   
                </div>
                <div class="event_data">
                    <label for="editmonth">New Month: </label>
                    <input id="editmonth" type="text" name="editmonth">
                </div>
                <div class="event_data">
                    <label for="edityear">New Year: </label>
                    <input id="edityear" type="text" name="edityear">
                </div>
                <div class="event_data">
                     <button id="IDtoEdit_button">Edit</button>
                </div>
                <br>
                <div class="event_data">
                    <strong>SHARE AN EVENT WITH ANOTHER USER</strong>
                </div>
                <div class="event_data">
                    <label for="alpha">Your Event ID:</label>
                    <input id="alpha" type="text" name="alpha">
                </div>
                <div class="event_data">
                    <label for="beta">Other User's Username: </label>
                    <input id="beta" type="text" name="beta">
                </div>
                <div class="event_data">
                    <button id="omega">Share</button>
                </div>
                <div class="event_data">
                    <div id="userList">
                    
                </div>
                </div>
                
                <br>
          </div>
        </div>
        
        <div class="calendargroup">
            <div class="btngroup">
                <div class="monthbtn">
                    <input id="prev_month_btn" type="button" name="prevmonth" value="&#8592;">
                    <h1 class="currMonth" id="currentMonth"></h1>
                    <input id="next_month_btn" type="button" name="nextmonth" value="&#8594;">
                </div>
            </div>
            <br>
            <div id="upcomingEvents">
               
            </div>
            <br>
            <!-- the calendar starts here -->
            <div class="box">
                <table id="calendar">
                    <thead>
                        <tr class="days">
                            <th>Sunday</th>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th>Saturday</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

  <!-- load jQuery, jQuery UI, JS for Calendar API (from wiki), and custom JS for this home page, respectively -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="calendar.min.js"></script>
  <script src="calendar.js"></script>
  <script src="login.js"></script>
  <script src="logout.js"></script>
  <script src="check_login.js"></script>
  <script src="register.js"></script>
  <!--<script src="event.js"></script>-->
  
  </body>
</html>