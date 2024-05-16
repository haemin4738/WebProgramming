<?php
$con = mysqli_connect("localhost", "user1", "12345", "sample");

if (!$con) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

$num = $_GET["num"];

$sql = "SELECT * FROM board WHERE num = $num";
$result = mysqli_query($con, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    $file_name = $row["file_name"];
    $file_copied = $row["file_copied"];
    $file_type = $row["file_type"];
    $file_path = "./data/" . $file_copied;

    // 파일이 존재하고 읽을 수 있는 경우에만 다운로드
    if (file_exists($file_path) && is_readable($file_path)) {
        header("Pragma: public");
        header("Expires: 0");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$file_name\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . filesize($file_path));

        readfile($file_path);
        exit;
    } else {
        die("File does not exist or is not readable.");
    }
} else {
    die("Invalid file.");
}
?>
