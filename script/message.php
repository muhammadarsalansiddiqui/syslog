<?php
include dirname(__FILE__)."/../models/const.php";
  header('Content-Type:text/html;chatset=utf-8');
  $filePath = '/var/log/messages';
  $file = fopen($filePath,'r') or die("cannot open file");
  $xml = simplexml_load_file("seed.xml");
  $content = [];
  $item = [];
  $count = 0;
  if($file){
       while(!feof($file)){
           $line = fgets($file);
           $a_str = explode(': ',$line,2);
           if(count($a_str)!=2) break;
           $xnode = $xml->addChild('item');
           $date = preg_split('/\s+/',$a_str[0],5);
           $item['date']='2017-'.$_MONTH[$date[0]].'-'.$date[1].' '.$date[2];
           $item['date'] = date("Y-m-d h:i:s",strtotime($item['date'])); 
           $xnode->addChild('datetime',$item['date']);
           $item['type'] = $date[3];
           $xnode->addChild('type',$item['type']);
           $item['crter']  = preg_replace('/\[[0-9]{1,}\]/','',$date[4]);
           $xnode->addChild('creator',$item['crter']);
           if(array_key_exists($item['crter'],$_LV)){
                $item['lv'] =  $_LV[$item['crter']];
             }
           else{
                $item['lv'] = $_LV['commen'];
         }
         $xnode->addChild('lv',$item['lv']);
         $temp = preg_split('/[0-9]\]/',$a_str[1],2);
         $item['content'] = count($temp)==2?$temp[1]:$temp[0];
         $xnode->addChild('content',\rtrim($item['content']));
         $count++;
       }
    }
  else{
      exit("fail to open");
     }
     $fxml = $xml->asXML();
     file_put_contents('./messages.xml',$fxml);
//     echo 'message load success \n';
     echo $count;
