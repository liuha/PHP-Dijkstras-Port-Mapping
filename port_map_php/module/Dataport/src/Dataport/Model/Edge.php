<?php
namespace Dataport\Model;

class Edge
{
	public $FromNode_id;
    public $ToNode_id;

	public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function exchangeArray($data)
    {
    	$this->FromNode_id 	 = (isset($data['FromNode_id'])) ? $data['FromNode_id'] : null;
        $this->ToNode_id     = (isset($data['ToNode_id'])) ? $data['ToNode_id'] : null;       
    }
}