<?php
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 테마 모양이 필요없는 팝업 등은 그누보드 원래 테마를 이용
switch($_SERVER['SCRIPT_NAME'])
{
	case '/bbs/login.php': 
	case '/bbs/password.php': 
	case '/bbs/member_confirm.php': 
		include_once(G5_THEME_PATH.'/head.def.php'); return;

	case '/bbs/board.php': 
	case '/bbs/write.php': return;
}

include_once(G5_THEME_PATH.'/functions.php');
include_once(G5_THEME_PATH.'/head.def.php');

// 팝업류는 그누보드 기본 스타일 유지
add_stylesheet('<link rel="stylesheet" href="'.run_replace('head_css_url', G5_THEME_CSS_URL.'/default'.(defined('_SHOP_') ? '_shop' : '').'.css?ver='.G5_CSS_VER, G5_THEME_URL).'">', 1);

if ($is_member) {
	$sr_admin_msg = '';
	if ($is_admin == 'super')      $sr_admin_msg = '최고관리자 ';
	else if ($is_admin == 'group') $sr_admin_msg = '그룹관리자 ';
	else if ($is_admin == 'board') $sr_admin_msg = '게시판관리자 ';

	echo '<div id="hd_login_msg">'.$sr_admin_msg.get_text($member['mb_nick']).'님 로그인 중 ';
	echo '<a href="'.G5_BBS_URL.'/logout.php">로그아웃</a></div>';
}
