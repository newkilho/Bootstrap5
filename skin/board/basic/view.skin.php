<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/custom.css">', 0);

$mb_info = get_member_info($view['mb_id'], $view['wr_name'], $view['wr_email'], $view['wr_homepage']);
?>

<div>

	<blockquote><h3><?php echo cut_str(get_text($view['wr_subject']), 70); ?></h3></blockquote>

	<div class="d-flex mb-2">
		<img class="view-icon rounded me-3" src="<?php echo $mb_info['img'] ?>">
		<div>
			<ul class="list-inline mb-0">
				<li class="list-inline-item">
					<a href="#" data-bs-toggle="dropdown" class="text-dark"><?php echo get_text($view['wr_name']); ?></a>
					<?php if ($is_ip_view) { ?>
					<small class="text-muted">(<?php echo $ip ?>)</small>
					<?php } ?>
					<?php echo $mb_info['menu'] ?>
				</li>
			</ul>
			<ul class="list-inline text-muted small pt-1">
				<li class="list-inline-item"><i class="fa fa-clock-o"></i> <?php echo $view['datetime2'] ?></li>
				<li class="list-inline-item"><i class="fa fa-eye"></i> <?php echo number_format($view['wr_hit']) ?> 회</li>
				<li class="list-inline-item"><i class="fa fa-commenting-o"></i> <?php echo number_format($view['wr_comment']) ?> 건</li>
			</ul>
		</div>
	</div>

	<div id="bo_v_con" class="mb-2">
		<?php
		// 파일 출력
		for ($i=0; $i<=count($view['file']); $i++)
			if ($view['file'][$i]['view'])
				echo '<img class="img-fluid d-block" src="'.$view['file'][$i]['path'].'/'.$view['file'][$i]['file'].'">';

		// 본문 내용
		echo str_replace('<img ', '<img class="img-fluid d-block" ', $view['content']);
		?>
	</div>

	<div class="mb-2">
		<?php if ($is_signature) { ?><p><?php echo $signature ?></p><?php } ?>
	</div>

	<?php if($board['bo_use_good'] || $board['bo_use_nogood']) { ?>
	<div class="mb-2 pt-5 text-center">
		<?php if($board['bo_use_good']) { ?>
		<a href="<?php echo $good_href?>" id="good_button" class="btn btn-outline-primary <?php if(!$good_href) echo 'disabled'; ?>"><i class="fa fa-thumbs-up"></i> <strong><?php echo number_format($view['wr_good']); ?></strong></a>
		<b id="bo_v_act_good"></b>
		<?php } ?>
		<?php if($board['bo_use_nogood']) { ?>
		<a href="<?php echo $nogood_href?>" id="nogood_button" class="btn btn-outline-secondary <?php if(!$nogood_href) echo 'disabled'; ?>"><i class="fa fa-thumbs-down"></i> <strong><?php echo number_format($view['wr_nogood']); ?></strong></a>
		<b id="bo_v_act_nogood"></b>
		<?php } ?>
	</div>
	<?php } ?>

	<ul class="list-group mb-4">
		<!-- 첨부파일 -->
		<?php
		$cnt = 0;
		if ($view['file']['count'])
			for ($i=0; $i<count($view['file']); $i++)
				if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) $cnt++;

		if($cnt)
		{
			// 가변 파일
			for ($i=0; $i<count($view['file']); $i++)
			{
				if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view'])
				{
		?>
		<li class="list-group-item">
			<small class="text-muted"><i class="fa fa-download"></i></small>
			<a href="<?php echo $view['file'][$i]['href'];  ?>" class="text-dark"><?php echo $view['file'][$i]['source'] ?></a>
			<?php echo $view['file'][$i]['content'] ?> <small class="text-muted">(<?php echo $view['file'][$i]['size'] ?>)</small>
			<!--<span class="badge badge-light badge-pill float-end"><?php echo $view['file'][$i]['download'] ?> 회</span>-->
			<small class="float-end text-muted"><?php echo $view['file'][$i]['download'] ?> 회</small>
		</li>
		<?php
				}
			}
		}
		?>

		<!-- 관련링크 -->
		<?php 
		if(isset($view['link'][1]) && $view['link'][1])
		{
			$cnt = 0;
			for ($i=1; $i<=count($view['link']); $i++)
			{
				if ($view['link'][$i])
				{
					$cnt++;
					$link = cut_str($view['link'][$i], 70);
		?>
		<li class="list-group-item">
			<small class="text-muted"><i class="fa fa-link"></i></small>
			<a href="<?php echo $view['link_href'][$i] ?>" class="text-dark" target="_blank"><?php echo $link ?></a>
			<small class="float-end text-muted"><?php echo $view['link_hit'][$i] ?> 회</small>
		</li>
		<?php
				}
			}
		} 
		?>
	</ul>

	<?php include_once(G5_THEME_PATH."/skin/sns/view.sns.skin.php"); ?>

	<div class="d-flex flex-sm-row flex-column justify-content-sm-between mb-4">
		<div class="d-flex justify-content-center mb-2 mb-sm-0">
			<?php if ($update_href || $delete_href || $copy_href || $move_href || $search_href) { ?>
			<div class="btn-group xs-100">
				<?php if ($update_href) { ?><a href="<?php echo $update_href ?>" class="btn btn-danger"><i class="fa fa-pencil-square-o"></i> 수정</a><?php } ?>
				<?php if ($delete_href) { ?><a href="<?php echo $delete_href ?>" onclick="del(this.href); return false;" class="btn btn-danger"><i class="fa fa-trash-o"></i> 삭제</a><?php } ?>
				<?php if ($copy_href) { ?><a href="<?php echo $copy_href ?>" onclick="board_move(this.href); return false;" class="btn btn-danger"><i class="fa fa-copy"></i> 복사</a><?php } ?>
				<?php if ($move_href) { ?><a href="<?php echo $move_href ?>" onclick="board_move(this.href); return false;" class="btn btn-danger"><i class="fa fa-arrows-alt"></i> 이동</a><?php } ?>
				<?php if ($search_href) { ?><a href="<?php echo $search_href ?>" class="btn btn-danger"><i class="fa fa-search"></i> 검색</a><?php } ?>
			</div>
			<?php } ?>
		</div>
		<div class="d-flex justify-content-center">
			<div class="btn-group xs-100">
				<a href="<?php echo $list_href ?>" class="btn btn-primary"><i class="fa fa-list"></i> 목록</a>
				<?php if ($scrap_href) { ?><a href="<?php echo $scrap_href;  ?>" target="_blank" class="btn btn-primary" onclick="win_scrap(this.href); return false;"><i class="fa fa-clipboard"></i> 스크랩</a><?php } ?>
				<?php if ($reply_href) { ?><a href="<?php echo $reply_href ?>" class="btn btn-primary"><i class="fa fa-reply"></i> 답변</a><?php } ?>
				<?php if ($write_href) { ?><a href="<?php echo $write_href ?>" class="btn btn-primary"><i class="fa fa-pencil"></i> 글쓰기</a><?php } ?>
			</div>
		</div>
	</div>

	<?php if ($prev_href || $next_href) { ?>
	<ul class="list-group mb-4">
		<?php if ($prev_href) { ?><li class="list-group-item"><small class="text-muted"><i class="fa fa-caret-up"></i><span class="d-none d-md-inline"> 이전글</span></small> <a href="<?php echo $prev_href ?>" class="text-dark"><?php echo $prev_wr_subject;?></a> <small class="float-end text-muted d-none d-md-inline"><?php echo str_replace('-', '.', substr($prev_wr_date, '2', '8')); ?></small></li><?php } ?>
		<?php if ($next_href) { ?><li class="list-group-item"><small class="text-muted"><i class="fa fa-caret-down"></i><span class="d-none d-md-inline"> 다음글</span></small> <a href="<?php echo $next_href ?>" class="text-dark"><?php echo $next_wr_subject;?></a> <small class="float-end text-muted d-none d-md-inline"><?php echo str_replace('-', '.', substr($next_wr_date, '2', '8')); ?></small></li><?php } ?>
	</ul>
	<?php } ?>

	<?php
	// 코멘트 입출력
	include_once(G5_BBS_PATH.'/view_comment.php');
	?>

</div>

<script>
<?php if ($board['bo_download_point'] < 0) { ?>
$(function() {
    $("a.view_file_download").click(function() {
        if(!g5_is_member) {
            alert("다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.");
            return false;
        }

        var msg = "파일을 다운로드 하시면 포인트가 차감(<?php echo number_format($board['bo_download_point']) ?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?";

        if(confirm(msg)) {
            var href = $(this).attr("href")+"&js=on";
            $(this).attr("href", href);

            return true;
        } else {
            return false;
        }
    });
});
<?php } ?>

function board_move(href)
{
    window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
}
</script>

<script>
$(function() {
    // 추천, 비추천
    $("#good_button, #nogood_button").click(function() {
        var $tx;
        if(this.id == "good_button")
            $tx = $("#bo_v_act_good");
        else
            $tx = $("#bo_v_act_nogood");

        excute_good(this.href, $(this), $tx);
        return false;
    });

    //sns공유
    $(".btn_share").click(function(){
        $("#bo_v_sns").fadeIn();
   
    });

    $(document).mouseup(function (e) {
        var container = $("#bo_v_sns");
        if (!container.is(e.target) && container.has(e.target).length === 0){
        container.css("display","none");
        }	
    });
});

function excute_good(href, $el, $tx)
{
    $.post(
        href,
        { js: "on" },
        function(data) {
            if(data.error) {
                alert(data.error);
                return false;
            }

            if(data.count) {
                $el.find("strong").text(number_format(String(data.count)));

				/*
                if($tx.attr("id").search("nogood") > -1) {
                    $tx.text("이 글을 비추천하셨습니다.");
                    $tx.fadeIn(200).delay(2500).fadeOut(200);
                } else {
                    $tx.text("이 글을 추천하셨습니다.");
                    $tx.fadeIn(200).delay(2500).fadeOut(200);
                }
				*/
            }
        }, "json"
    );
}
</script>