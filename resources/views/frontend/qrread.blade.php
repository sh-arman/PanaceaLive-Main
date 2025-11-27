<html>
<head>
    <title>Yoooo!</title>

</head>

<body>
<form action="{{ url('qrsubmit') }}" method="post" enctype="multipart/form-data">
    <input name="fileToUpload" type="file"
           value="">
    <button name="submit" type="submit">Submit</button>
    {!! csrf_field() !!}
</form>
</body>


</html>