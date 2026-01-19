<?php

namespace App\Http\Controllers\Backend\Employments;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Backend\ElectronicForms\ElectronicForms;

class EmploymentsController extends Controller
{
    public function index()
    {
        return view('content.Backend.Employments.index');
    }

    public function showForm($formSlug)
    {
        $form = ElectronicForms::where('slug', $formSlug)->firstOrFail();
        return view('content.Backend.ElectronicForms.show', compact('form'));
    }

    public function submitResponse()
    {
        
    }
}
