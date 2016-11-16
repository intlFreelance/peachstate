<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FormSiteForm;

class FormSiteController extends Controller
{
    public function getFormsiteForms() 
    {
        $form_api = new FormSiteForm;
        dd($form_api->allForms());
    }

    public function getNewHireResults()
    {
        $pageNum = 1;
        $form_api = new FormSiteForm;
        $xmlDoc = $form_api->getFormResults('form18', $pageNum);
        
        // check response status
		if($xmlDoc->firstChild->getAttribute("status") == "failure") {
			// get error message
			$error = $xmlDoc->firstChild->nodeValue;
			die($error);
		}
        echo '<h1>Page ' . $pageNum . '</h1>';
        $this->outputFormResults($xmlDoc);

        while($xmlDoc->getElementsByTagName("result")->length > 0) {
            $pageNum++;
            $xmlDoc = $form_api->getFormResults('form18', $pageNum);
            if($xmlDoc->getElementsByTagName("result")->length > 0) {
                echo '<h1>Page ' . $pageNum . '</h1>';
                $this->outputFormResults($xmlDoc);
            }
        }
	}

    private function outputFormResults($xmlDoc)
    {
        // get headings
		$headings = $xmlDoc->getElementsByTagName("heading");
		foreach($headings as $heading) {
			// store headings indexed by id
			$headingMap[$heading->getAttribute("for")] = $heading->nodeValue;
		}
		
        $results = $xmlDoc->getElementsByTagName("result");
		// output each result
		foreach($results as $result) {
			$alt = false;
		
			$metas = $result->getElementsByTagName("meta");
			$items = $result->getElementsByTagName("item");
			
			echo("Result #".$result->getAttribute("id"));
			echo('<table style="border: 1px solid black;
    border-collapse: collapse">\n');
			echo('<tr><th style="border: 1px solid black;">id</th><th style="border: 1px solid black;">value</th></tr>\n');
			
			foreach($metas as $meta) {
				$alt = !$alt;
				
				echo("<tr".($alt ? ' class="alt"' : '').">"); // add alt class to every other line
				echo('<td style="border: 1px solid black;">');
				if(isset($headingMap)) {
					echo($headingMap[$meta->getAttribute("id")]); // lookup heading
				} else {
					echo($meta->getAttribute("id"));
				}
				echo("</td>");
				echo('<td style="border: 1px solid black;">');
				echo($meta->nodeValue);
				echo("</td>");
				echo("</tr>\n");
			}
			
			foreach($items as $item) {
				$alt = !$alt;
				
				echo("<tr".($alt ? ' class="alt"' : '').">"); // add alt class to every other line
				echo('<td style="border: 1px solid black;">');
				if(isset($headingMap)) {
					echo($headingMap[$item->getAttribute("id")]); // lookup heading
				} else {
					echo($item->getAttribute("id"));
				}
				echo("</td>");
				
				$values = $item->getElementsByTagName("value");
				
				echo('<td style="border: 1px solid black;">');
				foreach($values as $value) {
					echo($value->nodeValue);
					echo(" ");
				}
				echo("</td>");
				
				echo("</tr>\n");
			}
			
			echo("</table>\n");
			echo("<br/>");
		}
    }
}
