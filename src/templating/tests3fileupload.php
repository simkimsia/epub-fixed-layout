<html>
<head>
    <title>Test S3 File Upload</title>
</head>
<body>

<p>We can upload file if the file already exists in file directory.</p>

<p>Now we try to do it when user uploads a file</p>


    <form action="s3process.php" method="post" enctype="multipart/form-data">
        <input type="file" name="file" />
        <input type="submit" />
    </form>
</body>
</html>