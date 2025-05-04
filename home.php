<?php

include 'includes/session.php';
include 'includes/header_code.php';

function is_active_election($conn){
	$sql = "SELECT * FROM election WHERE title = 'CSC - College of Student Council Election' && status = 1";
	$result = $conn->query($sql);

	if($result->num_rows > 0){
		return true;
	} else{
		return false;
	}
}

if(!is_active_election($conn)){
	header("location: no_active_election_home.php");
	exit();
}

?>

<body class="hold-transition skin-black layout-top-nav">
<div class="wrapper">

	<?php include 'includes/navbar_code.php'; ?>
	 
	  <div class="content-wrapper">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
          <div class="image-container">
	      	<h1 class="page-header text-center title">
	      		<b>COLLEGE STUDENT COUNCIL <br> ELECTIONS</b>
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
                            
                            // Trigger confetti when success message is shown
                            echo "<script>
                                  document.addEventListener('DOMContentLoaded', function() {
                                      startConfetti();
                                  });
                                </script>";
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
				    	$sql = "SELECT * FROM votes_csc WHERE voters_id = '".$voter['id']."'";
				    	$vquery = $conn->query($sql);
				    	if($vquery->num_rows > 0){
				    		?>
				    		<div class="text-center">
					    		<h3>You have already voted for this election.</h3>
					    		<a href="#view" data-toggle="modal" class="btn btn-flat btn-primary btn-lg">View Ballot</a>
					    	</div>
                            <div id="live-poll-results" class="poll-container">
    <!-- Poll results will be loaded here using AJAX -->
</div>
<script>
    function updatePollResults() {
        fetch('fetch_poll_results.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                const pollContainer = document.getElementById("live-poll-results");
                pollContainer.innerHTML = data;
                
                // Add animation for updated results
                pollContainer.style.transition = 'opacity 0.5s ease';
                pollContainer.style.opacity = '0';
                setTimeout(() => {
                    pollContainer.style.opacity = '1';
                }, 100);
            })
            .catch(error => {
                console.error('Error fetching poll results:', error);
                document.getElementById("live-poll-results").innerHTML = 
                    '<p class="poll-error">Unable to load poll results. Please try again later.</p>';
            });
    }

    // Initial fetch and update every 5 seconds for smoother performance
    updatePollResults();
    setInterval(updatePollResults, 5000);
</script>

<!-- Updated CSS for Live Poll Styling -->
<style>
    .poll-container {
        background-color: #f9f9f9;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
        border: 2px solid black;
    }

    .poll-container h3 {
        color: black;
        font-size: 1.5rem;
        margin-bottom: 15px;
        text-align: center;
        font-weight: bold;
    }

    .poll-container ul {
        list-style: none;
        padding: 0;
    }

    .poll-container li {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #ccc;
        font-size: 1rem;
        color: #333;
    }

    .poll-container li:last-child {
        border-bottom: none;
    }

    .poll-error {
        color: #dc3545;
        text-align: center;
        font-size: 1rem;
    }
</style>
        </div>
        <?php
    }
    else{
				    		?>
			    			<!-- Voting Ballot -->
						    <form method="POST" id="ballotForm" action="submit_ballot.php">
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
    if (!isset($_SESSION['organization'])) {
        $userQuery = "SELECT organization FROM voters WHERE id = '$userId'";
        $userResult = $conn->query($userQuery);
        if ($userResult) {
            if ($userResult->num_rows > 0) {
                $userRow = $userResult->fetch_assoc();
                $_SESSION['organization'] = $userRow['organization'];
            } else {
                echo "No organization found for user ID: $userId";
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
                                    'Vice President',
                                    'Secretary',
                                    'Treasurer',
                                    'Auditor',
                                    'P.R.O',
                                    'Business Manager',
                                ];                                

                                if (isset($_SESSION['organization'])) {
                                    switch ($_SESSION['organization']) {
                                        case 'JPCS':
                                            $positions[] = 'BSIT Rep';
                                            break;
                                        case 'HMSO':
                                            $positions[] = 'BSHM Rep';
                                            break;
                                        case 'PASOA':
                                            $positions[] = 'BSOAD Rep';
                                            break;
                                        case 'YMF':
                                            if (isset($_SESSION['major']) && $_SESSION['major'] == 'BSED') {
                                                $positions[] = 'BSED Rep';
                                            } elseif (isset($_SESSION['major']) && $_SESSION['major'] == 'BEED') {
                                                $positions[] = 'BEED Rep';
                                            }
                                            break;
                                        case 'CODE-TG':
                                            $positions[] = 'BS CRIM Rep';
                                            break;
                                    }
                                }

                                $candidate = '';
                                $sql = "SELECT * FROM categories WHERE election_id = 20 ORDER BY priority ASC";
                                $query = $conn->query($sql);
                                while($row = $query->fetch_assoc()){
                                    if (!in_array($row['name'], $positions)) {
                                        continue; // Skip positions not in the list
                                    }
                                      echo '
                                    <div class="position-container">
                                        <div class="box box-solid" id="'.$row['id'].'">
                                            <div class="box-header" style="background-color: #000000;">
                                                <h3 class="box-title" style="color: #fff;">'.$row['name'].'</h3>
                                                <button type="button" class="btn btn-success btn-sm btn-flat reset" data-desc="'.slugify($row['name']).'"><i class="fa fa-refresh"></i> Reset</button>
                                            </div>
                                            <div class="box-body">
                                                <p class="instruction">You may select up to '.$row['max_vote'].' candidates</p>
                                                <div class="candidate-list">
                                                    <ul>';
                                                    $sql = "SELECT * FROM candidates WHERE category_id='" . $row['id'] . "'";
                                                    $cquery = $conn->query($sql);
                                                    
                                                    while ($crow = $cquery->fetch_assoc()) {
                                                        $slug = slugify($row['name']);
                                                        $image = (!empty($crow['photo'])) ? 'images/' . $crow['photo'] : 'images/profile.jpg';
                                                        $maxVote = $row['max_vote']; // Fetch max vote per position
                                                        
                                                        if(isset($_SESSION['post'][$slug])){
                                                            $value = $_SESSION['post'][$slug];
                                                        
                                                            if(is_array($value)){
                                                                foreach($value as $val){
                                                                    if($val == $crow['id']){
                                                                        $checked = 'checked';
                                                                    }
                                                                }
                                                            }
                                                            else{
                                                                if($value == $crow['id']){
                                                                    $checked = 'checked';
                                                                }
                                                            }
                                                        }
                                                    
                                                        $inputId = $slug.'_'.$crow['id']; // Generate a unique ID for the input
                                                        $input = ($maxVote > 1) ? 
                                                            '<input type="checkbox" id="'.$inputId.'" class="flat-red '.$slug.'" name="'.$slug."[]".'" value="'.$crow['id'].'" '.$checked.'>' : 
                                                            '<input type="radio" id="'.$inputId.'" class="flat-red '.$slug.'" name="'.slugify($row['name']).'" value="'.$crow['id'].'" '.$checked.'>';
                                                    
                                                        // Generate candidate container
                                                        echo '<div class="candidate-container" 
                                                                    data-id="' . $crow['id'] . '" 
                                                                    data-position="' . $slug . '" 
                                                                    data-max-vote="' . $maxVote . '">
                                                                <img src="' . $image . '" alt="' . $crow['firstname'] . ' ' . $crow['lastname'] . '" class="candidate-image"> <br>
                                                                <span class="candidate-name">' . $crow['firstname'] . ' ' . $crow['lastname'] . '</span> <br>
                                                                 <button type="button" style="background-color: #000000;" class="btn btn-primary btn-sm btn-flat platform" data-platform="'.$crow['platform'].'" data-fullname="'.$crow['firstname'].' '.$crow['lastname'].'">PLATFORM</button>
                                                                </button>
                                                            </div>';
                                                    
                                                    
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
                                        <div class="modal-header" style="background-color: black">
                                            <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <p>Are you sure you want to submit your vote?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-success btn-flat" id="preview"><i class="fa fa-file-text"></i> Preview</button> 
                                            <button type="submit" class="btn btn-primary" id="submitBtn" name="vote">Yes, Submit</button>
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
  	<?php include 'includes/ballot_modal.php'; ?>
</div>

<!-- Confetti Canvas -->
<canvas id="confetti-canvas" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 9999;"></canvas>

<?php include 'includes/scripts.php'; ?>

<!-- Confetti JS Code -->
<script>
    // Confetti script
    var confettiCanvas = document.getElementById('confetti-canvas');
    var confettiContext = confettiCanvas.getContext('2d');
    var supportsAnimationFrame = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame;
    var colors = ['#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5', '#2196f3', '#03a9f4', '#00bcd4', '#009688', '#4CAF50', '#8BC34A', '#CDDC39', '#FFC107', '#FF9800', '#FF5722'];
    var streamingConfetti = false;
    var animationTimer = null;
    var particles = [];
    var waveAngle = 0;

    function resetParticle(particle, width, height) {
        particle.color = colors[(Math.random() * colors.length) | 0];
        particle.x = Math.random() * width;
        particle.y = Math.random() * height - height;
        particle.diameter = Math.random() * 10 + 5;
        particle.tilt = Math.random() * 10 - 10;
        particle.tiltAngleIncrement = Math.random() * 0.07 + 0.05;
        particle.tiltAngle = 0;
        return particle;
    }

    function startConfetti() {
        var width = window.innerWidth;
        var height = window.innerHeight;
        confettiCanvas.width = width;
        confettiCanvas.height = height;
        
        particles = [];
        for (var i = 0; i < 150; i++) {
            particles.push(resetParticle({}, width, height));
        }
        
        streamingConfetti = true;
        if (animationTimer === null) {
            (function runAnimation() {
                if (streamingConfetti) {
                    confettiContext.clearRect(0, 0, confettiCanvas.width, confettiCanvas.height);
                    
                    for (var i = 0; i < particles.length; i++) {
                        var particle = particles[i];
                        particle.tiltAngle += particle.tiltAngleIncrement;
                        particle.y += (Math.cos(waveAngle) + particle.diameter + 0.1) * 0.5;
                        particle.tilt = Math.sin(particle.tiltAngle) * 15;
                        
                        if (particle.y > height) {
                            if (!streamingConfetti && particles.length <= 0) {
                                confettiContext.clearRect(0, 0, width, height);
                                animationTimer = null;
                                return;
                            }
                            
                            if (streamingConfetti) {
                                resetParticle(particle, width, height);
                            } else {
                                particles.splice(i, 1);
                                i--;
                                continue;
                            }
                        }
                        
                        confettiContext.beginPath();
                        confettiContext.lineWidth = particle.diameter;
                        confettiContext.strokeStyle = particle.color;
                        var x = particle.x + particle.tilt;
                        confettiContext.moveTo(x + particle.diameter / 2, particle.y);
                        confettiContext.lineTo(x, particle.y + particle.tilt + particle.diameter / 2);
                        confettiContext.stroke();
                    }
                    
                    waveAngle += 0.01;
                }
                
                if (supportsAnimationFrame) {
                    animationTimer = requestAnimationFrame(runAnimation);
                } else {
                    animationTimer = setTimeout(runAnimation, 16.67);
                }
            })();
        }
        
        // Stop the confetti after 3 seconds and hide the canvas
        setTimeout(function() {
            stopConfetti();
            confettiCanvas.style.display = 'none';
        }, 3000);
    }

    function stopConfetti() {
        streamingConfetti = false;
    }

    function resizeCanvas() {
        confettiCanvas.width = window.innerWidth;
        confettiCanvas.height = window.innerHeight;
    }

    window.addEventListener('resize', resizeCanvas, false);
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const candidateContainers = document.querySelectorAll('.candidate-container');
    const resetButtons = document.querySelectorAll('.reset');
    const platformButtons = document.querySelectorAll('.platform-button'); // Platform button selector

    // Candidate selection logic
    candidateContainers.forEach(container => {
        container.addEventListener('click', function () {
            const position = this.getAttribute('data-position'); // Get position of candidate
            const maxVotes = parseInt(this.getAttribute('data-max-vote'), 10); // Get max vote allowed for position
            const selectedCandidates = document.querySelectorAll(`.candidate-container[data-position='${position}'].selected`);

            // Darken the candidate that is clicked on first
            if (!this.classList.contains('selected') && !this.classList.contains('unselected')) {
                this.classList.add('unselected'); // Darken the candidate on first click
            }

            // If candidate is already selected, deselect it
            if (this.classList.contains('selected')) {
                this.classList.remove('selected');
                this.classList.add('unselected'); // Mark as unselected
            } else {
                // If max votes are reached, deselect the first selected candidate to allow reselection
                if (selectedCandidates.length >= maxVotes) {
                    const earliestSelected = selectedCandidates[0];
                    earliestSelected.classList.remove('selected');
                    earliestSelected.classList.add('unselected'); // Mark as unselected
                }

                // Select the current candidate
                this.classList.add('selected');
                this.classList.remove('unselected'); // Remove unselected class to restore opacity
            }

            // Update hidden inputs for form submission
            document.querySelectorAll(`input[name='${position}[]']`).forEach(input => input.remove()); // Clear previous inputs

            document.querySelectorAll(`.candidate-container[data-position='${position}'].selected`).forEach(candidate => {
                let selectedInput = document.createElement('input');
                selectedInput.type = 'hidden';
                selectedInput.name = `${position}[]`;
                selectedInput.value = candidate.getAttribute('data-id');
                document.getElementById('ballotForm').appendChild(selectedInput);
            });

            // Update the preview section
            const previewElement = document.getElementById('preview_' + position);
            const selectedCandidatesList = Array.from(document.querySelectorAll(`.candidate-container[data-position='${position}'].selected`));
            const selectedNames = selectedCandidatesList.map(candidate => candidate.querySelector('.candidate-name').textContent);

            if (selectedCandidatesList.length > 0) {
                previewElement.innerHTML = `${position}: <strong>${selectedNames.join(', ')}</strong>`;

                // ** Update the selected candidate name display for maxVotes == 1 **
                if (maxVotes === 1) {
                    const candidateName = selectedCandidatesList[0].querySelector('.candidate-name').textContent;
                    document.getElementById('selectedCandidateName').innerText = candidateName;
                }
            } else {
                previewElement.innerHTML = `${position}: <em>No selection</em>`;

                // Clear the candidate name display if nothing is selected
                if (maxVotes === 1) {
                    document.getElementById('selectedCandidateName').innerText = '';
                }
            }
        });
    });

    // Reset button logic
    resetButtons.forEach(button => {
        button.addEventListener('click', function () {
            const position = this.getAttribute('data-desc'); // Get the position from 'data-desc'

            // Deselect all candidates for the position and restore opacity
            document.querySelectorAll(`.candidate-container[data-position='${position}']`).forEach(candidate => {
                candidate.classList.remove('selected');
                candidate.classList.remove('unselected'); // Remove the unselected class to restore opacity
            });

            // Clear hidden inputs for the position
            document.querySelectorAll(`input[name='${position}[]']`).forEach(input => input.remove());

            // Clear the preview section
            const previewElement = document.getElementById('preview_' + position);
            previewElement.innerHTML = `${position}: <em>No selection</em>`;

            // Clear the candidate name display
            document.getElementById('selectedCandidateName').innerText = '';
        });
    });

    // Platform button click logic
    platformButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            // Prevent candidate selection when clicking the platform button
            const candidateContainer = this.closest('.candidate-container');

            // If the candidate is already selected, don't change its state.
            if (candidateContainer.classList.contains('selected')) {
                // Prevent platform button from triggering deselection
                event.stopPropagation();
            }

            // Show the platform modal (replace with your actual modal logic)
            const platformContent = this.getAttribute('data-platform');
            const modal = document.getElementById('platformModal'); // Assuming you have a modal with this ID
            const modalBody = modal.querySelector('.modal-body');
            modalBody.innerHTML = platformContent;

            // Display the modal
            modal.style.display = 'block'; // Replace with your modal display logic

            // Prevent event propagation to avoid candidate selection
            event.stopPropagation();
        });
    });

    // Close modal when clicked outside (if you want this behavior)
    document.addEventListener('click', function (event) {
        const modal = document.getElementById('platformModal');
        if (modal && !modal.contains(event.target)) {
            modal.style.display = 'none'; // Hide the modal when clicked outside
        }
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

        // Trigger confetti when form is submitted
        $('#ballotForm').on('submit', function() {
            startConfetti();
        });

        $(document).on('click', '.platform', function(e){
            e.preventDefault();
            $('#platform').modal('show');
            var platform = $(this).data('platform');
            var fullname = $(this).data('fullname');
            $('.candidate').html(fullname);
            $('#plat_view').html(platform);
        });



        $('#preview').click(function(e){
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
                    url: 'preview.php',
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


body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .position-container {
            max-width: 1500px;
            margin: 5px auto;
            padding: 5px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .box {
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .box-header {
            background-color: darkgreen;
            color: #fff;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .box-header h3 {
            margin: 0;
            font-size: 1.00rem;
        }

        .box-header button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .box-header button:hover {
            background-color: #000000;
        }

        .box-body {
            padding: 20px;
            background-color: #f9f9f9;
        }

        .instruction {
            font-size: 1rem;
            margin-bottom: 15px;
            color: #555;
        }

.reset {
    background-color: #dc3545;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 10px 15px;
    font-size: 14px; /* Increased font size */
    font-weight: bold; /* Make text bold */
    cursor: pointer;
    transition: background-color 0.3s;
    margin-left: auto; /* Align reset button to the right */
}

.reset:hover {
    background-color: #c82333;
}

/* Style for the box title */
.box-title {
    margin: 0;
    font-size: 15px;
    font-weight: 300;
}

/* Style for the box body */
.box-body {
    padding: 10px;
    display: flex;
    flex-direction: column; /* Stack elements vertically */
    align-items: center; /* Center align all the children */
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
    margin: 0;
    display: flex;
    flex-wrap: wrap; /* Allow wrapping of candidates */
    justify-content: center; /* Center align candidate items */
}

.candidate-list li {
    display: flex;
    flex-direction: column; /* Stack elements vertically */
    justify-content: center;
    align-items: center; /* Center content inside each list item */
    border-radius: 10px;
    padding: 10px;
    margin: 10px;
    background-color: #f9f9f9;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    border: 2px solid #ccc;
    transition: transform 0.3s;
    width: 200px; /* Set a fixed width for each candidate item */
}

/* Hover effect for candidate list item */
.candidate-list li:hover {
    transform: scale(1.05); /* Slight zoom effect */
}

.candidate-list li img {
    width: 120px; /* Adjust width for smaller screens */
    height: 120px; /* Adjust height */
    border-radius: 8px;
    transition: transform 0.3s;
}

/* Hover effect for candidate images */
.candidate-list li:hover img {
    transform: scale(1.1); /* Slightly enlarge image on hover */
}

/* Style for candidate container */
.candidate-container {
    display: inline-block;
    text-align: center;
    padding: 10px;
    margin: 10px;
    border: 2px solid transparent;
    border-radius: 10px;
    background-color: #f9f9f9;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, border-color 0.3s, opacity 0.3s;
    cursor: pointer;
    width: 200px;
    position: relative; /* Make sure the platform button stays inside the candidate container */
    opacity: 1;  /* Full opacity by default */
    transform: scale(1);  /* Default scale */
}

/* Hover effect for candidate container */
.candidate-container:hover {
    transform: scale(1.05);
}

/* When no candidate is selected, darken the unselected ones */
.candidate-container.unselected {
    opacity: 0.5; /* Darken the unselected candidates */
}

/* Highlight the selected candidate with border and scale effect */
.candidate-container.selected {
    border: 4px solid black;  /* Border color for selected */
    opacity: 1;  /* Ensure the selected one remains fully visible */
    transform: scale(1.10);  /* Make the selected candidate "pop" slightly */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.9); /* Optional shadow for selected candidates */
}

/* Optional: Add a hover effect for unselected candidates */
.candidate-container.unselected:hover {
    opacity: 0.9;
    transform: scale(1.10);
    box-shadow: 0 15px 15px rgba(0.1, 0.1, 0.1, 0.7); /* Stronger shadow on hover */
}

/* Candidate image style */
.candidate-image {
    width: 120px;
    height: 120px;
    border-radius: 10px;
    transition: transform 0.3s;
}

/* Candidate name style */
.candidate-name {
    margin-top: 10px;
    font-size: 14px;
    font-weight: bold;
}

/* Style for the platform button */
.platform-button {
    margin-top: 10px; /* Space between the candidate's name and the button */
    font-size: 14px;
    background-color: #000000;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 5px 10px;
    cursor: pointer;
    transition: background-color 0.3s;
}

/* Hover effect for platform button */
.platform-button:hover {
    background-color: #000000;
}

/* Consolidated reset button styles */
.reset {
    background-color: #dc3545;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 5px 10px;
    cursor: pointer;
    transition: background-color 0.3s;
    margin-left: auto; /* Align to the right */
}

.reset:hover {
    background-color: #c82333;
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

/* Styles for confetti effect */
#confetti-canvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 9999;
}
</style>
</body>
</html>