<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (!$board['bo_use_sns']) return;

$sns_msg = urlencode(str_replace('\"', '"', $view['subject']));
//$sns_url = googl_short_url('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
//$msg_url = $sns_msg.' : '.$sns_url;

/*
$facebook_url  = 'http://www.facebook.com/sharer/sharer.php?s=100&p[url]='.$sns_url.'&p[title]='.$sns_msg;
$twitter_url   = 'http://twitter.com/home?status='.$msg_url;
$gplus_url     = 'https://plus.google.com/share?url='.$sns_url;
*/

$sns_send  = G5_BBS_URL.'/sns_send.php?longurl='.urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
//$sns_send .= '&amp;title='.urlencode(utf8_strcut(get_text($view['subject']),140));
$sns_send .= '&amp;title='.$sns_msg;

$facebook_url = $sns_send.'&amp;sns=facebook';
$twitter_url  = $sns_send.'&amp;sns=twitter';
$gplus_url    = $sns_send.'&amp;sns=gplus';
$bo_v_sns_class = $config['cf_kakao_js_apikey'] ? 'show_kakao' : '';
?>

<?php if($config['cf_kakao_js_apikey']) { ?>
<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
<script src="<?php echo G5_JS_URL; ?>/kakaolink.js"></script>
<script>
    // 사용할 앱의 Javascript 키를 설정해 주세요.
    Kakao.init("<?php echo $config['cf_kakao_js_apikey']; ?>");
</script>
<?php } ?>

<li class="list-inline-item">
	<a href="#" data-bs-toggle="dropdown" class="text-muted"><i class="fa fa-share-alt"></i> 공유</a>
	<ul class="dropdown-menu">
		<li><a class="dropdown-item" href="<?php echo $facebook_url; ?>" target="_blank">페이스북으로 공유</a></li>
		<li><a class="dropdown-item" href="<?php echo $twitter_url; ?>" target="_blank"">트위터로  공유</a></li>
		<li><a class="dropdown-item" href="<?php echo $gplus_url; ?>" target="_blank">구글플러스로 공유</a></li>
		<?php if($config['cf_kakao_js_apikey']) { ?>
		<li><a class="dropdown-item" href="javascript:kakaolink_send('<?php echo str_replace(array('%27', '\''), '', $sns_msg); ?>', '<?php echo urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); ?>');">카카오톡으로 보내기</a></li>
		<?php } ?>
	</ul>
</li>