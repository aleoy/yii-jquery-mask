<?php
/**
 * JMask Class File
 * 
 * @author Vitor Silva  <vitor@aleoy.com>
 * @version 0.1
 * @license http://www.opensource.org/licenses/mit-license.php MIT license
 * 
 * @desc JMask (jQuery Mask Plugin) is a wrapper for https://github.com/igorescobar/jQuery-Mask-Plugin
 * plugin documentation http://igorescobar.github.io/jQuery-Mask-Plugin/
 */

class JMask extends CWidget {

    /**
     * @var string Path of the asset files after publishing.
     */
    private $assetsPath;
    
    /**
     * @var string the selected HTML elements
     */
    public $element = null;
    
    /**
     * @var object instance of CModel
     */
    public $model = null;
    
    /**
     * @var string attribute name of $this->model 
     */
    public $attribute = null;
    
    /**
     * @var array options for maskMoney 
     */
    public $config = array();
    
    /**
     * @var string custom mask javascript handler 
     */
    public $maskScript;
    
    public function init() {
        $assets = dirname(__FILE__) . '/' . 'assets';
        $this->assetsPath = Yii::app()->getAssetManager()->publish($assets);
        Yii::app()->getClientScript()->registerScriptFile($this->assetsPath . '/' . 'jquery.mask.js');
        Yii::app()->clientScript->registerCoreScript('jquery');
    }

     public function run() {
         static $runCount = 1;
         $maskCallScript = $mask = '';
         
         if(isset($this->config['mask'])){
             $mask = $this->config['mask'];
             unset($this->config['mask']);
         }
         
         $this->setElement();
         
         if($this->maskScript !== NULL){
             $maskCallScript = $this->maskScript;
         }
         elseif(empty($this->config)){
             $maskCallScript = '$("'.$this->element.'").mask(\''.$mask.'\')';
         }
         else{
             $config =  json_encode($this->config);
             $config = ltrim ($config,'[');
             $config = rtrim ($config,']');
             $config = str_replace('"', '', $config);
             //isset($this->config['symbol']) ? '': $this->config['symbol'] = Yii::app()->getLocale()->getCurrencySymbol($this->currency);
             $maskCallScript = '$("'.$this->element.'").mask(\''.$mask.'\', '.$config.');';
         }
         
         Yii::app()->clientScript->registerScript('processPrint'.$runCount, $maskCallScript);
         $runCount++;
     }
     
     /*
      * Sets the element attribute that will serve as DOM reference to apply the mask
      */
     protected function setElement(){    
         if($this->element === NULL && is_object($this->model) && $this->attribute !== NULL){
             $this->element = '#'.CHtml::activeId($this->model, $this->attribute);
         }
     }
}

?>