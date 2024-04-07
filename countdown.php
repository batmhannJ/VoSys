<?php
include 'includes/conn.php';
// Establish a database connection (replace these values with your actual database credentials)
$sql = "SELECT starttime, endtime FROM election WHERE status = '1'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0){
                while ($row  = $result->fetch_assoc()) {
                    $start = new DateTime($row['starttime']);
                    $end = new DateTime($row['endtime']);
                    $interval = $start->diff($end);
                    $span = sprintf(
                        "%02d:%02d:%02d",
                        $interval->h + ($interval->days * 24),
                        $interval->i,
                        $interval->s
                    );

                }
            }
            else{
                echo "No Records Found";
            }

$conn->close();
?>
