<?php
/**
 * This file is part of the Vietnamese package.
 *
 * @copyright (c) NEDKA. All rights reserved.
 * @license MIT License.
 */

$rows = explode("\n", trim(file_get_contents('data/syllables.tsv')));
$headers = explode("\t", array_shift($rows));
$words= [];

// Remove 2 first column headers
unset($headers[0]);
unset($headers[1]);

$headers = array_merge([''], (array) array_values($headers));

foreach ($rows as $row)
{
	$columns = explode("\t", $row);
	$syllable = array_shift($columns);

	foreach ($headers as $i => $header)
	{
		if ($columns[$i] !== '' && $columns[$i] !== 'X')
		{
			$words[] = $header . $syllable;
		}
	}
}

$content = "['" . implode("', '", $words) . "']";
file_put_contents('export/words.txt', $content);
echo('DONE!');
