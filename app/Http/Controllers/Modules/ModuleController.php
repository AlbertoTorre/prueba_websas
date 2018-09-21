<?php

namespace App\Http\Controllers\Modules;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Module;

class ModuleController extends Controller
{
    public function show()
    {
    	$ms = Module::where('active', '1')->orderBy('parent_id','ASC')->get()->toArray();
    	return view('modules.show', ['modules'=> $ms ] );
    }
}
