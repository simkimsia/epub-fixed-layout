<?php


if ($_FILES['file']['error'] === UPLOAD_ERR_OK) { 
    require '../vendor/s3bootstrap.php';

    $pathToFile = $_FILES['file']['tmp_name'];
    $key = 'filename/1/' . $_FILES['file']['name'];

    // Upload an object by streaming the contents of a file
    // $pathToFile should be absolute path to a file on disk
    $result = $client->putObject(array(
        'Bucket'     => $bucket,
        'Key'        => $key,
        'SourceFile' => $pathToFile,
        'ACL'           => 'public-read',
    ));

    // We can poll the object until it is accessible
    $client->waitUntilObjectExists(array(
        'Bucket' => $bucket,
        'Key'    => $key
    ));

    // public read is https://bucketname.s3.amazonaws.com/path/to/file.png
    var_dump($result); // 
}