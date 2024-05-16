<!DOCTYPE html>
<html>
<head> 
<meta charset="utf-8">
<title>Momento</title>
<link rel="stylesheet" type="text/css" href="./css/common.css">
<link rel="stylesheet" type="text/css" href="./css/board.css">
<link rel="stylesheet" type="text/css" href="./css/comment.css">
<style>
    section {
        flex: 1;
        overflow-y: auto;
    }
    </style>
</head>
<body> 
<header>
    <?php include "header.php";?>
</header>  
<section>
    <div id="board_box">
        <h3 class="title">
            게시판 > 내용보기
        </h3>
        <?php
        $num  = $_GET["num"];
        $page  = $_GET["page"];

        $con = mysqli_connect("localhost", "user1", "12345", "sample");
        $sql = "select * from board where num=$num";
        $result = mysqli_query($con, $sql);

        $row = mysqli_fetch_array($result);
        $id      = $row["id"];
        $name      = $row["name"];
        $regist_day = $row["regist_day"];
        $subject    = $row["subject"];
        $content    = $row["content"];
        $file_name    = $row["file_name"];
        $file_type    = $row["file_type"];
        $file_copied  = $row["file_copied"];
        $hit          = $row["hit"];
        $recommend  = $row["recommend"];

        $content = str_replace(" ", "&nbsp;", $content);
        $content = str_replace("\n", "<br>", $content);

        $new_hit = $hit + 1;
        $sql = "update board set hit=$new_hit where num=$num";   
        mysqli_query($con, $sql);

        if(isset($_POST['recommend'])) {
            $new_recommend = $recommend + 1;
            $sql = "update board set recommend=$new_recommend where num=$num";   
            mysqli_query($con, $sql);
            $recommend = $new_recommend;
        }
        ?>      
        <ul id="view_content">
            <li>
                <span class="col1"><b>제목 :</b> <?=$subject?></span>
                <span class="col2"><?=$name?> | <?=$regist_day?></span>
                <ul id="recommend-text">추천 수 : <?=$recommend?></ul>
            <li>
                <?php
                    if($file_name) {
                        $file_path = "./data/".$file_copied;
                        $file_size = filesize($file_path);

                        echo "▷ 첨부파일 : $file_name ($file_size Byte) &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href='board_download.php?num=$num'>[저장]</a><br><br>";
                        if (strstr($file_type, "image")) { // 이미지 파일인 경우에만 사진을 보여줍니다.
                            echo "<img src='$file_path' width='500' alt='첨부이미지'><br><br>";
                        }
                    }
                ?>
                <?=$content?>
            </li>       
        </ul>
        <div id="comment_box">
                <h4>댓글 목록</h4>
                <?php
    if ($userid) {
        ?>
                <form action="comment_insert.php" method="post">
                    <input type="hidden" name="board_num" value="<?= $num ?>">
                    <input type="hidden" name="page" value="<?= $page ?>">
                    <textarea name="content" rows="4" cols="100" placeholder="댓글을 입력하세요"></textarea><br>
                    <button type="submit">작성</button>
                </form>
                <?php
    } else {
        ?>
        <p>댓글을 작성하려면 로그인이 필요합니다.</p>
        <?php
    }
    ?>
            </div>

            <!-- 댓글 리스트 -->
            <ul id="comment_list">
                <?php

                $sql = "SELECT * FROM comment WHERE board_num=$num ORDER BY id ASC";
                $result = mysqli_query($con, $sql);

                $loggedInUser = isset($_SESSION['userid']) ? $_SESSION['userid'] : ""; // 기본값으로 빈 문자열을 설정
                while ($row = mysqli_fetch_array($result)) {
                    $comment_id = $row['id'];
                    $comment_name = $row['name'];
                    $comment_content = $row['content'];
                    $comment_date = $row['date'];

                    echo "<li>";
                    echo "<div class='comment_content'>";
                    echo "<span class='col1'>$comment_name</span>";
                    echo "<span class='col2'>$comment_date</span><br>";
                    echo "<span class='col3'>$comment_content</span>";

                    // 사용자의 댓글인 경우에만 삭제 버튼 표시
                    if ($row['user_id'] == $loggedInUser || $_SESSION["userlevel"] == 1) {
                        echo "<button class='delete_button' onclick=\"deleteComment($comment_id, $num, $page)\">삭제</button>";
                    }

                    echo "</div>";
                    echo "</li>";
                }
                ?>
            </ul>

            <!-- 스크립트 추가 -->
            <script>
                function deleteComment(commentId, boardNum, page) {
                    if (confirm("댓글을 삭제하시겠습니까?")) {
                        location.href = "comment_delete.php?comment_id=" + commentId + "&board_num=" + boardNum + "&page=" + page;
                    }
                }
            </script>

            <ul class="buttons">
                <li><button onclick="location.href='board_list.php?page=<?= $page ?>'">목록</button></li>
                <li>
                    <?php
                    $loggedInUser = isset($_SESSION['userid']) ? $_SESSION['userid'] : ""; // 기본값으로 빈 문자열을 설정
                    if ($loggedInUser == $id) {
                        echo '<button onclick="location.href=\'board_modify_form.php?num=' . $num . '&page=' . $page . '\'">수정</button>';
                    }
                    ?>
                </li>
                <li>
                    <?php
                    $loggedInUser = isset($_SESSION['userid']) ? $_SESSION['userid'] : ""; // 기본값으로 빈 문자열을 설정
                    $loggedInUserLevel = isset($_SESSION['userlevel']) ? $_SESSION['userlevel'] : 2;
                    if ($loggedInUser == $id || $loggedInUserLevel == 1) {
                        echo '<button onclick="location.href=\'board_delete.php?num=' . $num . '&page=' . $page . '\'">삭제</button>';
                    }
                    ?>
                </li>
            <ul class="buttons">
                <?php
                if ($loggedInUser) { // Check if user is logged in
                    ?>
                    <li>
                        <form method="post">
                            <input type="hidden" name="recommend" value="true">
                            <button type="submit">추천</button>
                        </form>
                    </li>
                    <?php
                }
                ?>
            </ul>
    </div> <!-- board_box -->
</section> 
<footer>
    <?php include "footer.php";?>
</footer>
</body>
</html>
