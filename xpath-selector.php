<?php
if (!empty($_POST)) {

	$elementsWithTagName = array();

	foreach ($_POST as $key=>$value) {

		foreach($value as $keyValue => $valueValue) {
			
			$expKeyValue = explode("=", $keyValue);

			$elementAttribute = array();
			$elementAttribute['attributeName'] = $expKeyValue[0];
			$elementAttribute['attributeValue'] = $expKeyValue[1];

			$elementAttribute['attributeValue'] = str_replace("outline-element", "", $elementAttribute['attributeValue']);
			$elementAttribute['attributeValue'] = trim($elementAttribute['attributeValue']);

			if (empty($valueValue)) {
				$valueValue = $elementAttribute['attributeValue'];
			}

			if (empty($valueValue)) {
				continue;
			}

			$element = array();
			$element['tagName'] = str_replace("//","",$key);
			$element['functionName'] = ucfirst($valueValue);
			$element['attributes'] = $elementAttribute; 
			$elementsWithTagName[] = $element;
		}


	}

	$scraperSource = '<?php' . PHP_EOL;
	$scraperSource .= ''. PHP_EOL;
	$scraperSource .= 'class AutoGeneratedScraper {'. PHP_EOL;

	foreach($elementsWithTagName as $element) {
		$scraperSource .= ''. PHP_EOL;
		$scraperSource .= '	function get'.$element['functionName'].'($doc){'. PHP_EOL;
		$scraperSource .= '	'. PHP_EOL;
		$scraperSource .= '		$response = array();'. PHP_EOL;
		$scraperSource .= '		'. PHP_EOL;
		$scraperSource .= '		foreach($doc->getElementsByTagName("'.$element['tagName'] . '") as $' . $element['tagName'] . ') {' . PHP_EOL;

		
			$scraperSource .= '			if($'.$element['tagName'].'->getAttribute("'.$element['attributes']['attributeName'].'") == "'.$element['attributes']['attributeValue'].'") {'. PHP_EOL;

			if ($element['tagName'] == "img") {
				$scraperSource .= '				$response[] = $d->getAttribute("src");'. PHP_EOL;
			} else {
				$scraperSource .= '				$response = $d->nodeValue;'. PHP_EOL;
			}

			$scraperSource .= '			}'. PHP_EOL;
		

		$scraperSource .= '		}'. PHP_EOL;
		$scraperSource .= '		'. PHP_EOL;
		$scraperSource .= '		return $response;'. PHP_EOL;
		$scraperSource .= '	'. PHP_EOL;
		$scraperSource .= '	}'. PHP_EOL;
	}

	$scraperSource .= '}'. PHP_EOL;
	$scraperSource .= ''. PHP_EOL;
	$scraperSource .= '?>';

	highlight_string($scraperSource);
	// var_dump($elementsWithTagName);
	die();
}
?>
<iframe id="vendor-iframe" src="iframe.php?url=https://www.thetoyshop.com/collectibles/L-O-L-Surprise%21-Outfit-Of-The-Day-Advent-Calendar/p/534828"></iframe>

<form method="post">
<div id="scrap-box">
</div>
<button type="submit" id="generate-scraper">
	Generate Scraper
</button>
</form>

<style>
	#vendor-iframe {
		width:79%;
		height:100%;
		border: 1px solid #0000001a;
		float:left;
		background: #8a8a8a1a;
	}
	#scrap-box {
		overflow-y:scroll;
	    width: 20%;
	    height: 90%;
	    border: 1px solid #0000001a;
	    float: left;
	    background: #8a8a8a1a;
	}
	#generate-scraper {
		border: 3px solid #f3e923;
		background: #ecfe46;
		padding: 20px;
		width: 20%;
		height: 10%;
		float: left;
		font-size: 23px;
		color: #e66b28;
		cursor:pointer;
	}
	#generate-scraper:hover,active {
		background: #d9fe46;
	}
	.xpath-element {
		margin-top:5px;
		padding:2px;
	}
</style>

<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js' type='text/javascript'></script>
<script type="text/javascript">
var selectedElements = [];
$(document).ready(function(){

	var iframeDoc = document.getElementById('vendor-iframe').contentWindow;

	$(iframeDoc).mouseover(function(event){
		$(event.target).addClass('outline-element');		
	}).mouseout(function(event){
		$(event.target).removeClass('outline-element');		
	}).click(function(event){
		var selectedElement = {
			"xpath":getElementXPath(event.target),
			"html":$(event.target).html()
		};
		selectedElements.push(selectedElement);
		$(event.target).toggleClass('outline-element-clicked');

		// console.log(selectedElements);
		updateSelectedElements(selectedElements);
	});

});

function updateSelectedElements(selectedElements) {

	var html = '';

	$.each(selectedElements, function(k, v) {
		html += '<div class="xpath-element">' + v.xpath + '<br /><textarea>' + v.html + '</textarea>';
		html += '<br />Name: <input type="text" name="' + v.xpath + '">';
		html += '</div>';
	});

	$('#scrap-box').append(html);
}

function getElementXPath(element) {
	return "//" + $(element).andSelf().map(function(){
		var $this = $(this);
		var tagName = this.nodeName;
		var className = this.className;

		if (className.length > 0) {
			tagName += '[class=' + className + ']';
		}

		return tagName;
	}).get().join("/").toLowerCase();
}
</script>