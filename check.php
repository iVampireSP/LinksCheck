<?php
$links = file_get_contents('https://example.blog/linksjson');
$links = json_decode($links);
$i = 0;

function FormatHeader($url)
{
 // 解析url
 $temp = parse_url($url);
 $header = array (
 "Host: {$temp['host']}",
 "Referer: http://{$temp['host']}/",
 "Content-Type: text/xml; charset=utf-8",
 'Accept: application/json, text/javascript, */*; q=0.01',
 'Accept-Encoding:gzip, deflate, br',
 'Accept-Language:zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
 'Connection:keep-alive',
 'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:83.0) Gecko/20100101 Firefox/83.0',
 'X-Requested-With: XMLHttpRequest',
 );
 return $header;
}

function check_url($url)
{
    $header = FormatHeader($url);
    $ch = curl_init();
    $timeout = 3;
    $useragent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:83.0) Gecko/20100101 Firefox/83.0';
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_exec($ch);
    curl_close($ch);
    return curl_getinfo($ch, CURLINFO_HTTP_CODE);

}

$failed_links = [];

echo '共计' . count($links->links) . '个站点' . PHP_EOL; 
foreach ($links->links as $link) {
    $i++;
    echo "[{$i}]" . '正在检查: ' . $link->name . ' ' . $link->link . PHP_EOL;
    $code = check_url($link->link);
    if ($code == 0) {
        $failed_links['links'][] = ['name' => $link->name, 'link' => $link->link];
        echo "[{$i}]" . $code . ' 存在问题: ' . $link->name . ' ' . $link->link . PHP_EOL;
    }
}

if ($failed_links['links'] != 0) {
    echo '-----以下' . count($failed_links['links']) . '个网站存在问题-----' . PHP_EOL;
    $i = 0;
    foreach ($failed_links['links'] as $link) {
        $i++;
        echo "[{$i}]" . $link['name'] . ' ' . $link['link'] . PHP_EOL;
    }
} else {
    echo '太棒了，没有网站出现问题！' . PHP_EOL;
}
