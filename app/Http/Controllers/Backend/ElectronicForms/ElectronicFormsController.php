<?php

namespace App\Http\Controllers\Backend\ElectronicForms;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use App\Models\Backend\ElectronicForms\ElectronicForms;
use App\Livewire\Backend\ElectronicForms\ElectronicForm;

class ElectronicFormsController extends Controller
{
    public function index()
    {
        return view('content.Backend.ElectronicForms.index');
    }
}
