<?php

namespace App\Http\Controllers\Modules;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Module;
use App\Library\Tree;

class ModuleController extends Controller
{

	protected function validator(array $data, $update = false)
	{
		return Validator::make($data, [
		  'name' => [
		    'unique:modules',
		    'required'
		  ],
		  'url' => 'required',
		  'active' => 'required'
		]);
	}

    public function show()
    {
    	$ms = Module::where('active', '1')->orderBy('parent_id','ASC')->get()->toArray();
		$tree = new Tree;
		$tree->_listData = $ms;
    	return view('modules.show', ['modules'=> json_encode( $tree->listTree() ) ] );
    }

    public function create(Request $request)
    {
    	if( $request->ajax() )
    	{
    		$d = $request->all();
    		$d['active'] =  ( isset( $d['active'] ) )?'1':'0';

			if( !$this->validator( $d )->fails() )
			{
	  	  		$mc = Module::create( $d );
	    		if( $mc !== null )
	    		{
	    			$return = ['submit'=> true, 'msn'=> 'Menu creado correctamente.', 'id'=> $mc->id ];
	    		}
	    		else
	    		{
	    			$return = ['submit'=> true, 'msn'=> 'Ocurrio un error inesperado, vuelva a intentarlo.'];
	    		}
	    	}
	    	else
	    	{
				$errors = '';
				$vv = $this->validator($request->all())->errors()->messages();
				foreach ($vv as $ve)
				{
				  $errors.=' - '.array_shift($ve).' <br> ';
				}
				$return = [ 'submit' => false, 'msn' => $errors ];
	    	}
	    	echo  json_encode($return);
    	}
    	else
    	{
    		redirect('/');
    	}
    }


    public function update(Request $request)
    {
    	if( $request->ajax() )
    	{
			$d = $request->all();
			unset($d['parent_id']);
			unset($d['id']);
			$d['active'] =  ( isset( $d['active'] ) )?'1':'0';
			
			if( !$this->validator($d, true)->fails() )
			{
	  	  		$mu = Module::where('id', $request->input('id'))
	  	  				   ->where('parent_id', $request->input('parent_id') )
	  	  				   ->update( $d );
	    		if( $mu !== null )
	    		{
	    			$return = ['submit'=> true, 'msn'=> 'Menu actualizado correctamente.'];
	    		}
	    		else
	    		{
	    			$return = ['submit'=> true, 'msn'=> 'Ocurrio un error inesperado, vuelva a intentarlo.'];
	    		}
	    	}
	    	else
	    	{
				$errors = '';
				$vv = $this->validator($request->all())->errors()->messages();
				foreach ($vv as $ve)
				{
				  $errors.=' - '.array_shift($ve).' <br> ';
				}
				$return = [ 'submit' => false, 'msn' => $errors ];
	    	}
	    	echo  json_encode($return);
    	}
    	else
    	{
    		redirect('/');
    	}
    }

    public function delete(Request $request, $id)
    {
    	if( $request->ajax() && $id)
    	{	
    		$msp = Module::where('parent_id', $id )->count();
    		if( !$msp )
    		{
	  	  		$md = Module::where('id', $id )->delete();
	    		if( $md !== null )
	    		{
	    			$return = ['submit'=> true, 'msn'=> 'Menu elminado correctamente.' ];
	    		}
	    		else
	    		{
	    			$return = ['submit'=> false, 'msn'=> 'Ocurrio un error inesperado, vuelva a intentarlo.'];
	    		}
    		}
    		else
    		{
	    		$return = ['submit'=> false, 'msn'=> "No puede eliminar el menu porque tiene $msp sub-menus asociados, eliminalos y vuelva a intentarlo."];
    		}
	    	echo  json_encode($return);
    	}
    	else
    	{
    		redirect('/');
    	}
    }
}
