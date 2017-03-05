<?php

include('simple_html_dom.php');
include( '/data/classRatings.php' );
function filter($row){
	if($row=="DAY/TIMES"||
	   $row=="\fSCHEDULE OF CLASSES - WINTER 2017"||
	   $row=='SECTION'||
	   $row=='COURSE NUMBER'||
	   $row=='COURSE TITLE/TEACHER' ||
	   $row == '300.11 - Music & Social Science'||
	   $row=='200.11 - Music & Science' ||
	   $row== '300.A0 - Social Science / Commerce Courses' ||
	   $row =='500.11 - Music & Arts Literature and Communications' ||
	   $row=='CR1 - ALC - CREATION' ||
	   $row=='EX1 - ALC - EXPLORATIONS' ||
	   $row=='500.11 - Creative Arts And Music' ||
	   $row=='500.AE - Arts, Literature and Communication' ||
	   $row=='700.B0 - Liberals Arts Courses'){
		return false;
	}
	else{
		return true;
	}
}

function stripAccents($str) {
	return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}

//Get text file and create new array for each line
$txtFile = file_get_contents('data/RawCourseDataUpdated.txt');
$rows = explode ("\n", $txtFile);

//Remove empty lines and make keys sequential
$rows = array_filter($rows);
$rows = array_filter($rows, 'filter');
$rows = array_values($rows);


/***CREATE CLASSES ***/
//Create class to store each classes' data in an array
class ClassData{
	public $data = array();
}

//Create object class for each College Class to store all relevant information
class CollegeClass{
	public $classID = 0;
	public $sectionNumber = '00001';
	public $courseCode = '000-AAA-00';
	public $className = 'Introduction to Letters';
	public $teacherName = 'John Doe';
	public $teacherFirstName = "John";
	public $teacherLastName = "Doe";
	public $type = 'Complementary';
	public $allottedTimes = array();
	public $teacherRating = '-';
	public $teacherNumberRatings = 'No Ratings';
}
$classDatas = array();
foreach($rows as $key=>$row) {
	if ( substr( $row, 0, 3 ) == '000' ) {
		$classData = new ClassData;
		array_push( $classData->data, $row );
		for ( $i = 1; $i <= 20; $i ++ ) {
			if ( substr( $rows[ $key + $i ], 0, 3 ) != '000' ) {
				if ( $key + $i < count($rows)-1 ) {
					array_push( $classData->data, $rows[ $key + $i ] );
				} else {
					break;
				}
			}else{
				break;
			}
		}
		array_push( $classDatas, $classData );
	}
}
//var_dump($classDatas);

//Create array to store all class objects
$classes = array();

//Loop over each object that stored unedited information about each class to reconfigure the data
foreach($classDatas as $classData){

	$class = new CollegeClass();
	//Create array to store different strings each representing the class name since class names are sometimes split in 2
	$classNames = array();
	//Array to store the different days the class occurs
	$days = array();
	//Array to store the different times the class occurs
	$times = array();
	//Check whether it's the first date entry
	$first = 0;
	$first2 = 0;
	//Loop over each element of the array of the data class to add the information to the College Class object
	foreach ($classData->data as $key=>$data ){
		//Section
		if(substr( $data, 0, 3 ) == '000' ){
			$class->sectionNumber = $classData->data[$key];
		}
		//Course Code
		elseif ($key==1){
			$class->courseCode = $classData->data[$key];
			$firstPos = $key+1;
		}
		//Class Name
//		elseif (substr($data, 0, 3) === strtoupper(substr($data, 0, 3)) & strlen($data)>6 & preg_match("/[a-z]/i", $data)) {
//			array_push($classNames, $data );
//		}
		//Teacher Name
		elseif (strpos($data, ',')!=false && $key!=$firstPos){
			$class->teacherName = $data;
			if ($first2==0){
				$lastPos=$key-1;
			}
			$first2=1;
		}
		//Days
		elseif (strlen($data)<=3 & $data === strtoupper($data) & preg_match("/^M|T|W|H|F|S$/", $data) & $data !='GYM' & $data!='TBA'){
			array_push($days, $data);
			if($first==0){
				$class->teacherName = $classData->data[$key-1];
				//$lastPos = $key-2;
			}
			$first = 1;
		}
		//Times
		elseif (strlen($data) == 11 && !preg_match("/[a-z]/i", $data)){
			array_push($times, $data);
		}

		//Concatenate each class name to get full class name

	}
	$class->className ='';
	for ($i=$firstPos;$i<=$lastPos;$i++){
		$class->className = $class->className.' '.$classData->data[$i];
	}

	//Create array for times
	$class->allottedTimes=array();
	//Loop over the array of dates
	foreach($days as $key2=>$day){
		//Loop over the letters of each element of the array, creating a new array for each letter with the start and end time
		for ($i=0; $i<strlen($day); $i++){
			array_push($class->allottedTimes, array(
				substr($day, $i, 1),
				intval(str_replace(':','',substr($times[$key2], 0, 5))),
				intval(str_replace(':','',substr($times[$key2], 6, 5)))
			));
		}
	}
	array_push($classes,$class);
}

$classes = array_values( array_unique( $classes, SORT_REGULAR ) );

for ($i=0;$i<count($classes);$i++){
	$classes[$i]->classID = $i;
}

$teachersArray = array();
$html = file_get_html('http://ca.ratemyteachers.com/marianopolis-college/38444-s');
$teachers = $html->find('div.teacher');
foreach ($teachers as $key=>$teacher){
	$teacherName = $teacher->find('div.info h3.teacher_name a');
	$teacherName = $teacherName[0]->innertext;
	$teacherName = stripAccents(strtolower(substr($teacherName,1, strlen($teacherName)-2)));
	$teacherRating = $teacher->find('div.score div.star-rating');
	$teacherRating = $teacherRating[0]->title;
	$teacherRating = floatval(substr($teacherRating,0,3));
	$teacherNumberRating = $teacher->find('div.score div.rating_count');
	$teacherNumberRating = $teacherNumberRating[0]->innertext;
	$teacherBoth = array($teacherName,$teacherRating, $teacherNumberRating);
	array_push($teachersArray, $teacherBoth);
}

foreach($classes as $class){
	$teacherName = stripAccents(strtolower($class->teacherName));
	$commaPos = strpos($teacherName, ', ');
	$class->teacherFirstName = substr($teacherName, $commaPos+2, strlen($teacherName)-$commaPos);
	$class->teacherLastName = substr($teacherName, 0, $commaPos);
	if ($class->teacherFirstName =="theodore"){
		$class->teacherFirstName = 'ted';
	}
	$firstNamePos = array_keys($teacherFirstNames,$class->teacherFirstName);
	$lastNamePos = array_keys($teacherLastNames,$class->teacherLastName);
	$common = array_values(array_intersect($firstNamePos, $lastNamePos));
	if (count($common)!=0){
		$class->teacherRating = strval(round($teacherRatings[$common[0]][2],2));
		$class->teacherNumberRatings = $teacherRatings[$common[0]][3];
	};

}

$classesCourseCodes = array();
$classesNames = array();
$classesTeachers = array();
foreach ($classes as $class){
	array_push($classesCourseCodes, $class->courseCode);
	array_push($classesNames, $class->className);
	array_push($classesTeachers, $class->teacherName);

}
foreach($classes as $class){
	var_dump($class);
}
function createJSFile($array, $fileName){
	$array = array_values( array_unique( $array, SORT_REGULAR ) );
	$array = json_encode($array );
	$jsonFile2 = fopen($fileName,'w');
	fwrite($jsonFile2, $array);
	fclose($jsonFile2);
}


createJSFile($classesCourseCodes, 'data/courseCode.js');
createJSFile($classesTeachers, 'data/teacherName.js');
createJSFile($classesNames, 'data/className.js');

$classes = json_encode($classes);
$jsonFile = fopen('data/classes.json','w');
fwrite($jsonFile, $classes);
fclose($jsonFile);

file_put_contents('data/classes.php', '<?php $classes = ' . var_export($classes, true) . ';');