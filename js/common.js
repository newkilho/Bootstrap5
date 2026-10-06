$(function() {
	$('.block').on('click', function() {
		if (confirm('이 회원을 차단 하시겠습니까?'))
		{
			$.ajax({
				url: g5_theme_api_url,
				type: 'post',
				contentType: 'application/json',
				data: JSON.stringify({'order': 'block', 'mb_id':$(this).data('id'), 'token':g5_theme_api_token}),
				dataType: 'json',
				success: function(data) {
					alert(data.msg);
				},
				error: function() {
					alert('오류가 발생하였습니다.\n\n잠시 후 다시 시도해 주세요.');
				}
			});
		}

		return false;
	});

	$('.report').on('click', function() {
		if (confirm('이 게시물을 신고 하시겠습니까?\n\n신고는 취소가 불가합니다.\n\n주의) 허위 신고시 신고자의 서비스 이용이 제한됩니다.'))
		{
			$.ajax({
				url: g5_theme_api_url,
				type: 'post',
				contentType: 'application/json',
				data: JSON.stringify({'order': 'report', 'bo_table':g5_bo_table, 'wr_id':$(this).data('id'), 'token':g5_theme_api_token}),
				dataType: 'json',
				success: function(data) {
					alert(data.msg);
				},
				error: function() {
					alert('오류가 발생하였습니다.\n\n잠시 후 다시 시도해 주세요.');
				}
			});
		}

		return false;
	});
});

// 게시판 목록 선택 복사/이동/삭제
function all_checked(sw) {
    var f = document.fboardlist;

    for (var i=0; i<f.length; i++) {
        if(f.elements[i].name == "chk_wr_id[]")
            f.elements[i].checked = sw;
    }
}

function fboardlist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if(f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if(!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택복사") {
        select_copy("copy");
        return;
    }

    if(document.pressed == "선택이동") {
        select_copy("move");
        return;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
            return false;

        f.removeAttribute("target");
        f.action = g5_bbs_url+"/board_list_update.php";
    }

    return true;
}

// 선택한 게시물 복사 및 이동
function select_copy(sw) {
    var f = document.fboardlist;

    if(sw == "copy")
        str = "복사";
    else
        str = "이동";

    var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

    f.sw.value = sw;
    f.target = "move";
    f.action = g5_bbs_url+"/move.php";
    f.submit();
}
