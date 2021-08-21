<?
// exit();
$start = microtime(true);
$_SERVER['DOCUMENT_ROOT'] = '/home/bitrix/ext_www/gjhjh.ru';
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/vhk.php");
 // header("Content-Type: text/html; charset=utf-8");
 // 
$timeStart = file_get_contents($_SERVER['DOCUMENT_ROOT']."/upload/dfgdfgdf.txt");
if($timeStart && $timeStart < time() - 60*60*2){
	//скрипт работает долго
}elseif($timeStart){
	echo "1\n";
    exit();
}

$fp = fopen($_SERVER['DOCUMENT_ROOT']."/upload/dgfgdffg.txt", "w");
if (flock($fp, LOCK_EX)) { 
    fwrite($fp, time());
    flock($fp, LOCK_UN);
} else {
    echo "File lock";
    exit();
}

fclose($fp);
?>
<?CModule::IncludeModule("iblock");
CModule::IncludeModule("highloadblock");

// file_get_contents("https://api..org/:-ipf02JYA". '/?chat_id=190539045&text=Запущен парсер');
// $url = 'https://m.vk.com/id42102522';
// 'https://../?client_id=4530940&=notify,friends,photos,audio,video,docs,notes,pages,status,offers,questions,wall,groups,messages,notifications,offline&redirect_uri=http://api.vk.com/blank.html&display=page&response_type=token'
$access_token = '';
// $access_token = '';
// $access_token = '';
// 
global $DB;

class Model_Vk {

    private $access_token;
    private $url = "https://..//";

    /**
     * Конструктор
     */
    public function __construct($access_token) {

        $this->access_token = $access_token;
    }

    /**
     * Делает запрос к  
     * @param $method
     * @param $params
     */
    public function method($method, $params = null) {

        $p = "";
        if( $params && is_array($params) ) {
            foreach($params as $key => $param) {
                $p .= ($p == "" ? "" : "&") . $key . "=" . urlencode($param);
            }
        }
        $response = file_get_contents($this->url . $method . "?" . ($p ? $p . "&" : "") . "access_token=" . $this->access_token."&v=5.102");

        if( $response ) {
            return json_decode($response);
        }
        return false;
    }
}



   
    $a = 0;
    $arIds = [];
    $arPosts = [];
    while ($arItem = $res->fetch()) {

        // $arrAll[$arItem['ID']] = $arItem;
        // print_r($arItem);
        // continue;
        $arQuery = parse_url($arItem['UF_LINK'], PHP_URL_QUERY);
        parse_str($arQuery, $output);

        $param = str_replace('wall', '', $output['w']);

        // echo $param;

        // print_r($arParam);
        // print_r($output);
        // echo '<hr>';
        $arIds[] = $param;

        $arPosts[$param] = $arItem;
        // $arItem['UF_LINK'] =

        // $arDomain = explode('/', $arItem['UF_LINK']);
        // $domain = end($arDomain);

    }
    if(!$arIds){

        break;
    }
    // print_r($arIds);
    // ,525001837_608,-178248951_1
    $arIdsChank = array_chunk($arIds, 100);
    // print_r($arIdsChank);
    $params['code'] = 'var result = [];'."\n";
    $params['code'] .= 'var a = "";'."\n";
    $params['code'] .= 'var arr = [];'."\n";
    $params['code'] .= 'var num = 0;'."\n";
    $params['code'] .= 'var num_a = 0;'."\n";
    $params['code'] .= 'var i = 0;';
    $params['code'] .= 'var i_a = 0;';
    $params['code'] .= 'var str = "";';
    $params['code'] .= 'var arr_a = [];'."\n";

    foreach ($arIdsChank as $key => $value) {
        $params['code'] .= 'a = "";'."\n";
        $params['code'] .= 'arr = [];'."\n";
        $params['code'] .= 'num = 0;'."\n";
        $params['code'] .= 'num_a = 0;'."\n";
        $params['code'] .= 'i = 0;'."\n";
        $params['code'] .= 'i_a = 0;'."\n";
        $params['code'] .= 'arr_a = [];'."\n";
        $params['code'] .= 'str = "'.implode(',', $value).'";'."\n";
        $params['code'] .= 'arr = str.split(",");'."\n";
        $params['code'] .= 'a = API.wall.getById({"posts": str});'."\n";
        $params['code'] .= 'num = arr.length;'."\n";
        $params['code'] .= 'num_a = a.length;'."\n";
        $params['code'] .= '
        while(i_a < num_a){
            arr_a.push(a[i_a].from_id + "_" + a[i_a].id);
            i_a = i_a + 1;

        }
        while(i < num ){
            if(arr_a.indexOf(arr[i]) == -1){
                result.push(arr[i]);
            }

            i = i + 1;
        };'."\n";
    }
    $params['code'] .= 'return [result, "ok"];';
    // print_r($params)

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL =>
        CURLOPT_POST => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => [
            'code' => $params['code'],
            'access_token' => $access_token,
            'v' => '5.102'
        ]
    ]);
    $response = curl_exec($curl);
    $out = $response;
    // print_r($response);
    $response = json_decode($response, true);
    // print_r($response['response']);

    // foreach ($response['response'] as $key => $value) {
    //     print_r($arPosts[$value]);

    //     // $strEntityDataClassProv::update($arPosts[$value]['ID'], ['UF_STATUS' => 'DEL', 'UF_DATE_CHECK' => date('d.m.Y H:i:s')]);
    // }
    if(!$response['response']){
        print_r($out);
        print_r($response);
        echo 'Time work: '.round(microtime(true) - $start, 4).' s '.$numPost."\n";
        break;
    }


    foreach ($arPosts as $key => $value) {
        if(in_array($key, $response['response'][0])){
            $arFields = ['UF_STATUS' => 'DEL', 'UF_DATA_CHECK' => date('d.m.Y H:i:s')];
        }else{
            $arFields = ['UF_DATA_CHECK' => date('d.m.Y H:i:s')];
   
        $strEntityDataClassProv::update($value['ID'], $arFields);
    }

    // usleep(400000);
    echo $numPost."\n";
    // break;
}
//

