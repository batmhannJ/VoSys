<?php 
include 'includes/session.php';

if(isset($_POST['id'])){
    $id = $_POST['id'];

    // Prepare the SQL query to fetch the election details, including the academic year
    $sql = "SELECT title, academic_yr, id FROM election WHERE id = '$id'";
    $query = $conn->query($sql);
    
    // Check if a record was found
    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();
        
        // Return the data as a JSON response
        echo json_encode([
            'title' => $row['title'],
            'academic_yr' => $row['academic_yr'],
            'id' => $row['id']
        ]);
    } else {
        // Return error response if no data found
        echo json_encode(['error' => 'No data found']);
    }
}
?>