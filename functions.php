<?php
function is_active_menu($item)
{
	global $g5;

	$part = parse_url($item['me_link']);
	$item['path'] = (isset($part['path']) ? $part['path'] : '').'/';

	$part = parse_url($_SERVER['REQUEST_URI']);
	$self['path'] = (isset($part['path']) ? $part['path'] : '').'/';

	if(isset($g5['me_code']))
	{
		if($item['me_code'] == $g5['me_code']) return true;
	}else{
		if(!in_array($item['path'], array('', '/')) && strncmp($item['path'], $self['path'], strlen($item['path']))===0) return true;
	}

	return false;
}

function get_layout_menu($menu)
{
	global $g5;

	$html = '';

	foreach($menu as $item)
	{
		$item['html'] = '';
		$item['active'] = is_active_menu($item) ? 'active' : '';

		if(isset($item['sub']) && $item['sub'])
		{
			$item['html'] .= '<div class="dropdown-menu">';

			foreach($item['sub'] as $href)
			{
				//$href['active'] = is_active_menu($href) ? 'active' : '';
				if(is_active_menu($href))
				{
					$href['active'] = $item['active'] = 'active';

				}else{
					$href['active'] = '';
				}

				if($href['me_id']==-1)
					$item['html'] .= '<div class="dropdown-divider"></div>';
				else
					$item['html'] .= '<a href="'.$href['me_link'].'" target="_'.$href['me_target'].'" class="dropdown-item '.$href['active'].'">'.$href['me_name'].'</a>';
			}

			$item['html'] .= '</div>';
		}

		$html .= sprintf('<li class="nav-item %5$s"><a href="%1$s" target="_%2$s" class="nav-link %4$s %6$s" %7$s>%3$s</a>%8$s</li>', 
			$item['me_link'], 
			$item['me_target'], 
			$item['me_name'],
			$item['active'],
			$item['html'] ? 'dropdown' : '',
			$item['html'] ? 'dropdown-toggle' : '', 
			$item['html'] ? 'data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : '',
			$item['html']
		);
	}

	return $html;
}

function get_layout_breadcrumb($menu, $recursive=false)
{
	global $g5;

	$output = '';
	foreach($menu as $item)
	{
		if($item['me_code'] == substr($g5['me_code'], 0, strlen($item['me_code'])))
			if($item['me_code'] != $g5['me_code'])
				$output .= '<li class="breadcrumb-item"><a href="'.$item['me_link'].'">'.$item['me_name'].'</a></li>';
			else
				$output .= '<li class="breadcrumb-item active">'.$item['me_name'].'</li>';

		if($item['sub']) $output .= get_layout_breadcrumb($item['sub'], true);
	}

	if(!$recursive) $output = '<li class="breadcrumb-item"><a href="'.G5_URL.'">Home</a></li>'.$output;

	return $output;
}

function get_member_info($mb_id, $name='', $email='', $homepage='')
{
    global $config;
    global $g5;
    global $bo_table, $sca, $is_admin, $member;

    $email_enc = new str_encrypt();
    $email = $email_enc->encrypt($email);
    $homepage = set_http(clean_xss_tags($homepage));

    $name     = get_text($name, 0, true);
    $email    = get_text($email);
    $homepage = get_text($homepage);

	$menu = '';

	$mb_ico_url = G5_IMG_URL.'/no_profile.gif';
	$mb_img_url = G5_IMG_URL.'/no_profile.gif';

    if ($mb_id)
	{
		$mb_icon_img = $mb_id.'.gif';

		if(file_exists(G5_DATA_PATH.'/member/'.substr($mb_id,0,2).'/'.$mb_icon_img))
			$mb_ico_url = G5_DATA_URL.'/member/'.substr($mb_id,0,2).'/'.$mb_icon_img;

		if(file_exists(G5_DATA_PATH.'/member_image/'.substr($mb_id,0,2).'/'.$mb_icon_img))
			$mb_img_url = G5_DATA_URL.'/member_image/'.substr($mb_id,0,2).'/'.$mb_icon_img;
	} else {
		if(!$bo_table)
		  return array('ico'=>$mb_ico_url, 'img'=>$mb_img_url, 'menu'=>'');

		$menu .= '<a href="'.G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&amp;sca='.$sca.'&amp;sfl=wr_name,1&amp;stx='.$name.'" title="'.$name.' 이름으로 검색" class="dropdown-item" rel="nofollow" onclick="return false;">'.$name.'</a>';
	}

	$menu = '<div class="dropdown-menu">';

    if($mb_id)
        $menu .= '<a href="'.G5_BBS_URL.'/memo_form.php?me_recv_mb_id='.$mb_id.'" class="dropdown-item" onclick="win_memo(this.href); return false;">쪽지보내기</a>';
    if($email)
        $menu .= '<a href="'.G5_BBS_URL.'/formmail.php?mb_id='.$mb_id.'&name='.urlencode($name).'&email='.$email.'" class="dropdown-item"  onclick="win_email(this.href); return false;">메일보내기</a>';
    if($homepage)
        $menu .= '<a href="'.$homepage.'" class="dropdown-item" target="_blank">홈페이지</a>';
    if($mb_id)
        $menu .= '<a href="'.G5_BBS_URL.'/profile.php?mb_id='.$mb_id.'" onclick="win_profile(this.href); return false;" class="dropdown-item" >자기소개</a>';
    if($bo_table) {
        if($mb_id)
            $menu .= '<a href="'.G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&sca='.$sca.'&sfl=mb_id,1&stx='.$mb_id.'" class="dropdown-item" >아이디로 검색</a>';
        else
            $menu .= '<a href="'.G5_BBS_URL.'/board.php?bo_table='.$bo_table."&sca=".$sca.'&sfl=wr_name,1&stx='.$name.'" class="dropdown-item" >이름으로 검색</a>';
    }
    if($mb_id)
        $menu .= '<a href="'.G5_BBS_URL.'/new.php?mb_id='.$mb_id.'" class="dropdown-item" onclick="check_goto_new(this.href, event);">전체게시물</a>';
    if($is_admin == "super" && $mb_id) {
        $menu .= '<a href="'.G5_ADMIN_URL.'/member_form.php?w=u&mb_id='.$mb_id.'" class="dropdown-item" target="_blank">회원정보변경</a>';
        $menu .= '<a href="'.G5_ADMIN_URL.'/point_list.php?sfl=mb_id&stx='.$mb_id.'" class="dropdown-item" target="_blank">포인트내역</a>';
    }

	$menu .= '</div>';

    return array('ico'=>$mb_ico_url, 'img'=>$mb_img_url, 'menu'=>$menu);
}

function chg_paging($write_pages)
{
	$remove = array();
	$remove[] = '<span class="sound_only">페이지';
	$remove[] = '<span class="pg">';
	$remove[] = '</span>';
	$remove[] = ' pg_start';
	$remove[] = ' pg_end';
	$remove[] = ' pg_next';
	$remove[] = ' pg_prev';

	$write_pages = str_replace('<nav class="pg_wrap">', '<nav><ul class="pagination">', $write_pages);
	$write_pages = str_replace('</nav>', '</ul></nav>', $write_pages);
	$write_pages = str_replace($remove, '', $write_pages);
	$write_pages = str_replace('pg_page', 'page-link', $write_pages);

	$write_pages = str_replace('<a href="', '<li class="page-item"><a href="', $write_pages);
	$write_pages = str_replace('</a>', '</a></li>', $write_pages);

	$write_pages = str_replace('<span class="sound_only">열린<strong class="pg_current">', '<li class="page-item active"><a href="#" class="page-link">', $write_pages);
	$write_pages = str_replace('</strong>', '</a></li>', $write_pages);


	$write_pages = str_replace('처음', '<i class="fa fa-angle-double-left"></i>', $write_pages);
	$write_pages = str_replace('이전', '<i class="fa fa-angle-left"></i>', $write_pages);
	$write_pages = str_replace('다음', '<i class="fa fa-angle-right"></i>', $write_pages);
	$write_pages = str_replace('맨끝', '<i class="fa fa-angle-double-right"></i>', $write_pages);

	return $write_pages;
}

function chg_board_list($str_board_list)
{
	$str_board_list = str_replace('<li>', '<li class="list-inline-item">', $str_board_list);
	$str_board_list = str_replace('<strong>', '', $str_board_list);
	$str_board_list = str_replace('</strong><span class="cnt_cmt">', ' <span class="badge badge-light">', $str_board_list);
	$str_board_list = str_replace(' class=sch_on>', ' class="btn btn-primary btn-sm active">', $str_board_list);
	$str_board_list = str_replace(' >', ' class="btn btn-primary btn-sm">', $str_board_list);

	return $str_board_list;
}