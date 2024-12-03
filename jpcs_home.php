<?php

include 'includes/session.php';
include 'includes/header_jpcs.php';

function is_active_election($conn){
	$sql = "SELECT * FROM election WHERE title = 'JPCS - Junior Philippine Computer Society Election' && status = 1";
	$result = $conn->query($sql);

	if($result->num_rows > 0){
		return true;
	} else{
		return false;
	}
}

if(!is_active_election($conn)){
	header("location: no_election_jpcs.php");
	exit();
}

?>

<body class="hold-transition skin-green layout-top-nav">
<div class="wrapper">

	<?php include 'includes/navbar_jpcs.php'; ?>
	 
	  <div class="content-wrapper">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
          <div class="image-container">
	      	<h1 class="page-header text-center title">
	      		<b>JUNIOR PHILIPPINE COMPUTER SOCIETY<br> ELECTIONS</b>
            </h1>
            <section class="discover section" id="discover">      
        <!--<center><h4 id="electionTitle" class="heading">Remaining time to vote</h4></center>-->
        <div class="timer">
            <!--<div class="sub_timer">
                <h1 id="day" class="digit">00</h1>
                <p class="digit_name">Days</p>
            </div>-->
            <div class="sub_timer">
                <h1 id="hour" class="digit">00</h1>
                <p class="digit_name">Hours</p>
            </div>
            <div class="sub_timer">
                <h1 id="min" class="digit">00</h1>
                <p class="digit_name">Minutes</p>
            </div>
            <div class="sub_timer">
                <h1 id="sec" class="digit">00</h1>
                <p class="digit_name">Seconds</p>
            </div>
        </div>
    </section>
</div>
	        <div class="row">
	        	<div class="col-sm-10 col-sm-offset-1">
	        		<?php
					    if(isset($_SESSION['error'])){
					        ?>
					        <div class="alert alert-danger alert-dismissible" id="error-alert">
					            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					            <ul>
					                <?php
					                    foreach($_SESSION['error'] as $error){
					                        echo "<li>".$error."</li>";
					                    }
					                ?>
					            </ul>
					        </div>
					        <?php
					        unset($_SESSION['error']);
					    }
					    if(isset($_SESSION['success'])){
					        echo "
					            <div class='alert alert-success alert-dismissible' id='success-alert'>
					                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					                <h4><i class='icon fa fa-check'></i> Success!</h4>
					                ".$_SESSION['success']."
					            </div>
					        ";
					        unset($_SESSION['success']);
					    }
					?>

					<div class="alert alert-danger alert-dismissible" id="alert" style="display:none;">
					    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					    <span class="message"></span>
					</div>

			        <script>
					    // Function to hide alerts after 3 seconds
					    function hideAlerts() {
					        setTimeout(function() {
					            document.getElementById('error-alert').style.display = 'none';
					            document.getElementById('success-alert').style.display = 'none';
					            document.getElementById('alert').style.display = 'none';
					        }, 3000); // 3 seconds
					    }

					    // Call the function when the page is loaded
					    window.onload = function() {
					        hideAlerts();
					    };
					</script>

				    <?php
				    	$sql = "SELECT * FROM votes WHERE voters_id = '".$voter['id']."'";
				    	$vquery = $conn->query($sql);
				    	if($vquery->num_rows > 0){
				    		?>
				    		<div class="text-center">
					    		<h3>You have already voted for this election.</h3>
					    		<a href="#view" data-toggle="modal" class="btn btn-flat btn-primary btn-lg">View Ballot</a>
					    	</div>
				    		<?php
				    	}
				    	else{
				    		?>

			    			<!-- Voting Ballot -->
						    <form method="POST" id="ballotForm" action="submit_ballot_jpcs.php">
                            <?php
session_start();

// Verify the database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch the user's ID from session
if (isset($voter['id'])) {
    $userId = $voter['id'];

    // Fetch the user's organization and set it in the session (if not already set)
    if (!isset($_SESSION['yearLvl'])) {
        $userQuery = "SELECT yearLvl FROM voters WHERE id = '$userId'";
        $userResult = $conn->query($userQuery);
        if ($userResult) {
            if ($userResult->num_rows > 0) {
                $userRow = $userResult->fetch_assoc();
                $_SESSION['yearLvl'] = $userRow['yearLvl'];
            } else {
                echo "No yearLvl found for user ID: $userId";
            }
        } else {
            echo "Error in query: " . $conn->error;
        }
    }
} else {
    echo "User ID not set in session.";
}

                                include 'includes/slugify.php';

                                $positions = [
                                    'President',
                                    'VP for Internal Affairs',
                                    'VP for External Affairs',
                                    'Secretary',
                                    'Treasurer',
                                    'Auditor',
                                    'P.R.O',
                                    'Dir. for Membership',
                                    'Dir. for Special Project',
                                ];

                                if (isset($_SESSION['yearLvl'])) {
                                    switch ($_SESSION['yearLvl']) {
                                        case '1-A':
                                            $positions[] = '2-A Rep';
                                            break;
                                        case '1-B':
                                            $positions[] = '2-B Rep';
                                            break;
                                        case '2-A':
                                            $positions[] = '3-A Rep';
                                            break;
                                        case '2-B':
                                            $positions[] = '3-B Rep';
                                            break;
                                        case '3-A':
                                            $positions[] = '4-A Rep';
                                            break;
                                        case '3-B':
                                            $positions[] = '4-B Rep';
                                            break;
                                    }
                                }

                                $candidate = '';
                                $sql = "SELECT * FROM categories WHERE election_id = 1 ORDER BY priority ASC";
                                $query = $conn->query($sql);
                                while($row = $query->fetch_assoc()){
                                    if (!in_array($row['name'], $positions)) {
                                        continue; // Skip positions not in the list
                                    }
                                      echo '
                                    <div class="position-container">
                                        <div class="box box-solid" id="'.$row['id'].'">
                                            <div class="box-header" style="background-color: darkgreen;">
                                                <h3 class="box-title" style="color: #fff;">'.$row['name'].'</h3>
                                                <button type="button" class="btn btn-success btn-sm btn-flat reset" data-desc="'.slugify($row['name']).'"><i class="fa fa-refresh"></i> Reset</button>
                                            </div>
                                            <div class="box-body">
                                                <p class="instruction">You may select up to '.$row['max_vote'].' candidates</p>
                                                <div class="candidate-list">
                                                    <ul>';
                                    $sql = "SELECT * FROM candidates WHERE category_id='".$row['id']."'";
                                    $cquery = $conn->query($sql);
                                    while ($crow = $cquery->fetch_assoc()) {
                                        $slug = slugify($row['name']);
                                        $checked = '';
                                        if (isset($_SESSION['post'][$slug]) && $_SESSION['post'][$slug] == $crow['id']) {
                                            $checked = 'checked';
                                        }
                                    
                                        $inputId = $slug . '_' . $crow['id']; // Generate a unique ID for the input
                                        $image = (!empty($crow['photo'])) ? 'images/' . $crow['photo'] : 'images/profile.jpg';
                                    
                                        echo '
                                        <li class="candidate-item">
                                            <input type="radio" id="' . $inputId . '" class="candidate-radio" name="' . slugify($row['name']) . '" value="' . $crow['id'] . '" ' . $checked . ' hidden>
                                            <label for="' . $inputId . '" class="candidate-label">
                                                <img src="' . $image . '" alt="' . $crow['firstname'] . ' ' . $crow['lastname'] . '" class="candidate-image">
                                                <span class="cname">' . $crow['firstname'] . ' ' . $crow['lastname'] . '</span>
                                            </label>
                                        </li>';
                                    }                                    
                                    
                                echo '</ul>
                            </div>
                        </div>
                    </div>
                </div>';
            }
            ?>
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary btn-flat" style="background-color: darkgreen;" id="submitBtn"><i class="fa fa-check-square-o"></i> Submit</button>
                                </div>

                                <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <p>Are you sure you want to submit your vote?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-success btn-flat" id="preview_jpcs"><i class="fa fa-file-text"></i> Preview</button> 
                                            <button type="submit" class="btn btn-primary" id="submitBtn" name="vote_jpcs">Yes, Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            </form>
				        	<!-- End Voting Ballot -->
				    		<?php
				    	}

				    ?>

	        	</div>
	        </div>
	      </section>
	     
	    </div>
	  </div>
  
  	<?php include 'includes/footer.php'; ?>
  	<?php include 'includes/ballot_modal_jpcs.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
  document.querySelectorAll('.candidate-list li img').forEach(img => {
    img.addEventListener('click', (event) => {
        // Prevent click from propagating to parent elements
        event.stopPropagation();
        
        // Remove selection from all candidates
        document.querySelectorAll('.candidate-list li').forEach(li => li.classList.remove('selected'));
        
        // Highlight the container of the clicked image
        const parentLi = img.closest('li');
        parentLi.classList.add('selected');
    });
});

</script>
<script>
    function updateCountdown(endTime) {
        var now = new Date();
        var timeRemaining = endTime - now;
        
        // If time remaining is negative or zero, display message
        if (timeRemaining <= 0) {
            //document.getElementById("day").innerText = "00";
            document.getElementById("hour").innerText = "00";
            document.getElementById("min").innerText = "00";
            document.getElementById("sec").innerText = "00";
            document.getElementById("electionTitle").innerText = "NO ONGOING ELECTION. Stay Tuned, Madlang Pipol!";
            return;
        }

        // Calculate days, hours, minutes, and seconds remaining
        //var days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
        var hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);
        
        // Update the HTML elements with the new countdown values
        //document.getElementById("day").innerText = formatTime(days);
        document.getElementById("hour").innerText = formatTime(hours);
        document.getElementById("min").innerText = formatTime(minutes);
        document.getElementById("sec").innerText = formatTime(seconds);
    }

    // Function to format time (prepend 0 if single digit)
    function formatTime(time) {
        return time < 10 ? "0" + time : time;
    }

    // Function to fetch end time from the server
    function fetchEndTime() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'getEndTime.php', true); // Adjust the path to getEndTime.php if needed
        xhr.onreadystatechange = function () {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                if (xhr.status == 200) {
                    var endTime = new Date(xhr.responseText);
                    updateCountdown(endTime);
                    setInterval(function() {
                        updateCountdown(endTime);
                    }, 1000);
                }
            }
        };
        xhr.send();
    }
    fetchEndTime();
    $(function(){
        $('.content').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });

        $(document).on('click', '.reset', function(e){
            e.preventDefault();
            var desc = $(this).data('desc');
            $('.'+desc).iCheck('uncheck');
        });

         $('#submitBtn').click(function(event) {
            $('#confirmationModal').modal('show');
        });

        // Handle confirmation modal submission
        $('#confirmSubmit').click(function() {
            $('#ballotForm').submit();
        });

        $(document).on('click', '.platform', function(e){
            e.preventDefault();
            $('#platform').modal('show');
            var platform = $(this).data('platform');
            var fullname = $(this).data('fullname');
            $('.candidate').html(fullname);
            $('#plat_view').html(platform);
        });



        $('#preview_jpcs').click(function(e){
            e.preventDefault();
            var form = $('#ballotForm').serialize();
            if(form == ''){
                $('.message').html('You must vote at least one candidate');
                $('#alert').show();

                // Hide the alert after 3 seconds
                setTimeout(function() {
                    $('#alert').hide();
                }, 3000); // 3 seconds
            }
            else{
                $.ajax({
                    type: 'POST',
                    url: 'preview_jpcs.php',
                    data: form,
                    dataType: 'json',
                    success: function(response){
                        if(response.error){
                            var errmsg = '';
                            var messages = response.message;
                            for (i in messages) {
                                errmsg += messages[i]; 
                            }
                            $('.message').html(errmsg);
                            $('#alert').show();


                            // Hide the alert after 3 seconds
                            setTimeout(function() {
                                $('#alert').hide();
                            }, 3000); // 3 seconds
                        }
                        else{
                            $('#preview_modal').modal('show');
                            $('#preview_body').html(response.list);
                        }
                    }
                });
            }
            
        });

    });
</script>
</body>

<style>


    /* Style for the position container */
.position-container {
    margin: 20px auto; /* Center the container horizontally and add margin on top and bottom */
    max-width: 800px; /* Set a maximum width to make it responsive */
    padding: 20px; /* Add padding inside the container */
    border: 1px solid #ccc; /* Add border for visual separation */
    border-radius: 10px; /* Add border radius for rounded corners */
    background-color: #fff; /* Change background color */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.9); /* Add shadow for depth */
}

/* Style for the box header */
.box-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: black;
    color: #013220;
    padding: 10px;
}

/* Adjust space between position and reset button */
.box-header .box-title {
    margin-right: auto; /* Push position title to the left */
}

/* Style for the reset button */
.reset {
    margin-left: auto; /* Push reset button to the right */
}

/* Style for the box title */
.box-title {
    margin: 0;
    font-size: 20px;
    font-weight: 300;
}

/* Style for the box body */
.box-body {
    padding: 10px;
}

/* Style for the voting instructions */
.instruction {
    font-size: 16px;
    margin-bottom: 10px;
}

/* Style for the candidate list */
.candidate-list ul {
    list-style-type: none;
    padding: 0;
}

/* Bagong istilo para sa mga item sa listahan ng mga kandidato */
.candidate-list li {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    border-radius: 10px;
    padding: 10px;
    margin-bottom: 10px;
    background-color: #f9f9f9;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    border: 2px solid #ccc;
    cursor: pointer; /* Make the entire item clickable */
    transition: border-color 0.3s, transform 0.3s; /* Smooth effects */
}

.candidate-list li img {
    width: 100px;
    height: 100px;
    border-radius: 8px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.candidate-list li:hover img {
    transform: scale(1.1);
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
}

/* Add style for selected candidate */
.candidate-list li.selected {
    border: 2px solid darkgreen; /* Highlight with green border */
    background-color: #eaf7ea; /* Optional: light green background */
}

/* Optional hover effect for the whole container */
.candidate-list li:hover {
    transform: scale(1.02); /* Slightly enlarge the entire container */
}
/* Container style for candidate */
.candidate-card {
    display: flex;
    flex-direction: column; /* Arrange items vertically */
    align-items: center; /* Center the image, name, and button */
    padding: 10px;
    border: 2px solid #ccc;
    border-radius: 10px;
    transition: border-color 0.3s, box-shadow 0.3s;
    cursor: pointer; /* Make the whole card clickable */
    background-color: #f9f9f9; /* Default background */
}

/* Candidate image */
.candidate-image {
    width: 100px;
    height: 100px;
    border-radius: 8px;
    transition: transform 0.3s;
}

/* Candidate name and platform button container */
.candidate-details {
    text-align: center; /* Center-align name and button */
    margin-top: 10px;
}

.cname {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

/* Button styling */
.platform {
    background-color: darkgreen;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
}

/* Hover and selection effects */
.candidate-card:hover {
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
}

.candidate-card.selected {
    border-color: darkgreen; /* Highlight the selected card */
    background-color: #eaf7ea; /* Optional: light green background */
}



/* Media query para sa mas maliit na mga screen */
@media (max-width: 768px) {
    .platform {
        padding: 6px 16px; /* I-adjust ang padding para sa mas maliit na screen */
        font-size: 14px; /* I-adjust ang font size */
        width: auto; /* I-adjust ang lapad */
        font-family: sans-serif; /* Change the font family to Arial or any desired font */
    }
    .candidate-list li {
        flex-direction: column; /* Baguhin ang direksyon ng flex container sa column */
        align-items: center; /* I-align ang mga item sa gitna */
        padding: 15px; /* I-adjust ang padding para sa mas maliit na screen */
        border: 2px solid #ccc; /* Add border */
    border-radius: 10px; /* Rounded corners */
    }

    .candidate-list li img {
        width: 100px; /* I-adjust ang lapad ng mga larawan para sa mas maliit na screen */
        height: 100px; /* I-adjust ang taas ng mga larawan para sa mas maliit na screen */
        margin: 0 auto; /* Ilipat ang mga larawan sa gitna */
        display: block; /* Make the image a block element */
        transition: transform 0.3s; /* Add transition effect */
}

.candidate-list li:hover img {
    transform: scale(1.1); /* Make the image slightly larger on hover */
}
}



/* Adjusted style for candidate name */
.cname {
    font-size: 18px; /* Default font size */
    margin-left: auto; /* Push candidate name to the end */
    font-weight: bold;
}

/* Media query for smaller screens */
@media (max-width: 768px) {
    .cname {
        font-size: 15px; /* Reduce font size on smaller screens */
    }
}

/* Adjusted style for platform button */
.platform {
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 20px; /* Make it pill-shaped */
    padding: 8px 20px; /* Add padding */
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-left: auto; /* Push platform button to the end */
    display: flex; /* Use flexbox to align icon and text */
    align-items: center; /* Center items vertically */
}

.platform:hover {
    background-color: #0056b3;
}

.platform i {
    font-style: normal; /* Ibalik ang font style sa normal */
    font-weight: bold; /* I-set ang font weight sa bold */
    font-size: 14px; /* I-adjust ang font size */
}

/* Media query for smaller screens */
@media (max-width: 768px) {
  
    .platform {
        padding: 6px 16px; /* I-adjust ang padding para sa mas maliit na screen */
        font-size: 14px; /* I-adjust ang font size */
        width: auto; /* I-adjust ang lapad */
        margin: 10px auto; /* Igitna ang platform button */
    }


    .platform i.fa {
        margin-right: 0; /* Remove right margin for icon */
    }

    .platform span.text {
        display: none; /* Hide text on smaller screens */
    }
}

/* Style for the image to make it visually interactive */
.clist {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 50%; /* Circular image */
    border: 2px solid transparent;
    transition: border-color 0.3s, transform 0.3s;
    cursor: pointer;
}

input[type="radio"]:checked + .clist,
input[type="checkbox"]:checked + .clist {
    border-color: green; /* Highlight the selected image */
    transform: scale(1.1); /* Slightly enlarge the selected image */
}


/* Media query for smaller screens */
@media (max-width: 768px) {
    .position-container {
        padding: 10px; /* Adjust padding for smaller screens */
    }
}

/* Media query for larger screens */
@media (min-width: 768px) {
    /* Apply flex-end alignment to candidate image */
    .candidate-list li {
        display: flex;
        justify-content: space-between; /* Align items to the end of the container */
        align-items: center;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
        background-color: #f9f9f9;
    }

    /* Updated styles for candidate image */
    .clist {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        margin-right: 10px;
        grid-column: span 1;
    }

    /* Media query for smaller screens */
    @media (max-width: 768px) {
        .candidate-list li {
            display: flex;
            align-items: center; /* Center items vertically */
            margin-bottom: 10px;
        }

        .clist {
            width: 80px; /* Reduce image width on smaller screens */
            height: 80px; /* Reduce image height on smaller screens */
            margin-right: 10px; /* Adjust margin for smaller screens */
        }
    }
}

              /* Style for the primary button */
              .btn-primary {
    background-color: black;
    color: #fff;
    border-color: #007bff;
}

/* Style for the success button */
.btn-success {
    background-color: #28a745;
    color: #fff;
    border-color: #28a745;
}

/* Style for the secondary button */
.btn-secondary {
    color: #6c757d;
    border-color: #6c757d;
}

/* Style for the modal header */
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: black;
    color: #fff;
}

/* Style for the modal title */
.modal-title {
    margin-right: auto; /* Pushes the modal title to the left */
    font-weight: bold;
}

/* Style for the close button in the modal header */
.modal-header .close {
    padding-left: 20px; /* Adds space to the left of the close button */
    color: #fff;
    opacity: 0.5;
}

/* Style for the modal body */
.modal-body {
    padding: 20px;
}

/* Style for the modal footer */
.modal-footer {
    justify-content: space-between;
    padding: 20px;
}

/* Center the text in the text-center div */
.text-center {
    text-align: center;
}

.content {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    background-image: url('your-background-image.jpg');
    background-size: cover;
    background-position: center;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
.page-header {
    margin-top: 10px; /* Increase margin from the top */
    padding: 80px 20px; /* Increase padding on top and bottom to expand the box */
    background-image: url('images/bj.png'); /* Add background image */
    background-size: cover; /* Cover the entire background */
    background-repeat: no-repeat; /* Prevent background image from repeating */
    background-position: center; /* Center the background image */
    width: auto;
    height: auto;
    color: #fff; /* Text color */
    text-align: center; /* Center-align text */
    border-bottom: 2px solid #0056b3; /* Add a solid border at the bottom */
    border-radius: 10px; /* Add border radius for rounded corners */
    position: relative; /* Position the content relative to the box */
}

.page-header img {
    width: 100px; /* Adjust image width */
    height: 70px; /* Adjust image height */
    border-radius: 50%; /* Circular image */
    margin-bottom: 10px; /* Add margin at the bottom */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Soft shadow */
    position: absolute; /* Position the image */
    top: 50%; /* Position from the top */
    left: 50%; /* Position from the left */
    transform: translate(-50%, -50%); /* Center the image */
}


.title {
    font-size: 40px; /* Decrease font size */
    margin-bottom: 10px; /* Decrease margin bottom */
    font-family: 'Arial', sans-serif; /* Change font family */
    font-weight: bold; /* Bold font weight */
    text-transform: uppercase; /* Uppercase text */
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.9); /* Add shadow effect */
}

.subtitle {
    font-size: 18px; /* Decrease font size */
    font-weight: 300; /* Decrease font weight */
}

/* Responsive Styling */
@media (max-width: 768px) {
    .page-header {
        padding: 100px 20px; /* Adjust padding for smaller screens */
    }
    
    .page-header img {
        width: 100px; /* Adjust image width for smaller screens */
    }

    .title {
        font-size: 24px; /* Decrease font size for smaller screens */
    }

    .subtitle {
        font-size: 14px; /* Decrease font size for smaller screens */
    }
}


.timer {
    position: fixed;
    top: 0;
    right: 0;
    z-index: 1000;
    width: 250px;
    display: flex;
    justify-content: center;
    margin-top: 570px;
    margin-right: 12px;
    margin-bottom: 30px;
}

.sub_timer {
    width: 90px;
    background: rgba(255, 255, 255, 0.19);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    overflow: hidden;
    height: 100px;
    margin-left: 10px;
}

.digit {
    color: black;
    font-weight: lighter;
    font-size: 30px;
    text-align: center;
}

.digit_name {
    color: #000;
    background: lightgrey;
    text-align: center;
    font-size: 15px;
}

/* Media query for larger screens */
@media (min-width: 1024px) {
    .timer {
        position: fixed;
        bottom: 0;
        right: 0;
        z-index: 1000;
        width: 250px;
        display: flex;
        justify-content: center;
        margin-bottom: 30px;
        margin-right: 12px;
        margin-top: 50px; /* Override margin-top */
    }
}

/* Media query for smaller screens */
@media (max-width: 768px) {
    .timer {
        position: relative;
        width: 100%;
        margin-top: 20px;
        margin-right: 0;
        margin-bottom: 20px;
    }

    .sub_timer {
        width: 70px;
        height: 80px;
    }

    .digit {
        font-size: 24px;
    }

    .digit_name {
        font-size: 12px;
    }
}
</style>
</body>
</html>