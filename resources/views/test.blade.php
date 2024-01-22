<?php 

require_once __DIR__ . '\..\..\..\vendor\autoload.php';

use Cwin\BasicWord\WordProcessing\Source\Indonesia\WordFactoryIndonesia;
use Cwin\BasicWord\WordProcessing\Source\English\WordFactoryEnglish;

use function PHPUnit\Framework\isEmpty;

$wordSpelling = new Cwin\BasicWord\WordSpelling(new WordFactoryIndonesia);
$suggestion = new Cwin\Component\Suggestion\Suggestion();

$checkSpelling = $wordSpelling->checkSpelling('indonesi sudah merkedeka sejak kenapa tahukdfa enn empat lima');
$suggestion->setMaxListSuggestion(3);

foreach ($checkSpelling->spellingResult() as $spelling) {
    print_r( count($suggestion->setSpelling($spelling)->suggest()) == 0);
	echo '<span '.$spelling->getWord().' '.(true ? 'class="error word"' : 'class="word"').'>' . $spelling->getWord() ;
	if($spelling->hasError() || str_contains($spelling->getWord(), 'ke') ) {
		echo " <span class='suggest'><ul><li>".implode("</li><li>", $suggestion->setSpelling($spelling)->suggest())."</li></ul></span> " ;
	}
	echo '</span> ';
}