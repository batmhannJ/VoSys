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
                            <?php
                        }
                        else{
                            ?>
                            <!-- Voting Ballot -->
                            <form method="POST" id="ballotForm" action="submit_ballot.php">
                                <?php
                                include 'includes/slugify.php';

                                $candidate = '';
                                $sql = "SELECT * FROM categories WHERE election_id = 20 ORDER BY priority ASC";
                                $query = $conn->query($sql);
                                while($row = $query->fetch_assoc()){
                                    echo '
                                    <div class="position-container">
                                        <div class="box box-solid" id="'.$row['id'].'">
                                            <div class="box-header">
                                                <h3 class="box-title">'.$row['name'].'</h3>
                                                <button type="button" class="btn btn-success btn-sm btn-flat reset" data-desc="'.slugify($row['name']).'"><i class="fa fa-refresh"></i> Reset</button>
                                            </div>
                                            <div class="box-body">
                                                <p class="instruction">You may select up to '.$row['max_vote'].' candidates</p>
                                                <div class="candidate-list">
                                                    <ul>';
                                    $sql = "SELECT * FROM candidates WHERE category_id='".$row['id']."'";
                                    $cquery = $conn->query($sql);
                                    while($crow = $cquery->fetch_assoc()){
                                        $display = false;
                                        switch ($row['name']) {
                                            case 'JPCS':
                                                $display = $crow['name'] == 'BSIT Rep';
                                                break;
                                            case 'YMF':
                                                $display = in_array($crow['name'], ['BSED Rep', 'BEED Rep']);
                                                break;
                                            case 'CODE-TG':
                                                $display = $crow['name'] == 'BS CRIM Rep';
                                                break;
                                            case 'PASOA':
                                                $display = $crow['name'] == 'BSOAD Rep';
                                                break;
                                            case 'HMSO':
                                                $display = $crow['name'] == 'BSHM Rep';
                                                break;
                                        }

                                        if ($display) {
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
                                            $input = ($row['max_vote'] > 1) ? '<input type="checkbox" class="flat-red '.$slug.'" name="'.$slug."[]".'" value="'.$crow['id'].'" '.$checked.'>' : '<input type="radio" class="flat-red '.$slug.'" name="'.slugify($row['name']).'" value="'.$crow['id'].'" '.$checked.'>';
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

      <?php include 'includes/footer_code.php'; ?>
</div>

<?php include 'includes/scripts_code.php'; ?>
<?php include 'includes/preview_modal_code.php'; ?>

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

        $('#submitBtn').click(function(e){
            e.preventDefault();
            $('#confirmationModal').modal('show');
        });

        // Handle form submission inside the modal
        $('#confirmationModal').on('click', '#submitBtn', function(e) {
            $('#ballotForm').submit();
        });
    });
</script>

</body>
</html>
