<?php

if($webUI->isInstalled()) {
        global $adb;
	if($_SERVER['PHP_SELF'] == '/index.php'){
                $req_url = ltrim($_SERVER['REQUEST_URI'],"/");
        }else{
                $php_self = str_replace('index.php','',$_SERVER['PHP_SELF']);
                $req_url = str_replace($php_self,'',$_SERVER['REQUEST_URI']);
        }
        $can_q = $adb->pquery("select * from jo_canonical",array());
        while($can_re = $adb->fetch_array($can_q)) {
                $in_format = $can_re['input_format'];
                $in_format = '/'.str_replace('/','\/',$in_format).'/';
                preg_match($in_format, $req_url, $matches, PREG_OFFSET_CAPTURE);
                if(!empty($matches)) {
                        $out_format = $can_re['output_format'];
                        $out_url = parse_url($out_format, PHP_URL_QUERY);
                        $query = explode('&amp;',$out_url);
                        foreach($query as $i=>$q) {
                                list($key,$value) = explode('=',$q);
                                $_REQUEST[$key] = $matches[$i + 1][0];

                        }

                        break;
                }
        }

}

?>
