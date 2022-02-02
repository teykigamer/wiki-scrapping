<?php 

function getPage($xpath,$first)
{
	$base = "https://terraria.fandom.com";
	$data = $xpath->query('//*[@class="mw-allpages-body"]/ul/li/a');

	$links = [];
	foreach( $data as $node )
	{
		$links[] = $base.$node->getAttribute('href');
		// echo $node->nodeValue ."<br/>";
	}

	// get next page
	$xpagenext = $xpath->query('//div[@class="mw-allpages-nav"]/a');
	if ($first) {
		$pagenext = $base.$xpagenext[0]->getAttribute('href');
	}else{
		if ($xpagenext->length == 2) {
			$pagenext = false;
		}else{				
			$pagenext = $base.$xpagenext[1]->getAttribute('href');
		}
	}

	return [
		'links' => $links,
		'next' => $pagenext
	];
}

$index = 'https://terraria.fandom.com/wiki/Special:AllPages?hideredirects=1';

$first = true;
do {

	echo $index.PHP_EOL;

	$allpage = file_get_contents($index);
	$dom = new DOMDocument();
	@$dom->loadHTML($allpage);
	$xpath = new DOMXPath($dom);

	$page = getPage($xpath,$first);
	file_put_contents('pagesterraria.txt', implode($page['links'], PHP_EOL).PHP_EOL, FILE_APPEND);

	if (!empty($page['next'])) {
		$index = $page['next'];
		$first = false;
	}

} while (!empty($page['next']));