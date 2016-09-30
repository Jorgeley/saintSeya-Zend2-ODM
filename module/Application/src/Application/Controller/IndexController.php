<?php
/**
 * Simples  projeto exemplo usando as tecnologias Zend2 + Doctrine2(ODM) + Mongodb
 *                                      por Athenaaaaaaaaaa!!!
 * Simple sample project using the technologies Zend2 + Doctrine2(ODM) + Mongodb
 *                                      for Athenaaaaaaaaaa!!!
 *
 * @author Jorgeley <jorgeley@gmail.com>
 */

namespace Application\Controller;

use Application\Documents\Knight;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\{  Configuration, 
                            DocumentManager,
                            Mapping\Driver\AnnotationDriver };

class IndexController extends AbstractActionController{
    private $dm;        //the doctrine mongo ODM Document Manager
    private $knights;   //the collection
    const KNIGHTS_REF = "Application\Documents\Knight"; //the collection class reference

    //get the entire collection
    function __construct(){
        $this->knights = $this->getDM()->getRepository(self::KNIGHTS_REF)->findAll();
    }
    
    //show a grid of knights
    public function indexAction(){
        return new ViewModel(array( "knights" => $this->knights ));
    }
    
    //add a new one or update an existing knight
    public function saveAction(){
        //d'you post?
        if ($this->getRequest()->isPost()){
            $knight = $this->getKnight();
            $knight->setName( $this->getRequest()->getPost("name") );
            $knight->setArmor( $this->getRequest()->getPost("armor") );
            //persist the knight
            $this->getDM()->persist($knight);
            $this->getDM()->flush();
        }
        //go back to home (indexAction) to show the grid of knights
        $this->redirect()->toRoute("home");
    }
    
    //show de knight data on form
    public function editAction(){
        return new ViewModel(array( 
                                "knight" => $this->getKnight(),
                                "knights" => $this->knights));
    }
    
    //delete a knight   :(
    public function deleteAction(){
        //farewell brave knight, your cosmo will stay with us forever...
        $this->getDM()->remove($this->getKnight());
        $this->getDM()->flush();
        //go back to home (indexAction) to show the grid of knights
        $this->redirect()->toRoute("home");
    }
    
    //get the knight object
    private function getKnight(){
        //is there an Id forwarded by GET or POST?
        if ($this->getKnightId())
            //find the knight by his Id
            return $this->getDM()->find(self::KNIGHTS_REF, $this->getKnightId());
        else
            //create a new knight
            return new Knight();
    }
    
    //trying to get the knight id
    private function getKnightId(){
        $knightId   = $this->getRequest()->getPost("id") //POST?
                    ?? $this->params("id") //GET?
                    ?? null; //no way
        return $knightId;
    }
    
    //the bootstrap method to get a doctrine mongo ODM Document Manager
    private function getDM(){
        if (null === $this->dm){
            $connection = new Connection();
            $config = new Configuration();
            $config->setProxyDir(__DIR__ . '/../Documents/Proxies');
            $config->setProxyNamespace('Proxies');
            $config->setHydratorDir(__DIR__ . '/../Documents/Hydrators');
            $config->setHydratorNamespace('Hydrators');
            $config->setDefaultDB('saintSeya');
            $config->setMetadataDriverImpl(AnnotationDriver::create(__DIR__ . '/../Documents'));
            AnnotationDriver::registerAnnotationClasses();
            $this->dm = DocumentManager::create($connection, $config);
        }
        return $this->dm;
    }
    
}