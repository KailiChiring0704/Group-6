<div class="container-fluid" style="margin-top:98px">

	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
				<form action="partials/_menuManage.php" method="post" enctype="multipart/form-data">
					<div class="card">
						<div class="card-header" style="background-color: rgb(111 202 203);">
							Create New Item
						</div>
						<div class="card-body">
							<div class="form-group">
								<label class="control-label">Name: </label>
								<input type="text" class="form-control" name="name" required>
							</div>
							<div class="form-group">
								<label class="control-label">Description: </label>
								<textarea cols="30" rows="3" class="form-control" name="description" required></textarea>
							</div>
							<div class="form-group">
								<label class="control-label">Category: </label>
								<select class="form-control" name="categoryId" required>
									<option value="" disabled selected>Select Category</option>
									<?php
									$catsql = "SELECT categoryId, categoryName FROM categories";
									$catresult = mysqli_query($conn, $catsql);
									while ($catRow = mysqli_fetch_assoc($catresult)) {
										echo '<option value="' . $catRow['categoryId'] . '">' . $catRow['categoryName'] . '</option>';
									}
									?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Sizes and Prices:</label>
								<div class="input-group mb-3">
									<input type="text" class="form-control" placeholder="Large" name="sizes[]">
									<input type="number" class="form-control" placeholder="45" name="prices[]">
									<div class="input-group-append">
										<button class="btn btn-outline-secondary" type="button" onclick="addSize()">Add Size</button>
									</div>
								</div>
								<div id="additionalSizes"></div>
								<script>
									function addSize() {
										let html = `<div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Size" name="sizes[]">
                                        <input type="number" class="form-control" placeholder="Price" name="prices[]">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-danger" type="button" onclick="removeSize(this)">Remove</button>
                                        </div>
                                    </div>`;
										document.getElementById('additionalSizes').insertAdjacentHTML('beforeend', html);
									}

									function removeSize(button) {
										button.closest('.input-group').remove();
									}
								</script>
							</div>

						</div>
						<div class="card-footer">
							<button type="submit" name="createItem" class="btn btn-primary">Create</button>
						</div>
					</div>
				</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-body">
						<table class="table table-bordered table-hover mb-0">
							<thead style="background-color: rgb(111 202 203);">
								<tr>
									<th class="text-center">Name</th>
									<th class="text-center">Description</th>
									<th class="text-center">Category</th>
									<th class="text-center">Size</th>
									<th class="text-center">Price</th>
									<th class="text-center">Date Added</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$sql = "SELECT p.prodId, p.prodName, p.prodDesc, p.prodPubDate, ps.size, ps.price, c.categoryName 
                            FROM prod p
                            JOIN prod_sizes ps ON p.prodId = ps.prodId
                            JOIN categories c ON p.prodCategoryId = c.categoryId
                            ORDER BY p.prodPubDate DESC";
								$result = mysqli_query($conn, $sql);
								while ($row = mysqli_fetch_assoc($result)) {
									echo "<tr>
                            <td>{$row['prodName']}</td>
                            <td>{$row['prodDesc']}</td>
							<td>{$row['categoryName']}</td>
                            <td>{$row['size']}</td>
                            <td>PHP {$row['price']}.00</td>
                            <td>" . date('Y-m-d', strtotime($row['prodPubDate'])) . "</td>
							<td class='text-center'>
									<div class='dropdown'>
										<i class='fas fa-ellipsis-v' id='dropdownMenuButton{$row['prodId']}' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' style='cursor: pointer;'></i>
										<div class='dropdown-menu' aria-labelledby='dropdownMenuButton{$row['prodId']}'>
											<a class='dropdown-item' href='#' data-toggle='modal' data-target='#updateItem{$row['prodId']}'>Edit</a>
											<a class='dropdown-item text-danger' href='#' onclick='confirmDeletion(\"{$row['prodId']}\")'>Remove</a>
										</div>
									</div>
								</td>
                          </tr>";
								}
								?>
							</tbody>
						</table>
					</div>
				</div>

			</div>
			<!-- Table Panel -->
		</div>
	</div>
</div>

<?php
$prodsql = "SELECT p.prodId, p.prodName, p.prodDesc, p.prodPubDate, c.categoryName, ps.size, ps.price
            FROM prod p
            JOIN prod_sizes ps ON p.prodId = ps.prodId
            JOIN categories c ON p.prodCategoryId = c.categoryId
            ORDER BY p.prodPubDate DESC";
$prodResult = mysqli_query($conn, $prodsql);
while ($prodRow = mysqli_fetch_assoc($prodResult)) {
	$prodId = $prodRow['prodId'];
	$prodName = $prodRow['prodName'];
	$prodDesc = $prodRow['prodDesc'];
	$categoryName = $prodRow['categoryName'];
	$size = $prodRow['size'];
	$price = $prodRow['price'];
?>

	<!-- Modal -->
	<div class="modal fade" id="updateItem<?php echo $prodId; ?>" tabindex="-1" role="dialog" aria-labelledby="updateItem<?php echo $prodId; ?>" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header" style="background-color: rgb(111 202 203);">
					<h5 class="modal-title" id="updateItem<?php echo $prodId; ?>">Item Id: <?php echo $prodId; ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="partials/_menuManage.php" method="post">
						<div class="text-left my-2">
							<b><label for="name">Name</label></b>
							<input class="form-control" id="name" name="name" value="<?php echo $prodName; ?>" type="text" required>
						</div>
						<div class="text-left my-2">
							<b><label for="desc">Description</label></b>
							<textarea class="form-control" id="desc" name="desc" rows="2" required><?php echo $prodDesc; ?></textarea>
						</div>
						<div class="text-left my-2">
							<b><label for="size">Size</label></b>
							<input class="form-control" id="size" name="size" value="<?php echo $size; ?>" type="text" required>
						</div>
						<div class="text-left my-2">
							<b><label for="price">Price</label></b>
							<input class="form-control" id="price" name="price" value="<?php echo $price; ?>" type="number" min="1" required>
						</div>
						<div class="text-left my-2">
							<b><label for="category">Category</label></b>
							<select class="form-control" id="category" name="category">
								<?php
								$catsql = "SELECT * FROM categories";
								$catResult = mysqli_query($conn, $catsql);
								while ($catRow = mysqli_fetch_assoc($catResult)) {
									echo "<option value='{$catRow['categoryId']}'" . ($catRow['categoryName'] == $categoryName ? " selected" : "") . ">{$catRow['categoryName']}</option>";
								}
								?>
							</select>
						</div>
						<br>
						<input type="hidden" id="prodId" name="prodId" value="<?php echo $prodId; ?>">
						<button type="submit" class="btn btn-success" name="updateItem">Update</button>
					</form>
				</div>
			</div>
		</div>
	</div>


	<script>
		function confirmDeletion(prodId) {
			if (confirm('Are you sure you want to delete this item?')) {
				const form = document.createElement('form');
				form.method = 'post';
				form.action = 'partials/_menuManage.php';

				const inputId = document.createElement('input');
				inputId.type = 'hidden';
				inputId.name = 'prodId';
				inputId.value = prodId;

				const inputAction = document.createElement('input');
				inputAction.type = 'hidden';
				inputAction.name = 'removeItem';
				inputAction.value = '1';

				form.appendChild(inputId);
				form.appendChild(inputAction);
				document.body.appendChild(form);
				form.submit();
			}
		}
	</script>

<?php
}
?>