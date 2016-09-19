<?php

require "vendor/autoload.php";

use Goutte\Client;
$client = new Client();

//Semester course selection
$crawler = $client->request('GET', 'https://aits.encs.concordia.ca/oldsite/resources/schedules/courses/');

$links = [];
$crawler->filter('#maincontent > div.insidecontent > div:nth-child(3) > ul > li > a')->each(function($node, $i) use (&$links){
	print($i.": ".$node->text()."\n");
	array_push($links, $node->link());
});

print("Select a Semester: ");
$semester = stream_get_line(STDIN, 1024, PHP_EOL);

$crawler = $client->click($links[$semester]);

//Filter out the rows
$rows = $crawler->filter('table > tbody > tr');

//Courses
$courses = [];

//Extract all courses from rows.
$rows->each(function($node, $i) use (&$courses, &$client){
	$course_number = $node->filter('th')->text();
	$course_crawler = $client->click($node->selectLink($course_number)->link());

	$course_sections = $course_crawler->filter('table')->eq(1)->filter('tbody > tr');
	$course_data = [];

	$course_sections->each(function($node, $i) use (&$course_data){
		$td = $node->filter('td');
		$td_1 = $td->eq(1)->text();
		$td_2 = $td->eq(2)->text();
		
		$data = [
			'type' => trim($td->eq(0)->text()),
			'section' => ($td_1) ? $td_1 : $td_2,
			'day' => trim($td->eq(4)->text()),
			'start' => trim($td->eq(5)->text()),
			'end' => trim($td->eq(6)->text()),
			'room' => trim($td->eq(7)->text()),
			'instructor' => trim($td->eq(8)->text())
		];

		array_push($course_data, $data);
	});

	$course = [
		'number' => $course_number,
		'name' => $node->filter('td')->first()->text(),
		'sections' => $course_data
	];

	array_push($courses, $course);
});

$file_name = trim($crawler->filter('.insidecontent > h1')->first()->text()).'.json';
file_put_contents($file_name, json_encode($courses, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK));

print('Extracted to ' . $file_name);