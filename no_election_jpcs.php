	<?php

	include 'includes/session.php';
	include 'includes/header_jpcs.php';

	?>

	<body class="hold-transition skin-green layout-top-nav">
	<div class="wrapper">

		<?php include 'includes/navbar_jpcs.php'; ?>
		 
		  <div class="content-wrapper">
		    <div class="container">

		      <!-- Main content -->
		      <section class="content">
		      	<h1 class="page-header text-center title">
		      		<img src="images/jpcs.jpg" alt="CSC Logo" style="width: 100px; height: 100px; border-radius: 50%; margin-right: 10px;">
		      		<b>JPCS - Junior Philippine Computer Society Election</b></h1>
		        <div class="row">
		        	<div class="col-sm-10 col-sm-offset-1">
					    <?php
    // Assuming you have already established a database connection
    
    // Perform a query to fetch the most recent announcement
    $query = "SELECT * FROM announcement ORDER BY id DESC LIMIT 1";
    $result = $conn->query($query);
    
    // Check if the query was successful and if there is at least one row
    if ($result && $result->num_rows > 0) {
        // Fetch the row as an associative array
        $row = $result->fetch_assoc();
        
        // Now you can access the elements of $row safely
?>
<div class="announcement-container">
    <div class="announcement">
        <div class="announcement-header">
            <b><h3>New Announcement</h3></b>
            <p class="announcement-date"><?php echo date("F j, Y"); ?></p>
        </div>
        <div class="announcement-body">
        	<hr>
            <p class="announcement-text"><?php echo $row["announcement"]; ?></p>
            <p class="announcement-author">Posted by: <?php echo $row["addedby"]; ?></p>
        </div>
    </div>
</div>
<?php
    } else {
        // Handle the case where there are no announcements
        echo "<p>No announcements found.</p>";
    }
?>

					</div><!--end of col-sm-->
		        </div>
		      </section>
		     
		    </div>
		  </div>
	  
	  	<?php include 'includes/footer.php'; ?>
	  	<?php include 'includes/ballot_modal.php'; ?>
	</div>
	<style>		
		hr {
	        border: none; /* Remove default border */
	        height: 3px; /* Set the height to make it thicker */
	        background-color: black; /* Set the color of the line */
	        margin: 20px 0; /* Adjust margin as needed */
	    }
		/* Container for announcements */
/* Container for announcements */
.announcement-container {
    margin: 20px 0;
}

/* Individual announcement card */
.announcement {
    background-color: #f8f9fa; /* Light gray background */
    border: 2px solid #006400; /* Matte blue border */
    border-radius: 20px; /* Increased border radius for a rounded look */
    padding: 20px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Added transition for transform and box-shadow */
}

.announcement:hover {
    transform: translateY(-5px); /* Move the card up slightly on hover */
    box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15); /* Enhance shadow on hover */
}

/* Header of the announcement */
.announcement-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.announcement-header h3 {
    color: #006400; /* Matte blue color for header text */
    margin: 0;
    font-size: 24px; /* Increased font size for header */
}

/* Date of the announcement */
.announcement-date {
    color: #777;
    font-size: 14px;
}

/* Body text of the announcement */
.announcement-body {
	font-size: 20px;
    line-height: 1.6;
    color: #000; /* Dark gray color for body text */
}

/* Author of the announcement */
.announcement-author {
    color: #999; /* Light gray color for author */
    font-style: italic;
    font-size: 13px;
}


	</style>
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
				$('.message').html('You must vote atleast one candidate');
				$('#alert').show();
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