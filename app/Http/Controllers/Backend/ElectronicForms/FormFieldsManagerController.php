<?php

namespace App\Http\Controllers\Backend\ElectronicForms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormFieldsManagerController extends Controller
{
    public function index(Request $request)
    {
        $formId = $request->form;
        return view('content.Backend.ElectronicForms.form-fields-manager', compact('formId'));
    }
}
