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
	    	<?php
				$sql = "SELECT title FROM election";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
    				$row = $result->fetch_assoc();
    				$pageTitle = $row["title"];
				} else {
    				$pageTitle = "Default Title";
				}
				?>
	      <!-- Main content -->
	      <section class="content">
	      	<h1 class="page-header text-center title">
<img src="images/jpcs.jpg" alt="CSC Logo" style="width: 100px; height: 100px; border-radius: 50%; margin-right: 10px;">
	      		<b><?php echo $pageTitle; ?></b>
	      	</h1>
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
                            <!--countdown-->
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
 					        <!--end-->
                            <!-- Voting Ballot -->
                            <form method="POST" id="ballotForm" action="submit_ballot_jpcs.php">
                                <?php
                                include 'includes/slugify.php';

                                $candidate = '';
                                $sql = "SELECT * FROM categories WHERE election_id = 1 ORDER BY priority ASC";
                                $query = $conn->query($sql);
                                while($row = $query->fetch_assoc()){
                                    $sql = "SELECT * FROM candidates WHERE category_id='".$row['id']."'";
                                    $cquery = $conn->query($sql);
                                    while($crow = $cquery->fetch_assoc()){
                                        $slug = slugify($row['name']);
                                        $checked = '';
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
                                        $input = ($row['max_vote'] > 1) ? '<input type="checkbox" class="flat-red '.$slug.'" name="'.$slug."[]".'" value="'.$crow['id'].'" '.$checked.'>' : '<input type="radio" class="flat-red '.$slug.'" name="'.slugify($row['description']).'" value="'.$crow['id'].'" '.$checked.'>';
                                        $image = (!empty($crow['photo'])) ? 'images/'.$crow['photo'] : 'images/profile.jpg';
                                        $candidate .= '
                                            <li>
                                                '.$input.'<button type="button" class="btn btn-primary btn-sm btn-flat clist platform" data-platform="'.$crow['platform'].'" data-fullname="'.$crow['firstname'].' '.$crow['lastname'].'"><i class="fa fa-search"></i> Platform</button><img src="'.$image.'" height="100px" width="100px" class="clist"><span class="cname clist">'.$crow['firstname'].' '.$crow['lastname'].'</span>
                                            </li>
                                        ';
                                    }

                                    $instruct = ($row['max_vote'] > 1) ? 'You may select up to '.$row['max_vote'].' candidates' : 'Select only one candidate';

                                    echo '
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="box box-solid" id="'.$row['id'].'">
                                                <div class="box-header with-border"style="background-color: darkgreen; color: #fff;"> 
                                                        <h3 class="box-title"><b>'.$row['name'].'</b></h3>
                                                    </div>
                                                    <div class="box-body">
                                                        <p>'.$instruct.'
                                                            <span class="pull-right">
                                                                <button type="button" class="btn btn-success btn-sm btn-flat reset" data-desc="'.slugify($row['description']).'"><i class="fa fa-refresh"></i> Reset</button>
                                                            </span>
                                                        </p>
                                                        <div id="candidate_list">
                                                            <ul>
                                                                '.$candidate.'
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ';

                                    $candidate = '';

                                }   
                                ?>
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary btn-flat" id="submitBtn"><i class="fa fa-check-square-o"></i> Submit</button>
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
                                            <button type="button" class="btn btn-success btn-flat" id="preview"><i class="fa fa-file-text"></i> Preview</button> 
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
<style>
.timer {
    position: fixed;
    top: 0;
    right: 0;
    z-index: 1000; /* Adjust z-index as needed */
    width: 250px; /* Adjust width as needed */
    display: flex;
    justify-content: center;
    margin-top: 570px;
    margin-right: 12px; /* Adjust margin as needed */
    margin-bottom: 50px;
    
}
.sub_timer {
    width: 90px;
    background: rgba(255, 255, 255, 0.19);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    overflow: hidden;
    height: 100px;
    margin-left: 10px;
    margin-bottom: 50px;
}
.digit {
    color: black;
    font-weight: lighter;
    font-size: 30px;
    text-align: center;
    /*padding: 3.5rem 0;*/
}
.digit_name {
    color: #000;
    background: lightgrey;
    text-align: center;
    /*padding: 0.6rem 0;*/
    font-size: 15px;
}
</style>
</body>
</html>