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
	      		<img src="images/codg.jpg" alt="CSC Logo" style="width: 100px; height: 100px; border-radius: 50%; margin-right: 10px;">
	      		<b>Coalition of Disciplined<br>Future Enforcers of Todays <br>Generation Election</b></h1>
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
                                    <button type="button" class="btn btn-primary btn-sm btn-flat platform" data-platform="'.$crow['platform'].'" data-fullname="'.$crow['firstname'].' '.$crow['lastname'].'"><i class="fa fa-search"></i> Platform</button>
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
</form>
<style>
    /* Style for the position container */
   /* Style for the position container */
.position-container {
    margin-bottom: 20px;
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

/* Style for the box title */
.box-title {
    margin: 0;
    font-size: 20px;
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

/* Style for individual candidate */
.candidate-list li {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 10px;
    background-color: #f9f9f9;
}

/* Style for candidate information */
.candidate-info {
    display: flex;
    align-items: flex-start; /* Adjust alignment */
}

/* Bagong istilo para sa mga imahe */
.cimage {
    float: left; /* Ilipat ang imahe sa kaliwa */
    margin-right: 20px; /* Dagdag na puwang sa kanan ng imahe */
}

/* Bagong istilo para sa teksto */
.ctext {
    overflow: hidden; /* Iwasang maglapat ang teksto sa mga imahe */
}

/* Bagong istilo para sa container ng platform button */
.platform-container {
    display: flex; /* Baguhin ang display property */
    align-items: center; /* I-adjust ang alignment */
    margin-top: 5px; /* Itaas ang button mula sa pangalan ng kandidato */
}

/* Bagong istilo para sa pangalan ng kandidato */
.cname {
    margin: 0;
    font-weight: bold;
    font-size: 18px;
    margin-bottom: 5px; /* Dagdag na puwang sa ibaba ng pangalan */
    margin-left: 10px;
}

/* Bagong istilo para sa platform button */
.platform {
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 3px;
    padding: 5px 10px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-left: 10px; /* Dagdag na puwang sa kaliwa */
}

.platform:hover {
    background-color: #0056b3;
}


/* Style for candidate image */
.clist {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 50%;
    margin-right: 10px;
}

</style>

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
                        <style>
              /* Style for the primary button */
.btn-primary {
    background-color: #007bff;
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


                            </style>
                        

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
</html>