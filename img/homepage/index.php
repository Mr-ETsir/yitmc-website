<?php
/**
 * YITMC · PCL2 自定义主页（动态生成 PCL2 XAML）
 *
 * PCL2 → 设置 → 个性化 → 自定义主页 → 填入本页 URL 即可。
 * 每次启动器加载时实时查询各服务器状态并生成原生 XAML 卡片。
 *
 * 数据源：minetools.eu（状态+延迟）为主，mcstatus.io 兜底；
 * 查询结果缓存 60 秒，避免多人同时启动时打爆上游 API。
 *
 * 安全：本文件不含任何用户输入；所有请求目标均为下方硬编码的
 * 公网服务器，发起请求前逐个校验 host（拒绝内网/环回/保留地址）。
 */

declare(strict_types=1);

date_default_timezone_set('Asia/Shanghai');
header('Content-Type: application/xml; charset=UTF-8');
header('Cache-Control: no-store');
header('X-Robots-Tag: noindex');

/* ==================== 配置区 ==================== */

const HTTP_TIMEOUT = 8;   // 单个上游 API 超时（秒）
const CACHE_TTL = 60;     // 状态缓存（秒）
const MAX_MOTD_LINES = 3; // MOTD 最多显示行数
const MINETOOLS = 'https://api.minetools.eu/ping/';
const MCSTATUS = 'https://api.mcstatus.io/v2/status/java/';

// port=0 表示自动 SRV 解析，failPort 为 SRV 查询失败时的兜底端口
$SERVERS = [
    ['name' => '燕通联合服务器',   'note' => '社团主服',        'host' => 'srvl.yitmc.cn',     'display' => 'srvl.yitmc.cn',     'port' => 0,     'failPort' => 20043],
    ['name' => '复原工程建筑服',   'note' => '校园复刻工程',    'host' => 'building.yitmc.cn', 'display' => 'building.yitmc.cn', 'port' => 0,     'failPort' => 19000],
    ['name' => '小游戏服务器',     'note' => '床战 · 空岛 · PVP','host' => 'play.yitmc.cn',    'display' => 'play.yitmc.cn',     'port' => 0,     'failPort' => 20091],
    ['name' => '津高联联合服务器', 'note' => '原版生存',        'host' => 'unioncompute.top',  'display' => 'unioncompute.***',  'port' => 26149, 'failPort' => 26149],
    ['name' => 'MUA Lobby',        'note' => 'MUA 高校联盟大厅', 'host' => 'lobby.mualliance.cn','display' => 'lobby.mualliance.cn','port' => 0,    'failPort' => 25565],
];

/* ================================================ */

/** 仅允许公网域名：格式校验 + 解析结果不得为环回/私有/保留地址 */
function isPublicHost(string $host): bool
{
    if (!preg_match('/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?)(\.[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?)+$/i', $host)) {
        return false;
    }
    $ip = @gethostbyname($host);
    if ($ip === $host) {
        return false;
    }
    return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false;
}

/** SRV 解析，失败返回 0 */
function resolveSrvPort(string $host): int
{
    if (!isPublicHost($host)) {
        return 0;
    }
    $rec = @dns_get_record($host, DNS_SRV);
    if (is_array($rec) && isset($rec[0]['port'], $rec[0]['target'])) {
        $target = (string) $rec[0]['target'];
        if (isPublicHost(rtrim($target, '.'))) {
            return (int) $rec[0]['port'];
        }
    }
    return 0;
}

/** GET 一个 https URL，cURL 可用则用 cURL，否则用原生流（服务器无需额外 PHP 扩展） */
function httpGet(string $url): ?string
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => HTTP_TIMEOUT,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_FOLLOWLOCATION => false,
        ]);
        $body = curl_exec($ch);
        curl_close($ch);
        return is_string($body) && $body !== '' ? $body : null;
    }

    $ctx = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => HTTP_TIMEOUT,
            'follow_location' => 0,
            'max_redirects' => 0,
            'header' => "User-Agent: YITMC-PCL-Homepage/1.0\r\n",
        ],
        'ssl' => ['verify_peer' => true, 'verify_peer_name' => true],
    ]);
    $body = @file_get_contents($url, false, $ctx);
    return is_string($body) && $body !== '' ? $body : null;
}

/** 查询单台服务器（minetools 为主：一条请求同时拿到状态和延迟） */
function queryMinetools(string $host, int $port): ?array
{
    if (!isPublicHost($host)) {
        return null;
    }
    $url = MINETOOLS . rawurlencode($host) . '/' . $port;
    $body = httpGet($url);
    if (!is_string($body) || $body === '') {
        return null;
    }
    $j = json_decode($body, true);
    // minetools 在线时返回 version/latency；离线或查询失败返回 {"error": "..."}
    if (!is_array($j) || isset($j['error']) || !isset($j['version']['name'])) {
        return null;
    }
    $latency = isset($j['latency']) && is_numeric((string) $j['latency']) ? (int) round((float) $j['latency']) : null;
    $desc = $j['description'] ?? '';
    if (is_array($desc)) {
        $desc = $desc['text'] ?? json_encode($desc, JSON_UNESCAPED_UNICODE);
    }
    return [
        'online' => true,
        'version' => (string) $j['version']['name'],
        'players' => (int) ($j['players']['online'] ?? 0),
        'max' => (int) ($j['players']['max'] ?? 0),
        'motd' => (string) $desc,
        'latency' => ($latency !== null && $latency > 0) ? $latency : null,
    ];
}

/** mcstatus.io 兜底（无延迟数据） */
function queryMcstatus(string $host, int $port): ?array
{
    if (!isPublicHost($host)) {
        return null;
    }
    $addr = $port === 25565 ? $host : $host . ':' . $port;
    $body = httpGet(MCSTATUS . rawurlencode($addr));
    if (!is_string($body) || $body === '') {
        return null;
    }
    $j = json_decode($body, true);
    if (!is_array($j) || !isset($j['online'])) {
        return null;
    }
    $motd = $j['motd']['raw'] ?? '';
    return [
        'online' => (bool) $j['online'],
        'version' => (string) ($j['version']['name_clean'] ?? ''),
        'players' => (int) ($j['players']['online'] ?? 0),
        'max' => (int) ($j['players']['max'] ?? 0),
        'motd' => is_string($motd) ? $motd : '',
        'latency' => null,
    ];
}

function queryAll(array $servers): array
{
    $out = [];
    foreach ($servers as $cfg) {
        $port = $cfg['port'];
        if ($port === 0) {
            $port = resolveSrvPort($cfg['host']);
            if ($port === 0) {
                $port = $cfg['failPort'];
            }
        }
        $st = queryMinetools($cfg['host'], $port);
        if ($st === null) {
            // minetools 失败或报错时用 mcstatus.io 兜底确认（含真实离线判定）
            $fb = queryMcstatus($cfg['host'], $port);
            if ($fb !== null) {
                $st = $fb;
            }
        }
        if ($st === null) {
            $st = ['online' => false, 'version' => '', 'players' => 0, 'max' => 0, 'motd' => '', 'latency' => null];
        }
        $st['port'] = $port;
        $out[] = $st;
    }
    return $out;
}

/** 60 秒状态缓存（存的是查询结果，XAML 每次实时渲染） */
function queryCached(array $servers): array
{
    $file = sys_get_temp_dir() . '/yitmc-pcl-status.json';
    if (is_file($file) && time() - (int) filemtime($file) < CACHE_TTL) {
        $cached = json_decode((string) @file_get_contents($file), true);
        if (is_array($cached) && count($cached) === count($servers)) {
            return $cached;
        }
    }
    $result = queryAll($servers);
    @file_put_contents($file, json_encode($result, JSON_UNESCAPED_UNICODE), LOCK_EX);
    return $result;
}

/* ---------- XAML 生成 ---------- */

function xesc(?string $s): string
{
    $s = htmlspecialchars($s ?? '', ENT_QUOTES | ENT_XML1, 'UTF-8');
    return str_replace(["\r\n", "\n", "\r"], '&#10;', $s);
}

/** 去掉 § 颜色代码，只留纯文本；截断到指定行数 */
function plainMotd(string $motd): string
{
    $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $motd));
    $clean = [];
    foreach ($lines as $line) {
        $line = trim(preg_replace('/§./u', '', $line));
        if ($line !== '') {
            $clean[] = $line;
        }
        if (count($clean) >= MAX_MOTD_LINES) {
            break;
        }
    }
    return implode("\n", $clean);
}

/** UTF-8 字符数（服务器可能未装 mbstring，用 PCRE 实现） */
function uLen(string $s): int
{
    $n = preg_match_all('/./us', $s);
    return $n === false ? strlen($s) : $n;
}

function uSub(string $s, int $start, int $len): string
{
    $chars = preg_split('//us', $s, -1, PREG_SPLIT_NO_EMPTY);
    return is_array($chars) ? implode('', array_slice($chars, $start, $len)) : $s;
}

function trunc(string $s, int $max): string
{
    $s = trim($s);
    return uLen($s) > $max ? uSub($s, 0, $max - 1) . '…' : $s;
}

function latencyText(?int $ms): string
{
    return $ms !== null ? $ms . 'ms' : '--';
}

function serverCard(array $cfg, array $st): string
{
    $online = $st['online'];
    $color = $online ? '#4CAF50' : '#E53935';
    $statusText = $online ? '在线' : '离线';
    $addrText = $cfg['display'] . ($cfg['port'] > 0 ? ':' . $cfg['port'] : '');
    // 津高联：展示打码地址，复制的也是打码形式（与其余成员服一致）
    $copyText = $cfg['display'] === 'unioncompute.***'
        ? 'unioncompute.*** -p ' . $cfg['port']
        : $addrText;

    $xaml = '    <local:MyCard Title="' . xesc($cfg['name']) . '" Margin="0,0,0,15" CanSwap="True">' . "\n";
    $xaml .= '        <StackPanel Margin="25,40,23,15">' . "\n";
    $xaml .= '            <StackPanel Orientation="Horizontal" Margin="0,0,0,8">' . "\n";
    $xaml .= '                <TextBlock Text="● " Foreground="' . $color . '" FontSize="14" VerticalAlignment="Center" />' . "\n";
    $xaml .= '                <TextBlock Text="' . $statusText . '" Foreground="' . $color . '" FontSize="14" FontWeight="Bold" VerticalAlignment="Center" />' . "\n";
    $xaml .= '            </StackPanel>' . "\n";
    $xaml .= '            <local:MyListItem Margin="-5,2,-5,2" Logo="pack://application:,,,/images/Blocks/CommandBlock.png" Title="地址" Info="' . xesc($addrText) . '" Type="Clickable" EventType="复制文本" EventData="' . xesc($copyText) . '" />' . "\n";
    $xaml .= '            <local:MyListItem Margin="-5,2,-5,2" Logo="pack://application:,,,/images/Blocks/GoldBlock.png" Title="版本" Info="' . xesc($online ? trunc($st['version'], 60) : '--') . '" />' . "\n";
    $xaml .= '            <local:MyListItem Margin="-5,2,-5,2" Logo="pack://application:,,,/images/Blocks/Grass.png" Title="玩家" Info="' . ($online ? xesc($st['players'] . ' / ' . $st['max']) : '--') . '" />' . "\n";
    $xaml .= '            <local:MyListItem Margin="-5,2,-5,2" Logo="pack://application:,,,/images/Blocks/RedstoneBlock.png" Title="延迟" Info="' . ($online ? xesc(latencyText($st['latency'])) : '--') . '" />' . "\n";

    $motd = $online ? plainMotd($st['motd']) : '';
    if ($motd !== '') {
        $xaml .= '            <TextBlock TextWrapping="Wrap" Margin="0,6,0,0" FontSize="12" Foreground="{DynamicResource ColorBrush4}" Text="' . xesc($motd) . '" />' . "\n";
    }

    if (!$online) {
        $xaml .= '            <local:MyHint Margin="0,8,0,0" Theme="Yellow" Text="服务器暂时离线，请稍后再试" />' . "\n";
    } elseif ($cfg['note'] !== '') {
        $xaml .= '            <local:MyHint Margin="0,8,0,0" Theme="Blue" Text="' . xesc($cfg['note']) . '" />' . "\n";
    }

    $xaml .= '        </StackPanel>' . "\n";
    $xaml .= '    </local:MyCard>' . "\n";
    return $xaml;
}

/* ---------- 主流程 ---------- */

$statuses = queryCached($SERVERS);

$xaml = "<StackPanel>\n";

// 公告卡：链接 + 最近动态标题（直接读服务器上后台维护的 news.json）
$newsFile = dirname(__DIR__) . '/data/news.json';
$latestNews = [];
if (is_file($newsFile)) {
    $news = json_decode((string) @file_get_contents($newsFile), true);
    $items = is_array($news) ? ($news['news'] ?? $news) : [];
    if (is_array($items)) {
        usort($items, fn($a, $b) => strcmp((string) ($b['date'] ?? ''), (string) ($a['date'] ?? '')));
        $latestNews = array_slice($items, 0, 2);
    }
}

$xaml .= '    <local:MyCard Title="燕京理工学院 MC 玩家创作协会" Margin="0,0,0,15" CanSwap="True">' . "\n";
$xaml .= '        <StackPanel Margin="25,40,23,15">' . "\n";
$xaml .= '            <TextBlock TextWrapping="Wrap" FontSize="13" Foreground="{DynamicResource ColorBrush4}" Text="欢迎加入 YITMC！点击下方服务器卡片中的地址即可复制，状态数据每次启动自动更新。" />' . "\n";
foreach ($latestNews as $item) {
    $title = trunc((string) ($item['title'] ?? ''), 40);
    $date = (string) ($item['date'] ?? '');
    $xaml .= '            <local:MyListItem Margin="-5,2,-5,2" Logo="pack://application:,,,/images/Blocks/GoldBlock.png" Title="' . xesc($title) . '" Info="' . xesc($date) . '" />' . "\n";
}
$xaml .= '            <StackPanel Orientation="Horizontal" Margin="0,10,0,0">' . "\n";
$xaml .= '                <local:MyButton Text="访问官网" Padding="13,7,13,8" Margin="0,0,10,0" Type="Clickable" EventType="打开网页" EventData="https://www.yitmc.cn/" />' . "\n";
$xaml .= '                <local:MyButton Text="加入QQ群" Padding="13,7,13,8" Margin="0,0,10,0" Type="Clickable" EventType="打开网页" EventData="https://qm.qq.com/q/942717135" />' . "\n";
$xaml .= '                <local:MyButton Text="社团皮肤站" Padding="13,7,13,8" Type="Clickable" EventType="打开网页" EventData="https://skin.yitmc.cn/" />' . "\n";
$xaml .= '            </StackPanel>' . "\n";
$xaml .= '            <TextBlock Margin="0,8,0,0" FontSize="11" Foreground="{DynamicResource ColorBrush4}" Text="状态更新于 ' . xesc(date('Y-m-d H:i:s')) . '" />' . "\n";
$xaml .= '        </StackPanel>' . "\n";
$xaml .= '    </local:MyCard>' . "\n";

foreach ($SERVERS as $i => $cfg) {
    $xaml .= serverCard($cfg, $statuses[$i]);
}

$xaml .= "</StackPanel>\n";

echo $xaml;
