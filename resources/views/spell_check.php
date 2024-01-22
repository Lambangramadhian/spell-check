
<?php


// require_once __DIR__ . '\..\..\..\vendor\autoload.php';

use Cwin\BasicWord\WordProcessing\Source\Indonesia\WordFactoryIndonesia;
use Cwin\BasicWord\WordProcessing\Source\English\WordFactoryEnglish;

$wordSpelling = new Cwin\BasicWord\WordSpelling(new WordFactoryIndonesia);
$suggestion = new Cwin\Component\Suggestion\Suggestion;

$text = $_POST['text'];
$max_suggestions = $_POST['max_suggestions'] ? $_POST['max_suggestions'] : 3;

$checkSpelling = $wordSpelling->checkSpelling($text);
$suggestion->setMaxListSuggestion(3);

foreach ($checkSpelling->spellingResult() as $spelling) {

	// maximum list suggestion
	$spelling->maxListSuggestion = $max_suggestions;

	$baseWord = $spelling->getBaseWord();
	$word = $spelling->getWord();
	$hasError = $spelling->hasError();

	/**
	 * memberbaiki glitch aneh kata mengandung "ke"
	 * tidak di anggap error walaupun tidak ada dalam kamus
	 */
	$word_contain_ke = str_contains($spelling->getWord(), 'ke');
	$suggestion_array = $suggestion->setSpelling($spelling)->suggest();
	$suggestion_is_empty = count($suggestion_array) == 0;

	if ($hasError || ($word_contain_ke && !$suggestion_is_empty)) {
		echo "<span id='typo-$word' class='typo'>";
		echo $word;
		echo "<ul class='suggestions'>
					<li class='hint'>" . implode("</li><li class='hint'>", $suggestion->setSpelling($spelling)->suggest()) . "</li>
				</ul>";
		echo "</span>";
	} else {
		// mengecek jika word adalah simbol tanda baca
		// jika ya, tidak di tambahkan spasi
		$string = strpos(' . , ? !', $word) ? $word  : ' ' . $word;
		echo $string;
	}
}




// $word = ""; // This space should be the non-breaking space character, not a regular space

// // Decode HTML entities
// $decodedWord = html_entity_decode('&nbsp;');
// $dd = htmlentities($decodedWord);

// // Check if the decoded word is equal to a non-breaking space
// if ($decodedWord === ' ') {
//     echo "The word is equal to &nbsp;";
// } else {
//     echo "The word is not equal to $dd";
// }