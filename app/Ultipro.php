<?php

namespace App;

use Exception;
use SoapVar;
use Artisaninweb\SoapWrapper\Service;

class Ultipro
{
    protected $name = 'ultipro';
    protected $trace = true;
    protected $options = [
        'soap_version'=>SOAP_1_2,
        'trace'=>true
    ];
    private $token;
    private $base_url;
    private $service;
    public function __construct(){
        $this->authenticate();
    }
    private function authenticate(){
        $service = new Service();
        if(empty(config('ultipro.login_wsdl'))){
            throw new Exception("The variable 'ultipro.login_wsdl' must be set.");
        }
        if(empty(config('ultipro.login_header'))){
             throw new Exception("The configuration 'ultipro.login_header' must be set.");
         }
         if(empty(config('ultipro.base_url'))){
             throw new Exception("The configuration 'ultipro.base_url' must be set.");
         }
        $this->base_url = config('ultipro.base_url');
        $service->wsdl(config('ultipro.login_wsdl'));
        $service->options($this->options);
        $service->createClient();
        $service->header('http://www.w3.org/2005/08/addressing','Action', $this->base_url.'/loginservice/ILoginService/Authenticate', true);
        foreach(config('ultipro.login_header') as $key => $value){
            $service->header($this->base_url.'/loginservice',$key, $value);
        }
        $response = $service->call('Authenticate',[]);
        if($response->Status != "Ok"){
            throw new Exception($response->StatusMessage);
        }
        $this->token = $response->Token;
    }
    public function sendResult($newHire){
        ini_set('max_execution_time', 600);
        if(empty(config('ultipro.newHire_wsdl'))){
            throw new Exception("The variable 'ultipro.newHire_wsdl' must be set.");
        }
        $service = new Service();
        $login_header = config('ultipro.login_header');
        $service->wsdl(config('ultipro.newHire_wsdl'));
        $service->options($this->options);
        $service->createClient();
        $service->header('http://www.w3.org/2005/08/addressing','Action', $this->base_url.'/employeenewhire/IEmployeeNewHire/NewHireUsa', true);
        $service->header('http://www.ultimatesoftware.com/foundation/authentication/ultiprotoken', 'UltiProToken', $this->token);
        $service->header('http://www.ultimatesoftware.com/foundation/authentication/clientaccesskey','ClientAccessKey',$login_header["ClientAccessKey"]);
        echo var_dump($service->getFunctions());
        $newHireXML = $this->getNewHireXML($newHire);
        $e = new SoapVar($newHireXML, XSD_ANYXML);
        $response = $service->call('NewHireUsa',[$e]);
        echo htmlentities($service->getLastRequest());
        echo "<br><br>";
        dd($response);
        //return $response;
    }
    
    private function getNewHireXML($newHire){
        $xml = '<NewHireUsa xmlns="http://www.ultipro.com/services/employeenewhire">
            <entities xmlns:b="http://www.ultipro.com/contracts" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
                <b:Employee>
                    <b:AddressLine1>'.$newHire["AddressLine1"].'</b:AddressLine1>
                    <b:AddressLine2>'.$newHire["AddressLine2"].'</b:AddressLine2>
                    <b:AlternateEmailAddress/>
                    <b:AlternateTitle/>
                    <b:BenefitSeniorityDate>2017-09-16T00:00:00</b:BenefitSeniorityDate>
                    <b:BirthDate>1993-03-24T00:00:00</b:BirthDate>
                    <b:City>Piermont</b:City>
                    <b:CompanyIdentifier i:type="b:CompanyCodeIdentifier">
                      <b:CompanyCode>PSTC</b:CompanyCode>
                    </b:CompanyIdentifier>
                    <b:Country>USA</b:Country>
                    <b:County/>
                    <b:DeductionBenefitGroup>NONE</b:DeductionBenefitGroup>
                    <b:DirectDeposits>
                      <b:DirectDeposit>
                        <b:AccountIsActive>1</b:AccountIsActive>
                        <b:AccountNumber>999999999</b:AccountNumber>
                        <b:AccountType>C</b:AccountType>
                        <b:AmountRule>P</b:AmountRule>
                        <b:BankName>Wells Fargo</b:BankName>
                        <b:FlatOrPercentAmount>0.50</b:FlatOrPercentAmount>
                        <b:RoutingNumber>122100024</b:RoutingNumber>
                      </b:DirectDeposit>
                    </b:DirectDeposits>
                    <b:DistributionCenterCode>JEFFCD</b:DistributionCenterCode>
                    <b:EarningsGroup>ALL</b:EarningsGroup>
                    <b:EmailAddress/>
                    <b:EmployeeNumber>59978</b:EmployeeNumber>
                    <b:EmployeeType>REG</b:EmployeeType>
                    <b:EthnicOrigin>1</b:EthnicOrigin>
                    <b:FederalAdditionalAmountWithheld>0</b:FederalAdditionalAmountWithheld>
                    <b:FederalEmployeeClaimsExempt>false</b:FederalEmployeeClaimsExempt>
                    <b:FederalFilingStatus>m</b:FederalFilingStatus>
                    <b:FederalSubjectToBackupWithholding>false</b:FederalSubjectToBackupWithholding>
                    <b:FederalTotalAllowancesClaimed>0</b:FederalTotalAllowancesClaimed>
                    <b:FederalW2Pension>false</b:FederalW2Pension>
                    <b:FirstName>Test</b:FirstName>
                    <b:FormerLastName/>
                    <b:FullOrPartTime>f</b:FullOrPartTime>
                    <b:Gender>f</b:Gender>
                    <b:HireDate>2016-11-20T00:00:00</b:HireDate>
                    <b:HireSource/>
                    <b:HomePhone/>
                    <b:HourlyOrSalaried>h</b:HourlyOrSalaried>
                    <b:I9Verification/>
                    <b:JobCode>UNDEFND</b:JobCode>
                    <b:JobGroup/>
                    <b:LastName>Testington</b:LastName>
                    <b:LocalWorkInTaxResidentStatus/>
                    <b:LocationCode>FL</b:LocationCode>
                    <b:MailStop/>
                    <b:MaritalStatus/>
                    <b:MiddleName/>
                    <b:NextPerformanceReviewDate>0001-01-01T00:00:00</b:NextPerformanceReviewDate>
                    <b:NextSalaryReviewDate>0001-01-01T00:00:00</b:NextSalaryReviewDate>
                    <b:OrgLevel1/>
                    <b:OrgLevel2/>
                    <b:OrgLevel3/>
                    <b:OrgLevel4/>
                    <b:OtherPhone/>
                    <b:OtherPhoneExtension/>
                    <b:OtherPhoneType/>
                    <b:OtherRate1 i:nil="true"/>
                    <b:OtherRate2 i:nil="true"/>
                    <b:OtherRate3 i:nil="true"/>
                    <b:OtherRate4 i:nil="true"/>
                    <b:PayAutomatically>false</b:PayAutomatically>
                    <b:PayGroup>BIWKLY</b:PayGroup>
                    <b:PayRate>0</b:PayRate>
                    <b:PayRateType>H</b:PayRateType>
                    <b:PayScaleCode/>
                    <b:PreferredFirstName/>
                    <b:Prefix/>
                    <b:Project/>
                    <b:ResidentCounty/>
                    <b:ResidentJurisdiction/>
                    <b:ResidentStateAdditionalAllowances>0</b:ResidentStateAdditionalAllowances>
                    <b:ResidentStateAdditionalAmountWithheld>0</b:ResidentStateAdditionalAmountWithheld>
                    <b:ResidentStateEmployeeClaimsExempt>false</b:ResidentStateEmployeeClaimsExempt>
                    <b:ResidentStateFilingStatus>M</b:ResidentStateFilingStatus>
                    <b:ResidentStateTotalAllowancesClaimed>0</b:ResidentStateTotalAllowancesClaimed>
                    <b:SSN>123458771</b:SSN>
                    <b:ScheduledHours>0</b:ScheduledHours>
                    <b:SelfServiceProperties xmlns:c="http://schemas.microsoft.com/2003/10/Serialization/Arrays">
                      <c:KeyValueOfstringstring>
                        <c:Key/>
                        <c:Value/>
                      </c:KeyValueOfstringstring>
                    </b:SelfServiceProperties>
                    <b:SeniorityDate>2015-09-16T00:00:00</b:SeniorityDate>
                    <b:ShiftCode/>
                    <b:ShiftGroup/>
                    <b:StateGeographicCode/>
                    <b:StateOccupationalCode>11-2021</b:StateOccupationalCode>
                    <b:StateOrProvince>GA</b:StateOrProvince>
                    <b:StepNo i:nil="true"/>
                    <b:Suffix/>
                    <b:Supervisor i:type="b:SsnIdentifier">
                      <b:CompanyCode>PSTC</b:CompanyCode>
                      <b:Ssn>999999991</b:Ssn>
                    </b:Supervisor>
                    <b:TimeClock/>
                    <b:UnionLocal/>
                    <b:UnionNational/>
                    <b:WorkExtension/>
                    <b:WorkPhone/>
                    <b:WorkStateAdditionalAllowances>0</b:WorkStateAdditionalAllowances>
                    <b:WorkStateAdditionalAmountWithheld>0</b:WorkStateAdditionalAmountWithheld>
                    <b:WorkStateDisabilityPlanType/>
                    <b:WorkStateEmployeeClaimsExempt>false</b:WorkStateEmployeeClaimsExempt>
                    <b:WorkStateFilingStatus>M</b:WorkStateFilingStatus>
                    <b:WorkStatePlan/>
                    <b:WorkStateTotalAllowancesClaimed>0</b:WorkStateTotalAllowancesClaimed>
                    <b:ZipOrPostalCode>33172</b:ZipOrPostalCode>
                </b:Employee>
            </entities>
        </NewHireUsa>';
        return $xml;
    }
}