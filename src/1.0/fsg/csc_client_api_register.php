<?php
/*
 * author: xjl
 */

$method=$argv[1];
$num=$argv[2];
$ms_ip=$argv[3];

//$method="create";
//$num="8";
//$ms_ip="192.168.1.224";

function curlPost($url,$data,$isJSON=true,$timeout=100)
{
        $ch = curl_init();
        $curl_opts[CURLOPT_URL] = $url;
        $curl_opts[CURLOPT_HEADER] = false;
        $curl_opts[CURLOPT_RETURNTRANSFER] = true;
        $curl_opts[CURLOPT_POST] = true;
        $curl_opts[CURLOPT_POSTFIELDS] = $data;
        $curl_opts[CURLOPT_TIMEOUT] = $timeout;
        $curl_opts[CURLOPT_VERBOSE] = false;
        curl_setopt_array($ch, $curl_opts);
        $result = curl_exec($ch);
        curl_close($ch);
        if($isJSON)
        {
                $json = json_decode($result, true);
                return $json;
        }
        else
        {
                return $result;
        }
}


if($method == "create")
{
        // $url="http://".$ms_ip."/csc_manage_http_register.php?method=create&num=".$num;
        $url="http://".$ms_ip."/manage/csc_manage_http_register.php?method=create&num=".$num;
        // echo $url;
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result=curl_exec($ch);
        curl_close($ch);
        $json=json_decode($result, true);
        
        if($json['nu'] ==0 )
        {
                
                echo "created {$json['num']} oid Succeed!";
                // exit;
        }
        else
        {
                echo "created {$json['num']} oid Failed!";
        }
}
?>
