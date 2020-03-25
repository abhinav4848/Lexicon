<!-- Button trigger modal -->
<a href="#" class="badge badge-primary" data-toggle="modal" data-target="#urlInserterModal">Add URL</a>
<a href="#" class="badge badge-danger"
    onclick="typeInTextarea(document.getElementById('body'), 'https://','')">https</a>
<a href="#" class="badge badge-success" onclick="typeInTextarea(document.getElementById('body'), 'http://','')">http</a>
<a href="#" class="badge badge-warning" onclick="typeInTextarea(document.getElementById('body'), '**','**')">Bold</a>
<a href="#" class="badge badge-dark" onclick="typeInTextarea(document.getElementById('body'), '*','*')">Italics</a>
<form>
    <input type="file" name="image" class="image mb-2">
    <button type="submit" name="submit" class="submit mb-2">Submit</button>
</form>
<div class="progress mb-1">
    <div class="progress-bar" style="width: 0%" role="progressbar" id="progress"></div>
</div>
<div class="output mb-1"></div>

<!-- Modal -->
<div class="modal fade" id="urlInserterModal" tabindex="-1" role="dialog" aria-labelledby="urlInsertLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="urlInsertLabel">Add a Link</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none" id="errorAlert"></div>
                <div class="form-group">
                    <label for="textToDisplay">Text to Display</label>
                    <input type="text" class="form-control" id="textToDisplay" aria-describedby="emailHelp"
                        placeholder="Enter text" autocomplete="off">
                    <small id="emailHelp" class="form-text text-muted">Leave blank if you don't want text</small>
                </div>
                <div class="form-group">
                    <label for="urlToInsert">URL to Link</label>
                    <a href="#" class="badge badge-danger"
                        onclick="typeInTextarea(document.getElementById('urlToInsert'), 'https://','')">https</a>
                    <a href="#" class="badge badge-success"
                        onclick="typeInTextarea(document.getElementById('urlToInsert'), 'http://','')">http</a>
                    <input type="url" class="form-control" id="urlToInsert" placeholder="https://" autocomplete="off">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="insertLink" onclick="modalAction()">Insert
                    Link</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
//https://stackoverflow.com/a/34278578/2365231
//need to switch to this plugin: https://github.com/timdown/rangyinputs
function typeInTextarea(el, earlyText, lateText) {
    //Find the cursor position starting from zero. It's the first selected char's index
    var start = el.selectionStart
    //end is cursor position (which is 1 more than last selected character's index. Hence everything before that ndex is selected upto the var start)
    var end = el.selectionEnd
    //get the textarea value
    var text = el.value
    //substring extracts characters from the string start->inclusive, end->exclusive
    var before = text.substring(0, start)
    var after = text.substring(end, text.length)
    console.log('start: ' + start + '\nend: ' + end + '\ntext: ' + text + '\nbefore: ' + before + '\nafter: ' + after)
    el.value = (before + earlyText + lateText + after)
    //move the cursor to the end of the 'earlyText'
    el.selectionStart = el.selectionEnd = start + earlyText.length
    el.focus()
}

function modalAction() {
    var textToDisplay = document.getElementById('textToDisplay').value;
    var urlToInsert = document.getElementById('urlToInsert').value;
    if (urlToInsert != '') {
        if (textToDisplay != '') {
            typeInTextarea(document.getElementById('body'), '[' + escapePars(textToDisplay) + ']', '(' + escapePars(
                urlToInsert) + ')');
        } else {
            typeInTextarea(document.getElementById('body'), escapePars(urlToInsert), '');
        }
        modalCloser();
        document.getElementById('textToDisplay').value = '';
        document.getElementById('urlToInsert').value = '';
        $("#errorAlert").html("").addClass('d-none');
    } else {
        $("#errorAlert").html("No URL Provided").removeClass('d-none').fadeIn(300).delay(1500).fadeOut(400);
    }
}

function escapePars(unsafe) {
    return unsafe
        .replace(/[\[]/g, "\\\[")
        .replace(/[\]]/g, "\\\]")
        .replace(/[\(]/g, "\\\(")
        .replace(/[\)]/g, "\\\)")
}

$(function() {
    $('.submit').on('click', function() {
        var file_data = $('.image').prop('files')[0];

        if (file_data != undefined) {
            var form_data = new FormData();
            form_data.append('file', file_data);
            $.ajax({
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();

                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            //document.getElementById('progress').innerHTML = percentComplete + '%';

                            document.getElementById("progress").style.width = percentComplete + '%';

                            if (percentComplete === 100) {
                                document.getElementById("progress").style.width = '0%';
                            }

                        }
                    }, false);
                    return xhr;
                },
                type: 'POST',
                url: 'includes/ajax-upload-receiver.php',
                contentType: false,
                processData: false,
                data: form_data,
                success: function(response) {
                    if (response == 'type') {
                        alert('Invalid file type');
                    } else if (response == 'exists') {
                        alert('File already exists');
                    } else {
                        $(".output").append("<p class='mb-0'>" + response + "</p>");
                    }

                    $('.image').val('');
                }
            });
        }
        return false;
    });
});
</script>