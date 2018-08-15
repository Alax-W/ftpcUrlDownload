<?php
/**
 * Created by PhpStorm.
 * User: jfwei
 * Date: 2018/8/15
 * Time: 10:04
 */
class downloadRangAction{

   /**
    * 从ftp下载大文件方法尝试
    * @param $url 为ftp的文件路径；例如：$url = "ftp://".$ftpIpAddress."/".文件在ftp下的路径;
    * @param $ftpPort ftp的端口号；
    * @param $ftpUser ftp的登陆用户；
    * @param $ftpPwd ftp的登陆密码；
    * User: jfwei
    * Date: 2018/8/15
    * Time: 10:04
    */
   public function curlsetoptDownLoad($url,$ftpPort,$ftpUser,$ftpPwd){
      $headerArr = array();
      $headerArr[] = 'X-Apple-Tz: 0';
      $headerArr[] = 'X-Apple-Store-Front: 143444,12';
      $headerArr[] = 'Accept: */*';
      $headerArr[] = 'Accept-Encoding: gzip, deflate';
      $headerArr[] = 'Accept-Language: en-US,en;q=0.5';
      $headerArr[] = 'Cache-Control: no-cache';
      $headerArr[] = 'Content-Type: application/x-www-form-urlencoded; charset=utf-8';
      $headerArr[] = 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0';
      $headerArr[] = 'X-MicrosoftAjax: Delta=true';
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_PORT, $ftpPort);
      //通过这个函数设置ftp的用户名和密码!
      curl_setopt($ch,CURLOPT_USERPWD,"$ftpUser:$ftpPwd");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 80);
      curl_setopt($ch, CURLOPT_TIMEOUT, 4500);
      curl_setopt($ch, CURLOPT_BUFFERSIZE, 83888608);//8M
      $flag=0;
      $fileInfo = pathinfo($url);
      $fileName  = $fileInfo['basename'];
      $fileExtnesion   = $fileInfo['extension'];
      $default_contentType = "application/octet-stream";
      $content_types_list = $this->mimeTypes();
      if (array_key_exists($fileExtnesion, $content_types_list))
      {
         $contentType = $content_types_list[$fileExtnesion];
      }else{
         $contentType =  $default_contentType;
      }
      curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch ,$str) use (&$flag,$contentType,$fileName){
         $len = strlen($str);
         $flag++;
         if($flag==1){
            $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
            $type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            $httpcode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            header("Content-type: {$contentType};charset=gbk");
            header("Accept-Ranges: bytes");
            header("Content-Length: ".$size);
            header("Content-Disposition: attachment; filename={$fileName}");
            header('Cache-Control:max-age=2592000');
         }
         /*用于查看内存使用情况
         $before =  sprintf("%.2f",memory_get_usage()/1024/1024)."MB\r\n";
         $logname_my = '/tmp/weijf.log';
         $fp = fopen($logname_my,"ab");
         fwrite($fp,$before,strlen($before));
         echo $str;
         $after =  sprintf("%.2f",memory_get_usage()/1024/1024)."MB\r\n";
         fwrite($fp,$after,strlen($after));
         fclose($fp);
         */
         echo $str;
         return $len;
      });
      $output = curl_exec($ch);
      curl_close($ch);
   }
   function mimeTypes()
   {
      $mime_types = array("323" => "text/h323",
        "tar" => "application/x-tar",
        "txt" => "text/plain",
        "xla" => "application/vnd.ms-excel",
        "xlc" => "application/vnd.ms-excel",
        "xlm" => "application/vnd.ms-excel",
        "xls" => "application/vnd.ms-excel",
        "xlt" => "application/vnd.ms-excel",
        "xlw" => "application/vnd.ms-excel",
        "csv" => "application/vnd.ms-excel",
        "xof" => "x-world/x-vrml",
        "xpm" => "image/x-xpixmap",
        "xwd" => "image/x-xwindowdump",
        "z" => "application/x-compress",
        "rar" => "application/x-rar-compressed",
        "zip" => "application/zip");
      return $mime_types;
   }
}
?>