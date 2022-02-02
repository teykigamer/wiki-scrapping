<?php 
require "vendor/autoload.php";

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\WebDriverExpectedCondition;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;

// Chromedriver (if started using --port=4444 as above)
$serverUrl = 'http://localhost:9515';
$proxyUrl = '127.0.0.1:9668';

$capabilities = new DesiredCapabilities(
    [
        WebDriverCapabilityType::BROWSER_NAME => 'chrome',
        WebDriverCapabilityType::PROXY => [
            'proxyType' => 'manual',
            'httpProxy' => $proxyUrl,
            'sslProxy' => $proxyUrl,
    ],
]);

$driver = RemoteWebDriver::create($serverUrl, $capabilities);


$links = file_get_contents('pagesterraria.txt');

foreach (explode(PHP_EOL, $links) as $link) {

	if (empty($link)) continue;

	try {
		$driver->get($link);
		$driver->executeScript('window.scrollTo(0,document.body.scrollHeight);');
		$driver->manage()->timeouts()->implicitlyWait(60);
	} catch (Exception $e) {
		continue;
	}
}