<style>
    .modal-content {
        padding: 20px;
    }

    .card-body {
        padding: 20px;
    }
</style>
<div class="container-fluid" style="margin-top:98px">
    <div class="row">
        <div class="col-lg-12">
            <button class="btn btn-primary float-right btn-sm" data-toggle="modal" data-target="#newUser"><i class="fa fa-plus"></i> New user</button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="card col-lg-12">
            <div class="card-body">
                <table class="table-striped table-bordered col-md-12 text-center">
                    <thead style="background-color: rgb(111 202 203);">
                        <tr>
                            <th>UserId</th>
                            <th>Nickname</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone No.</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM users";
                        $result = mysqli_query($conn, $sql);

                        while ($row = mysqli_fetch_assoc($result)) {
                            $userId = $row['userId'];
                            $nickname = $row['nickname'];
                            $firstName = $row['firstName'];
                            $lastName = $row['lastName'];
                            $email = $row['email'];
                            $phone = $row['phone'];
                            $userType = $row['userType'] == 0 ? "User" : "Admin";

                            echo '<tr>
                                    <td>' . $userId . '</td>
                                    <td>' . $nickname . '</td>
                                    <td>
                                        <p>First Name : <b>' . $firstName . '</b></p>
                                        <p>Last Name : <b>' . $lastName . '</b></p>
                                    </td>
                                    <td>' . $email . '</td>
                                    <td>' . $phone . '</td>
                                    <td>' . $userType . '</td>
                                    <td class="text-center">
                                        <div class="row mx-auto" style="width:112px">
                                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editUser' . $userId . '" type="button">Edit</button>';
                            if ($userId == 1) {
                                echo '<button class="btn btn-sm btn-danger" disabled style="margin-left:9px;">Delete</button>';
                            } else {
                                echo '<form action="partials/_userManage.php" method="POST">
                                                        <button name="removeUser" class="btn btn-sm btn-danger" style="margin-left:9px;">Delete</button>
                                                        <input type="hidden" name="Id" value="' . $userId . '">
                                                    </form>';
                            }

                            echo '</div>
                                    </td>
                                </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- newUser Modal -->
<div class="modal fade" id="newUser" tabindex="-1" role="dialog" aria-labelledby="newUser" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: rgb(111 202 203);">
                <h5 class="modal-title" id="newUser">Create New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="partials/_userManage.php" method="post">
                    <div class="form-group">
                        <b><label for="username">Username</label></b>
                        <input class="form-control" id="username" name="username" placeholder="Choose a unique Username" type="text" required minlength="3" maxlength="11">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <b><label for="firstName">First Name:</label></b>
                            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <b><label for="lastName">Last name:</label></b>
                            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <b><label for="email">Email:</label></b>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter Your Email" required>
                    </div>
                    <div class="form-group row my-0">
                        <div class="form-group col-md-6 my-0">
                            <b><label for="phone">Phone No:</label></b>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon">+63</span>
                                </div>
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter Phone No" required pattern="[0-9]{10}" maxlength="10">
                            </div>
                        </div>
                        <div class="form-group col-md-6 my-0">
                            <b><label for="userType">Type:</label></b>
                            <select name="userType" id="userType" class="custom-select browser-default" required>
                                <option value="0">User</option>
                                <option value="1">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <b><label for="password">Password:</label></b>
                        <input class="form-control" id="password" name="password" placeholder="Enter Password" type="password" required data-toggle="password" minlength="4" maxlength="21">
                    </div>
                    <div class="form-group">
                        <b><label for="password1">Renter Password:</label></b>
                        <input class="form-control" id="cpassword" name="cpassword" placeholder="Renter Password" type="password" required data-toggle="password" minlength="4" maxlength="21">
                    </div>
                    <button type="submit" name="createUser" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$usersql = "SELECT * FROM `users`";
$userResult = mysqli_query($conn, $usersql);
while ($userRow = mysqli_fetch_assoc($userResult)) {
    $userId = $userRow['userId'];
    $nickname = $userRow['nickname'];
    $firstName = $userRow['firstName'];
    $lastName = $userRow['lastName'];
    $email = $userRow['email'];
    $phone = $userRow['phone'];
    $userType = $userRow['userType'];
?>
    <!-- editUser Modal -->
    <div class="modal fade" id="editUser<?php echo $userId; ?>" tabindex="-1" role="dialog" aria-labelledby="editUser<?php echo $userId; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: rgb(111 202 203);">
                    <h5 class="modal-title" id="editUser<?php echo $userId; ?>">User Id: <b><?php echo $userId; ?></b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="partials/_userManage.php" method="post">
                    <div class="form-group">
                        <label for="nickname">Nickname</label>
                        <input class="form-control" id="nickname" name="nickname" value="<?php echo $nickname; ?>" type="text">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="firstName">First Name:</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo $firstName; ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lastName">Last name:</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo $lastName; ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone No:</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>" required pattern="[0-9]{10}" maxlength="10">
                    </div>
                    <div class="form-group">
                        <label for="userType">Type:</label>
                        <select name="userType" id="userType" class="custom-select">
                            <option value="0" <?php echo $userType == 0 ? 'selected' : ''; ?>>User</option>
                            <option value="1" <?php echo $userType == 1 ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                    <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                    <button type="submit" name="editUser" class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
    </div>
<?php
}
?>