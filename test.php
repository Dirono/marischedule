<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 1/6/2017
 * Time: 10:49 AM
 */

include('simple_html_dom.php');
function stripAccents($str) {
	return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}

$teachersArray = array();

function getTeacherRatings ($html){
	$array = array();
	$teachers = $html->find('div.teacher');
	foreach ($teachers as $key=>$teacher){
		$teacherName = $teacher->find('div.info h3.teacher_name a');
		$teacherName = $teacherName[0]->innertext;
		$teacherName = stripAccents(strtolower(substr($teacherName,1, strlen($teacherName)-2)));
		$teacherFirstName = substr($teacherName, 0, strpos($teacherName,' '));
		$teacherLastName = substr($teacherName, strpos($teacherName,' ')+1, strlen ($teacherName) -strpos($teacherName,' '));
		$teacherRating = $teacher->find('div.score div.star-rating');
		$teacherRating = $teacherRating[0]->title;
		$teacherRating = floatval(substr($teacherRating,0,3));
		$teacherNumberRating = $teacher->find('div.score div.rating_count');
		$teacherNumberRating = $teacherNumberRating[0]->innertext;
		$teacherEverything = array($teacherFirstName,$teacherLastName,$teacherRating, $teacherNumberRating);
		array_push($array, $teacherEverything);
	}
	return $array;
}

$html = file_get_html('http://ca.ratemyteachers.com/marianopolis-college/38444-s');
$teachersArray = array_merge($teachersArray, getTeacherRatings($html));
for ($i=2; $i<=4; $i++){
	$html = file_get_html('http://ca.ratemyteachers.com/marianopolis-college/38444-s/'.$i);
	$teachersArray = array_merge($teachersArray, getTeacherRatings($html));
}

$teacherFirstNames = array();
foreach ($teachersArray as $teacher){
	array_push($teacherFirstNames, $teacher[0]);
}
$teacherLastNames = array();
foreach ($teachersArray as $teacher){
	array_push($teacherLastNames, $teacher[1]);
}
file_put_contents('classRatings.php', '<?php $teacherRatings = ' . var_export($teachersArray, true) . ';'.'$teacherFirstNames = '.var_export($teacherFirstNames,true).';'.'$teacherLastNames = '.var_export($teacherLastNames,true).';');