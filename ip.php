<?
ini_set('display_errors', 'On');

error_reporting(E_ALL | E_STRICT);

function ip()
{
    //strcasecmp 比较两个字符，不区分大小写。返回0，>0，<0。
    if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $res = preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches[0] : '';
    return $res;
}

$ip = ip();

$callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需

if (isset($_SERVER['HTTP_REFERER']) && strops($_SERVER['HTTP_REFERER'], 'getip.icu') === false) {
    header("HTTP/1.1 403 Forbidden");
}

if (strpos(getenv('HTTP_USER_AGENT'), 'curl') === false && $_GET['text'] !== 'true') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ip' => $ip]);
} else if (isset($_GET['callback'])) {
    echo trim($_GET['callback']) . '(' . json_encode(['ip' => $ip]) . ')';
} else {
    echo $ip . "\n";
}
