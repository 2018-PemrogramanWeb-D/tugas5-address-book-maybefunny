<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
    $dbname = "addressbook";

	$conn = new mysqli($servername, $username, $password, $dbname);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>My Address Book</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
	<h1 class="display-4 text-center">My Address Book</h1>
	
	<table class="table">
		<thead class="thead-light">
			<tr>
				<th scope="col">id</th>
				<th scope="col">Name</th>
				<th scope="col">Phone</th>
				<th scope="col">Address</th>
				<th scope="col">Postal Code</th>
				<th scope="col">E-Mail</th>
				<th scope="col">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$result = $conn->query("SELECT * FROM addresslist");
				$i=1;
				if ($result) {
					while($row = mysqli_fetch_assoc($result)) {
						echo '
							<div id="myModal" class="modal">
								<div class="modal-content">
									<span class="close">&times;</span>
									<p>'. $row["id"] .'</p>
								</div>
							</div>
						';
						echo '
							<tr>
								<th scope="row">' . $i++. '</th>
								<td>' . $row["name"]. '</td>
								<td>' . $row["phone"]. '</td>
								<td>' . $row["address"]. '</td>
								<td>' . $row["postal_code"]. '</td>
								<td>' . $row["email"].'</td>
								<!-- Button trigger modal -->
								<td><button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#editModal'. $row["id"] .'">
									edit
								</button>

								<!-- Modal -->
								<div class="modal fade" id="editModal'. $row["id"] .'" tabindex="-1" role="dialog" aria-labelledby="editModal'. $row["id"] .'Title" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="editModal'. $row["id"] .'Title">Edit Form</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<form action="" method="post">
													<div class="form-group">
														<input type="hidden" name="id" value="'. $row['id'] .'">
														<label for="name">Name</label>
														<input type="text" class="form-control" name="name" value="'. $row['name'] .'">
														<label for="name">Phone</label>
														<input type="text" class="form-control" name="phone" value="'. $row['phone'] .'">
														<label for="name">Address</label>
														<input type="text" class="form-control" name="address" value="'. $row['address'] .'">
														<label for="name">Postal Code</label>
														<input type="text" class="form-control" name="postal_code" value="'. $row['postal_code'] .'">
														<label for="email">Email</label>
														<input type="email" class="form-control" name="email" value="'. $row['email'] .'">
														<br>
														<button type="submit" class="btn btn-primary" name="edit">Update</button>
													</div>
												</form>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div>
								
								<form method="POST" action="">
									<input type="hidden" id="id" name="id" value="'. $row["id"] .'">
									<button type="submit" class="btn btn-danger btn-sm btn-block" name="delete">delete</button>
								</form></td>
							</tr>';
						
					}
				} else {
					echo "0 results";
				}
			?>
			<form action="" method="post">
				<tr>
					<td>&nbsp</td>
					<td><input type="text" class="form-control" name="name" placeholder="Name"></td>
					<td><input type="text" class="form-control" name="phone" placeholder="Phone"></td>
					<td><input type="text" class="form-control" name="address" placeholder="Address"></td>
					<td><input type="text" class="form-control" name="postal_code" placeholder="Postal Code"></td>
					<td><input type="text" class="form-control" name="email" placeholder="E-mail"></td>
					<td><button type="submit" class="btn btn-primary" name="add">Add</button></td>
				</tr>
			</form>
		</tbody>
	</table>
	<?php
		//add section
		if (isset($_POST['add'])) {
			$name = $_POST['name'];
			$phone = $_POST['phone'];
			$address = $_POST['address'];
			$postal_code = $_POST['postal_code'];
			$email = $_POST['email'];
			$find="SELECT * FROM addresslist WHERE 
					name = '$name' 
					and phone = '$phone'
					and address = '$address'  
					and postal_code = '$postal_code'  
					and email = '$email'";
			$found = $conn->query($find);
			if(mysqli_num_rows($found)){
				echo "<script type='text/javascript'>alert('data already exist!')</script><meta http-equiv='refresh' content='0'>";
			}else{
				$add = "INSERT INTO addresslist (name, phone, address, postal_code, email)
				VALUES ('$name', '$phone', '$address', '$postal_code','$email')";
				if ($conn->query($add)) {
						echo "<script type='text/javascript'>alert('success')</script><meta http-equiv='refresh' content='0'>";
				} 
			}
		}

		//update section
		if (isset($_POST['edit'])) {
			$id = $_POST['id'];
			$name = $_POST['name'];
			$phone = $_POST['phone'];
			$address = $_POST['address'];
			$postal_code = $_POST['postal_code'];
			$email = $_POST['email'];
			$update = "UPDATE addresslist SET name='$name', phone='$phone', address='$address', postal_code='$postal_code', email='$email' WHERE id ='$id'";
			if (mysqli_query($conn, $update)) {
				echo "<script type='text/javascript'>alert('success')</script><meta http-equiv='refresh' content='0'>";
			} 
		}

		//delete section
		if (isset($_POST['delete'])) {
			$id = $_POST['id'];
			$delete ="DELETE FROM addresslist WHERE id = '$id' ";
			if ($conn->query($delete)) {
				echo "<script type='text/javascript'>alert('success')</script><meta http-equiv='refresh' content='0'>";
			} 
		}
	?>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</html>
