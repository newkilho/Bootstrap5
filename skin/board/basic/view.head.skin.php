<?php
	// 자동 등록시 출력폼
	$write['wr_content'] = preg_replace_callback('/<p>(.*?)<\/p>/i', 
		function($match) {
			$url = $match[1];

			if(substr($url, 0, 6)=='/data/') $url = G5_URL.$url;

			if(filter_var($url, FILTER_VALIDATE_URL))
			{
				$ext = strrchr($url, '.');
				switch($ext)
				{
					case '.jpg':
					case '.gif':
					case '.png': 
					case '.webp': $url = '<img src="'.$url.'" class="img-fluid">'; break;

					case '.mp4':
					case '.webm': $url = '<video controls muted loop><source src="'.$url.'"></video>';

					default:
						$uri = parse_url($url);

						switch($uri['host'])
						{
							case 'youtu.be':
								if($uri['path']) $url = 'https://www.youtube.com/embed/'.substr($uri['path'], 1);
								$url = '<div class="video"><iframe src="'.$url.'" frameborder="0" allow="allowfullscreen"></iframe></div>';
								break;

							case 'youtube.com':
							case 'www.youtube.com':
								parse_str($uri['query'], $q);
								if($q['url']) $url = 'https://www.youtube.com/embed/'.$q['url'];
								$url = '<div class="video"><iframe src="'.$url.'" frameborder="0" allow="allowfullscreen"></iframe></div>';
								break;
						}

						if($url[0] == '#') $url = '';
				}
			}

			if(!$url) $url = '&nbsp;';

			return '<p>'.$url.'</p>';
		}, $write['wr_content']);