<?php
	include_once( 'header.php' );
	include_once( 'data/classes.php' )
?>

	<body>

	<script>
		var classes = <?php echo $classes;?>;
		<?php
//		if(isset($_GET['code]'])){{
//			echo 'console.log(5);';
//			echo 'var queryClasses ="'.filter_var($_GET['code'],FILTER_SANITIZE_NUMBER_INT).'";';
//		}}
		?>
	</script>

	<div class="reveal saveReveal" data-reveal>

		<button class="close-button" data-close aria-label="Close modal" type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

	<div class="reveal loadReveal" data-reveal>
		<h4>Enter your save code:</h4>
		<h6>This will replace your current schedule with the one from your save code</h6>
		<input class="loadSchedule-input" type="text">
		<a href="" class="loadQuery-button large button">Load</a>
		<button class="close-button" data-close aria-label="Close modal" type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

	<div class="reveal exportReveal" data-reveal>
		<ul class="exportClasses">

		</ul>
		<button class="close-button" data-close aria-label="Close modal" type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

	<div class="reveal warningReveal" data-reveal>
		<h3>Warning</h3>
		<p>The website has been updated to take into account the new course offering but there might still be bugs so I urge you to double check your schedule. Unfortunately, old save codes are no longer compatible with the new schedule due to changes to classes.</p>
		<button class="close-button" data-close aria-label="Close modal" type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

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
						for ($u=0; $u<5; $u++){
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
							<h5 class="findClass-title">Find Class</h5>
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
			<div class="row">
				<div class="large-4 collapse columns">
					<a href="" class="saveButton button">Save Schedule</a>
				</div>
				<div class="large-4 collapse columns">
					<a href="" class="loadButton secondary button">Load Schedule</a>
				</div>
				<div class="large-4 collapse columns">
					<a href="" class="exportButton alert button">Export Schedule</a>
				</div>
			</div>
				<a href="" class="instructionButton success expanded button">Instructions</a>

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
	<script
		src="https://code.jquery.com/jquery-3.1.1.min.js"
		integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
		crossorigin="anonymous"></script>
<!--	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->
	<script src="vendor/js/vendor/foundation.min.js"></script>
	<script src="vendor/js/vendor/what-input.js"></script>
	<script src="vendor/autocomplete/jquery.easy-autocomplete.min.js"></script>
	<script src="vendor/js/app.js"></script>
	</body>


	<footer>
	</footer>

</html>