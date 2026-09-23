<?php

session_start();

// Autoloader for App namespace
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Flash messages handler
if (!isset($_SESSION['_flash'])) {
    $_SESSION['_flash'] = [];
}
if (!isset($_SESSION['_old_input'])) {
    $_SESSION['_old_input'] = [];
}

// Request Helper
class Request {
    public function get($key, $default = null) {
        return $_GET[$key] ?? $_POST[$key] ?? $default;
    }

    public function input($key = null, $default = null) {
        if ($key === null) {
            return array_merge($_GET, $_POST);
        }
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    public function has($key) {
        return isset($_POST[$key]) || isset($_GET[$key]);
    }

    public function method() {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function all() {
        return array_merge($_GET, $_POST);
    }
}

function request($key = null, $default = null) {
    static $req;
    if (!$req) {
        $req = new Request();
    }
    if ($key === null) {
        return $req;
    }
    return $req->input($key, $default);
}

// Auth Helper
class Auth {
    public function check() {
        return isset($_SESSION['user']) && !empty($_SESSION['user']);
    }

    public function user() {
        return $_SESSION['user'] ?? null;
    }

    public function login($user) {
        $_SESSION['user'] = (object) $user;
    }

    public function logout() {
        unset($_SESSION['user']);
    }
}

function auth() {
    static $auth;
    if (!$auth) {
        $auth = new Auth();
    }
    return $auth;
}

// Session Helper
class SessionHelper {
    public function get($key, $default = null) {
        if (isset($_SESSION['_flash'][$key])) {
            $val = $_SESSION['_flash'][$key];
            unset($_SESSION['_flash'][$key]);
            return $val;
        }
        return $_SESSION[$key] ?? $default;
    }

    public function has($key) {
        return isset($_SESSION['_flash'][$key]) || isset($_SESSION[$key]);
    }

    public function flash($key, $value) {
        $_SESSION['_flash'][$key] = $value;
    }

    public function put($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function forget($key) {
        unset($_SESSION[$key]);
        unset($_SESSION['_flash'][$key]);
    }
}

function session($key = null, $default = null) {
    static $session;
    if (!$session) {
        $session = new SessionHelper();
    }
    if ($key === null) {
        return $session;
    }
    return $session->get($key, $default);
}

// Redirect Helper
class RedirectResponse {
    protected $target;

    public function __construct($url) {
        $this->target = $url;
    }

    public function with($key, $value) {
        $_SESSION['_flash'][$key] = $value;
        return $this;
    }

    public function withInput() {
        $_SESSION['_old_input'] = $_POST;
        return $this;
    }

    public function withErrors($errors) {
        $_SESSION['_flash']['errors'] = (array) $errors;
        return $this;
    }

    public function send() {
        header("Location: " . $this->target);
        exit;
    }
}

function redirect($to = null) {
    if ($to === null) {
        return new class {
            public function route($name, $params = []) {
                return new RedirectResponse(route($name, $params));
            }
            public function back() {
                $ref = $_SERVER['HTTP_REFERER'] ?? '/';
                return new RedirectResponse($ref);
            }
        };
    }
    return new RedirectResponse($to);
}

function back() {
    $ref = $_SERVER['HTTP_REFERER'] ?? '/';
    return new RedirectResponse($ref);
}

function old($key, $default = '') {
    $val = $_SESSION['_old_input'][$key] ?? $default;
    unset($_SESSION['_old_input'][$key]);
    return htmlspecialchars($val ?? '', ENT_QUOTES, 'UTF-8');
}

// Route manager & Named Routes
class Route {
    public static $routes = [];
    public static $namedRoutes = [];

    public static function get($uri, $action) {
        return self::add('GET', $uri, $action);
    }

    public static function post($uri, $action) {
        return self::add('POST', $uri, $action);
    }

    protected static function add($method, $uri, $action) {
        $route = [
            'method' => $method,
            'uri' => '/' . trim($uri, '/'),
            'action' => $action,
            'name' => null
        ];
        self::$routes[] = &$route;
        return new class($route) {
            protected $routeRef;
            public function __construct(&$route) {
                $this->routeRef = &$route;
            }
            public function name($name) {
                $this->routeRef['name'] = $name;
                Route::$namedRoutes[$name] = $this->routeRef;
                return $this;
            }
        };
    }
}

function route($name, $params = []) {
    if (!isset(Route::$namedRoutes[$name])) {
        return '/' . ltrim($name, '/');
    }
    $uri = Route::$namedRoutes[$name]['uri'];
    if (!is_array($params)) {
        $params = [$params];
    }
    foreach ($params as $k => $v) {
        if (is_string($k)) {
            $uri = str_replace('{' . $k . '}', $v, $uri);
        } else {
            $uri = preg_replace('/\{[^}]+\}/', $v, $uri, 1);
        }
    }
    return $uri === '' ? '/' : $uri;
}

// Simple Blade-like View Renderer
function view($viewPath, $data = []) {
    $file = __DIR__ . '/../resources/views/' . str_replace('.', '/', $viewPath) . '.blade.php';
    if (!file_exists($file)) {
        http_response_code(404);
        echo "View [{$viewPath}] not found.";
        exit;
    }

    // Extract passed variables
    extract($data);

    // Read view content
    $content = file_get_contents($file);

    // Check for @extends
    $layout = null;
    if (preg_match('/@extends\([\'"](.+?)[\'"]\)/', $content, $matches)) {
        $layout = $matches[1];
        $content = str_replace($matches[0], '', $content);
    }

    // Extract sections
    $sections = [];
    if (preg_match_all('/@section\([\'"](.+?)[\'"]\s*,\s*[\'"](.+?)[\'"]\)/', $content, $singleMatches, PREG_SET_ORDER)) {
        foreach ($singleMatches as $m) {
            $sections[$m[1]] = $m[2];
            $content = str_replace($m[0], '', $content);
        }
    }

    if (preg_match_all('/@section\([\'"](.+?)[\'"]\)(.*?)@endsection/s', $content, $blockMatches, PREG_SET_ORDER)) {
        foreach ($blockMatches as $m) {
            $sections[$m[1]] = $m[2];
            $content = str_replace($m[0], '', $content);
        }
    }

    // If layout exists, load layout and inject sections
    if ($layout) {
        $layoutFile = __DIR__ . '/../resources/views/' . str_replace('.', '/', $layout) . '.blade.php';
        if (file_exists($layoutFile)) {
            $layoutContent = file_get_contents($layoutFile);

            // Replace @yield
            $finalHtml = preg_replace_callback('/@yield\([\'"](.+?)[\'"](?:\s*,\s*[\'"](.*?)[\'"])?\)/', function ($m) use ($sections) {
                $sec = $m[1];
                $default = $m[2] ?? '';
                return $sections[$sec] ?? $default;
            }, $layoutContent);

            $content = $finalHtml;
        }
    }

    // Evaluate Blade template PHP logic cleanly
    // Convert Blade tags to PHP
    $compiled = $content;
    $compiled = preg_replace('/@csrf/', '<input type="hidden" name="_token" value="dummy-csrf-token">', $compiled);
    $compiled = preg_replace('/\{\{\-\-(.+?)\-\-\}\}/s', '', $compiled);
    $compiled = preg_replace('/\{\{\s*(.+?)\s*\}\}/', '<?= htmlspecialchars($1 ?? "", ENT_QUOTES, "UTF-8") ?>', $compiled);
    $compiled = preg_replace('/\{!!\s*(.+?)\s*!!\}/', '<?= $1 ?? "" ?>', $compiled);
    $compiled = preg_replace('/@if\s*\((.+?)\)/', '<?php if ($1): ?>', $compiled);
    $compiled = preg_replace('/@elseif\s*\((.+?)\)/', '<?php elseif ($1): ?>', $compiled);
    $compiled = preg_replace('/@else/', '<?php else: ?>', $compiled);
    $compiled = preg_replace('/@endif/', '<?php endif; ?>', $compiled);
    $compiled = preg_replace('/@foreach\s*\((.+?)\)/', '<?php foreach ($1): ?>', $compiled);
    $compiled = preg_replace('/@endforeach/', '<?php endforeach; ?>', $compiled);
    $compiled = preg_replace('/@empty\s*\((.+?)\)/', '<?php if (empty($1)): ?>', $compiled);

    // Render using output buffer
    ob_start();
    try {
        eval('?>' . $compiled);
    } catch (\Throwable $e) {
        ob_end_clean();
        echo "Rendering error: " . $e->getMessage() . " on line " . $e->getLine();
        exit;
    }
    return ob_get_clean();
}
