<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FormSiteForm;

class FormSiteController extends Controller
{
    public function getFormsiteForms() 
    {
        $form_api = new FormSiteForm;
        var_dump($form_api->allForms());
    }
}
