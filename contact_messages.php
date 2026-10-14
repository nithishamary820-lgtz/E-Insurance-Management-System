<?php
include("../includes/db.php");
include("header.php");

// Fetch all contact messages
$sql = "SELECT * FROM contact_messages ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="container mt-5">

    <h2 class="text-center mb-4">Contact Messages</h2>

    <table class="table table-bordered table-striped">

        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

        <?php
        if(mysqli_num_rows($result) > 0)
        {
            while($row = mysqli_fetch_assoc($result))
            {
        ?>

        <tr>

            <td><?php echo $row['id']; ?></td>

            <td><?php echo htmlspecialchars($row['name']); ?></td>

            <td><?php echo htmlspecialchars($row['email']); ?></td>

            <td><?php echo htmlspecialchars($row['subject']); ?></td>

            <td><?php echo htmlspecialchars($row['message']); ?></td>

            <td>
                <a href="delete_message.php?id=<?php echo $row['id']; ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Delete this message?');">
                    Delete
                </a>
            </td>

        </tr>

        <?php
            }
        }
        else
        {
            echo "<tr><td colspan='6' class='text-center'>No Messages Found</td></tr>";
        }
        ?>

        </tbody>

    </table>

</div>

<?php include("footer.php"); ?>