<?php
session_start();
$board_num = $_POST["board_num"];
$page = $_POST["page"];
$user_id = $_SESSION["userid"]; // 현재 로그인한 사용자의 아이디
$name = $_SESSION["username"];
$content = $_POST["content"];
$date = date("Y-m-d H:i:s");

$con = mysqli_connect("localhost", "user1", "12345", "sample");
$sql = "INSERT INTO comment (board_num, user_id, name, content, date) VALUES ('$board_num', '$user_id', '$name', '$content', '$date')";
$result = mysqli_query($con, $sql);

if ($result) {
  mysqli_close($con);
  echo "
    <script>
      location.href = 'board_view.php?num=$board_num&page=$page';
    </script>
  ";
} else {
  echo "댓글을 작성할 수 없습니다.";
}
?>