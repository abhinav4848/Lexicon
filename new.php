<?php
include('includes/connect-db.php');
if (array_key_exists("submit", $_POST) AND $_POST['submit']=='addEntry' AND $_POST['heading']!='' AND $_POST['body']!='') {
	$query = "INSERT INTO `mbbs_entries` (`heading`, `body`, `comment_by`, `timestamp`) 
	VALUES (
	'".mysqli_real_escape_string($link, $_POST['heading'])."',
	'".mysqli_real_escape_string($link, $_POST['body'])."',
	'".mysqli_real_escape_string($link, $_POST['comment_by'])."',
	'".date('d-m-Y h:i:sa')."');";

	if(mysqli_query($link, $query)) {
		$entry_id = mysqli_insert_id($link);
		$query = "INSERT INTO `mbbs_categories_entries_relation` (`category_id`, `entry_id`, `created_at`, `created_by`) 
		VALUES (
		'".mysqli_real_escape_string($link, $_POST['category'])."',
		'".mysqli_real_escape_string($link, $entry_id)."',  
		'".mysqli_real_escape_string($link, date('d-m-Y h:i:sa'))."',
		'".mysqli_real_escape_string($link, $_POST['comment_by'])."');";

		if(mysqli_query($link, $query)) {
			if (array_key_exists('category', $_GET)) {
				header("Location: view.php?category=".$_GET['category']."&successEdit=1");
			} else {
				header("Location: view.php?successEdit=1");
			}
		} else {
			echo '<div id="tablediv">';
			echo "failed to add the category to entry relation.";
			echo '</div>';
		}
	} else {
		echo '<div id="tablediv">';
		echo "failed insert the entry.";
		echo '</div>';
	}
}
?>
<div id="newdata">
    <form method="post">
        <table class="table table-responsive-xs mt-2">
            <thead class="thead-dark">
                <tr>
                    <th>Add Entry</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="form-group">
                            <label for="heading">Heading</label>
                            <textarea class="form-control" name="heading" id="heading"><?php 
							if (array_key_exists('heading', $_POST)) {
								echo $_POST['heading'];
							}
							?></textarea>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <label for="body">Body</label>
                            <?php include 'includes/advancedEdits.php'; //include oe click insert links?>
                            <textarea class="form-control" name="body" rows="10" id="body"><?php 
              					//positining of php tags like this is important else textarea accepts the tabspaces in code as actual spaces in the final output.
                  			if (array_key_exists('body', $_POST)) {
                  				echo $_POST['body'];
                  			}
                  			?></textarea>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select class="form-control" name="category" id="category">
                                <?php
                  				$query = "SELECT * FROM `mbbs_categories` ORDER BY `name` ASC";
                  				$result = mysqli_query($link, $query);
                  				while ($mbbs_categories = mysqli_fetch_array($result)) {
                  					if (array_key_exists('category', $_GET) AND $_GET['category'] == $mbbs_categories['id']) {
                  						echo '<option value="'.$mbbs_categories['id'].'" selected>'.$mbbs_categories['name'].'</option>';
                  					} else {
                  						echo '<option value="'.$mbbs_categories['id'].'">'.$mbbs_categories['name'].'</option>';
                  					}
                  				}
                  				?>
                            </select>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <label for="comment_by">Comment By</label>
                            <input type="text" class="form-control" name="comment_by" id="comment_by" readonly value="<?php 
                  			if (array_key_exists('comment_by', $_POST)) {
                  				echo $_POST['comment_by'];
                  				} else {
                  					echo "1";
                  				} ?>">
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <button type="submit" name="submit" class="btn btn-primary" value="addEntry">Add Entry</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>