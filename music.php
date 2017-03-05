<?php
include_once( 'header.php' );
include_once( 'data/classes.php' )
?>

<style>td{
		width: 14.28%;
	}</style>
<body>
<script>
	var classes = <?php echo $classes;?>;
</script>

<?php
?>
<div class="reveal" id="exampleModal1" data-reveal>

	<button class="close-button" data-close aria-label="Close modal" type="button">
		<span aria-hidden="true">&times;</span>
	</button>
</div>

<!--	<div class="revealLoad" id="exampleModal1" data-reveal>-->
<!--		<h3>Please copy your schedule code here</h3>-->
<!--		<input class="load-input" type="text">-->
<!--		<button class="close-button" data-close aria-label="Close modal" type="button">-->
<!--			<span aria-hidden="true">&times;</span>-->
<!--		</button>-->
<!--	</div>-->

<div class="reveal revealInstructions" id="exampleModal1" data-reveal>
	<h3>Instructions</h3>
	<ul>
		<li>Choose how you want to search for a class (code, name, teacher) by selecting an option from the dropdown</li>
		<li>Enter your search into the box under find class and hit enter or click on the search icon to search for classes</li>
		<li>Click on the class options that appear to add them to your schedule</li>
	</ul>
	<button class="close-button" data-close aria-label="Close modal" type="button">
		<span aria-hidden="true">&times;</span>
	</button>
</div>

<div class="expanded row">
	<div class="large-9 collapse small-12 columns">
		<table>
			<tr>
				<th style="text-align: left;">Time</th>
				<th>Monday</th>
				<th>Tuesday</th>
				<th>Wednesday</th>
				<th>Thursday</th>
				<th>Friday</th>
				<th>Saturday</th>
			</tr>

			<?php

			function int_toTime($time){
				$time = strval($time);
				$time = substr_replace($time,':',-2,0);
				return $time;
			}
			$time=1015;
			function createCells(){
				for ($i=0; $i<24; $i++){
					$time = 815;
					$time = $time + 30*($i%2) +floor($i/2)*100;
					if ($i%2==0){
						$time2 = $time+30;
					}else{
						$time2 = $time+70;
					}
					echo '<tr>';
					echo '<td>'.int_toTime($time).'</td>';
//						.\'-\'.int_toTime($time2).\'
					for ($u=0; $u<6; $u++){
						echo '<td class="c'.$u.' r'.$i.'"></td>';
					}
					echo '</tr>';
				}
			}

			createCells();
			?>
		</table>

	</div>

	<div class="large-3 collapse right-column small-12 columns">
		<div class="classOptions">
			<div class="addClass">
				<div class="row">
					<div class="large-5 collapse columns">
						<h4 class="findClass-title">Find Class</h4>
					</div>
					<div style="text-align: right" class="large-7 collapse columns">
						<select id="searchType">
							<option value="courseCode">Course Code</option>
							<option value="className">Class Name</option>
							<option value="teacherName">Teacher</option>
						</select>
					</div>
				</div>
				<div class="row">
					<input id="autocomplete" placeholder = "603-103-MQ" >
					<i class="search-magnifying-glass fi-magnifying-glass"></i>
				</div>
			</div>
		</div>
		<a href="" class="instructionButton button">Instructions</a>
		<!--			<a href="" class="saveButton button">Save Schedule</a>-->
		<!--			<a href="" class="loadButton secondary button">Load Schedule</a>-->
		<div class="chosenClasses">

		</div>
		<!--			<div class="loadSchedule">-->
		<!--				<div class="row">-->
		<!--					<div class="large-8 columns">-->
		<!--						<h5>Load Schedule</h5>-->
		<!--					</div>-->
		<!--				</div>-->
		<!--				<div class="row">-->
		<!--					<div class="large-8 columns">-->
		<!--						<input class="loadSchedule-input" type="text">-->
		<!--					</div>-->
		<!--					<div class="large-4 columns">-->
		<!--						<a href="" class="button loadSchedule-button">Load</a>-->
		<!--					</div>-->
		<!--				</div>-->
		<!--			</div>-->

	</div>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="vendor/js/vendor/foundation.min.js"></script>
	<script src="vendor/js/vendor/what-input.js"></script>
	<script src="vendor/autocomplete/jquery.easy-autocomplete.min.js"></script>
	<script src="vendor/js/app.js"></script>
</body>


<footer>
</footer>

</html>