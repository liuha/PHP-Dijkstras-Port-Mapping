<?php

namespace Dataport\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Dataport\Model\Port;
use Dataport\Model\Graph;

class DataportController extends AbstractActionController
{

    public function indexAction()
    {
        
        $port = null;
        $error = null;
        $path_list = null;
        $port_list = null;

        $request = $this->getRequest();
        if($request->isPost()){
        	$form_name = $request->getPost()->get('form_name');
                
        	$portTable = $this->getServiceLocator()->get('PortTable');
            $nodeTable = $this->getServiceLocator()->get('NodeTable');

            #search by dataport numbert and floor
        	if($form_name == "dp_Search"){
        		$form_port = (int)$this->params()->fromPost('port');
        		$form_floor = (int)$this->params()->fromPost('floor');

        		$portinfo = $portTable->getPortByPortandFloor($form_port, $form_floor);
        		$port = $portinfo['port'];
        		$error = $portinfo['error'];

                # if port is not null, get the shortest path from door to given node.
                if ( $port != null)
                {
                    $path_list = $this->search_path($port); 
                }
        	}

            #update dataport's coordinates
            if ( $form_name == "dp_Update"){
                
                $form_port = (int)$this->params()->fromPost('port');
                $form_floor = (int)$this->params()->fromPost('floor');
                $form_x_update = (int)$this->params()->fromPost('x_update');
                $form_y_update = (int)$this->params()->fromPost('y_update');

                //#update port coordinates
                $portinfo = $portTable->getPortByPortandFloor($form_port, $form_floor);
                $port = $portinfo['port'];
                $port->x_coord = $form_x_update;
                $port->y_coord = $form_y_update;
                $portTable->updatePort($port);

                #update node coordinates
                $node = null;
                $nodename = (string)$port->floor . "_" . (string)$port->port;  
                $node = $nodeTable->getNode($nodename, $form_floor);

                if ( $node )
                {
                    $node->x_coord = $form_x_update;
                    $node->y_coord = $form_y_update;
                    $nodeTable->updateNode($node);   
                }
                  
                # get the shortest path from door to given node.
                $path_list = $this->search_path($port); 
            }

            # search dataports by room number, 
            if ( $form_name == "room_Search"){
                $form_room = (string)$this->params()->fromPost('search_room');
                $form_room = trim($form_room);
                $port_listinfo = $portTable->getPortByRoom($form_room);
                $port_list = $port_listinfo['portset'];
                $error = $port_listinfo['error'];
                //$error = $port_listinfo['portset'];
            } 
        }
      
        $ViewModel = new ViewModel(array('port'	=> $port,
        				'error'	=> $error,
        				'path_list'	=> $path_list,
        				'port_list'	=> $port_list
        			));

        return $ViewModel;
    }

    #return the path from entry door to destination dataport
    protected function search_path(Port $port)
    {
        $path_list = null;
        $edge = null;

        $nodeTable = $this->getServiceLocator()->get('NodeTable');
        $edgeTable = $this->getServiceLocator()->get('EdgeTable');
        #check whether the node is connected to any room door or not. 
        #if exists, return path, otherwise, return none. 

        $end = (string)$port->floor . "_" . (string)$port->port;
        $end_node = $nodeTable->getNode($end, $port->floor);

        // if end node as this port is existing, then check is there any node connecting to this node.   
        if ($end_node) 
        {
            $edgeset = $edgeTable->getEdgebyToNode($end_node->id);
                        
            //if some nodes connect to end node, then find the shorest path from floor entrance to it. 
            // otherwise $path_list is null 
            if ($edgeset->count() != 0){
                if($port->floor == 3 ){
                    $star = "3_enter";
                }elseif ( $port->floor == 4 ){
                    $star = "4_enter";
                }else {
                    $star = "5_enter";
                }
                
                //get entrance node id;
                $entrance_node = $nodeTable->getNode($star, $port->floor);

                $graph = new Graph();
                $all_nodes = $nodeTable->getNodebyFloor($port->floor);


                foreach ($all_nodes as $edge_node) {
                    $neighbour = null;

                    //************* this dijkstras-algorithm graph is undirected,
                    //************* but edges in database are directed, 
                    //************* therefore, need to add edge: a=>b and b=>a ********
                    //************* in php, Arrays and objects can not be used as keys, then, using node name as key
                    //************* a=>b ***********
                    $start_node_edges = $edgeTable->getEdgebyFromNode($edge_node->id);
                    if ($start_node_edges-> count() != 0 ){
                        foreach ($start_node_edges as $each_edge) {
                             $to_node = $nodeTable->getNodebyId($each_edge->ToNode_id);
                             $x = (int)$edge_node->x_coord - (int)$to_node->x_coord;
                             $y = (int)$edge_node->y_coord - (int)$to_node->y_coord;
                             $distances = ceil(sqrt(pow($x,2) + pow($y,2)));
                             $neighbour[$to_node->name] = $distances;
                        } 
                        $graph->add_vertex($edge_node->name, $neighbour);
                    }
                    
                    //********* b=>a *************
                    //if edges in database are undirected ( a<=>b ), then comment below part. 
                    /*
                    $end_node_edges = $edgeTable->getEdgebyToNode($edge_node->id);
                    if ($end_node_edges-> count() != 0 ){
                        foreach ($end_node_edges as $each_edge) {
                             $from_node = $nodeTable->getNodebyId($each_edge->FromNode_id);
                             $x = (int)$edge_node->x_coord - (int)$from_node->x_coord;
                             $y = (int)$edge_node->y_coord - (int)$from_node->y_coord;
                             $distances = ceil(sqrt(pow($x,2) + pow($y,2)));
                             $neighbour[$from_node->name] = $distances;
                        } 
                        $graph->add_vertex($edge_node->name, $neighbour);
                    }
                    */
                }
                // return a path list Indexed Array, value are node_names;
                $path_list_info = $graph->shortest_path($entrance_node->name, $end_node->name);

                //convert the above array to object array which value is node object. 
                for($index = 0 ; $index < count($path_list_info); $index++){
                    $path_node = $nodeTable->getNode($path_list_info[$index], $port->floor); 
                    $path_list[$index] = $path_node;
                } 
              
            }
        }
        return $path_list;
    }
    
}
