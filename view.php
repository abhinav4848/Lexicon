<?php
include('includes/connect-db.php');
include 'includes/Parsedown.php';
$parsedown = new Parsedown();
$parsedown->setSafeMode(true);

if (array_key_exists("category", $_GET) and $_GET['category']!='') {
    //Replace the category id with category name
    $query2 = "SELECT * FROM `mbbs_categories` WHERE id='".mysqli_real_escape_string($link, $_GET['category'])."'";
    $result2 = mysqli_query($link, $query2);
    $mbbs_categories = mysqli_fetch_array($result2);
} else {
    header("Location: index.php");
}

function tokenTruncate($string, $your_desired_width=18)
{
    //truncate long words at space
    $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
    $parts_count = count($parts);

    $length = 0;
    $last_part = 0;
    for (; $last_part < $parts_count; ++$last_part) {
        $length += strlen($parts[$last_part]);
        if ($length > $your_desired_width) {
            break;
        }
    }

    return implode(array_slice($parts, 0, $last_part));
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--syntax highlighter -->
    <link href="includes/prism.css" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css"
        integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    <title>Lexicon- By Abhinav (<?= $mbbs_categories['name']; ?>)</title>
    <style type="text/css">
    .preserveLines {
        white-space: pre-wrap;
    }

    #newdata {
        display: none;
        border: 1px solid #A9A9A9;
        border-radius: 3px;
        background-color: #F5F5F5;
        margin-bottom: 6px;
    }

    #top {
        margin-top: 63px;
    }

    .footer {
        position: relative;
        bottom: 0;
        width: 100%;
        height: 60px;
        /* Set the fixed height of the footer here */
        line-height: 60px;
        /* Vertically center the text there */
        background-color: #f5f5f5;
        text-align: center;
        font-size: 12px;
    }

    h4 {
        margin-bottom: 0px !important;
    }

    /*restore the cancel button on searchbox that bootstrap breas*/
    input[type="search"]::-webkit-search-cancel-button {
        -webkit-appearance: searchfield-cancel-button;
    }

    .bg-light {
        box-shadow: 0 0 2px grey;
        color: rgba(0, 0, 0, .4);
    }

    /*since the category name may be too long to display on mobile, display the truncated version on mobile */
    .phoneHeader {
        display: none;
    }

    @media only screen and (max-width: 600px) {
        .phoneHeader {
            display: inline;
        }

        .normalHeader {
            display: none;
        }
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <a class="navbar-brand" href="index.php"
            title="Choose another category">Lexicon-<?= '<span class="phoneHeader">'.tokenTruncate($mbbs_categories['name'], 18).'</span><span class="normalHeader">'.$mbbs_categories['name'].'</span>'; ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <?php
                    if (array_key_exists("deleted", $_GET)) {
                        //if we are on deleted page, give link to go back to normal page
                        echo '<a class="nav-link" href="view.php?category='.$_GET['category'].'">Show Normal</a>';
                    } else {
                        //if we are not on deleted page, give link to go to deleted page
                        echo '<a class="nav-link" href="view.php?category='.$_GET['category'].'&deleted=1">Show Deleted</a>';
                    }
                    ?>
                </li>
                <?php if ($_GET['category']==1) {
                        echo '<li class="nav-item">';
                        echo '<a class="nav-link" href="https://www.google.com/search?q=translate%20from%20english%20to%20kannada" target="_blank" title="Google Translate"><i class="fab fa-google"></i></a>';
                        echo '</li>';
                    }?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Status
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php
                        $query3 = "SELECT * FROM `mbbs_users` WHERE id='".mysqli_real_escape_string($link, $mbbs_categories['created_by'])."' LIMIT 1";
                        $result3 = mysqli_query($link, $query3);
                        $mbbs_users = mysqli_fetch_array($result3);
                        echo '<a class="dropdown-item" href="#"><span class="small">Name:</span> '.$mbbs_categories['name'].' </a> ';
                        echo '<a class="dropdown-item" href="#"><span class="small">Privacy:</span> '.ucfirst($mbbs_categories['privacy']).' </a> ';
                        echo '<a class="dropdown-item" href="#"><span class="small">Created By:</span> '.$mbbs_users['name'].' </a> ';
                        echo '<a class="dropdown-item" href="#"><span class="small">At:</span> '.$mbbs_categories['created_at'].' </a> ';
                        echo '<a class="dropdown-item" href="#"><span class="small">Comments:</span> '.$mbbs_categories['comments'].' </a> ';
                        ?>
                    </div>
                </li>
            </ul>
            <button type="button" id="showHideAddnew" class="btn btn-outline-primary mr-2" title="Add New Entries">Add
                New
                <?php
                if (isset($_GET['successEdit']) and $_GET['successEdit'] == 1) {
                    echo '<span class="badge badge-success" title="Successfully Added"><i class="fas fa-check"></i></span>';
                } elseif (isset($_GET['failedEdit']) and $_GET['failedEdit'] == 1) {
                    echo '<span class="badge badge-danger" title="Edit was Unsuccessful"><i class="fas fa-times"></i></span>';
                }
                ?>
            </button>
            <!--<form class="form-inline my-2 my-lg-0">
				<input class="form-control mr-sm-2" type="search" placeholder="Universal Search" aria-label="Search">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
			</form>-->
        </div>
    </nav>
    <div class="container-fluid" id="top">
        <?php include("new.php");

        if (array_key_exists("deleted", $_GET)) {
            //doesn't work currently.
            $sql = "SELECT COUNT(*) FROM `mbbs_deleted_entries`";
        } else {
            $sql = "SELECT COUNT(*) FROM `mbbs_entries`";
        }

        $resultCount = mysqli_query($link, $sql) or trigger_error("SQL", E_USER_ERROR);
        $r = mysqli_fetch_row($resultCount);
        ?>
        <input autofocus="" type="search" id="myInput" class="form-control mb-2"
            placeholder="Search from <?= $r[0]; ?> entries" title="Type in something">

        <table class='table table-striped table-responsive-sm' id='myTable'>
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Results</th>
                </tr>
            </thead>
            <tbody>
                <?php
                 //get all entry ids matching the category
                 $query = "SELECT * FROM `mbbs_categories_entries_relation` WHERE `category_id`= ".mysqli_real_escape_string($link, $_GET['category'])." ORDER BY `entry_id`";

                 //decide which table to take the actual entries from
                 if (array_key_exists("deleted", $_GET)) {
                     $table = 'mbbs_deleted_entries';
                 } else {
                     $table = 'mbbs_entries';
                 }

                 //get actual entry data by checking for the matching entry_id in $table.
                 $result = mysqli_query($link, $query);
                 while ($row = mysqli_fetch_array($result)) {
                     $query_get_entry = "SELECT * FROM ".$table." WHERE `id` = ".mysqli_real_escape_string($link, $row['entry_id'])." LIMIT 1";
                     $result_get_entry = mysqli_query($link, $query_get_entry);
                     $row_get_entry = mysqli_fetch_array($result_get_entry);

                     //since some entries get moved to other table on deletion
                     //but continue to exist unchanged in `mbbs_categories_entries_relation`,
                     //we need to check if we actually found a matching entry_id in $table
                     //only if the result is not empty we can bother to make a table row.
                     if (mysqli_num_rows($result_get_entry)>0) {
                         //Replace the user id with user name who had made the entry
                         $query3 = "SELECT * FROM `mbbs_users` WHERE id='".mysqli_real_escape_string($link, $row_get_entry['comment_by'])."' LIMIT 1";
                         $result3 = mysqli_query($link, $query3);
                         $mbbs_users = mysqli_fetch_array($result3);

                         /*$row_get_entry['body'] = htmlspecialchars($row_get_entry['body'], ENT_QUOTES);
     					$url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
     					$row_get_entry['body'] = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $row_get_entry['body']);*/
                         echo "<tr><td>";

                         echo '<h4>' . htmlspecialchars($row_get_entry['heading'], ENT_QUOTES) . '</h4>';
                         echo '<span class="small text-success">'.$row_get_entry['timestamp'].' | '. htmlspecialchars($mbbs_users['name'], ENT_QUOTES).' | #';
                         //if not showing the deleted list, provide an edit link
                         echo  $row_get_entry['id'];
                         if (!array_key_exists("deleted", $_GET)) {
                             echo ' | <a href="edit.php?id=' . $row_get_entry['id'] . '&category='.$_GET['category'].'" title="Edit">E</a>';
                             echo ' | <a href="view-one.php?id=' . $row_get_entry['id'] . '&category='.$_GET['category'].'" title="View">V</a>';
                         }
                         echo '</span><hr />';

                         echo '<span class="preserveLines">' . $parsedown->line($row_get_entry['body']) . '</span>';
                         //echo '<td>' . $mbbs_categories['name'] . '</td>';
                         echo "</tr></td>";
                     }
                 }
                 ?>
            </tbody>
        </table>
    </div>
    <footer class="footer">
        <div class="container">
            <span class="text-muted"
                title="Kasturba medical college, Mangalore. Use at your own risk. Kannada is not my native language and I don't really have much experience with it.">Created
                by Abhinav Kumar. 2018.</span>
        </div>
    </footer>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <!--<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>-->
    <!-- https://stackoverflow.com/questions/44212202/my-javascript-is-returning-this-error-ajax-is-not-a-function -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script src="includes/prism.js"></script>

    <script>
    function filterTable(event) {
        var filter = event.target.value.toUpperCase();
        var rows = document.querySelector("#myTable tbody").rows;

        for (var i = 0; i < rows.length; i++) {
            var firstCol = rows[i].cells[0].textContent.toUpperCase();
            if (firstCol.indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }

    document.querySelector('#myInput').addEventListener('keyup', filterTable, false);
    //http://help.dottoro.com/ljdvxmhr.php
    document.querySelector('#myInput').addEventListener("search", filterTable, false);


    $("#showHideAddnew").click(function() {
        $("#newdata").toggle("fast", "linear");
        $("#showHideAddnew").button('toggle');
        //https://stackoverflow.com/a/52948533/2365231
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
        document.getElementById("heading").focus();
    });

    //for collapsing navbar on clicking outside
    //$(document).click(function(event) {
    //	$(event.target).closest(".navbar").length || $(".navbar-collapse.show").length && $(".navbar-collapse.show").collapse("hide")
    //});
    $(function() {
        $(document).click(function(event) {
            $('.navbar-collapse').collapse('hide');
        });
    });

    function modalCloser() {
        $('#urlInserterModal').modal('hide');
    }
    </script>
</body>

</html>