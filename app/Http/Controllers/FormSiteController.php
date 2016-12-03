<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FormSiteForm;
use App\Applicant;
use App\ResultLog;
use App\Ultipro;
use Carbon\Carbon;
use Exception;

class FormSiteController extends Controller
{
    private $applicants;
    private $logs;
    public function getFormsiteForms(){
        $form_api = new FormSiteForm;
        dd($form_api->allForms());
    }

    public function getNewHireResults(){
        ini_set('max_execution_time', 600);
        $this->applicants = 0;
        $maxApplicationId = ResultLog::getMaxApplicationId() + 1;
        $form_api = new FormSiteForm;
        $parameters = ['fs_min_id'=>'9401306'];//$maxApplicationId];
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
            // $this->outputFormResults($xmlDoc); exit;
        }
        return $this->applicants . " application(s) successfully inserted to Ultipro.";
    }
    private function mapFormResults($xmlDoc){
        try{
            $results = $xmlDoc->getElementsByTagName("result");
            $ultipro = new Ultipro();
            foreach($results as $result){
                // map each result
                $metas = $result->getElementsByTagName("meta");
                $result_status = $this->getElementValueByAttribute($metas, "id", "result_status");
                $applicant = $this->getApplicant($result);

                //map the result log retrieved from formsite
                $log = new ResultLog;
                $log->applicationId = $applicant->applicationId;
                $log->firstName = $applicant->firstName;
                $log->lastName = $applicant->lastName;
                $log->description = "Application Status: {$result_status}. Application retrieved from Formsite.";
                $log->save();
                
                //skip if the result is not complete
                if($result_status != "Complete"){
                    continue;
                }
                //send applicant to Ultipro
                $ultiproArray = $applicant->toUltiproArray();
                // dd($ultiproArray);
                $response = $ultipro->sendResult($ultiproArray);
                $responseCode = $response->getStatusCode();
                
                //map the result log sent to ultipro
                $log = new ResultLog;
                $log->applicationId = $applicant->applicationId;
                $log->firstName = $applicant->firstName;
                $log->lastName = $applicant->lastName;
                if($responseCode == 201){
                    $log->description = "Application successfully sent to Ultipro.";
                    $this->applicants++;
                }else{
                    $log->description = "Application NOT sent to Ultipro. Response Code: {$responseCode}. Response Body: {$response->getBody()}";
                }
                $log->save();
                
            }
        }catch(Exception $ex){
            die($ex->getMessage());
        }
    }
    private function getApplicant($result){
        $items = $result->getElementsByTagName("item");
        $applicant = new Applicant;
        $applicant->applicationId = $result->getAttribute("id");
        $applicant->firstName = $this->properCase($this->getElementValuesByAttribute($items, "id", "1"));
        $applicant->middleName = $this->properCase($this->getElementValuesByAttribute($items, "id", "4"));
        $applicant->lastName = $this->properCase($this->getElementValuesByAttribute($items, "id", "2"));
        $applicant->preferredName = $this->properCase($this->getElementValuesByAttribute($items, "id", "301"));
        $applicant->gender = $this->getElementValuesByAttribute($items, "id", "23");
        $strDateOfBirth = $this->getElementValuesByAttribute($items, "id", "165");
        $dateOfBirth = isset($strDateOfBirth) ? Carbon::createFromFormat('m/d/Y', $strDateOfBirth) : null;
        $applicant->dateOfBirth = $dateOfBirth;
        $applicant->maritalStatus = $this->getElementValuesByAttribute($items, "id", "25");
        $applicant->race = $this->getElementValuesByAttribute($items, "id", "24");
        $applicant->ssn = $this->getElementValuesByAttribute($items, "id", "12");
        $applicant->email = $this->getElementValuesByAttribute($items, "id", "21");
        $applicant->addressLine1 = $this->properCase($this->getElementValuesByAttribute($items, "id", "15"));
        $applicant->addressLine2 = $this->properCase($this->getElementValuesByAttribute($items, "id", "16"));
        $applicant->city = $this->properCase($this->getElementValuesByAttribute($items, "id", "17"));
        $applicant->state = $this->properCase($this->getElementValuesByAttribute($items, "id", "18"));
        $applicant->zipCode = $this->getElementValuesByAttribute($items, "id", "19");
        $applicant->location = $this->getElementValuesByAttribute($items, "id", "312");
        $applicant->phoneNumber = $this->getElementValuesByAttribute($items, "id", "20");
        $applicant->employeeNumber = $this->getElementValuesByAttribute($items, "id", "166");
        $applicant->supervisor = $this->properCase($this->getElementValuesByAttribute($items, "id", "115"));
        $applicant->orgLevel2 = $this->properCase($this->getElementValuesByAttribute($items, "id", "313"));
        $strHireDate = $this->getElementValuesByAttribute($items, "id", "248");
        $hireDate = isset($strHireDate) ? Carbon::createFromFormat('m/d/Y', $strHireDate) : null;
        $applicant->hireDate = $hireDate;
        $applicant->fullOrPartTime = $this->getElementValuesByAttribute($items, "id", "173");
        $applicant->compType = $this->getElementValuesByAttribute($items, "id", "172");
        $strPayRate = $this->getElementValuesByAttribute($items, "id", "170");
        $payRate = is_numeric($strPayRate) ? $strPayRate : 0.00;
        $applicant->payRate = $payRate;
        // dd($applicant);
        return $applicant;
    }
    private function properCase($str){
        return ucwords(strtolower($str));
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

    private function outputFormResults($xmlDoc)
    {
        // get headings
        $headings = $xmlDoc->getElementsByTagName("heading");
        foreach($headings as $heading) {
                // store headings indexed by id
                $headingMap[$heading->getAttribute("for")] = $heading->nodeValue;
                echo "</br>".$heading->getAttribute("for")." > ".$heading->nodeValue;
        }
        $results = $xmlDoc->getElementsByTagName("result");
		// output each result
		foreach($results as $result) {
			$alt = false;
		
			$metas = $result->getElementsByTagName("meta");
			$items = $result->getElementsByTagName("item");
			
			echo("Result #".$result->getAttribute("id"));
			echo('<table style="border: 1px solid black;border-collapse: collapse">\n');
			echo('<tr><th style="border: 1px solid black;">id</th><th style="border: 1px solid black;">value</th></tr>\n');
			echo('<tr><th style="border: 1px solid black;" colspan="2">metas</th></tr>\n');
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
			echo('<tr><th style="border: 1px solid black;" colspan="2">items</th></tr>\n');
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
