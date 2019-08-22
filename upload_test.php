<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>PHP File Upload</title>
</head>
<body>
  <?php
if (isset($_SESSION['message']) && $_SESSION['message']) {
	echo "<script> alert(\"" . $_SESSION['message'] . "\")</script>";
	unset($_SESSION['message']);
}
?>
  <form method="POST" action="api/api.php" enctype="multipart/form-data">
    <div>
      <span>Upload a File:</span>
      <input type="file" name="uploadedFile" />
    </div>

    <input type="submit" name="type" value="upload" />
  </form>
</body>
</html>
