<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Library\Tree;
use App\Models\Module;

class TreeTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGenerateTree()
    {
		$ms = Module::where('active', '1')->orderBy('parent_id','ASC')->get()->toArray();
    	if( count($ms) && $ms !== null ){
    		$tree = new Tree;
    		$tree->_listData = $ms;
    		$this->assertInternalType( 'array', $tree->listTree() );
    		$this->assertNotCount( 0, $tree->listTree() );
    	}
    }
}
