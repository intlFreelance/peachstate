<?php

namespace App;

class Applicant
{   
    public function toUltiproArray(){
        return [
            "JobCode"=>"UNDEFND",//default
            "NameFirst"=>$this->firstName,
            "NameMiddle"=>empty($this->middleName) ? null : $this->middleName,
            "NameLast"=>$this->lastName,
            "GenderCode" => empty($this->gender) ? null : ($this->gender=="Male"? "M" : "F") ,
            "DateOfBirth"=> empty($this->dateOfBirth) ? null : $this->dateOfBirth->format("Y-m-d"),
            "MaritalStatusCode"=> empty($this->maritalStatus) ? null : substr($this->maritalStatus, 0, 1),
            "EthnicCode"=>$this->getEthnicCode(),
            "SsnOrSin"=>empty($this->ssn) ? null : str_replace("-", "", $this->ssn),
            "PrimaryEmailAddress"=>empty($this->email) ? null : $this->email,
            "AddressLine1" => $this->addressLine1,
            "AddressLine2"=> empty($this->addressLine2) ? null : $this->addressLine2,
            "City"=>empty($this->city) ? null : $this->city,
            "StateOrProvinceCode"=>$this->getStateCode(),
            "ZipOrPostalCode"=>empty($this->zipCode) ? null : $this->zipCode,
            "LocationCode"=>$this->getLocationCode(),
            "HomePhoneNumber"=>empty($this->phoneNumber) ? null : str_replace("-", "", $this->phoneNumber),
            "EmployeeNumber"=>empty($this->employeeNumber) ? null : $this->employeeNumber,
            "StartDate"=>empty($this->hireDate) ? null : $this->hireDate->format("Y-m-d"),
            "FullTimeOrPartTimeCode"=>empty($this->fullOrPartTime) ? null : (trim($this->fullOrPartTime) == "Full Time" ? "FT" : "PT"),
            "HourlyOrSalaryCode"=>$this->getHourlyOrSalaryCode(),
            "PayRate"=>empty($this->payRate) ? null : $this->payRate
        ];
    }private function getHourlyOrSalaryCode(){
        switch(trim($this->compType)){
            case "Hourly":
                return "H";
            case "Salary":
                return "S";
            default:
                return null;
        }
    }
    private function getEthnicCode(){
        switch($this->race){
            case "American Indian/Alaskan Native":
                return "5";
            case "Asian":
                return "6";
            case "Black or African American":
                return "2";
            case "Hispanic or Latino":
                return "3";
            case "Native Hawaiian or Other Pacific Islander":
                return "7";
            case "Two or More Races":
                return "8";
            case "White":
                return "1";
            default:
                return null;
        }
    }
    private function getStateCode(){
        $states = array(
        'Alabama'=>'AL',
        'Alaska'=>'AK',
        'Arizona'=>'AZ',
        'Arkansas'=>'AR',
        'California'=>'CA',
        'Colorado'=>'CO',
        'Connecticut'=>'CT',
        'Delaware'=>'DE',
        'Florida'=>'FL',
        'Georgia'=>'GA',
        'Hawaii'=>'HI',
        'Idaho'=>'ID',
        'Illinois'=>'IL',
        'Indiana'=>'IN',
        'Iowa'=>'IA',
        'Kansas'=>'KS',
        'Kentucky'=>'KY',
        'Louisiana'=>'LA',
        'Maine'=>'ME',
        'Maryland'=>'MD',
        'Massachusetts'=>'MA',
        'Michigan'=>'MI',
        'Minnesota'=>'MN',
        'Mississippi'=>'MS',
        'Missouri'=>'MO',
        'Montana'=>'MT',
        'Nebraska'=>'NE',
        'Nevada'=>'NV',
        'New Hampshire'=>'NH',
        'New Jersey'=>'NJ',
        'New Mexico'=>'NM',
        'New York'=>'NY',
        'North Carolina'=>'NC',
        'North Dakota'=>'ND',
        'Ohio'=>'OH',
        'Oklahoma'=>'OK',
        'Oregon'=>'OR',
        'Pennsylvania'=>'PA',
        'Rhode Island'=>'RI',
        'South Carolina'=>'SC',
        'South Dakota'=>'SD',
        'Tennessee'=>'TN',
        'Texas'=>'TX',
        'Utah'=>'UT',
        'Vermont'=>'VT',
        'Virginia'=>'VA',
        'Washington'=>'WA',
        'West Virginia'=>'WV',
        'Wisconsin'=>'WI',
        'Wyoming'=>'WY'
        );
        return empty($states[$this->state]) ? null : $states[$this->state];
    }
    private function getLocationCode(){
        switch(trim($this->location)){
            case "Forest Park": 
            case "Jefferson": 
            case "SV Center": 
            case "Austell": 
            case "Norcross":
            case "McDonough": 
            case "PDC": 
            case "Byron":
                return "PSFL";
            case "Select Trucks":
                return "SEL";
            case "Birmingham":
            case "Tuscaloosa":
                return "BFL";
            default:
                return null;
        }
    }
}
