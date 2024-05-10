<?php
// Include necessary files and initialize database connection
include 'includes/session.php';
include 'includes/db.php';

// Initialize arrays to store updated data
$presidentData = array();
$vicePresidentInternalData = array();
$vicePresidentExternalData = array();
$secretaryData = array();
$treasurerData = array();
$auditorData = array();
$proData = array();
$membershipDirData = array();
$specialProjectDirData = array();
$blockA1stYearRepData = array();
$blockB1stYearRepData = array();
$blockA2ndYearRepData = array();
$blockB2ndYearRepData = array();
$blockA3rdYearRepData = array();
$blockB3rdYearRepData = array();
$blockA4thYearRepData = array();
$blockB4thYearRepData = array();

// Fetch updated data for president candidates
$sqlPresident = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM positions 
                LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'President'
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                WHERE voters1.organization != ''
                GROUP BY candidates.id";
$queryPresident = $conn->query($sqlPresident);
if ($queryPresident) {
    while ($row = $queryPresident->fetch_assoc()) {
        $presidentData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching president data: " . $conn->error;
}

// Fetch updated data for vice president for internal affairs candidates
$sqlVicePresidentInternal = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                            FROM positions 
                            LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Vice President for Internal Affairs'
                            LEFT JOIN votes ON candidates.id = votes.candidate_id
                            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                            WHERE voters1.organization != ''
                            GROUP BY candidates.id";
$queryVicePresidentInternal = $conn->query($sqlVicePresidentInternal);
if ($queryVicePresidentInternal) {
    while ($row = $queryVicePresidentInternal->fetch_assoc()) {
        $vicePresidentInternalData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching vice president for internal affairs data: " . $conn->error;
}

// Fetch updated data for vice president for external affairs candidates
$sqlVicePresidentExternal = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                            FROM positions 
                            LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Vice President for External Affairs'
                            LEFT JOIN votes ON candidates.id = votes.candidate_id
                            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                            WHERE voters1.organization != ''
                            GROUP BY candidates.id";
$queryVicePresidentExternal = $conn->query($sqlVicePresidentExternal);
if ($queryVicePresidentExternal) {
    while ($row = $queryVicePresidentExternal->fetch_assoc()) {
        $vicePresidentExternalData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching vice president for external affairs data: " . $conn->error;
}

// Fetch updated data for secretary candidates
$sqlSecretary = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM positions 
                LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Secretary'
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                WHERE voters1.organization != ''
                GROUP BY candidates.id";
$querySecretary = $conn->query($sqlSecretary);
if ($querySecretary) {
    while ($row = $querySecretary->fetch_assoc()) {
        $secretaryData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching secretary data: " . $conn->error;
}

// Fetch updated data for treasurer candidates
$sqlTreasurer = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM positions 
                LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Treasurer'
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                WHERE voters1.organization != ''
                GROUP BY candidates.id";
$queryTreasurer = $conn->query($sqlTreasurer);
if ($queryTreasurer) {
    while ($row = $queryTreasurer->fetch_assoc()) {
        $treasurerData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching treasurer data: " . $conn->error;
}

// Fetch updated data for auditor candidates
$sqlAuditor = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM positions 
                LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Auditor'
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                WHERE voters1.organization != ''
                GROUP BY candidates.id";
$queryAuditor = $conn->query($sqlAuditor);
if ($queryAuditor) {
    while ($row = $queryAuditor->fetch_assoc()) {
        $auditorData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching auditor data: " . $conn->error;
}

// Fetch updated data for P.R.O. candidates
$sqlPRO = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM positions 
                LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'P.R.O.'
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                WHERE voters1.organization != ''
                GROUP BY candidates.id";
$queryPRO = $conn->query($sqlPRO);
if ($queryPRO) {
    while ($row = $queryPRO->fetch_assoc()) {
        $proData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching P.R.O. data: " . $conn->error;
}

// Fetch updated data for Director for Membership candidates
$sqlMembershipDir = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM positions 
                LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Director for Membership'
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                WHERE voters1.organization != ''
                GROUP BY candidates.id";
$queryMembershipDir = $conn->query($sqlMembershipDir);
if ($queryMembershipDir) {
    while ($row = $queryMembershipDir->fetch_assoc()) {
        $membershipDirData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching Director for Membership data: " . $conn->error;
}

// Fetch updated data for Director for Special Project candidates
$sqlSpecialProjectDir = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM positions 
                LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Director for Special Project'
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                WHERE voters1.organization != ''
                GROUP BY candidates.id";
$querySpecialProjectDir = $conn->query($sqlSpecialProjectDir);
if ($querySpecialProjectDir) {
    while ($row = $querySpecialProjectDir->fetch_assoc()) {
        $specialProjectDirData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching Director for Special Project data: " . $conn->error;
}

// Fetch updated data for Block A 1st Year Representative candidates
$sqlBlockA1stYearRep = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM positions 
                LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Block A 1st Year Representative'
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                WHERE voters1.organization != ''
                GROUP BY candidates.id";
$queryBlockA1stYearRep = $conn->query($sqlBlockA1stYearRep);
if ($queryBlockA1stYearRep) {
    while ($row = $queryBlockA1stYearRep->fetch_assoc()) {
        $blockA1stYearRepData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching Block A 1st Year Representative data: " . $conn->error;
}

// Fetch updated data for Block B 1st Year Representative candidates
$sqlBlockB1stYearRep = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM positions 
                LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Block B 1st Year Representative'
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                WHERE voters1.organization != ''
                GROUP BY candidates.id";
$queryBlockB1stYearRep = $conn->query($sqlBlockB1stYearRep);
if ($queryBlockB1stYearRep) {
    while ($row = $queryBlockB1stYearRep->fetch_assoc()) {
        $blockB1stYearRepData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching Block B 1st Year Representative data: " . $conn->error;
}

// Fetch updated data for Block A 2nd Year Representative candidates
$sqlBlockA2ndYearRep = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM positions 
                LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Block A 2nd Year Representative'
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                WHERE voters1.organization != ''
                GROUP BY candidates.id";
$queryBlockA2ndYearRep = $conn->query($sqlBlockA2ndYearRep);
if ($queryBlockA2ndYearRep) {
    while ($row = $queryBlockA2ndYearRep->fetch_assoc()) {
        $blockA2ndYearRepData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching Block A 2nd Year Representative data: " . $conn->error;
}

// Fetch updated data for Block B 2nd Year Representative candidates
$sqlBlockB2ndYearRep = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM positions 
                LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Block B 2nd Year Representative'
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                WHERE voters1.organization != ''
                GROUP BY candidates.id";
$queryBlockB2ndYearRep = $conn->query($sqlBlockB2ndYearRep);
if ($queryBlockB2ndYearRep) {
    while ($row = $queryBlockB2ndYearRep->fetch_assoc()) {
        $blockB2ndYearRepData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching Block B 2nd Year Representative data: " . $conn->error;
}

// Fetch updated data for Block A 3rd Year Representative candidates
$sqlBlockA3rdYearRep = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM positions 
                LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Block A 3rd Year Representative'
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                WHERE voters1.organization != ''
                GROUP BY candidates.id";
$queryBlockA3rdYearRep = $conn->query($sqlBlockA3rdYearRep);
if ($queryBlockA3rdYearRep) {
    while ($row = $queryBlockA3rdYearRep->fetch_assoc()) {
        $blockA3rdYearRepData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching Block A 3rd Year Representative data: " . $conn->error;
}

// Fetch updated data for Block B 3rd Year Representative candidates
$sqlBlockB3rdYearRep = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM positions 
                LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Block B 3rd Year Representative'
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                WHERE voters1.organization != ''
                GROUP BY candidates.id";
$queryBlockB3rdYearRep = $conn->query($sqlBlockB3rdYearRep);
if ($queryBlockB3rdYearRep) {
    while ($row = $queryBlockB3rdYearRep->fetch_assoc()) {
        $blockB3rdYearRepData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching Block B 3rd Year Representative data: " . $conn->error;
}

// Fetch updated data for Block A 4th Year Representative candidates
$sqlBlockA4thYearRep = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM positions 
                LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Block A 4th Year Representative'
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                WHERE voters1.organization != ''
                GROUP BY candidates.id";
$queryBlockA4thYearRep = $conn->query($sqlBlockA4thYearRep);
if ($queryBlockA4thYearRep) {
    while ($row = $queryBlockA4thYearRep->fetch_assoc()) {
        $blockA4thYearRepData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching Block A 4th Year Representative data: " . $conn->error;
}

// Fetch updated data for Block B 4th Year Representative candidates
$sqlBlockB4thYearRep = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM positions 
                LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Block B 4th Year Representative'
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                WHERE voters1.organization != ''
                GROUP BY candidates.id";
$queryBlockB4thYearRep = $conn->query($sqlBlockB4thYearRep);
if ($queryBlockB4thYearRep) {
    while ($row = $queryBlockB4thYearRep->fetch_assoc()) {
        $blockB4thYearRepData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching Block B 4th Year Representative data: " . $conn->error;
}

// Close database connection
$conn->close();

// Combine the updated data into a single array
$response = array(
    'presidentData' => $presidentData,
    'vicePresidentInternalData' => $vicePresidentInternalData,
    'vicePresidentExternalData' => $vicePresidentExternalData,
    'secretaryData' => $secretaryData,
    'treasurerData' => $treasurerData,
    'auditorData' => $auditorData,
    'proData' => $proData,
    'membershipDirData' => $membershipDirData,
    'specialProjectDirData' => $specialProjectDirData,
    'blockA1stYearRepData' => $blockA1stYearRepData,
    'blockB1stYearRepData' => $blockB1stYearRepData,
    'blockA2ndYearRepData' => $blockA2ndYearRepData,
    'blockB2ndYearRepData' => $blockB2ndYearRepData,
    'blockA3rdYearRepData' => $blockA3rdYearRepData,
    'blockB3rdYearRepData' => $blockB3rdYearRepData,
    'blockA4thYearRepData' => $blockA4thYearRepData,
    'blockB4thYearRepData' => $blockB4thYearRepData,
);

// Return the updated data as JSON
echo json_encode($response);
?>
