<?php

/**
 * Выборка элементов по внешнему фильтру
 * @author dimabresky
 */

class TravelsoftIBlockGetListByFilter extends CBitrixComponent {
    
    /**
     * @var array массив фильтра
     */
    protected $arFilter = null;
    
    /**
     * @var array массив полей для выборки
     */
    protected $arSelect = null;
            
    /**
     * @var array массив полей для сортировки 
     */
    protected $arSort = null;
    
    /**
     * @var array массив, содержащий ограничение выборки количества элементов
     */
    protected $arNavigation = null;
    
    /**
     * @var string папка кеша
     */
    protected $cacheDir = "/travelsoft_iblock_getlist_byfilter";
    
    /**
     * @var string id кеша
     */
    protected $cacheID = null;
    
    /**
     * Установка масcива сортировки
     */
    protected function setSorting () {
        
        $this->arSort = false;
        
        if ($this->arParams['SORT'] != "") {
            $this->arSort[$this->arParams['SORT']] = strtoupper($this->arParams['ORDER']) == "DESC" ? "DESC" : "ASC";           
        }
   
    }
    
    /**
     * Установка фильтра
     */
    protected function setFilter () {
        
        $this->arFilter = false;
        
        if (!EMPTY($this->arParams['FILTER']))
            $this->arFilter = $this->arParams['FILTER'];
        
        if (isset($GLOBALS[$this->arParams['FILTER_NAME']]) && !empty($GLOBALS[$this->arParams['FILTER_NAME']])) {
            
            if ($this->arFilter) {
                $this->arFilter = array_merge($GLOBALS[$this->arParams['FILTER_NAME']], $this->arFilter);
            } else
                $this->arFilter = $GLOBALS[$this->arParams['FILTER_NAME']];
            
        }
        
    }
    
    /**
     * Установка arSelect
     */
    protected function setSelect () {
        
        $this->arSelect = array("*", "PROPERTY_*");
        
        array_filter(
                $this->arParams['SELECT'],
                function ($el) { return ($el && !empty($el)); }
         );
        
        if (!empty($this->arParams['SELECT'])) {
            $this->arSelect = $this->arParams['SELECT'];
        }
        
    }
    
    /**
     * Устанока arNavigation
     */
    protected function setNavigation () {
        
        $this->arNavigation = false;
        if ($this->arParams['CNT'] > 0) {
             $this->arNavigation =array('nTopCount' => (int)$this->arParams['CNT']);
        }
        
    }
    
    /**
     * Устанавливаем ID кеша
     */
    protected function setCacheID () {
        
        $this->cacheID = md5(serialize($this->arParams) 
                                            . serialize($this->arFilter) 
                                                . serialize($this->arSelect) 
                                                    . serialize($this->arSort) 
                                                        . serialize($this->arNavigation));
    }
    
    /**
     * Запуск работы компонента
     */
    public function executeComponent() {
        
        if ( ! \Bitrix\Main\Loader::includeModule('iblock') ) {
            return;
        }
        
        $this->setFilter();
        
        if (empty ($this->arFilter) ) return;
        
        $this->setSelect();
        
        $this->setSorting();
        
        $this->setNavigation();
        
        $this->setCacheID();

        $сache = Bitrix\Main\Data\Cache::createInstance();
        
        // очищаем кеш, если выборка рандомная
        if (isset($this->arSort['RAND'])) {
            $сache->clearCache(true, $this->cacheDir);
        }
        
        if ($сache->initCache($this->arParams['CACHE_TIME'], $this->cacheID, $this->cacheDir)) {
            
            $this->arResult = $сache->getVars(); 
            
        } elseif ($сache->startDataCache()) {
          
            $this->arResult = array();
            
            $dbList = CIBlockElement::GetList(
                        $this->arSort,
                        $this->arFilter,
                        false,
                        $this->arNavigation,
                        $this->arSelect
                    );
            
            $cnt = 0;
            while ($res = $dbList->GetNextElement()) {
                
                $this->arResult[$cnt] = $res->GetFields();
                $this->arResult[$cnt]['PROPERTIES'] = $res->GetProperties(); 
                $cnt++;
                
            }
            
            if ($this->arResult) {
 
                if(defined("BX_COMP_MANAGED_CACHE")) {
                        global $CACHE_MANAGER;
                        $CACHE_MANAGER->StartTagCache($this->cacheDir);
                        $CACHE_MANAGER->RegisterTag("iblock_id_".$this->arFitler["IBLOCK_ID"]);                    
                        $CACHE_MANAGER->EndTagCache();
                }
                
                $сache->endDataCache($this->arResult);
                
            } else
                $сache->abortDataCache();
 
        } 

        if ($this->arParams['RETURN_RESULT'] == "Y") {
                return $this->arResult;		
        }        

        $this->includeComponentTemplate();
        
    }
    
}

