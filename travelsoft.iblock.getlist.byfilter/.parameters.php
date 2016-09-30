<?php

$arComponentParameters["PARAMETERS"]["FILTER_NAME"] = array(

                "PARENT" => "BASE",
                "NAME" => "Название фильтра",
                "TYPE" => "STRING"
    
);

$arComponentParameters["PARAMETERS"]["CNT"] = array(

                "PARENT" => "BASE",
                "NAME" => "Максимальное количество элементов в выборке",
                "TYPE" => "STRING",
    
);

$arComponentParameters["PARAMETERS"]["SORT"] = array(

                "PARENT" => "BASE",
                "NAME" => "Код поля для сортировки",
                "TYPE" => "STRING"
    
);

$arComponentParameters["PARAMETERS"]["ORDER"] = array(

                "PARENT" => "BASE",
                "NAME" => "Направление сортировки",
                "TYPE" => "LIST",
                "VALUES" => array("ASC" => "По возрастанию", "DESC" => "По убыванию")
    
);

$arComponentParameters["PARAMETERS"]["RETURN_RESULT"] = array(

                "PARENT" => "BASE",
                "NAME" => "Возвращать только результат",
                "TYPE" => "CHECKBOX",
    
);

$arComponentParameters["PARAMETERS"]["TITLE"] = array(

                "PARENT" => "BASE",
                "NAME" => "Использовать как заголовок списка",
                "TYPE" => "STRING",
    
);

$arComponentParameters["PARAMETERS"]["CACHE_TIME"]  =  array("DEFAULT"=>36000000);
