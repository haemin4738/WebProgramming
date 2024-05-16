<?php
session_start();
$comment_id = $_GET["comment_id"];
$board_num = $_GET["board_num"];
$page = $_GET["page"];

$con = mysqli_connect("localhost", "user1", "12345", "sample");

// 사용자의 댓글인지 확인
$sql = "SELECT user_id FROM comment WHERE id=$comment_id";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_array($result);
$comment_name = $row["user_id"];
// 현재 사용자의 아이디
$current_user = $_SESSION["userid"];

if ($comment_name == $current_user) {
  // 사용자의 댓글이면 삭제 진행
  $sql = "DELETE FROM comment WHERE id=$comment_id";
  mysqli_query($con, $sql);

  mysqli_close($con);

  // 댓글 삭제 후 게시글 상세 페이지로 이동
  echo "<script>
                location.href = 'board_view.php?num=$board_num&page=$page';
              </script>";
} else {
  // 사용자의 댓글이 아니면 삭제 권한이 없음
  mysqli_close($con);

  // 삭제 권한이 없는 경우 경고 메시지 표시 후 게시글 상세 페이지로 이동
  echo "<script>
                alert('댓글 삭제 권한이 없습니다.');
                location.href = 'board_view.php?num=$board_num&page=$page';
              </script>";
}
?>