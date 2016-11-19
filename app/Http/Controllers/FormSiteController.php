<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FormSiteForm;
use App\Applicant;
use Carbon\Carbon;
use Exception;

class FormSiteController extends Controller
{
    public function getFormsiteForms(){
        $form_api = new FormSiteForm;
        dd($form_api->allForms());
    }

    public function getNewHireResults(){
        ini_set('max_execution_time', 600);
        $i = 0;
        $maxApplicationId = Applicant::getMaxApplicationId() + 1;
        $pageNum = 1;
        $form_api = new FormSiteForm;
        $parameters = ['fs_min_id'=>$maxApplicationId];
        do{
            $parameters['fs_page'] = $pageNum;
            $xmlDoc = $form_api->getFormResults('form18', $parameters);
            $status = $xmlDoc->firstChild->getAttribute("status");
            if($status == "failure") {
                // get error message
                $error = $xmlDoc->firstChild->nodeValue;
                die($error);
            }
            $resultLength = $xmlDoc->getElementsByTagName("result")->length;
            if($resultLength > 0) {
                $this->mapFormResults($xmlDoc);
                $i++;
            }
            $pageNum++;
        }while($resultLength > 0);
        return $i . "new hire applications were successfully inserted";
    }
    private function mapFormResults($xmlDoc){
        try{
            $results = $xmlDoc->getElementsByTagName("result");
            foreach($results as $result){
                // map each result
                $metas = $result->getElementsByTagName("meta");
                $items = $result->getElementsByTagName("item");
                $result_status = $this->getElementValueByAttribute($metas, "id", "result_status");
                if($result_status != "Complete"){
                    continue;
                }
                $applicant = new Applicant;
                $applicant->applicationId = $result->getAttribute("id");
                $applicant->firstName = $this->getElementValuesByAttribute($items, "id", "1");
                $applicant->middleName = $this->getElementValuesByAttribute($items, "id", "4");
                $applicant->lastName = $this->getElementValuesByAttribute($items, "id", "2");
                $applicant->gender = $this->getElementValuesByAttribute($items, "id", "23");
                $strDateOfBirth = $this->getElementValuesByAttribute($items, "id", "165");
                $dateOfBirth = isset($strDateOfBirth) ? Carbon::createFromFormat('m/d/Y', $strDateOfBirth) : null;
                $applicant->dateOfBirth = $dateOfBirth;
                $applicant->maritalStatus = $this->getElementValuesByAttribute($items, "id", "25");
                $applicant->race = $this->getElementValuesByAttribute($items, "id", "24");
                $applicant->ssn = $this->getElementValuesByAttribute($items, "id", "12");
                $applicant->email = $this->getElementValuesByAttribute($items, "id", "21");
                $applicant->addressLine1 = $this->getElementValuesByAttribute($items, "id", "15");
                $applicant->addressLine2 = $this->getElementValuesByAttribute($items, "id", "16");
                $applicant->city = $this->getElementValuesByAttribute($items, "id", "17");
                $applicant->state = $this->getElementValuesByAttribute($items, "id", "18");
                $applicant->zipCode = $this->getElementValuesByAttribute($items, "id", "19");
                $applicant->location = $this->getElementValuesByAttribute($items, "id", "113");
                $applicant->phoneNumber = $this->getElementValuesByAttribute($items, "id", "20");
                $applicant->bankName = $this->getElementValuesByAttribute($items, "id", "139");
                $applicant->accountType = $this->getElementValuesByAttribute($items, "id", "145");
                $applicant->routingNumber = $this->getElementValuesByAttribute($items, "id", "140");
                $applicant->accountNumber = $this->getElementValuesByAttribute($items, "id", "143");
                $applicant->employeeNumber = $this->getElementValuesByAttribute($items, "id", "166");
                $applicant->supervisor = $this->getElementValuesByAttribute($items, "id", "115");
                $strHireDate = $this->getElementValuesByAttribute($items, "id", "248");
                $hireDate = isset($strHireDate) ? Carbon::createFromFormat('m/d/Y', $strHireDate) : null;
                $applicant->hireDate = $hireDate;
                $applicant->fullOrPartTime = $this->getElementValuesByAttribute($items, "id", "173");
                $applicant->compType = $this->getElementValuesByAttribute($items, "id", "172");
                $strPayRate = $this->getElementValuesByAttribute($items, "id", "170");
                $payRate = is_numeric($strPayRate) ? $strPayRate : 0.00;
                $applicant->payRate = $payRate;
                $applicant->save();
            }
        }catch(Exception $ex){
            die($ex->getMessage());
        }
    }
    private function getElementValuesByAttribute($xmlDoc, $attribute, $value, $multiple=false){
        $values = [];
        for ($i=0; $i < $xmlDoc->length; $i++) {
            $item = $xmlDoc->item($i);
            $attr = $item->getAttribute($attribute);
            if ($attr == $value) {
                $nodeValues = $item->getElementsByTagName("value");
                foreach($nodeValues as $value){
                    $values[] = $value->nodeValue;
                }
                if($multiple){
                    return $values;
                }else{
                    return (isset($values[0]) ? $values[0] : null);
                }
            }
        }
    }
    private function getElementValueByAttribute($xmlDoc, $attribute, $value){
        for ($i=0; $i < $xmlDoc->length; $i++) {
            $item = $xmlDoc->item($i);
            $attr = $item->getAttribute($attribute);
            if ($attr == $value) {
                return $item->nodeValue;
            }
        }
        return null;
    }

    // private function outputFormResults($xmlDoc)
    // {
    //     // get headings
    //     $headings = $xmlDoc->getElementsByTagName("heading");
    //     foreach($headings as $heading) {
    //             // store headings indexed by id
    //             $headingMap[$heading->getAttribute("for")] = $heading->nodeValue;
    //             echo "</br>".$heading->getAttribute("for")." > ".$heading->nodeValue;
    //     }
    //     $results = $xmlDoc->getElementsByTagName("result");
	// 	// output each result
	// 	foreach($results as $result) {
	// 		$alt = false;
		
	// 		$metas = $result->getElementsByTagName("meta");
	// 		$items = $result->getElementsByTagName("item");
			
	// 		echo("Result #".$result->getAttribute("id"));
	// 		echo('<table style="border: 1px solid black;border-collapse: collapse">\n');
	// 		echo('<tr><th style="border: 1px solid black;">id</th><th style="border: 1px solid black;">value</th></tr>\n');
	// 		echo('<tr><th style="border: 1px solid black;" colspan="2">metas</th></tr>\n');
	// 		foreach($metas as $meta) {
	// 			$alt = !$alt;
				
	// 			echo("<tr".($alt ? ' class="alt"' : '').">"); // add alt class to every other line
	// 			echo('<td style="border: 1px solid black;">');
	// 			if(isset($headingMap)) {
	// 				echo($headingMap[$meta->getAttribute("id")]); // lookup heading
	// 			} else {
	// 				echo($meta->getAttribute("id"));
	// 			}
	// 			echo("</td>");
	// 			echo('<td style="border: 1px solid black;">');
	// 			echo($meta->nodeValue);
	// 			echo("</td>");
	// 			echo("</tr>\n");
	// 		}
	// 		echo('<tr><th style="border: 1px solid black;" colspan="2">items</th></tr>\n');
	// 		foreach($items as $item) {
	// 			$alt = !$alt;
				
	// 			echo("<tr".($alt ? ' class="alt"' : '').">"); // add alt class to every other line
	// 			echo('<td style="border: 1px solid black;">');
	// 			if(isset($headingMap)) {
	// 				echo($headingMap[$item->getAttribute("id")]); // lookup heading
	// 			} else {
	// 				echo($item->getAttribute("id"));
	// 			}
	// 			echo("</td>");
				
	// 			$values = $item->getElementsByTagName("value");
				
	// 			echo('<td style="border: 1px solid black;">');
	// 			foreach($values as $value) {
	// 				echo($value->nodeValue);
	// 				echo(" ");
	// 			}
	// 			echo("</td>");
				
	// 			echo("</tr>\n");
	// 		}
			
	// 		echo("</table>\n");
	// 		echo("<br/>");
	// 	}
    // }
}
