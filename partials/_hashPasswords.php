<?php
include '_dbconnect.php';

$sql = "SELECT userId, password FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $hashedPassword = crypt($row['password'], PASSWORD_DEFAULT);

        $updateSql = $conn->prepare("UPDATE users SET password = ? WHERE userId = ?");
        $updateSql->bind_param("si", $hashedPassword, $row['userId']);
        $updateSql->execute();
    }
    echo "Passwords hashed successfully.";
} else {
    echo "No users found or error fetching users.";
}

$conn->close();
