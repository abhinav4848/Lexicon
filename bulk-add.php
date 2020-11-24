<?php
include('includes/connect-db.php');

if (array_key_exists('category', $_POST) and array_key_exists('values', $_POST) and array_key_exists('comment_by', $_POST)) {
    //https://stackoverflow.com/a/7870846/2365231
    $textAr = explode(PHP_EOL, trim($_POST['values']));

    foreach ($textAr as $line) {
        $line = trim($line, ' ()'); //remove paranthesis and spaces on the outside
        $explodArray = explode(',', $line);

        $heading = trim($explodArray[0], '\'');
        $body = trim($explodArray[1], '\'');
        
        $query = "INSERT INTO `mbbs_entries` (`heading`, `body`, `comment_by`, `timestamp`) 
        VALUES (
        '".mysqli_real_escape_string($link, $heading)."',
        '".mysqli_real_escape_string($link, $body)."',
        '".mysqli_real_escape_string($link, $_POST['comment_by'])."',
        '".date('d-m-Y h:i:sa')."');";

        if (mysqli_query($link, $query)) {
            $entry_id = mysqli_insert_id($link);
            $query = "INSERT INTO `mbbs_categories_entries_relation` (`category_id`, `entry_id`, `created_at`, `created_by`) 
            VALUES (
            '".mysqli_real_escape_string($link, $_POST['category'])."',
            '".mysqli_real_escape_string($link, $entry_id)."',  
            '".mysqli_real_escape_string($link, date('d-m-Y h:i:sa'))."',
            '".mysqli_real_escape_string($link, $_POST['comment_by'])."');";
    
            if (mysqli_query($link, $query)) {
                header("Location: update-relations.php?successEdit=1");
            } else {
                echo '<div id="tablediv">';
                echo "failed to add the category to entry relation.";
                echo '</div>';
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <title>Insert Data</title>
</head>

<body>
    <div class="container">
        <h1>Enter data to populate</h1>
        <a href="./">Go back</a>
        <h5>Category</h5>
        <form method="post">
            <div class="form-group">
                <select name="category" class="form-control">
                    <?php
                        $query = "SELECT * FROM `mbbs_categories` ORDER BY `name`";
                        $result = mysqli_query($link, $query);
                        while ($row = mysqli_fetch_array($result)) {
                            echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <h5>Values</h5>
                <p>
                    Format: <code>('heading','body')</code>. Start on new line for next entry.
                </p>
                <textarea class="form-control" rows="8" cols="50" name="values">('heading','body')
('aloha','hello')
('roger','federer')</textarea>
            </div>
            <label>Comment By (User Id): <input type="number" name="comment_by" value="1" class="form-control"></label>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
</body>

</html>