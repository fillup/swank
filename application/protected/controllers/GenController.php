<?php

class GenController extends Controller {

    public function actionIndex() {
        $this->menu = array(
            array('label' => 'Define Application', 'url' => '#application'),
            array('label' => 'Define APIs', 'url' => '#apis'),
            array('label' => 'Define Operations', 'url' => '#operations'),
            array('label' => 'Define Parameters', 'url' => '#Parameters'),
            array('label' => 'Define Responses', 'url' => '#responses'),
        );
        
        $this->render('index', array(
        
        ));
    }

}