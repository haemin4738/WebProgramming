<!DOCTYPE html>
<html>
<head> 
<meta charset="utf-8">
<title>Momento</title>
<link rel="stylesheet" type="text/css" href="./css/common.css">
<link rel="stylesheet" type="text/css" href="./css/board2.css">
<style>
    section {
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
        <h3>
            오늘의 추천 사진 게시판
        </h3>
        <ul id="board_list">
            <li>
            <span class="col4">첨부</span>
                <span class="col2">제목</span>
                <span class="col3">글쓴이</span>
                <span class="col5">등록일</span>
                <span class="col6">조회</span>
                <span class="col7">추천 수</span>
            </li>
            <?php
            if (isset($_GET["page"]))
                $page = $_GET["page"];
            else
                $page = 1;

                $current_date = date("Y-m-d"); // 오늘 날짜를 가져옴

                $con = mysqli_connect("localhost", "user1", "12345", "sample");
                $sql = "SELECT * FROM board WHERE DATE(regist_day) = '$current_date' ORDER BY recommend DESC, num DESC LIMIT 10";
                $result = mysqli_query($con, $sql);
                $total_record = mysqli_num_rows($result); // 전체 글 수
    
                $scale = 10;
    
                // 전체 페이지 수($total_page) 계산 
                if ($total_record % $scale == 0)
                    $total_page = floor($total_record / $scale);
                else
                    $total_page = floor($total_record / $scale) + 1;
    
                // 표시할 페이지($page)에 따라 $start 계산  
                $start = ($page - 1) * $scale;
    
                $number = $total_record - $start;
    
                for ($i = $start; $i < $start + $scale && $i < $total_record; $i++) {
                    mysqli_data_seek($result, $i);
                    // 가져올 레코드로 위치(포인터) 이동
                    $row = mysqli_fetch_array($result);
                    // 하나의 레코드 가져오기
                    $num = $row["num"];
                    $id = $row["id"];
                    $name = $row["name"];
                    $subject = $row["subject"];
                    $regist_day = $row["regist_day"];
                    $hit = $row["hit"];
                    $recommend = $row["recommend"];
    
                    $file_image = "";
                    if ($row["file_name"]) {
                        $file_image = "<a href='board_view.php?num=$num&page=$page'><img src='./data/" . $row["file_copied"] . "' width='100' height='100'></a>";
                        $file_path = "./data/" . $row["file_copied"];
                        $file_name = $row["file_name"];
                        $file_type = $row["file_type"];
                        $file_size = filesize($file_path);
                        $file_datetime = date("YmdHis", strtotime($regist_day));
                        $file_download_link = "download.php?num=$num&file_name=$file_name&file_type=$file_type&file_datetime=$file_datetime";
                        $file_html = "<a href='$file_download_link'>$file_image</a>";
                    } else {
                        $file_html = "";
                    }
                    ?>
                    <li>
                        <span class="col4"><?= $file_html ?></span>
                        <span class="col2"><a href="board_view.php?num=<?= $num ?>&page=<?= $page ?>"><?= $subject ?></a></span>
                        <span class="col3"><?= $name ?></span>
                        <span class="col5"><?= $regist_day ?></span>
                        <span class="col6"><?= $hit ?></span>
                        <span class="col7"><?= $recommend ?></span>
                    </li>
                    <?php
                    $number--;
                }
                mysqli_close($con);
                ?>
            </ul>
            <ul id="page_num">    
                <?php
                if ($total_page >= 2 && $page >= 2) {
                    $new_page = $page - 1;
                    echo "<li><a href='board_list.php?page=$new_page&mode=$mode'>◀ 이전</a> </li>";
                } else
                    echo "<li>&nbsp;</li>";
    
                // 게시판 목록 하단에 페이지 링크 번호 출력
                for ($i = 1; $i <= $total_page; $i++) {
                    if ($page == $i)     // 현재 페이지 번호 링크 안함
                    {
                        echo "<li><b> $i </b></li>";
                    } else {
                        echo "<li><a href='board_list.php?page=$i&mode=$mode'> $i </a><li>";
                    }
                }
                if ($total_page >= 2 && $page != $total_page) {
                    $new_page = $page + 1;
                    echo "<li> <a href='board_list.php?page=$new_page&mode=$mode'>다음 ▶</a> </li>";
                } else
                echo "<li>&nbsp;</li>";
                ?>
            </ul> <!-- page -->            
        </div> <!-- board_box -->
    </section> 
    <footer>
        <?php include "footer.php";?>
    </footer>
    </body>
    </html>