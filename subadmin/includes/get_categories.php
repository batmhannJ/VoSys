<!-- get_categories.php -->
<?php
include 'includes/conn.php';

if (isset($_GET['election_id']) && is_numeric($_GET['election_id'])) {
    $i = 1;
    $election_id = $_GET['election_id'];
    $election = $conn->prepare("SELECT * FROM categories WHERE election_id = ? ORDER BY created_at ASC");
    $election->bind_param('i', $election_id);
    $election->execute();
    $result = $election->get_result();

    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <th scope="row">' . $i++ . '</th>
                <td>' . $row['name'] . '</td>
                <td class="text-center">
                    <a href="#" class="btn btn-primary btn-sm edit-category" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="#" class="btn btn-danger btn-sm category-delete" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '">
                        <i class="bi bi-trash"></i> Delete
                    </a>
                </td>
              </tr>';
    }
} else {
    echo '<tr>
            <td colspan="3" class="text-center text-muted py-5 border-bottom-0">Select an Election to add and view categories.</td>
          </tr>';
}
?>
