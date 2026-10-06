<?php
include_once('./_common.php');
include_once(G5_THEME_PATH.'/functions.php');

header('Content-Type: application/json; charset=utf-8');

function kh_api_json($msg)
{
	die(json_encode(['msg' => $msg], JSON_UNESCAPED_UNICODE));
}

if (!$is_member) kh_api_json('로그인 후 이용해 주세요.');

$data = (array)json_decode(file_get_contents('php://input'), true) + ['order' => '', 'bo_table' => '', 'wr_id' => 0, 'mb_id' => '', 'token' => ''];

if (!$data['token'] || $data['token'] !== get_session('ss_theme_api_token')) kh_api_json('올바른 방법으로 이용해 주십시오.');

switch ($data['order']) {
	case 'report':
		if (empty($theme_config['enabled_report'])) kh_api_json('사용하지 않는 기능입니다.');

		$data['bo_table'] = preg_replace('/[^a-z0-9_]/i', '', $data['bo_table']);
		$data['wr_id'] = (int)$data['wr_id'];
		if (!$data['bo_table'] || !$data['wr_id'] || !get_board_db($data['bo_table'], true)) kh_api_json('게시물이 존재하지 않습니다.');

		$view = get_write($g5['write_prefix'].$data['bo_table'], $data['wr_id']);
		if (empty($view['wr_id'])) kh_api_json('게시물이 존재하지 않습니다.');

		sql_query(get_db_create_replace(" create table if not exists {$g5['board_table']}_report (
			si_id int(11) not null auto_increment,
			bo_table varchar(20) not null default '',
			wr_id int(11) not null default '0',
			mb_id varchar(20) not null default '',
			si_datetime datetime not null default '0000-00-00 00:00:00',
			primary key (si_id),
			key bo_table (bo_table, wr_id, mb_id)
		) ENGINE=MyISAM default CHARSET=utf8 "), false);

		if (sql_fetch(" select si_id from {$g5['board_table']}_report where bo_table = '{$data['bo_table']}' and wr_id = '{$data['wr_id']}' and mb_id = '".sql_real_escape_string($member['mb_id'])."' limit 1 "))
			kh_api_json('신고한 게시물입니다.');

		sql_query(" insert into {$g5['board_table']}_report (bo_table, wr_id, mb_id, si_datetime) values ('{$data['bo_table']}', '{$data['wr_id']}', '".sql_real_escape_string($member['mb_id'])."', now()) ");

		include_once(G5_LIB_PATH.'/mailer.lib.php');
		mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $config['cf_admin_email'], '게시글 신고 접수', "{$member['mb_name']}({$_SERVER['REMOTE_ADDR']})님이 ".G5_TIME_YMDHIS." 에 게시물을 신고하였습니다.\n제목: {$view['wr_subject']}\n링크:".get_pretty_url($data['bo_table'], $data['wr_id']), 2);

		kh_api_json("정상적으로 신고 하셨습니다.\n\n확인 후 조치하겠습니다.");

	case 'block':
		if (empty($theme_config['enabled_block'])) kh_api_json('사용하지 않는 기능입니다.');

		$data['mb_id'] = preg_replace('/[^a-z0-9_]/i', '', $data['mb_id']);
		if (!$data['mb_id'] || !get_member($data['mb_id'], 'mb_id')) kh_api_json('존재하지 않는 회원입니다.');
		if ($data['mb_id'] === $member['mb_id']) kh_api_json('자신을 차단할 수 없습니다.');
		if ($data['mb_id'] === $config['cf_admin']) kh_api_json('관리자를 차단할 수 없습니다.');

		sql_query(get_db_create_replace(" create table if not exists {$g5['member_table']}_block (
			bl_id int(11) not null auto_increment,
			bl_recv_mb_id varchar(20) not null default '',
			bl_send_mb_id varchar(20) not null default '',
			bl_datetime datetime not null default '0000-00-00 00:00:00',
			primary key (bl_id),
			key bl_recv_mb_id (bl_recv_mb_id)
		) engine=MyISAM default charset=utf8 "), false);

		if (sql_fetch(" select bl_id from {$g5['member_table']}_block where bl_recv_mb_id = '".sql_real_escape_string($member['mb_id'])."' and bl_send_mb_id = '{$data['mb_id']}' limit 1 "))
			kh_api_json('차단된 회원입니다.');

		sql_query(" insert into {$g5['member_table']}_block (bl_recv_mb_id, bl_send_mb_id, bl_datetime) values ('".sql_real_escape_string($member['mb_id'])."', '{$data['mb_id']}', now()) ");

		kh_api_json('해당 회원이 차단되었습니다.');
}

kh_api_json('잘못된 요청입니다.');
