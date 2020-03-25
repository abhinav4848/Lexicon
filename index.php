<?php
session_start();
$_SESSION['id'] = 1;

include('includes/connect-db.php');
include 'includes/Parsedown.php';
$parsedown = new Parsedown();
$parsedown->setSafeMode(true);

if (array_key_exists("submit", $_POST) AND $_POST['submit']=='addCategory') {
	if ($_POST['category']!='') {
		$query = "SELECT `id` FROM `mbbs_categories` WHERE `name`='".mysqli_real_escape_string($link, $_POST['category'])."' LIMIT 1";
		$result = mysqli_query($link, $query);
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
			echo "Category aleady exists as id number ".$row['id'];
			die();
		} else {
			$query = "INSERT INTO `mbbs_categories` (`name`, `privacy`, `created_by`, `created_at`, `comments`) 
			VALUES (
			'".mysqli_real_escape_string($link, ucfirst($_POST['category']))."',
			'".mysqli_real_escape_string($link, $_POST['privacy'])."',
			'".mysqli_real_escape_string($link, $_POST['created_by'])."',
			'".mysqli_real_escape_string($link, date("d-m-Y h:i:sa"))."',
			'".mysqli_real_escape_string($link, $_POST['comments'])."');";
			if(mysqli_query($link, $query)) {
				$entry_id = mysqli_insert_id($link);
				echo $entry_id;
				die();
			} else {
				echo 'Error in code';
				die();
			}
		}
	} else {
		echo 'please enter something';
		die();
	}
}

//Instant Search function
if (array_key_exists("searchtext", $_POST)) {
	if ($_POST['searchtext']!= '') {
		$query = "SELECT `id`, `heading`, `body`, `timestamp` FROM `mbbs_entries` WHERE `heading` LIKE '%".mysqli_real_escape_string($link, $_POST['searchtext'])."%' OR `body` LIKE '%".mysqli_real_escape_string($link, $_POST['searchtext'])."%' LIMIT 20";
		$result = mysqli_query($link, $query);
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_array($result)) {
				echo "<tr><td>";

				echo '<h4>' . htmlspecialchars($row['heading'], ENT_QUOTES) . '</h4>';
				echo '<span class="small text-success">'.$row['timestamp'].' | #' . $row['id'] . ' | <a href="edit.php?id=' . $row['id'] . '" title="Edit">E</a> | <a href="view-one.php?id=' . $row['id'] . '" title="View">V</a>';
				echo '</span><hr />';

				echo '<span class="preserveLines">' . $parsedown->line($row['body']) . '</span>';
				echo "</tr></td>";
			}
		}
	}
	die();
}
?>
<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

	<!-- Select 2 Smart select -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

	<style type="text/css">
	.container {
		width: 40vw;
		margin-top: 10px;
	}
	#newdata {
		display:none;
		border: 1px solid #A9A9A9;
		border-radius: 3px;
		background-color: #F5F5F5;
		margin-bottom: 6px;
	}
	.center {
		text-align: center;
	}
	/*restore the cancel button on searchbox that bootstrap breas*/
	input[type="search"]::-webkit-search-cancel-button {
		-webkit-appearance: searchfield-cancel-button;
	}
	@media only screen and (max-width: 600px) {
		.container {
			width: 100vw;
		}
	}
	#resultTable {
		display: none;
	}
	.preserveLines {
		white-space: pre-wrap;
	}.footer {
  position: absolute;
  bottom: 0;
  width: 100%;
  height: 60px; /* Set the fixed height of the footer here */
  line-height: 60px; /* Vertically center the text there */
  background-color: #f5f5f5;
}
</style>
<title>Lexicon-Home</title>
</head>
<body>
	<div class="container">
		<div class="center">
			<h1>Lexicon</h1>
			<p>Choose the topic you want results for: </p>
		</div>

		<select class="form-control choose" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
			<option></option>
			<?php 
			$query = "SELECT * FROM `mbbs_categories` ORDER BY `name`";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_array($result)) {
				echo '<option value="view.php?category='.$row['id'].'">'.$row['name'].'</option>';
			}
			?>
		</select>
		<div class="center">
			<button type="button"class="btn btn-outline-primary my-1" title="Add New Category" id="addnewcat">Add New Category</button>
			<button type="button" id="showHideAddnew" class="btn btn-outline-primary mr-2" title="Add New Entries">Add New Entry 
				<?php 
				if (isset($_GET['successEdit']) AND $_GET['successEdit'] == 1) {
					echo '<span class="badge badge-success" title="Successfully Added"><i class="fas fa-check"></i></span>';
				} else if (isset($_GET['failedEdit']) AND $_GET['failedEdit'] == 1) {
					echo '<span class="badge badge-danger" title="Edit was Unsuccessful"><i class="fas fa-times"></i></span>';
				}
				?>
			</button>
		</div>
		<?php include("new.php"); ?>
		<input autofocus="" type="search" class="form-control" name="inputID" id="inputID" placeholder="Or Search Anything" autocomplete="off">

		<table class='table table-striped table-responsive-xs mt-2' id='resultTable'>
			<thead class="thead-dark">
				<tr>
					<th scope="col">Results</th>
				</tr>
			</thead>
			<tbody id="results">

			</tbody>
		</table>
		<p class="sticky-bottom center my-2">A site by <a href="https://abhinavkr.ga/">Abhinav Kumar</a> :) </p>
	</div>

	<!-- Optional JavaScript -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.choose').select2({
				placeholder: "Select...",
				allowClear: true
			});
		});

		$(document).ready(function() {
			$('#addnewcat').click(function() {
				var category = prompt("Please enter your category:", "");
				if (category!== null) {
					$.ajax({
						type: "POST",
						url: "index.php",
						data: {submit:'addCategory',
						category: category, 
						privacy: 'public',
						created_by: 1,
						comments: 'none'
					},
					success: function(result) {
						if (result%1 == 0) {
							window.location.replace("./view.php?category="+result);
						} else {
							alert(result);
						}
					}
				})
				}
			});
		});

		document.querySelector('#inputID').addEventListener('keyup', search, false);
		//http://help.dottoro.com/ljdvxmhr.php
		document.querySelector('#inputID').addEventListener("search", search, false);	

		function search() {
			var searchtext = $("#inputID").val();
			$.ajax({
				type: "POST",
				url: "index.php",
				data: {searchtext: searchtext},
				success: function(result) {
					if (result!='') {
						$("#resultTable").show();
						$("#results").html(result);
					} else {
						$("#resultTable").hide();
					}
				}
			})
		}

		$("#showHideAddnew").click(function(){
			$("#newdata").toggle("fast","linear");
			$("#showHideAddnew").button('toggle');
 			//https://stackoverflow.com/a/52948533/2365231
 			window.scrollTo({ top: 0, behavior: 'smooth' });
 			document.getElementById("heading").focus();
 		});

 		function modalCloser(){
 			$('#urlInserterModal').modal('hide');
 		}
 	</script>
 </body>
 </html>