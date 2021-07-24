<?php
/**
 * Very basic view.
 * 
 * Written by Andrei Mosman <andrei.mosman@gmail.com>
 */

namespace Frameworkitto;

class View {

    private static $instance;

    protected $vars = [];

    protected $customScripts = [
        "header" => [],
        "footer" => [],
    ];

    protected $customStyles = [
        "header" => [],
        "footer" => [],
    ];

    protected $navigationBarView = null;
    protected $navigationBarContent = null;


    private function __construct() {
        $this->assign("BASE_URL", Router::getInstance()->getBaseURL());
        $this->assignByReference("customScripts", $this->customScripts);
        $this->assignByReference("customStyles", $this->customStyles);
        $this->assignByReference("_NAVIGATION_BAR_", $this->navigationBarContent);
    }

    public function setNavigationBarView($view) {
        $this->navigationBarView = $view;
        if( !$view ) $this->navigationBarContent = null;

        $this->getNavitagionBarContent();
    }

    public function getNavitagionBarContent() {
        if( $this->navigationBarContent ) return $this->navigationVarContent;
        $this->navigationBarContent = $this->show($this->navigationBarView,true);
        return $this->navigationBarContent;
    }

    public static function getInstance() {
        if( self::$instance === null ) self::$instance = new self;
        return self::$instance;
    }

    public function addCustomScript($target,$script) {
        $this->customScripts[$target][] = $script;
    }

    public function addCustomStyle($target,$style) {
        $this->customStyles[$target][] = $style;
    }

    public function assign($name,$value) {
        $this->vars[$name] = $value;
    }

    public function assignByReference($name,&$value) {
        $this->vars[$name] = &$value;
    }

    public function assignAllVariablesInArray($array) {
        $this->vars = array_merge($this->vars,$array);
    }

    public function show($view,$return=false) {
        $view = strtolower(str_replace(".","_",$view));
        $this->assign("__ROUTE__", Router::getInstance()->getRoute());

        $fileName = Router::getInstance()->getAppPath() . "/Application/Views/" . $view . ".php";
        extract($this->vars);
        
        ob_start();
        include($fileName);
        $output = ob_get_clean();

        if( $return ) return $output;
        
        echo $output;

    }


}
