<?php
/**
 * WordPress 配置文件模板
 *
 * 使用方式：复制此文件为 wp-config.php，填入真实信息
 * 不要将 wp-config.php 提交到 Git！
 */

// ===== 数据库设置 =====
define('DB_NAME', 'ceramic_tile');
define('DB_USER', 'ceramic_admin');
define('DB_PASSWORD', '请替换为真实数据库密码');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', 'utf8mb4_general_ci');

// ===== 数据表前缀 =====
$table_prefix = 'ct_';

// ===== 调试模式 =====
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);

// ===== 性能优化 =====
define('WP_POST_REVISIONS', 10);         // 保留最近 10 个修订版本
define('AUTOSAVE_INTERVAL', 300);        // 自动保存间隔 5 分钟
define('WP_MEMORY_LIMIT', '256M');

// ===== 文件编辑 =====
define('DISALLOW_FILE_EDIT', true);      // 禁止后台编辑主题/插件文件（安全）

// ===== 自动更新 =====
define('WP_AUTO_UPDATE_CORE', true);     // 自动更新 WordPress 核心

/**
 * 从 https://api.wordpress.org/secret-key/1.1/salt/ 获取
 * 复制整段粘贴到下方：
 */
define('AUTH_KEY',         '请替换');
define('SECURE_AUTH_KEY',  '请替换');
define('LOGGED_IN_KEY',    '请替换');
define('NONCE_KEY',        '请替换');
define('AUTH_SALT',        '请替换');
define('SECURE_AUTH_SALT', '请替换');
define('LOGGED_IN_SALT',   '请替换');
define('NONCE_SALT',       '请替换');

/* 不要编辑此行之后的内容 */

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

require_once ABSPATH . 'wp-settings.php';
