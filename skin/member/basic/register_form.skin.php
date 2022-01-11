<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

add_javascript(G5_POSTCODE_JS, 0);
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/custom.css">');
?>
	
<script src="<?php echo G5_JS_URL ?>/jquery.register_form.js"></script>

<?php if($config['cf_cert_use'] && ($config['cf_cert_ipin'] || $config['cf_cert_hp'])) { ?>
<script src="<?php echo G5_JS_URL ?>/certify.js?v=<?php echo G5_JS_VER; ?>"></script>
<?php } ?>

<div class="form-join">
<form id="fregisterform" name="fregisterform" action="<?php echo $register_action_url ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">

	<input type="hidden" name="w" value="<?php echo $w ?>">
	<input type="hidden" name="url" value="<?php echo $urlencode ?>">
	<input type="hidden" name="agree" value="<?php echo $agree ?>">
	<input type="hidden" name="agree2" value="<?php echo $agree2 ?>">
	<input type="hidden" name="cert_type" value="<?php echo $member['mb_certify']; ?>">
	<input type="hidden" name="cert_no" value="">
	<?php if (isset($member['mb_sex'])) {  ?><input type="hidden" name="mb_sex" value="<?php echo $member['mb_sex'] ?>"><?php }  ?>
	<?php if (isset($member['mb_nick_date']) && $member['mb_nick_date'] > date("Y-m-d", G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400))) { // 닉네임수정일이 지나지 않았다면  ?>
	<input type="hidden" name="mb_nick_default" value="<?php echo get_text($member['mb_nick']) ?>">
	<input type="hidden" name="mb_nick" value="<?php echo get_text($member['mb_nick']) ?>">
	<?php }  ?>

	<div class="text-center mb-5">
		<a href="<?php echo G5_URL ?>"><img src="<?php echo G5_IMG_URL ?>/logo.png" alt="<?php echo $config['cf_title']; ?>" style="max-width:150px; width:100%; height:auto;"></a>
	</div>

	<div class="mb-4">
		<label for="reg_mb_id">아이디</label>
		<input type="text" name="mb_id" value="<?php echo $member['mb_id'] ?>" id="reg_mb_id" <?php echo $required ?> <?php echo $readonly ?> class="form-control <?php echo $readonly ?>" minlength="3" maxlength="20" placeholder="아이디">
		<span style="font-size: 0.8rem;" class="text-muted">영문자, 숫자, _ 만 입력 가능. 최소 3자이상</span>
	</div>

	<div class="mb-2">
		<label for="reg_mb_password">비밀번호</label>
		<input type="password" name="mb_password" id="reg_mb_password" <?php echo $required ?> class="form-control" minlength="3" maxlength="20" placeholder="비밀번호">
	</div>

	<div class="mb-4">
		<label for="reg_mb_password_re">비밀번호 확인</label>
		<input type="password" name="mb_password_re" id="reg_mb_password_re" <?php echo $required ?> class="form-control" minlength="3" maxlength="20" placeholder="비밀번호 확인">
	</div>


	<div class="mb-4">
		<label for="reg_mb_name">이름</label>
		<input type="text" id="reg_mb_name" name="mb_name" value="<?php echo get_text($member['mb_name']) ?>" <?php echo $required ?> <?php echo $readonly; ?> class="form-control <?php echo $readonly ?>" placeholder="이름">

		<?php
		if($config['cf_cert_use']) {
		?>
		<div class="mt-1">
		<?php
			if($config['cf_cert_ipin'])
				echo '<button type="button" id="win_ipin_cert" class="btn btn-sm btn-info">아이핀 본인확인</button>'.PHP_EOL;
			if($config['cf_cert_hp'])
				echo '<button type="button" id="win_hp_cert" class="btn btn-sm btn-info">휴대폰 본인확인</button>'.PHP_EOL;

			echo '<noscript>본인확인을 위해서는 자바스크립트 사용이 가능해야합니다.</noscript>'.PHP_EOL;
		?>
		</div>
		<?php 
		} 

		if ($config['cf_cert_use'] && $member['mb_certify']) {
			if($member['mb_certify'] == 'ipin')
				$mb_cert = '아이핀';
			else
				$mb_cert = '휴대폰';
		?>

		<div id="msg_certify">
			<strong><?php echo $mb_cert; ?> 본인확인</strong><?php if ($member['mb_adult']) { ?> 및 <strong>성인인증</strong><?php } ?> 완료
		</div>
		<?php } ?>
	</div>

	<div class="mb-4">
		<?php if ($req_nick) {  ?>
		<label for="reg_mb_nick">닉네임</label>
			<input type="hidden" name="mb_nick_default" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?>">
			<input type="text" name="mb_nick" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?>" id="reg_mb_nick" required class="form-control nospace" maxlength="20" placeholder="닉네임">
			<span id="msg_mb_nick"></span>
			<span style="font-size: 0.8rem;" class="text-muted">공백없이 한글,영문,숫자만 입력 가능<br>닉네임 변경시 <?php echo (int)$config['cf_nick_modify'] ?>일 간 변경할 수 없습니다.</span>
		<?php }  ?>
	</div>

	<div class="mb-4">
		<label for="reg_mb_email">이메일</label>
		
		<?php if ($config['cf_use_email_certify']) {  ?>
		<span style="font-size: 0.8rem;" class="text-muted">
			<?php if ($w=='') { echo "E-mail 로 발송된 내용을 확인한 후 인증하셔야 회원가입이 완료됩니다."; }  ?>
			<?php if ($w=='u') { echo "E-mail 주소를 변경하시면 다시 인증하셔야 합니다."; }  ?>
		</span>
		<?php }  ?>
		<input type="hidden" name="old_email" value="<?php echo $member['mb_email'] ?>">
		<input type="text" name="mb_email" value="<?php echo isset($member['mb_email'])?$member['mb_email']:''; ?>" id="reg_mb_email" required class="form-control email" maxlength="100" placeholder="이메일">
	</div>

	<?php if ($config['cf_use_homepage']) {  ?>
	<div class="mb-4">
		<label for="reg_mb_homepage">홈페이지</label>
		<input type="text" name="mb_homepage" value="<?php echo get_text($member['mb_homepage']) ?>" id="reg_mb_homepage" <?php echo $config['cf_req_homepage']?"required":""; ?> class="form-control" maxlength="255" placeholder="홈페이지">
	</div>
	<?php }  ?>

	<?php if ($config['cf_use_tel']) {  ?>
	<div class="mb-4">
		<label for="reg_mb_tel">전화번호</label>
		<input type="text" name="mb_tel" value="<?php echo get_text($member['mb_tel']) ?>" id="reg_mb_tel" <?php echo $config['cf_req_tel']?"required":""; ?> class="form-control" maxlength="20" placeholder="전화번호">
	</div>
	<?php }  ?>

	<?php if ($config['cf_use_hp'] || $config['cf_cert_hp']) {  ?>
	<div class="mb-4">
		<label for="reg_mb_hp">휴대폰번호</label>
		
		<input type="text" name="mb_hp" value="<?php echo get_text($member['mb_hp']) ?>" id="reg_mb_hp" <?php echo ($config['cf_req_hp'])?"required":""; ?> class="form-control" maxlength="20" placeholder="휴대폰번호">
		<?php if ($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
		<input type="hidden" name="old_mb_hp" value="<?php echo get_text($member['mb_hp']) ?>">
		<?php } ?>
	</div>
	<?php }  ?>

	<?php if ($config['cf_use_addr']) { ?>
	<div class="mb-2">
		<label for="reg_mb_zip">주소</label>
		<div class="input-group">
			<button type="button" class="btn btn-outline-secondary" onclick="win_zip('fregisterform', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소검색</button>
			<input type="text" name="mb_zip" value="<?php echo $member['mb_zip1'].$member['mb_zip2']; ?>" id="reg_mb_zip" <?php echo $config['cf_req_addr']?"required":""; ?> class="form-control" maxlength="6"  placeholder="우편번호">
		</div>
	</div>
	<div class="mb-2">
		<input type="text" name="mb_addr1" value="<?php echo get_text($member['mb_addr1']) ?>" id="reg_mb_addr1" <?php echo $config['cf_req_addr']?"required":""; ?> class="form-control" placeholder="기본주소">
	</div>
	<div class="mb-2">
		<input type="text" name="mb_addr2" value="<?php echo get_text($member['mb_addr2']) ?>" id="reg_mb_addr2" class="form-control" placeholder="상세주소">
	</div>
	<div class="mb-2">
		<input type="text" name="mb_addr3" value="<?php echo get_text($member['mb_addr3']) ?>" id="reg_mb_addr3" class="form-control" readonly="readonly"  placeholder="참고항목">
	</div>
	<div class="mb-4">
		<input type="hidden" name="mb_addr_jibeon" value="<?php echo get_text($member['mb_addr_jibeon']); ?>">
	</div>
	<?php }  ?>

	<?php if ($config['cf_use_signature']) {  ?>
	<div class="mb-4">
		<label for="reg_mb_signature">서명</label>
		<textarea name="mb_signature" id="reg_mb_signature" <?php echo $config['cf_req_signature']?"required":""; ?> class="form-control"   placeholder="서명"><?php echo $member['mb_signature'] ?></textarea>
	</div>
	<?php }  ?>

	<?php if ($config['cf_use_profile']) {  ?>
	<div class="mb-4">
		<label for="reg_mb_profile">자기소개</label>
		<textarea name="mb_profile" id="reg_mb_profile" <?php echo $config['cf_req_profile']?"required":""; ?> class="form-control" placeholder="자기소개"><?php echo $member['mb_profile'] ?></textarea>
	</div>
	<?php }  ?>

	<?php if ($member['mb_level'] >= $config['cf_icon_level'] && $config['cf_member_img_size'] && $config['cf_member_img_width'] && $config['cf_member_img_height']) {  ?>
	<div class="mb-4">
		<label for="reg_mb_img">회원이미지</label>
		<input type="file" class="form-control" name="mb_img" id="reg_mb_img">
						
		<span style="font-size: 0.8rem;" class="text-muted">
			<!--이미지 크기는 가로 <?php echo $config['cf_member_img_width'] ?>픽셀, 세로 <?php echo $config['cf_member_img_height'] ?>픽셀 이하<br>-->
			gif, jpg, png파일만 가능, 용량은 <?php echo number_format($config['cf_member_img_size']) ?>바이트 이하
		</span>

		<?php if ($w == 'u' && file_exists($mb_img_path)) {  ?>
		<div class="mt-2">
			<img src="<?php echo $mb_img_url ?>" alt="회원이미지">
			<div class="form-check">
				<input type="checkbox" name="del_mb_img" value="1" id="del_mb_img" class="form-check-input">
				<label class="form-check-label" for="del_mb_img">삭제</label>
			</div>
		</div>
		<?php }  ?>
	
	</div>
	<?php } ?>

	<div class="mb-4">
		<div class="form-check mb-2">
			<input type="checkbox" name="mb_mailling" value="1" id="reg_mb_mailling" class="form-check-input" <?php echo ($w=='' || $member['mb_mailling'])?'checked':''; ?>>
			<label class="form-check-label" for="reg_mb_mailling">정보 메일을 받겠습니다.</label>
		</div>

		<?php if ($config['cf_use_hp']) {  ?>
		<div class="form-check mb-2">
			<input type="checkbox" name="mb_sms" value="1" id="reg_mb_sms" <?php echo ($w=='' || $member['mb_sms'])?'checked':''; ?>>
			<label class="form-check-label" for="reg_mb_sms">휴대폰 문자메세지를 받겠습니다.</label>
		</div>
		<?php }  ?>

		<?php if (isset($member['mb_open_date']) && $member['mb_open_date'] <= date("Y-m-d", G5_SERVER_TIME - ($config['cf_open_modify'] * 86400)) || empty($member['mb_open_date'])) { // 정보공개 수정일이 지났다면 수정가능  ?>
		<div>
			<input type="hidden" name="mb_open_default" value="<?php echo $member['mb_open'] ?>">
			<div class="form-check">
				<input type="checkbox" name="mb_open" value="1" class="form-check-input" <?php echo ($w=='' || $member['mb_open'])?'checked':''; ?> id="reg_mb_open">
				<label class="form-check-label" for="reg_mb_open">나의 정보를 공개합니다.</label>
			</div>
			<?php if($config['cf_open_modify']>0) { ?>
			<span style="font-size: 0.8rem;" class="text-muted">정보공개는 <?php echo (int)$config['cf_open_modify'] ?>일 이내 변경불가</span>
			<?php } ?>
		</div>
		<?php } else {  ?>
		<div>
			<input type="hidden" name="mb_open" value="<?php echo $member['mb_open'] ?>">
			<span style="font-size: 0.8rem;" class="text-danger">
				정보공개는 수정후 <?php echo (int)$config['cf_open_modify'] ?>일 이내, <?php echo date("Y년 m월 j일", isset($member['mb_open_date']) ? strtotime("{$member['mb_open_date']} 00:00:00")+$config['cf_open_modify']*86400:G5_SERVER_TIME+$config['cf_open_modify']*86400); ?> 까지는 변경이 안됩니다.<br>
				이렇게 하는 이유는 잦은 정보공개 수정으로 인하여 쪽지를 보낸 후 받지 않는 경우를 막기 위해서 입니다.
			</span>
		</div>
		<?php }  ?>
	</div>

	<?php
	//회원정보 수정인 경우 소셜 계정 출력
	if( $w == 'u' && function_exists('social_member_provider_manage') ){
		social_member_provider_manage();
	}
	?>

	<?php if ($w == "" && $config['cf_use_recommend']) {  ?>
	<div class="mb-4">
		<label for="reg_mb_recommend">추천인아이디</label>
		<input class="form-control" type="text" name="mb_recommend" id="reg_mb_recommend" placeholder="추천인아이디">
	</div>
	<?php }  ?>

	<div class="is_captcha_use">
		<div class="mb-4">
		<label>자동등록방지</label>
			<div>
				<?php echo captcha_html(); ?>
			</div>
		</div>
	</div>

	<div class="text-center mb-4">
		<input type="submit" value="<?php echo $w==''?'회원가입':'정보수정'; ?>" id="btn_submit" class="btn btn-primary w-100" accesskey="s">
	</div>

</form>
</div>

<script>
$(function() {
	$("#reg_zip_find").css("display", "inline-block");

	<?php if($config['cf_cert_use'] && $config['cf_cert_ipin']) { ?>
	// 아이핀인증
	$("#win_ipin_cert").click(function() {
		if(!cert_confirm())
			return false;

		var url = "<?php echo G5_OKNAME_URL; ?>/ipin1.php";
		certify_win_open('kcb-ipin', url);
		return;
	});

	<?php } ?>
	<?php if($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
	// 휴대폰인증
	$("#win_hp_cert").click(function() {
		if(!cert_confirm())
			return false;

		<?php
		switch($config['cf_cert_hp']) {
			case 'kcb':
				$cert_url = G5_OKNAME_URL.'/hpcert1.php';
				$cert_type = 'kcb-hp';
				break;
			case 'kcp':
				$cert_url = G5_KCPCERT_URL.'/kcpcert_form.php';
				$cert_type = 'kcp-hp';
				break;
			case 'lg':
				$cert_url = G5_LGXPAY_URL.'/AuthOnlyReq.php';
				$cert_type = 'lg-hp';
				break;
			default:
				echo 'alert("기본환경설정에서 휴대폰 본인확인 설정을 해주십시오");';
				echo 'return false;';
				break;
		}
		?>

		certify_win_open("<?php echo $cert_type; ?>", "<?php echo $cert_url; ?>");
		return;
	});
	<?php } ?>
});

// submit 최종 폼체크
function fregisterform_submit(f)
{
	// 회원아이디 검사
	if (f.w.value == "") {
		var msg = reg_mb_id_check();
		if (msg) {
			alert(msg);
			f.mb_id.select();
			return false;
		}
	}

	if (f.w.value == "") {
		if (f.mb_password.value.length < 3) {
			alert("비밀번호를 3글자 이상 입력하십시오.");
			f.mb_password.focus();
			return false;
		}
	}

	if (f.mb_password.value != f.mb_password_re.value) {
		alert("비밀번호가 같지 않습니다.");
		f.mb_password_re.focus();
		return false;
	}

	if (f.mb_password.value.length > 0) {
		if (f.mb_password_re.value.length < 3) {
			alert("비밀번호를 3글자 이상 입력하십시오.");
			f.mb_password_re.focus();
			return false;
		}
	}

	// 이름 검사
	if (f.w.value=="") {
		if (f.mb_name.value.length < 1) {
			alert("이름을 입력하십시오.");
			f.mb_name.focus();
			return false;
		}

		/*
		var pattern = /([^가-힣\x20])/i;
		if (pattern.test(f.mb_name.value)) {
			alert("이름은 한글로 입력하십시오.");
			f.mb_name.select();
			return false;
		}
		*/
	}

	<?php if($w == '' && $config['cf_cert_use'] && $config['cf_cert_req']) { ?>
	// 본인확인 체크
	if(f.cert_no.value=="") {
		alert("회원가입을 위해서는 본인확인을 해주셔야 합니다.");
		return false;
	}
	<?php } ?>

	// 닉네임 검사
	if ((f.w.value == "") || (f.w.value == "u" && f.mb_nick.defaultValue != f.mb_nick.value)) {
		var msg = reg_mb_nick_check();
		if (msg) {
			alert(msg);
			f.reg_mb_nick.select();
			return false;
		}
	}

	// E-mail 검사
	if ((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
		var msg = reg_mb_email_check();
		if (msg) {
			alert(msg);
			f.reg_mb_email.select();
			return false;
		}
	}

	<?php if (($config['cf_use_hp'] || $config['cf_cert_hp']) && $config['cf_req_hp']) {  ?>
	// 휴대폰번호 체크
	var msg = reg_mb_hp_check();
	if (msg) {
		alert(msg);
		f.reg_mb_hp.select();
		return false;
	}
	<?php } ?>

	if (typeof f.mb_icon != "undefined") {
		if (f.mb_icon.value) {
			if (!f.mb_icon.value.toLowerCase().match(/.(gif|jpe?g|png)$/i)) {
				alert("회원아이콘이 이미지 파일이 아닙니다.");
				f.mb_icon.focus();
				return false;
			}
		}
	}

	if (typeof f.mb_img != "undefined") {
		if (f.mb_img.value) {
			if (!f.mb_img.value.toLowerCase().match(/.(gif|jpe?g|png)$/i)) {
				alert("회원이미지가 이미지 파일이 아닙니다.");
				f.mb_img.focus();
				return false;
			}
		}
	}

	if (typeof(f.mb_recommend) != "undefined" && f.mb_recommend.value) {
		if (f.mb_id.value == f.mb_recommend.value) {
			alert("본인을 추천할 수 없습니다.");
			f.mb_recommend.focus();
			return false;
		}

		var msg = reg_mb_recommend_check();
		if (msg) {
			alert(msg);
			f.mb_recommend.select();
			return false;
		}
	}

	<?php echo chk_captcha_js();  ?>

	document.getElementById("btn_submit").disabled = "disabled";

	return true;
}

$(function(){
	//tooltip
    $(".tooltip_icon").click(function(){
        $(this).next(".tooltip").fadeIn(400);
    }).mouseout(function(){
        $(this).next(".tooltip").fadeOut();
    });
});
</script>