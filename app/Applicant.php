<?php

namespace App;

class Applicant
{   
    public function toUltiproArray(){
        return [
            "JobCode"=>"UNDEFND",//default
            "EmployeeTypeCode"=>"REG",
            "NameFirst"=>$this->firstName,
            "NameMiddle"=>empty($this->middleName) ? null : $this->middleName,
            "NameLast"=>$this->lastName,
            "NamePreferred"=> empty($this->preferredName) ? null : $this->preferredName,
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
            "OrgLevel1Code"=>$this->getOrgLevel1Code(),
            "OrgLevel2Code"=>$this->getOrgLevel2Code(),
            "HomePhoneNumber"=>empty($this->phoneNumber) ? null : str_replace("-", "", $this->phoneNumber),
            "EmployeeNumber"=>empty($this->employeeNumber) ? null : $this->employeeNumber,
            "StartDate"=>empty($this->hireDate) ? null : $this->hireDate->format("Y-m-d"),
            "FullTimeOrPartTimeCode"=>empty($this->fullOrPartTime) ? null : (trim($this->fullOrPartTime) == "Full Time" ? "F" : "P"),
            "HourlyOrSalaryCode"=>$this->getHourlyOrSalaryCode(),
            "PayRate"=>empty($this->payRate) ? null : $this->payRate,
            "EarningsGroupCode"=>"ALL",
            "ScheduledWorkHours"=>null
        ];
    }
    private function getHourlyOrSalaryCode(){
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
    private function getLocationCode() {
        $mappedCode = $this->mapLocationCode();
        if (is_null($mappedCode)) {
            $location = trim(strtoupper($this->location));
            $locationCodes = [
                "FP",
                "JEF",
                "AUST",
                "NOR",
                "MCD",
                "PDC",
                "BYRON",
                "SELECT",
                "SVC",
                "BHAM",
                "TUSC",
                "FL"
            ];

            if (in_array($location, $locationCodes)) {
                return $location;
            }
        }

        return $mappedCode;
    }
    private function mapLocationCode(){
        switch(trim(strtolower($this->location))){
            case "forest park": 
                return "FP";
            case "jefferson": 
                return "JEF";
            case "austell": 
                return "AUST";
            case "norcross":
                return "NOR";
            case "mcdonough": 
                return "MCD";
            case "pdc": 
                return "PDC";
            case "byron":
                return "BYRON";
            case "select trucks":
                return "SELECT";
            case "sv center": 
                return "SVC";
            case "birmingham":
                return "BHAM";
            case "tuscaloosa":
                return "TUSC";
            case "florida":
                return "FL";
            default:
                return null;
        }
    }
    private function getOrgLevel1Code(){
        switch(trim(strtolower($this->location))){
            case "forest park": 
                return "PSFL";
            case "jefferson": 
                return "PSFL";
            case "sv center": 
                return "PSFL";
            case "austell": 
                return "PSFL";
            case "norcross":
                return "PSFL";
            case "mcdonough": 
                return "PSFL";
            case "pdc": 
                return "PSFL";
            case "byron":
                return "PSFL";
            case "select trucks":
                return "SEL";
            case "birmingham":
                return "BFL";
            case "tuscaloosa":
                return "BFL";
            default:
                return null;
        }
    }
    private function getOrgLevel2Code() {
        switch(trim(strtolower($this->orgLevel2))){
            case "parts":
                return "PARTS";
            case "service":
                return "SERVP";
            case "service admin":
                return "SERVA"; 
            case "body shop": 
                return "BODP";
            case "body shop admin":
                return "BODA";
            case "administration":
                return "ADMIN"; 
            case "new trucks sales":
                return "NTS";
            case "new trucks sales admin":
                return "NTSA";
            case "used trucks sales":
                return "UTS";
            case "used trucks sales admin":
                return "UTSA";
            default:
                return null;
        }
    }
}
