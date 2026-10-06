<?php
if(!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/custom.css">', 0);

$write_pages = isset($total_page) ? get_bs_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, get_pretty_url($bo_table, '', $qstr.'&amp;page=')) : '';

$block = array();
if ($member['mb_id'] && !empty($theme_config['enabled_block']))
{
	$sql = " select bl_send_mb_id from {$g5['member_table']}_block where bl_recv_mb_id = '".sql_real_escape_string($member['mb_id'])."' ";

	$rst = sql_query($sql, false);
	while ($row = sql_fetch_array($rst)) $block[$row['bl_send_mb_id']] = true;
}
?>

<div>

	<blockquote><h3><?php echo $board['bo_subject'] ?></h3></blockquote>

	<?php 
		if($is_category)
		{
			$category_href = get_pretty_url($bo_table);
	?>

	<ul class="nav nav-tabs mb-2">
		<li class="nav-item">
			<a class="nav-link <?php if($sca=='') echo 'active'; ?>" href="<?php echo $category_href ?>">전체</a>
		</li>
		<?php
			$categories = explode('|', $board['bo_category_list']);
			foreach($categories as $category)
			{
		?>
		<li class="nav-item">
			<a class="nav-link <?php if($category==$sca) echo 'active'; ?>" href="<?php echo get_pretty_url($bo_table,'','sca='.urlencode($category)); ?>"><?php echo $category ?></a>
		</li>
		<?php
			}
		?>
	</ul>
	<?php } ?>

	<form name="fboardlist" id="fboardlist" action="<?php echo G5_BBS_URL; ?>/board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
	<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
	<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
	<input type="hidden" name="stx" value="<?php echo $stx ?>">
	<input type="hidden" name="spt" value="<?php echo $spt ?>">
	<input type="hidden" name="sca" value="<?php echo $sca ?>">
	<input type="hidden" name="sst" value="<?php echo $sst ?>">
	<input type="hidden" name="sod" value="<?php echo $sod ?>">
	<input type="hidden" name="page" value="<?php echo $page ?>">
	<input type="hidden" name="sw" value="">

	<div class="row">
	<?php
		for ($i=0; $i<count($list); $i++) 
		{	
			$mb_info = get_member_info($list[$i]['mb_id'], $list[$i]['wr_name'], $list[$i]['wr_email'], $list[$i]['wr_homepage']);
			$thumb = get_list_thumbnail($board['bo_table'], $list[$i]['wr_id'], 320, 240, false, true);

			if(isset($block[$list[$i]['mb_id']])) $list[$i]['href'] = '';
	?>
		<div class="col-md-6 col-lg-4 mb-4">
			<?php if($list[$i]['href']) { ?>
			<div class="card">
				<div class="corner-card">
					<?php if($list[$i]['icon_new']) { ?>
					<div class="corner-ribbon shadow">새로운</div>
					<?php }elseif(isset($list[$i]['icon_hot']) && $list[$i]['icon_hot']){ ?>
					<div class="corner-ribbon shadow">인기</div>
					<?php } ?>
					<a href="<?php echo $list[$i]['href'] ?>" class="w-100"><img src="<?php echo $thumb['src'] ?>" class="card-img-top"></a>
				</div>
				<div class="card-body">
					<div class="card-title text-truncate">
						<?php if($is_checkbox) { ?>
						<input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>" class="form-check-input">
						<?php } ?>
						<?php if(isset($list[$i]['icon_secret']) && $list[$i]['icon_secret']) echo '<small class="text-secondary"><i class="fa fa-lock"></i></small>'; ?>
						<a href="<?php echo $list[$i]['href'] ?>" class="text-dark"><?php echo $list[$i]['subject'] ?></a>
					</div>
					<div class="d-flex justify-content-between">
						<small class="text-muted">
							<img class="list-icon rounded" src="<?php echo $mb_info['img'] ?>"> 
							<?php echo $mb_info['name'] ?>
						</small>
						<small class="text-muted text-end">
							<span class="d-inline d-sm-none"><i class="fa fa-clock-o"></i> <?php echo $list[$i]['datetime2'] ?></span>
							<span class=""><i class="fa fa-eye ps-1"></i> <?php echo number_format($list[$i]['wr_hit']) ?></span>
							<span class=""><i class="fa fa-commenting-o ps-1"></i> <?php echo number_format($list[$i]['wr_comment']) ?></span>
						</small>
					</div>
				</div>
			</div>
			<?php }else{ ?>
			<div class="card h-100">
				<div class="card-body d-flex justify-content-center align-items-center"">
				<span class="text-muted">차단된 회원이 작성한 글입니다.</span>
				</div>
			</div>
			<?php } ?>
		</div>
	<?php } ?>
	</div>

	<div class="d-flex justify-content-center justify-content-sm-end mb-4">
		<?php echo $write_pages;  ?>
	</div>

	<div class="d-flex flex-sm-row flex-column justify-content-sm-between mb-4">
		<div class="d-flex justify-content-center mb-2 mb-sm-0">
			<?php if($is_checkbox && ($is_admin == 'super' || $is_auth)) { ?>
			<div class="btn-group xs-100">
				<button type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-danger"><i class="fa fa-trash-o"></i> 삭제</button>
				<button type="submit" name="btn_submit" value="선택복사" onclick="document.pressed=this.value" class="btn btn-danger"><i class="fa fa-file"></i> 복사</button>
				<button type="submit" name="btn_submit" value="선택이동" onclick="document.pressed=this.value" class="btn btn-danger"><i class="fa fa-arrows-alt"></i> 이동</button>

				<?php if($admin_href) { ?>
				<a href="<?php echo $admin_href ?>" class="btn btn-danger"><i class="fa fa-cog" aria-hidden="true"></i> 관리자</a>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
		<div class="d-flex justify-content-center">
			<div class="btn-group xs-100">
				<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#search"><i class="fa fa-search"></i> 검색</button>
				<?php if($list_href) { ?><a href="<?php echo $list_href ?>" class="btn btn-primary"><i class="fa fa-list" aria-hidden="true"></i> 목록</a><?php } ?>
				<?php if($write_href) { ?><a href="<?php echo $write_href ?>" class="btn btn-primary"><i class="fa fa-pencil" aria-hidden="true"></i> 글쓰기</a><?php } ?>
			</div>
		</div>
	</div>

	</form>

	<!-- Search Modal -->
	<form name="fsearch" method="get">
	<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
	<input type="hidden" name="sca" value="<?php echo $sca ?>">
	<input type="hidden" name="sop" value="and">
	<div id="search" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"><i class="fa fa-search"></i> 검색어 입력</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="input-group">
						<div class="input-group-text bg-white">
							<select class="form-select bg-transparent border-0" name="sfl" id="sfl">
								<?php echo get_board_sfl_select_options($sfl); ?>
							</select>
						</div>
						<input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" required id="stx" class="form-control" size="25" maxlength="20" placeholder="검색어">
					</div>
				</div>
				<div class="modal-footer">
					<div>
						<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> 검색</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	</form>

</div>

<?php if($is_checkbox) { ?>
<noscript>
<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>
<?php } ?>

<!-- } 게시판 목록 끝 -->
