<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/custom.css">');
?>

<div class="form-password">
    <form name="fmemberconfirm" action="<?php echo $url ?>" method="post">
    <input type="hidden" name="mb_id" value="<?php echo $member['mb_id'] ?>">
    <input type="hidden" name="w" value="u">

	<div class="text-center mb-5">
		<a href="<?php echo G5_URL ?>"><img src="<?php echo G5_IMG_URL ?>/logo.png" alt="<?php echo $config['cf_title']; ?>" class="logo"></a>
	</div>

	<?php
		if ($url == 'member_leave.php')
			$content = '비밀번호를 입력하시면 회원탈퇴가 완료됩니다.';
		else
			$content = '회원님의 정보를 안전하게 보호하기 위해 비밀번호를 한번 더 확인합니다.';
	?>	
	<div class="alert alert-danger mb-4">
		<h4 class="alert-heading"><?php echo $g5['title'] ?></h4>
		<p class="mb-0"><?php echo $content ?></p>
	</div>

	<div class="input-group mb-4">
		<input type="password" name="mb_password" class="form-control frm_input required" maxLength="20" placeholder="비밀번호" required>
		<button class="btn btn-primary" type="submit">확인</button>
	</div>

	</form>
</div>