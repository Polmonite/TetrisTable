<?php
	$level = isset($_GET['level']) ? $_GET['level'] : 1;
	$speed = isset($_GET['speed']) ? $_GET['speed'] : 52;
	$w = 10;
	$h = 22; // 2 top most rows are hidden
	$threedimode = isset($_GET['threedimode']) ? (bool)$_GET['threedimode'] : false;
	$preview = isset($_GET['preview']) ? $_GET['preview'] : 1;
?>
<!doctype HTML>
<html>
	<head>
		<title>Table-Tetris</title>
		<script
			src="http://code.jquery.com/jquery-3.2.1.min.js"
			integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
			crossorigin="anonymous"></script>
		<link href="https://fonts.googleapis.com/css?family=VT323" rel="stylesheet">
		<style>
			body {
				font-family: 'VT323', monospace;
				background: #2a3477;
				background-image: radial-gradient(circle, #4d6a96, #3e588d, #324682, #2a3477, #262069);
			}
			#game-container {
				margin-top: 25px;
				position: relative;
				z-index: 5;
			}
			#game-over {
				display: none;
				position: absolute;
				top: 50%;
				left: 50%;
				margin-left: -2.2em;
				margin-top: -10px;
				font-size: 5.5em;
				z-index: 999;
				color: white;
				text-shadow: 0 0 5px #1e4c96, 0 0 10px #1e4c96;
			}
			#game-over.show {
				display: inline-block;
			}
			table#grid td { background: grey; }
			table#grid td.I { background: cyan; }
			table#grid td.J { background: blue; }
			table#grid td.L { background: orange; }
			table#grid td.O { background: yellow; }
			table#grid td.S { background: lime; }
			table#grid td.T { background: #800080; }
			table#grid td.Z { background: red; }
			table#grid td:before {
				content: '';
				display: inline-block;
				width: 100%;
				height: 100%;
				box-sizing: border-box;
				border-top: 2px solid rgba(255, 255, 255, 0.4);
				border-left: 2px solid rgba(255, 255, 255, 0.4);
				border-right: 2px solid rgba(0, 0, 0, 0.4);
				border-bottom: 2px solid rgba(0, 0, 0, 0.4);
				position: absolute;
				top: 0;
				left: 0;
			}
			table#grid {
				margin: auto;
			}
			table#grid, table#grid td {
				border-collapse: collapse;
				border-color: transparent;
				padding: 0;
			}
			table#grid tr[data-row-index="0"], table#grid tr[data-row-index="1"] {
				display: none;
			}
			table#grid tr td {
				position: relative;
				width: 24px;
				height: 24px;
				margin: 0;
			}
			#info, #preview {
				color: white;
				text-shadow: 0 0 5px white, 0 0 15px white;
				margin-top: 25px;
				font-size: 1.4em;
			}
			#info {
				margin-right: 25px;
			}
			#preview {
				margin-left: 25px;
			}
			#preview td {
				vertical-align: top;
			}
			#next-0 {
				padding-left: 20px;
			}
		<?php
			if ($threedimode) {
		?>
			table#grid tbody:before {
				content: '';
				position: absolute;
				z-index: -1;
		    height: 103%;
		    width: 120%;
		    top: 0;
		    left: 50%;
		    transform-origin: center bottom;
		    transform: translateX(-50%);
		    background: rgba(0, 0, 0, 0.2);
			}
			#game-over {
				transform: rotateX(35deg);
			}
			#info, #preview, #game-over {
				transform: rotateX(-35deg);
			}
		<?php
			} else {
		?>
			table#grid {
				border-bottom: 24px solid rgba(0, 0, 0, 0.2);
				border-left: 24px solid rgba(0, 0, 0, 0.2);
				border-right: 24px solid rgba(0, 0, 0, 0.2);
			}
		<?php
			}
		?>
			/**\/
			table#grid tr td[data-x="0"]:before {
				content: attr(data-y);
				position: absolute;
				left: -24px;
				top: 6px;
			}
			table#grid tr td[data-y="2"]:after {
				content: attr(data-x);
				position: absolute;
				top: -24px;
				left: 6px;
			}
			/**\/
			@keyframes glowin_I {
				0% { box-shadow: 0 0 5px cyan, 0 0 15px cyan; }
				50% { box-shadow: 0 0 5px cyan, 0 0 15px cyan; }
				65% { box-shadow: 0 0 5px cyan, 0 0 15px cyan, 0 0 20px cyan; }
				75% { box-shadow: 0 0 5px cyan, 0 0 15px cyan; }
				85% { box-shadow: 0 0 5px cyan, 0 0 15px cyan, 0 0 25px cyan; }
				100% { box-shadow: 0 0 5px cyan, 0 0 15px cyan; }
			}
			@keyframes glowin_J {
				0% { box-shadow: 0 0 5px blue, 0 0 15px blue; }
				50% { box-shadow: 0 0 5px blue, 0 0 15px blue; }
				65% { box-shadow: 0 0 5px blue, 0 0 15px blue, 0 0 20px blue; }
				75% { box-shadow: 0 0 5px blue, 0 0 15px blue; }
				85% { box-shadow: 0 0 5px blue, 0 0 15px blue, 0 0 25px blue; }
				100% { box-shadow: 0 0 5px blue, 0 0 15px blue; }
			}
			@keyframes glowin_L {
				0% { box-shadow: 0 0 5px orange, 0 0 15px orange; }
				50% { box-shadow: 0 0 5px orange, 0 0 15px orange; }
				65% { box-shadow: 0 0 5px orange, 0 0 15px orange, 0 0 20px orange; }
				75% { box-shadow: 0 0 5px orange, 0 0 15px orange; }
				85% { box-shadow: 0 0 5px orange, 0 0 15px orange, 0 0 25px orange; }
				100% { box-shadow: 0 0 5px orange, 0 0 15px orange; }
			}
			@keyframes glowin_O {
				0% { box-shadow: 0 0 5px yellow, 0 0 15px yellow; }
				50% { box-shadow: 0 0 5px yellow, 0 0 15px yellow; }
				65% { box-shadow: 0 0 5px yellow, 0 0 15px yellow, 0 0 20px yellow; }
				75% { box-shadow: 0 0 5px yellow, 0 0 15px yellow; }
				85% { box-shadow: 0 0 5px yellow, 0 0 15px yellow, 0 0 25px yellow; }
				100% { box-shadow: 0 0 5px yellow, 0 0 15px yellow; }
			}
			@keyframes glowin_S {
				0% { box-shadow: 0 0 5px lime, 0 0 15px lime; }
				50% { box-shadow: 0 0 5px lime, 0 0 15px lime; }
				65% { box-shadow: 0 0 5px lime, 0 0 15px lime, 0 0 20px lime; }
				75% { box-shadow: 0 0 5px lime, 0 0 15px lime; }
				85% { box-shadow: 0 0 5px lime, 0 0 15px lime, 0 0 25px lime; }
				100% { box-shadow: 0 0 5px lime, 0 0 15px lime; }
			}
			@keyframes glowin_T {
				0% { box-shadow: 0 0 5px #800080, 0 0 15px #800080; }
				50% { box-shadow: 0 0 5px #800080, 0 0 15px #800080; }
				65% { box-shadow: 0 0 5px #800080, 0 0 15px #800080, 0 0 20px #800080; }
				75% { box-shadow: 0 0 5px #800080, 0 0 15px #800080; }
				85% { box-shadow: 0 0 5px #800080, 0 0 15px #800080, 0 0 25px #800080; }
				100% { box-shadow: 0 0 5px #800080, 0 0 15px #800080; }
			}
			@keyframes glowin_Z {
				0% { box-shadow: 0 0 5px red, 0 0 15px red; }
				50% { box-shadow: 0 0 5px red, 0 0 15px red; }
				65% { box-shadow: 0 0 5px red, 0 0 15px red, 0 0 20px red; }
				75% { box-shadow: 0 0 5px red, 0 0 15px red; }
				85% { box-shadow: 0 0 5px red, 0 0 15px red, 0 0 25px red; }
				100% { box-shadow: 0 0 5px red, 0 0 15px red; }
			}
			table#grid td {
				animation-duration: 24s;
				animation-iteration-count: infinite;
				animation-timing-function: ease-in-out;
				animation-direction: alternate;
			}
			table#grid td.I, table#preview-piece td.I { animation-name: glowin_I; }
			table#grid td.J, table#preview-piece td.J { animation-name: glowin_J; }
			table#grid td.L, table#preview-piece td.L { animation-name: glowin_L; }
			table#grid td.O, table#preview-piece td.O { animation-name: glowin_O; }
			table#grid td.S, table#preview-piece td.S { animation-name: glowin_S; }
			table#grid td.T, table#preview-piece td.T { animation-name: glowin_T; }
			table#grid td.Z, table#preview-piece td.Z { animation-name: glowin_Z; }
			/**/
			<?php
				if ($threedimode) {
			?>
				#game-container {
					perspective: 400px;
				}
				#grid thead {
					transform: translateY(80px) rotateX(-35deg);
				}
				#grid tbody {
					transform: rotateX(35deg);
				}
			<?php
				}
			?>
			table#preview-piece {
				margin: auto;
			}
			table#preview-piece tr td {
				position: relative;
				width: 24px;
				height: 24px;
				margin: 0;
			}
			table#preview-piece, table#preview-piece td {
				border-collapse: collapse;
				border-color: transparent;
				padding: 0;
			}
			table#preview-piece td { background: transparent; }
			table#preview-piece.I td.I { background: cyan; }
			table#preview-piece.J td.J { background: blue; }
			table#preview-piece.L td.L { background: orange; }
			table#preview-piece.O td.O { background: yellow; }
			table#preview-piece.S td.S { background: lime; }
			table#preview-piece.T td.T { background: #800080; }
			table#preview-piece.Z td.Z { background: red; }
			table#preview-piece.I td.I:before,
			table#preview-piece.J td.J:before,
			table#preview-piece.L td.L:before,
			table#preview-piece.O td.O:before,
			table#preview-piece.S td.S:before,
			table#preview-piece.T td.T:before,
			table#preview-piece.Z td.Z:before {
				content: '';
				display: inline-block;
				width: 100%;
				height: 100%;
				box-sizing: border-box;
				border-top: 2px solid rgba(255, 255, 255, 0.4);
				border-left: 2px solid rgba(255, 255, 255, 0.4);
				border-right: 2px solid rgba(0, 0, 0, 0.4);
				border-bottom: 2px solid rgba(0, 0, 0, 0.4);
				position: absolute;
				top: 0;
				left: 0;
			}
			#table-containers {
				position: relative;
				margin: auto;
				display: block;
				text-align: center;
				z-index: 5;
			}
			#table-containers > table {
				display: inline-block;
				vertical-align: top;
			}
			#space {
				display: inline-block;
				position: absolute;
				z-index: 2;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
			}

			/*@keyframes glowin_star_one {
				0% { opacity: 0; }
				50% { opacity: 1; }
				100% { opacity: 0; }
			}
			@keyframes glowin_star_two {
				0% { opacity: 0; }
				50% { opacity: 0.95; }
				100% { opacity: 0; }
			}
			@keyframes glowin_star_three {
				0% { opacity: 0; }
				50% { opacity: 0.9; }
				100% { opacity: 0; }
			}
			@keyframes glowin_star_four {
				0% { opacity: 0; }
				50% { opacity: 0.85; }
				100% { opacity: 0; }
			}
			@keyframes glowin_star_five {
				0% { opacity: 0; }
				50% { opacity: 0.8; }
				100% { opacity: 0; }
			}
			@keyframes glowin_star_six {
				0% { opacity: 0; }
				50% { opacity: 0.75; }
				100% { opacity: 0; }
			}
			@keyframes glowin_star_seven {
				0% { opacity: 0; }
				50% { opacity: 0.7; }
				100% { opacity: 0; }
			}
			@keyframes glowin_star_eight {
				0% { opacity: 0; }
				50% { opacity: 0.65; }
				100% { opacity: 0; }
			}
			@keyframes glowin_star_nine {
				0% { opacity: 0; }
				50% { opacity: 0.6; }
				100% { opacity: 0; }
			}
			@keyframes glowin_star_ten {
				0% { opacity: 0; }
				50% { opacity: 0.55; }
				100% { opacity: 0; }
			}

			#space .star {
				position: absolute;
				display: inline-block;
				width: 2px;
				height: 2px;
				border-radius: 50%;
				background: white;
				animation-duration: 5s;
				animation-iteration-count: infinite;
				animation-timing-function: linear;
			}
			#space .star.distance-1 { animation-name: glowin_star_one; box-shadow: 0 0 55px 10px white; opacity: 0; }
			#space .star.distance-2 { animation-name: glowin_star_two; box-shadow: 0 0 50px 9px white; opacity: 0; }
			#space .star.distance-3 { animation-name: glowin_star_three; box-shadow: 0 0 45px 8px white; opacity: 0; }
			#space .star.distance-4 { animation-name: glowin_star_four; box-shadow: 0 0 40px 7px white; opacity: 0; }
			#space .star.distance-5 { animation-name: glowin_star_five; box-shadow: 0 0 35px 6px white; opacity: 0; }
			#space .star.distance-6 { animation-name: glowin_star_six; box-shadow: 0 0 30px 5px white; opacity: 0; }
			#space .star.distance-7 { animation-name: glowin_star_seven; box-shadow: 0 0 25px 4px white; opacity: 0; }
			#space .star.distance-8 { animation-name: glowin_star_eight; box-shadow: 0 0 20px 3px white; opacity: 0; }
			#space .star.distance-9 { animation-name: glowin_star_nine; box-shadow: 0 0 15px 2px white; opacity: 0; }
			#space .star.distance-10 { animation-name: glowin_star_ten; box-shadow: 0 0 10px 1px white; opacity: 0; }
			#space .star.wait-1 { animation-delay: 0; }
			#space .star.wait-2 { animation-delay: 0.4s; }
			#space .star.wait-3 { animation-delay: 0.8s; }
			#space .star.wait-4 { animation-delay: 1.2s; }
			#space .star.wait-5 { animation-delay: 1.6s; }
			#space .star.wait-6 { animation-delay: 2s; }
			#space .star.wait-7 { animation-delay: 2.4s; }
			#space .star.wait-8 { animation-delay: 2.8s; }
			#space .star.wait-9 { animation-delay: 3.2s; }
			#space .star.wait-10 { animation-delay: 3.6s; }*/
		</style>
	</head>
	<body>
		<!-- <div id="space">
		<?php
			for ($i = 0; $i < mt_rand(150, 300); ++$i) {
				$left = mt_rand(5, 95);
				$top = mt_rand(5, 95);
				$distance = mt_rand(1, 10);
				$wait = mt_rand(1, 10);
				$style = "left:{$left}%;top:{$top}%;";
		?>
			<div class="star distance-<?= $distance ?> wait-<?= $wait ?>" style="<?= $style ?>">&nbsp;</div>
		<?php
			}
		?>
		</div> -->
		<div id="game-container">
			<h3 id="game-over">&nbsp;</h3>
			<br/>
			<div id="table-containers">
				<table id="info">
					<tbody>
						<tr>
							<td>LEVEL:</td>
							<td id="level">0</td>
						</tr>
						<tr>
							<td>SCORE:</td>
							<td id="points">0</td>
						</tr>
						<tr>
							<td>LINES:</td>
							<td id="lines">0</td>
						</tr>
					</tbody>
				</table>
				<table id="grid" data-w="<?= $w ?>" data-h="<?= $h ?>" data-speed="<?= $speed ?>">
					<tbody>
					<?php for ($i = 0; $i < $h; ++$i) { ?>
						<tr data-row-index="<?= $i ?>">
							<?php for ($j = 0; $j < $w; ++$j) { ?>
							<td class="cell" id="cell-<?= $j ?>-<?= $i ?>" data-x="<?= $j ?>" data-y="<?= $i ?>">&nbsp;</td>
							<?php } ?>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				<table id="preview">
					<tbody>
						<tr>
							<td>NEXT:</td>
							<td id="next-0">
								<table id="preview-piece">
									<tbody>
										<tr>
											<td>&nbsp;</td>
											<td class="I J L O">&nbsp;</td>
											<td class="O">&nbsp;</td>
										</tr>
										<tr>
											<td class="Z">&nbsp;</td>
											<td class="I J L S T Z O">&nbsp;</td>
											<td class="S O">&nbsp;</td>
										</tr>
										<tr>
											<td class="J S T">&nbsp;</td>
											<td class="I J L S T Z">&nbsp;</td>
											<td class="L Z T">&nbsp;</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td class="I">&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<script>
			var gameProc = true;
			var grid = jQuery('#grid');
			var info = jQuery('#info');
			var gameOverEl = jQuery('#game-over');
			var level = info.find('#level');
			var lines = info.find('#lines');
			var score = info.find('#points');
			var speed = grid.attr('data-speed')|0;
			var previewEl = jQuery('#preview');
			var previewPieceEl = previewEl.find('#preview-piece');
			var w = grid.attr('data-w')|0;
			var h = grid.attr('data-h')|0;

			var startCoords = {
				x: ((w / 2) - 1)|0,
				y: 0
			};

			var piecesIndex = [ 'I', 'J', 'L', 'O', 'S', 'T', 'Z' ];
			var piecesTypes = {
				I: {
					name: 'I',
					colour: "cyan",
					form: [
						[ -1, 0 ], [ 0, 0 ], [ 1, 0 ], [ 2, 0 ]
					],
					rotations: [
						[ [ 2, -1 ], [ 1, 0 ], [ 0, 1 ], [ -1, 2 ] ],
						[ [ 1, 2 ], [ 0, 1 ], [ -1, 0 ], [ -2, -1 ] ],
						[ [ -2, 1 ], [ -1, 0 ], [ 0, -1 ], [ 1, -2 ] ],
						[ [ -1, -2 ], [ 0, -1 ], [ 1, 0 ], [ 2, 1 ] ]
					]
				},
				J: {
					name: 'J',
					colour: "blue",
					form: [
						[ -1, 0 ], [ -1, 1 ], [ 0, 1 ], [ 1, 1 ]
					],
					rotations: [
						[ [ 2, 0 ], [ 1, -1 ], [ 0, 0 ], [ -1, 1 ] ],
						[ [ 0, 2 ], [ 1, 1 ], [ 0, 0 ], [ -1, -1 ] ],
						[ [ -2, 0 ], [ -1, 1 ], [ 0, 0 ], [ 1, -1 ] ],
						[ [ 0, -2 ], [ -1, -1 ], [ 0, 0 ], [ 1, 1 ] ]
					]
				},
				L: {
					name: 'L',
					colour: "orange",
					form: [
						[ -1, 1 ], [ 0, 1 ], [ 1, 1 ], [ 1, 0 ]
					],
					rotations: [
						[ [ 1, -1 ], [ 0, 0 ], [ -1, 1 ], [ 0, 2 ] ],
						[ [ 1, 1 ], [ 0, 0 ], [ -1, -1 ], [ -2, 0 ] ],
						[ [ -1, 1 ], [ 0, 0 ], [ 1, -1 ], [ 0, -2 ] ],
						[ [ -1, -1 ], [ 0, 0 ], [ 1, 1 ], [ 2, 0 ] ]
					]
				},
				O: {
					name: 'O',
					colour: "yellow",
					form: [
						[ 0, 0 ], [ 0, 1 ], [ 1, 0 ], [ 1, 1 ]
					],
					rotations: [
						[ [ 0, 0 ], [ 0, 0 ], [ 0, 0 ], [ 0, 0 ] ],
						[ [ 0, 0 ], [ 0, 0 ], [ 0, 0 ], [ 0, 0 ] ],
						[ [ 0, 0 ], [ 0, 0 ], [ 0, 0 ], [ 0, 0 ] ],
						[ [ 0, 0 ], [ 0, 0 ], [ 0, 0 ], [ 0, 0 ] ]
					]
				},
				S: {
					name: 'S',
					colour: "lime",
					form: [
						[ -1, 1 ], [ 0, 1 ], [ 0, 0 ], [ 1, 0 ]
					],
					rotations: [
						[ [ 1, -1 ], [ 0, 0 ], [ 1, 1 ], [ 0, 2 ] ],
						[ [ 1, 1 ], [ 0, 0 ], [ -1, 1 ], [ -2, 0 ] ],
						[ [ -1, 1 ], [ 0, 0 ], [ -1, -1 ], [ 0, -2 ] ],
						[ [ -1, -1 ], [ 0, 0 ], [ 1, -1 ], [ 2, 0 ] ]
					]
				},
				T: {
					name: 'T',
					colour: "#800080",
					form: [
						[ 0, 0 ], [ 0, 1 ], [ -1, 1 ], [ 1, 1 ]
					],
					rotations: [
						[ [ 1, 1 ], [ 0, 0 ], [ 1, -1 ], [ -1, 1 ] ],
						[ [ -1, 1 ], [ 0, 0 ], [ 1, 1 ], [ -1, -1 ] ],
						[ [ -1, -1 ], [ 0, 0 ], [ -1, 1 ], [ 1, -1 ] ],
						[ [ 1, -1 ], [ 0, 0 ], [ -1, -1 ], [ 1, 1 ] ]
					]
				},
				Z: {
					name: 'Z',
					colour: "red",
					form: [
						[ -1, 0 ], [ 0, 0 ], [ 0, 1 ], [ 1, 1 ]
					],
					rotations: [
						[ [ 2, 0 ], [ 1, 1 ], [ 0, 0 ], [ -1, 1 ] ],
						[ [ 0, 2 ], [ -1, 1 ], [ 0, 0 ], [ -1, -1 ] ],
						[ [ -2, 0 ], [ -1, -1 ], [ 0, 0 ], [ 1, -1 ] ],
						[ [ 0, -2 ], [ 1, -1 ], [ 0, 0 ], [ 1, 1 ] ]
					]
				}
			};

			var rand = function(min, max) {
				return Math.floor(Math.random() * max) + min;
			};

			var setupController = function() {
				// controller
				jQuery(window).on('keydown', function(e) {
					if (e.keyCode == 40) {
						jQuery(window).trigger('btn_down');
					} else if (e.keyCode == 39) {
						jQuery(window).trigger('btn_right');
					} else if (e.keyCode == 38) {
						jQuery(window).trigger('btn_up');
					} else if (e.keyCode == 37) {
						jQuery(window).trigger('btn_left');
					// } else if (e.keyCode == 65) {
					// 	jQuery(window).trigger('btn_a');
					// } else if (e.keyCode == 68) {
					// 	jQuery(window).trigger('btn_d');
					}
				});
			};

			var randPiece = function() {
				return piecesIndex[rand(0, piecesIndex.length - 1)];
			};

			var createEmptyRow = function() {
				var row = [];
				for (var j = 0; j < w; ++j) {
					row.push({
						status: 0,
						colour: null
					});
				}
				return row;
			};

			var gameOver = function() {
				gameProc = false;
				gameOverEl.text('GAME OVER');
				gameOverEl.addClass('show');
			};

			var removeAllPiecesClasses = function(el) {
				for (var i in piecesIndex) {
					el.removeClass(piecesIndex[i]);
				}
			};

			var Board = function() {
				var b = {
					boardStatus: [],
					cellReference: {},
					clearedLines: 0,
					level: 0,
					lines: 0,
					score: 0,
					// ---
					updateInfo: function() {
						lines.text(b.lines);
						level.text(b.level);
						score.text(b.score);
						removeAllPiecesClasses(previewPieceEl);
						previewPieceEl.addClass(Next.preview());
					},
					fillBoard: function() {
						while	(b.boardStatus.length < h) {
							b.boardStatus.unshift(createEmptyRow());
						}
					},
					init: function() {
						b.fillBoard();
						for (var i = 0; i < h; ++i) {
							for (var j = 0; j < w; ++j) {
								b.cellReference['cell-' + j + '-' + i] = grid.find('#cell-' + j + '-' + i);
							}
						}
					},
					drawCell: function(x, y, el) {
						var el = el || '';
						var cell = b.cellReference['cell-' + x + '-' + y];
						cell.removeClass('blowup').removeClass('glow');
						removeAllPiecesClasses(cell);
						if (el) {
							cell.addClass(el);
						}
					},
					drawListCell: function(list, el) {
						var el = el || '';
						for (var i in list) {
							b.drawCell(list[i].x, list[i].y, el);
						}
					},
					redraw: function() {
						for (var i = 0; i < h; ++i) {
							for (var j = 0; j < w; ++j) {
								if (b.boardStatus[i][j].status == 0) {
									b.drawCell(j, i);
								} else {
									b.drawCell(j, i, b.boardStatus[i][j].colour);
								}
							}
						}
					},
					setStatus: function(x, y, status, colour) {
						b.boardStatus[y][x].status = status;
						b.boardStatus[y][x].colour = colour;
					},
					setListStatus: function(list, status, colour) {
						for (var i in list) {
							b.setStatus(list[i].x, list[i].y, status, colour);
						}
					},
					incClearedLines: function(clearedLines) {
						b.lines += clearedLines;
						b.clearedLines = b.clearedLines + clearedLines; 
						var baseScore = 0;
						switch (clearedLines|0) {
							case 1:
								baseScore = 40;
								break;
							case 2:
								baseScore = 100;
								break;
							case 3:
								baseScore = 300;
								break;
							case 4:
								baseScore = 1200;
								break;
						}
						b.score = b.score + (baseScore * (clearedLines + 1));
						if (b.clearedLines > 10) {
							b.level += 1;
							speed = Math.max(2, speed - 2);
							b.clearedLines = b.clearedLines % 10;
						}
					},
					checkRow: function(rowIndex) {
						for (var i in b.boardStatus[rowIndex]) {
							if (b.boardStatus[rowIndex][i].status == 0) {
								return false;
							}
						}
						return true;
					},
					checkRows: function() {
						var clearedLines = 0;
						for (var i = b.boardStatus.length - 1; i >= 0; --i) {
							if (b.checkRow(i)) {
								b.boardStatus.splice(i, 1);
								clearedLines += 1;
							}
						}
						if (clearedLines > 0) {
							b.incClearedLines(clearedLines);
						}
						b.fillBoard();
					},
					isEmpty: function(x, y) {
						return b.boardStatus[y][x].status == 0;
					}
				};

				return b;
			}();

			var Next = function() {
				var n = {
					nextPieces: [],
					// ---
					init: function() {
						if (n.nextPieces.length != 0) {
							return;
						}
						for (var i = 0; i < 6; ++i) {
							n.nextPieces.push(randPiece());
						}
					},
					getNext: function() {
						n.nextPieces.push(randPiece());
						return n.nextPieces.shift();
					},
					preview: function() {
						return n.nextPieces[0];
					}
				};
				return n;
			}();

			var CurrPiece = function() {
				var p = {
					currType: null,
					coords: [],
					_isNewPiece: false,
					action: null,
					rotation: 0,
					horizontalMovement: 0,
					verticalMovement: 0,
					rotationMovement: false,
					// ---
					set: function(type) {
						p.currType = piecesTypes[type];
						p.coords.length = 0;
						for (var i in p.currType.form) {
							var mods = p.currType.form[i];
							var newStartCoord = {
								x: startCoords.x + mods[0],
								y: startCoords.y + mods[1]
							};
							if (!Board.isEmpty(newStartCoord.x, newStartCoord.y)) {
								gameOver();
							}
							p.coords.push(newStartCoord);
							p.rotation = 0;
							p.rotationMovement = false;
						}
						Board.drawListCell(p.coords, p.currType.name);
					},
					setHorizontalMovement: function(val) {
						p.horizontalMovement = val;
					},
					setVerticalMovement: function(val) {
						p.verticalMovement = val;
					},
					setAction: function(action) {
						p.action = action;
					},
					doAction: function() {
						p.horizontalMovement = 0;
						p.verticalMovement = 0;
						switch (p.action) {
							case 'right':
								p.horizontalMovement = 1;
								break;
							case 'left':
								p.horizontalMovement = -1;
								break;
							case 'down':
								p.verticalMovement = 1;
								break;
							case 'up':
								p.rotationMovement = true;
								break;
							// case 'a':
							// 	break;
							// case 'd':
							// 	break;
						}
						p.action = null;
					},
					newPiece: function() {
						p.set(Next.getNext());
						p._isNewPiece = true;
					},
					isNewPiece: function() {
						if (p._isNewPiece) {
							p._isNewPiece = false;
							return true;
						}
						return p._isNewPiece;
					},
					outOfBoundaries: function(x, y) {
						return (x < 0 || y < 0 || x >= w || y >= h);
					},
					canMoveLeft: function() {
						var minX = Math.min(
							p.coords[0].x,
							p.coords[1].x,
							p.coords[2].x,
							p.coords[3].x
						);
						return minX > 0;
					},
					canMoveRight: function() {
						var maxX = Math.max(
							p.coords[0].x,
							p.coords[1].x,
							p.coords[2].x,
							p.coords[3].x
						);
						return maxX < (w - 1);
					},
					canMoveDown: function() {
						for (var i in p.coords) {
							var c = p.coords[i];
							if (c.y >= h - 1) {
								return false;
							}
							if (!Board.isEmpty(c.x, c.y + 1)) {
								return false;
							}
						}
						return true;
					},
					move: function() {
						// `p` is an alias of CurrPiece
						if (p.isNewPiece()) {
							return;
						}
						// drawListCell redraw a list of coords; the second parameters is the css
						// class to apply to the cell; if no class is passed, they are rendered as
						// empty cells
						Board.drawListCell(p.coords);
						var newCoords = [];
						var canMove = true;
						for (var i in p.coords) {
							// horizontalMovement and verticalMovement are set depending on pressed keys
							// and loop iterations; not every loop iteration move the piece down
							if (
									(p.horizontalMovement == -1 && !p.canMoveLeft())
									|| (p.horizontalMovement == 1 && !p.canMoveRight())
								) {
								p.horizontalMovement = 0;
							}
							var newCoord = {
								x: (p.coords[i].x + p.horizontalMovement)|0,
								y: (p.coords[i].y + p.verticalMovement)|0
							};
							// rotationMovement is set depending on pressed keys
							if (p.rotationMovement) {
								var rotation = p.currType.rotations[p.rotation][i];
								newCoord.x = newCoord.x + rotation[0];
								newCoord.y = newCoord.y + rotation[1];
							}
							if (p.outOfBoundaries(newCoord.x, newCoord.y) || !Board.isEmpty(newCoord.x, newCoord.y)) {
								canMove = false;
								break;
							}
							newCoords.push(newCoord);
						}

						// if we can move, we update CurrPiece coords with the newCoords
						if (canMove) {
							p.coords = newCoords;
							Board.drawListCell(p.coords, p.currType.name);
							if (p.rotationMovement) {
								p.rotation = (p.rotation + 1) % 4;
								p.rotationMovement = false;
							}
						// if not, we look if we can still move down
						} else if (p.canMoveDown()) {
							for (var i in p.coords) {
								p.coords[i].y = (p.coords[i].y + p.verticalMovement)|0;
							}
							Board.drawListCell(p.coords, p.currType.name);
							p.rotationMovement = false;
						// otherwise, CurrPiece has found its new home; we save the new Board status,
						// check if some rows are now completed and ask for a new CurrPiece
						} else {
							Board.setListStatus(Object.assign(p.coords), 1, p.currType.name);
							Board.checkRows();
							Board.redraw();
							p.newPiece();
						}
					}
				};
				return p;
			}();

			var microSteps = 6;
			var microCurrStep = 0;

			var actions = [ 'up', 'down', 'right', 'left', /*'a', 'd'*/ ];
			var createHandler = function(action) {
				return function() {
					CurrPiece.setAction(action);
				};
			};
			for (var i in actions) {
				var action = actions[i];
				jQuery(window).on('btn_' + action, createHandler(action));
			}

			var step = function() {
				Board.updateInfo();
				microCurrStep = (microCurrStep + 1) % microSteps;
				CurrPiece.doAction();
				if (microCurrStep == 0) {
					CurrPiece.setVerticalMovement(1);
				}
				CurrPiece.move();
			};

			$(function() {
				Board.init();
				Next.init();
				CurrPiece.newPiece();
				setupController();

				// game start
				var gameProcFunc = function() {
					if (gameProc) {
						step();
						setTimeout(function() {
							gameProcFunc();
						}, speed);
					}
				};
				gameProcFunc();
			});
		</script>
	</body>
</html>