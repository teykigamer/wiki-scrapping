<?php 

function getPage($xpath)
{
	$base = "https://stardewvalleywiki.com";

	$data = $xpath->query('//*[@id="mw-content-text"]/div[@class="mw-spcontent"]/ol/li/a[2]');

	$links = [];
	foreach( $data as $node )
	{
		$links[] = $base.$node->getAttribute('href');
		// echo $node->nodeValue .PHP_EOL;
	}

	// get next page
	$xpagenext = $xpath->query('//*[@id="mw-content-text"]/div[@class="mw-spcontent"]/p[2]/a[@class="mw-nextlink"]');
	if ($xpagenext->length > 0) {
		$pagenext = $base.$xpagenext[0]->getAttribute('href');
	}else{
		$pagenext = false;
	}

	return [
		'links' => $links,
		'next' => $pagenext
	];
}

$index = 'https://stardewvalleywiki.com/Special:MIMESearch?mime=image%2F*';

do {

	echo $index.PHP_EOL;

	$allpage = file_get_contents($index);
	$dom = new DOMDocument();
	@$dom->loadHTML($allpage);
	$xpath = new DOMXPath($dom);

	$page = getPage($xpath);
	file_put_contents('files.txt', implode($page['links'], PHP_EOL), FILE_APPEND);

	if (!empty($page['next'])) {
		$index = $page['next'];
	}

} while (!empty($page['next']));