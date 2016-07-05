<?php

namespace Dataport\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Dataport\Model\Node;


class NodeController extends AbstractActionController
{

    public function indexAction()
    {
        //return new ViewModel();
        $edge_floor = null;
        $node_list = null;
        $error = null;
        $edgeerror = null;
        $End_Node = null;
        $Start_Node = null;

        //iniialize Add edge form
        $edge_floor = 3;
        $nodeTable = $this->getServiceLocator()->get('NodeTable');
        $node_list = $nodeTable->getNodebyFloor("3"); 



        $request = $this->getRequest();
        if($request->isPost()){
            $form_name = $request->getPost()->get('form_name');

            //add Node
            if( $form_name == "AddNode"){
            	$floor = (int)$this->params()->fromPost('floor');
            	$x_coord = (int)$this->params()->fromPost('x_add');
            	$y_coord = (int)$this->params()->fromPost('y_add');
            	$name = $this->params()->fromPost('node_name');
            	$data = array(
            		'name'    => $name,
    		        'floor'   => $floor,
    		        'x_coord' => $x_coord,
    		        'y_coord' => $y_coord,
    		    );
            	if ( $floor == 0 ){
            		$error = "Please select a floor from left side menu bar!";
            	}elseif ( $x_coord == 0 ) {
            		$error = "Pleaes click on the map to get node's coordinate!";
            	}else{

            		try{
            			$nodeTable = $this->getServiceLocator()->get('NodeTable');
            			$nodeTable->addNode($data);
            			$error = "Add the Node: \"" . $name . "\" for floor: " . $floor . " Sucessfully!";
            		}
            		catch(Exception $e) {
     					$error = $e->getMessage();
    				}
            	}
            }
            //return the node list for selected floor to create start node and end node dropdownlist
            if( $form_name == "node_list"){
                $edge_floor = (int)$this->params()->fromPost('floor');
                $nodeTable = $this->getServiceLocator()->get('NodeTable');
                //$edgeTable = $this->getServiceLocator()->get('EdgeTable');
                $node_list = $nodeTable->getNodebyFloor($edge_floor);     
            }

            //add the edge if start node is not same as end node
            if( $form_name == "AddEdge"){
                $edgeTable = $this->getServiceLocator()->get('EdgeTable'); 
                $nodeTable = $this->getServiceLocator()->get('NodeTable');

                $edge_floor = (int)$this->params()->fromPost('EdgeFloor');
                $Start_Node = (int)$this->params()->fromPost('StartNode');
                $End_Node = (int)$this->params()->fromPost('EndNode');
                $node_list = $nodeTable->getNodebyFloor($edge_floor); 

                $star = $nodeTable->getNodebyId($Start_Node)->name;
                $end = $nodeTable->getNodebyId($End_Node)->name;

                if ( $Start_Node == $End_Node ){
                    $edgeerror = "Start Node: $star and End Node: $end are same, try different pair!";
                }else{
                    $edge = $edgeTable->getEdge($Start_Node, $End_Node);

                    // add edges
                    if (isset($_POST['Add'])){
                        if ($edge != null){
                            $edgeerror = "The edge: $star <---> $end is already existing, try different pair!";
                        }else{
                            $edgeTable->addEdge($Start_Node, $End_Node);
                            $edgeerror = "Add edge: $star <---> $end Sucessfully!";                        
                        }    
                    }
                    //delete edges 
                    if (isset($_POST['Delete'])){
                        if ($edge == null){
                            $edgeerror = "The edge: $star <---> $end doesn't exist, try different pair!";
                        }else{
                            $edgeTable->deleteEdge($Start_Node, $End_Node);
                            $edgeerror = "Delete edge: $star <---> $end Sucessfully!";                        
                        }    
                    }
                }
            }
        }

        $ViewModel = new ViewModel(array('error' => $error,
                        'node_list' => $node_list,
                        'edge_floor' => $edge_floor,
                        'edgeerror' => $edgeerror,
                        'StartNode' => $Start_Node,
                        'EndNode'  => $End_Node
                    ));
        return $ViewModel;

    }


}

