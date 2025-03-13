<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV</title>
</head>
<body>
    <h2>Upload File CSV</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="csvFile" accept=".csv">
        <button type="submit">Upload</button>
    </form>
</body>
</html>