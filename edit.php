<?php
session_start();
include('includes/connect-db.php');
if (array_key_exists("id", $_GET)) {
	$query = "SELECT * FROM `mbbs_entries` WHERE id = '".mysqli_real_escape_string($link, $_GET['id'])."' LIMIT 1";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_array($result);

	$query_categories = "SELECT * FROM `mbbs_categories_entries_relation` WHERE `entry_id`= ".mysqli_real_escape_string($link, $_GET['id']);
	$result_categories = mysqli_query($link, $query_categories);
} else {
	header("Location: view.php?category=".$_GET['category']."&failedEdit=1");
}

if (array_key_exists("submit", $_POST)) {
	if ($_POST['submit'] == 'update' AND ($_POST['heading']!=$row['heading'] OR $_POST['body']!=$row['body'])) {
		$query1 = "UPDATE `mbbs_entries` 
		SET `heading` = '".mysqli_real_escape_string($link, $_POST['heading'])."',
		`body` = '".mysqli_real_escape_string($link, $_POST['body'])."'
		WHERE `mbbs_entries`.`id` = '".mysqli_real_escape_string($link, $_GET['id'])."' LIMIT 1";

		if(mysqli_query($link, $query1)) {
			$query2 = "INSERT INTO `mbbs_updates` (`entry_id`, `old_heading`, `old_body`, `updated_by_id`, `datetime`) 
			VALUES (
			'".mysqli_real_escape_string($link, $_GET['id'])."',
			'".mysqli_real_escape_string($link, $row['heading'])."',
			'".mysqli_real_escape_string($link, $row['body'])."',
			'1',
			'".date('d-m-Y h:i:sa')."');";
			if(mysqli_query($link, $query2)) {
				//header("Location: view.php?category=".$_GET['category']."&successEdit=1");
				header("Location: view-one.php?id=".$_GET['id']."&successEdit=1");
			} else {
				$error = "updated entry, but failed to add history.";
			}
		} else {
			$error = "failed to update entry.";
		}
	} else {
		$error = "No changes were made.";
	}
	if ($_POST['submit'] == 'delete') {
		$query_delete = "INSERT INTO `mbbs_deleted_entries` (`id`, `heading`, `body`, `comment_by`, `timestamp`) 
		VALUES (
		'".mysqli_real_escape_string($link, $_GET['id'])."',
		'".mysqli_real_escape_string($link, $row['heading'])."',
		'".mysqli_real_escape_string($link, $row['body'])."',
		'".mysqli_real_escape_string($link, $row['comment_by'])."',
		'".date('d-m-Y h:i:sa')."');";

		if(mysqli_query($link, $query_delete)) {
			$query1 = "DELETE FROM `mbbs_entries` WHERE `mbbs_entries`.`id` = '". $_GET['id'] ."' LIMIT 1";
			if(mysqli_query($link, $query1)) {
				header("Location: view.php?category=".$_GET['category']."&successEdit=1");
			} else {
				$error = "copied to backup, but failed to delete.";
			}
		} else {
			$error = "failed to copy to backup.";
			
		}
	}
}
?>

<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">

	<!-- Icons -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

	<style type="text/css">
	#delete {
		display: none;
	}
	.preserveLines {
		white-space: pre-wrap;
	}
</style>
<title>Update Entry</title>
</head>
<body>
	<div class="container-fluid" id="newdata">
		<?php
		echo '<h2>Update Entry <a href="view.php?category='.$_GET['category'].'" class="btn btn-outline-primary" role="button">Home Page</a></h2>';
		if (isset($error) AND $error!='') {
			echo '<div id="tablediv">';
			echo '<span class="badge badge-danger">'.$error.'</span>';
			echo '</div>';
		}
		?>
		<form method="post">
			<table class="table table-responsive-xs">
				<thead class="thead-dark"><tr> <th>Property-Value</th> </tr></thead>
				<tbody>
					<tr><td><div class="form-group"><label for="heading">Heading</label><textarea class="form-control" name="heading" id="heading"><?php echo $row['heading']; ?></textarea></div></td></tr>
					<tr><td><div class="form-group"><label for="body">Body</label>
						<?php include 'includes/advancedEdits.php'; //include oe click insert links?>
						<textarea class="form-control" name="body" rows="10" id="body"><?php echo $row['body']; ?></textarea></div>
					</td></tr>
					<tr><td>Category:<br /><?php 

					while($row_categories = mysqli_fetch_array($result_categories)) {
						$query_category_name = "SELECT * FROM `mbbs_categories` WHERE id = '".$row_categories['category_id']."' LIMIT 1";
						$result_category_name = mysqli_query($link, $query_category_name);
						$row_category_name = mysqli_fetch_array($result_category_name);

						echo '<a href="view.php?category='.$row_categories['category_id'].'">'.$row_category_name['name'].' (#'.$row_categories['category_id'].')</a>, ';
					}
					?></td></tr>
					<tr><td>Comment By:<br />
						<?php 
						$query1 = "SELECT * FROM `mbbs_users` WHERE id = '".$row['comment_by']."' LIMIT 1";
						$result1 = mysqli_query($link, $query1);
						$row1 = mysqli_fetch_array($result1);

						echo $row1['name'];

						?>
					</td></tr>
					<tr>
						<td>
							<button type="submit" name="submit" value="update" class="btn btn-primary m-1"><i class="far fa-edit"></i> Update Entry</button>
							<button type="button" id="revealDelete" class="btn btn-outline-danger m-1"><i class="fas fa-times"></i> Delete Entry</button>
							<button type="submit" id="delete" name="submit" value="delete" class="btn btn-danger m-1"><i class="fas fa-exclamation-triangle"></i> Confirm Delete</button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>

	<h2>History</h2>
	<table class='table table-striped table-responsive-sm' id='myTable'>
		<thead class="thead-dark">
			<tr>
				<th scope="col">ID</th>
				<th scope="col">Old Heading</th>
				<th scope="col">Old Body</th>
				<th scope="col">Overwritten By</th>
				<th scope="col">At time</th>
			</tr>
		</thead>
		<tbody>
			<?php
			//get old rcords from database
			$query_old = "SELECT * FROM `mbbs_updates` WHERE `entry_id` = ".$_GET['id']." ORDER BY id DESC";
			$result_old = mysqli_query($link, $query_old);
        	//echo out the contents of each row into a table
			while($row_old = mysqli_fetch_array($result_old)) {
           	//Replace the updated_by_id with user name who had made the update
				$query3 = "SELECT * FROM `mbbs_users` WHERE id='".$row_old['updated_by_id']."'";
				$result3 = mysqli_query($link, $query3);
				$mbbs_users = mysqli_fetch_array($result3);  

				echo "<tr>";
				echo '<th scope="row">' . $row_old['id'] . '</td>';
				echo '<td>' . htmlspecialchars($row_old['old_heading'], ENT_QUOTES) . '</td>';
				echo '<td class="preserveLines">' . htmlspecialchars($row_old['old_body'], ENT_QUOTES) . '</td>';
				echo '<td>' . htmlspecialchars($mbbs_users['name'], ENT_QUOTES) .'</td>';
				echo '<td>' . $row_old['datetime'].'</td>';
				echo "</tr>";
			}
			?>
		</tbody>
	</table>

	<!-- Optional JavaScript -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>

	<script type="text/javascript">
		$("#revealDelete").click(function(){
			$("#delete").toggle("fast","linear");
		});

		function modalCloser(){
 			$('#urlInserterModal').modal('hide');
 		}
	</script>
</body>
</html>