<?php
session_start();

include('includes/connect-db.php');
include 'includes/Parsedown.php';
$parsedown = new Parsedown();
$parsedown->setSafeMode(true);

include('includes/connect-db.php');
if (array_key_exists("id", $_GET) AND is_numeric($_GET['id'])) {
	$query = "SELECT * FROM `mbbs_entries` WHERE id = '".mysqli_real_escape_string($link, $_GET['id'])."' LIMIT 1";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_array($result);

	$query_categories = "SELECT * FROM `mbbs_categories_entries_relation` WHERE `entry_id`= ".mysqli_real_escape_string($link, $_GET['id']);
	$result_categories = mysqli_query($link, $query_categories);

	$query_user = "SELECT * FROM `mbbs_users` WHERE id = '".$row['comment_by']."' LIMIT 1";
	$result_user = mysqli_query($link, $query_user);
	$row_user = mysqli_fetch_array($result_user);
} else {
	header("Location: view.php?category=".$_GET['category']."&failedEdit=1");
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

	<style type="text/css">
	.preserveLines {
		white-space: pre-wrap;
	}

	.footer {
		position: relative;
		bottom: 0;
		width: 100%;
		height:60px; /* Set the fixed height of the footer here */
		line-height: 60px; /* Vertically center the text there */
		background-color: #f5f5f5;
		text-align: center;
		font-size: 12px;
		margin-top: 15px;
	}
	#top {
		margin-top: 63px;
	}
</style>
<title>Lexicon- Entry #<?= $row['id'] .' - '. $row['heading']; ?></title>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
		<a class="navbar-brand" href="index.php" title="Choose another category">Lexicon- <?= $row['id']; ?></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link" href="view.php?category=<?=$_GET['category'];?>" role="button">Go Back</a>
				</li>
			</ul>
		</div>
	</nav>

	<div class="container" id="top">
		<?php
		echo '<h2>'.htmlspecialchars($row['heading'], ENT_QUOTES).'</h2>';
		echo '<p class="preserveLines">' . $parsedown->line($row['body']) . '</p>';
		?>
		<hr />
		<!-- Button trigger modal -->
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#detailsModal">
			Details
		</button>		
	</div>

	<!-- Modal -->
	<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="detailsModalLabel"><?= htmlspecialchars($row['heading'], ENT_QUOTES); ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<?php
					echo '<p><b>ID: </b>'.$row['id'] .'</p>';
					echo '<p><b>Time: </b>'.$row['timestamp'].'</p>';
					echo '<p><b>User: </b>'.htmlspecialchars($row_user['name'], ENT_QUOTES).'</p>';
					echo '<p><b>Action: </b><a href="edit.php?id=' . $row['id'] . '&category='.$_GET['category'].'" title="Edit">Edit</a></p>';
					echo '<b>Categories: </b>';
					while($row_categories = mysqli_fetch_array($result_categories)) {
						$query_category_name = "SELECT * FROM `mbbs_categories` WHERE id = '".$row_categories['category_id']."' LIMIT 1";
						$result_category_name = mysqli_query($link, $query_category_name);
						$row_category_name = mysqli_fetch_array($result_category_name);

						echo '<a href="view.php?category='.$row_categories['category_id'].'">'.$row_category_name['name'].' (#'.$row_categories['category_id'].')</a>, ';
					}
					?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>


	<footer class="footer">
		<div class="container">
			<span class="text-muted" title="Kasturba medical college, Mangalore. Use at your own risk. Kannada is not my native language and I don't really have much experience with it.">Created by Abhinav Kumar. 2018.</span>
		</div>
	</footer>

	<!-- Optional JavaScript -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
</body>
</html>