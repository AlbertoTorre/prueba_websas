<?php

namespace  App\Library;

class Tree 
{
  public $_nameCode = 'id';
  public $_nameCodeParent = 'parent_id';
  public $_namePublic = 'name';
  public $_textAccess = 'url';

  public $_listData;
  public $_arrayIdsSelected;

  public function listTree()
  {
    $array_final = array();
    foreach ($this->_listData as $value) 
    {
      $datosArray = array(
        'id' => $value[$this->_nameCode],
        'parent_id' => $value[$this->_nameCodeParent],
        'name' => $value[$this->_namePublic],
        'url' => ($value[$this->_textAccess] !== '#')?url('/').str_replace(' ', '', $value[$this->_textAccess] ):'#',
        'order' => $value['order']
      );

      $insert = $this->createTree( 
        $array_final, 
        $value[$this->_nameCodeParent], 
        $datosArray, 
        $this->_arrayIdsSelected
      );
      
      if ( $insert != false )
      {
        $array_final = $insert;
      }
      else
      {
        $array_final[] = $datosArray;
      }
    }

    usort($array_final, function($a, $b) { return $a['order'] - $b['order']; });

    return $array_final;
  }


  public function buscarArray($array, $value, $field)
  {
    foreach( $array as $k => $v )
    {
      if( $v[$field] == $value )
      {
        return array('submit'=> true, 'key'=> $k);
      }
    }
    return array('submit'=> false);
  }

  public function createTree($data, $keySearch, $dataNew)
  {

    $key = $this->buscarArray($data, $keySearch, 'id');
    if( $key['submit'] )
    {
      $data[$key['key']]['childrens'][] =  $dataNew;
      usort($data[$key['key']]['childrens'], function($a, $b) { return $a['order'] - $b['order']; });
      return $data;
    }
    else
    {
      foreach ($data as $k => $v) 
      {
        if( isset($data[$k]['childrens']) && count($data[$k]['childrens']) )
        {
          $key = $this->buscarArray($data[$k]['childrens'], $keySearch, 'id');
          if($key['submit'])
          {
            $data[$k]['childrens'][$key['key']]['childrens'][] = $dataNew;
            usort($data[$k]['childrens'][$key['key']]['childrens'], function($a, $b) { return $a['order'] - $b['order']; });
            return $data;
          }
          else
          {
            $result = $this->createTree($data[$k]['childrens'], $keySearch, $dataNew);
            if( $result )
            {
              $data[$k]['childrens'] = $result;
              return $data;
            }
          }
        }
      }
      return false;
    }
  }
}
