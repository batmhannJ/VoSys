<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper">
	    <div class="container">
	      <!-- Main content -->
	      <section class="content">
	      	<h1 class="page-header text-center title">
	      		<b>Coalition of Disciplined Future Enforcers of Todays Generation Election</b></h1>
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
                            <form method="POST" id="ballotForm" action="submit_ballot_code.php">
    <?php
    include 'includes/slugify.php';

    $sql = "SELECT * FROM positions ORDER BY priority ASC";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()){
        echo '
        <div class="position-container">
            <div class="box box-solid" id="'.$row['id'].'">
                <div class="box-header">
                    <h3 class="box-title">'.$row['description'].'</h3>
                    <button type="button" class="btn btn-success btn-sm btn-flat reset" data-desc="'.slugify($row['description']).'"><i class="fa fa-refresh"></i> Reset</button>
                </div>
                <div class="box-body">
                    <p class="instruction">You may select up to '.$row['max_vote'].' candidates</p>
                    <div class="candidate-list">
                        <ul>';
                            $sql_candidates = "SELECT * FROM candidates WHERE position_id='".$row['id']."'";
                            $cquery = $conn->query($sql_candidates);
                            while($crow = $cquery->fetch_assoc()){
                                $slug = slugify($row['description']);
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
                                echo '
                                <li>
                                    <div class="candidate-info">
                                        '.$input.'
                                        <span class="cname">'.$crow['firstname'].' '.$crow['lastname'].'</span>
                                        
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm btn-flat platform" data-platform="'.$crow['platform'].'" data-fullname="'.$crow['firstname'].' '.$crow['lastname'].'">PLATFORM</button>
                                
                                    <img src="'.$image.'" alt="'.$crow['firstname'].' '.$crow['lastname'].'" class="clist">
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
                                            <button type="submit" class="btn btn-primary" id="submitBtn" name="vote_code">Yes, Submit</button>
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
  	<?php include 'includes/ballot_modal_code.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
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
    background-color: #800000;
    color: #fff;
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
    display: flex; /* Baguhin ang display sa flex */
    flex-wrap: wrap; /* Pahintulutan ang pag-wrap ng mga item sa loob ng flex container */
    justify-content: space-between; /* I-set ang mga item na sa layong pare-pareho */
    align-items: center; /* I-align ang mga item sa gitna */
    border-radius: 5px; /* Radius ng border */
    padding: 10px; /* Padding para sa mga item */
    margin-bottom: 10px; /* Espasyo sa pagitan ng mga item */
    background-color: #f9f9f9; /* Kulay ng background */
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); /* Shadow para sa depth */
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
    }

    .candidate-list li img {
        width: 100px; /* I-adjust ang lapad ng mga larawan para sa mas maliit na screen */
        height: 100px; /* I-adjust ang taas ng mga larawan para sa mas maliit na screen */
        margin: 0 auto; /* Ilipat ang mga larawan sa gitna */
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
    background-color: maroon;
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
    background-color: maroon;
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
    padding: 130px 20px; /* Increase padding on top and bottom to expand the box */
    background-image: url('images/bcc.png'); /* Add background image */
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
    width: 150px; /* Adjust image width */
    height: auto; /* Auto height */
    border-radius: 50%; /* Circular image */
    margin-bottom: 20px; /* Add margin at the bottom */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Soft shadow */
    position: absolute; /* Position the image */
    top: 50%; /* Position from the top */
    left: 50%; /* Position from the left */
    transform: translate(-50%, -50%); /* Center the image */
}

.title {
    font-size: 32px; /* Decrease font size */
    margin-bottom: 10px; /* Decrease margin bottom */
    font-family: 'Arial', sans-serif; /* Change font family */
    font-weight: bold; /* Bold font weight */
    text-transform: uppercase; /* Uppercase text */
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); /* Add shadow effect */
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










</style>
</html>