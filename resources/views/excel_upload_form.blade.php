<form action="/upload" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <label for="check-in-spreadsheet">Choose file to upload</label>
    <input type="file" name="spreadsheet" />
    <input type="submit" value="Upload" />
</form>