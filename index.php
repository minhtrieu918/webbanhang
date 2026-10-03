<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/app/helpers.php';

$scriptDirectory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$scriptDirectory = $scriptDirectory === '/' ? '' : rtrim($scriptDirectory, '/');
define('APP_BASE_URL', $scriptDirectory);

$route = $_GET['url'] ?? null;
if ($route === null) {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    if ($scriptDirectory !== '' && str_starts_with($path, $scriptDirectory)) {
        $path = substr($path, strlen($scriptDirectory));
    }
    $route = trim($path, '/');
}

$segments = array_values(array_filter(explode('/', trim((string) $route, '/')), 'strlen'));
$controllerSegment = $segments[0] ?? 'Product';
$action = $segments[1] ?? 'index';
$parameters = array_map(
    static fn (string $value): int|string => ctype_digit($value) ? (int) $value : $value,
    array_slice($segments, 2)
);

if (!preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $controllerSegment)
    || !preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $action)) {
    http_response_code(404);
    exit('Đường dẫn không hợp lệ.');
}

$controllerName = ucfirst($controllerSegment) . 'Controller';
$controllerFile = __DIR__ . '/app/controllers/' . $controllerName . '.php';

if (!is_file($controllerFile)) {
    http_response_code(404);
    exit('Không tìm thấy controller.');
}

require_once $controllerFile;
$controller = new $controllerName();

if (!is_callable([$controller, $action])) {
    http_response_code(404);
    exit('Không tìm thấy chức năng.');
}

try {
    call_user_func_array([$controller, $action], $parameters);
} catch (ArgumentCountError | TypeError $exception) {
    http_response_code(404);
    exit('Tham số đường dẫn không hợp lệ.');
}
