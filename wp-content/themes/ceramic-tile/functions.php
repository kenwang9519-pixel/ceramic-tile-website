<?php
/**
 * Ceramic Tile 子主题函数
 *
 * 功能：
 * - 加载父主题（Astra）样式 + 自定义样式
 * - WooCommerce 目录模式（关闭购物车、价格、购买按钮）
 * - 自定义产品分类法（空间、材质、尺寸）
 * - 后台菜单简化（非管理员角色）
 *
 * @package Ceramic_Tile
 * @version 1.0.0
 */

// ============================================================
// 1. 样式加载
// ============================================================

function ceramic_tile_enqueue_styles() {
    // 父主题样式
    wp_enqueue_style(
        'astra-parent-style',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme()->parent()->get('Version')
    );

    // 子主题样式声明
    wp_enqueue_style(
        'ceramic-tile-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('astra-parent-style'),
        '1.0.0'
    );

    // 品牌自定义样式（主要样式覆写）
    wp_enqueue_style(
        'ceramic-tile-custom',
        get_stylesheet_directory_uri() . '/assets/css/custom.css',
        array('astra-parent-style'),
        '1.0.0'
    );
}
add_action('wp_enqueue_scripts', 'ceramic_tile_enqueue_styles');

// ============================================================
// 2. WooCommerce 目录模式
// ============================================================

// 移除购买功能
add_filter('woocommerce_is_purchasable', '__return_false');

// 移除所有价格显示
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
add_filter('woocommerce_get_price_html', '__return_empty_string');

// 「加入购物车」→「立即询价」
add_filter('woocommerce_product_add_to_cart_text', 'ceramic_tile_inquiry_text');
add_filter('woocommerce_product_single_add_to_cart_text', 'ceramic_tile_inquiry_text');
function ceramic_tile_inquiry_text() {
    return '立即询价';
}

// 点击「立即询价」→ 跳转联系页面（带产品名称参数）
add_filter('woocommerce_product_add_to_cart_url', 'ceramic_tile_inquiry_url', 10, 2);
function ceramic_tile_inquiry_url($url, $product) {
    return home_url('/contact/?product=' . urlencode($product->get_name()));
}

// 隐藏不需要的 WooCommerce 页面（购物车、结算、我的账户）
add_action('init', 'ceramic_tile_hide_wc_pages');
function ceramic_tile_hide_wc_pages() {
    $pages_to_draft = array(
        get_option('woocommerce_cart_page_id'),
        get_option('woocommerce_checkout_page_id'),
        get_option('woocommerce_myaccount_page_id'),
    );
    foreach ($pages_to_draft as $page_id) {
        if ($page_id) {
            wp_update_post(array(
                'ID'          => $page_id,
                'post_status' => 'draft',
            ));
        }
    }
}

// 移除 WooCommerce 不需要的管理菜单子项
add_action('admin_menu', 'ceramic_tile_remove_wc_submenus', 999);
function ceramic_tile_remove_wc_submenus() {
    remove_submenu_page('woocommerce', 'wc-settings');
    remove_submenu_page('woocommerce', 'wc-status');
    remove_submenu_page('woocommerce', 'wc-addons');
    remove_submenu_page('woocommerce', 'wc-reports');
}

// ============================================================
// 3. 自定义产品分类法
// ============================================================

add_action('init', 'ceramic_tile_register_product_taxonomies');

function ceramic_tile_register_product_taxonomies() {
    // 空间分类（客厅、餐厅、浴室、厨房、商业空间）
    register_taxonomy('space', 'product', array(
        'label'        => '使用空间',
        'labels'       => array(
            'name'          => '使用空间',
            'singular_name' => '使用空间',
            'search_items'  => '搜索空间',
            'all_items'     => '全部空间',
            'edit_item'     => '编辑空间',
            'add_new_item'  => '添加新空间',
        ),
        'hierarchical' => true,
        'public'       => true,
        'show_ui'      => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'query_var'    => true,
        'rewrite'      => array('slug' => 'space'),
    ));

    // 材质分类（抛光砖、釉面砖、仿古砖、岩板、马赛克）
    register_taxonomy('material', 'product', array(
        'label'        => '材质',
        'labels'       => array(
            'name'          => '材质',
            'singular_name' => '材质',
            'search_items'  => '搜索材质',
            'all_items'     => '全部材质',
            'edit_item'     => '编辑材质',
            'add_new_item'  => '添加新材质',
        ),
        'hierarchical' => true,
        'public'       => true,
        'show_ui'      => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'query_var'    => true,
        'rewrite'      => array('slug' => 'material'),
    ));

    // 尺寸分类（300x600、600x600、600x1200、750x1500、900x1800+）
    register_taxonomy('size', 'product', array(
        'label'        => '规格尺寸',
        'labels'       => array(
            'name'          => '规格尺寸',
            'singular_name' => '规格尺寸',
            'search_items'  => '搜索尺寸',
            'all_items'     => '全部尺寸',
            'edit_item'     => '编辑尺寸',
            'add_new_item'  => '添加新尺寸',
        ),
        'hierarchical' => true,
        'public'       => true,
        'show_ui'      => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'query_var'    => true,
        'rewrite'      => array('slug' => 'size'),
    ));
}

// 在产品列表页显示自定义分类筛选
add_action('restrict_manage_posts', 'ceramic_tile_product_filter_dropdowns');
function ceramic_tile_product_filter_dropdowns() {
    global $typenow;
    if ($typenow !== 'product') {
        return;
    }

    $taxonomies = array(
        'space'    => '空间',
        'material' => '材质',
        'size'     => '尺寸',
    );

    foreach ($taxonomies as $tax_slug => $tax_name) {
        $taxonomy = get_taxonomy($tax_slug);
        if (!$taxonomy) continue;

        $selected = isset($_GET[$tax_slug]) ? $_GET[$tax_slug] : '';
        wp_dropdown_categories(array(
            'show_option_all' => '全部' . $tax_name,
            'taxonomy'        => $tax_slug,
            'name'            => $tax_slug,
            'selected'        => $selected,
            'hierarchical'    => true,
            'value_field'     => 'slug',
        ));
    }
}

// ============================================================
// 4. 后台菜单简化（非管理员角色）
// ============================================================

add_action('admin_menu', 'ceramic_tile_simplify_admin_menu', 999);

function ceramic_tile_simplify_admin_menu() {
    $user = wp_get_current_user();

    // 超级管理员不受限制
    if (in_array('administrator', $user->roles)) {
        return;
    }

    // 隐藏菜单
    remove_menu_page('edit.php');              // 文章
    remove_menu_page('edit-comments.php');     // 评论
    remove_menu_page('tools.php');             // 工具
    remove_menu_page('options-general.php');   // 设置
    remove_menu_page('plugins.php');           // 插件
    remove_menu_page('themes.php');            // 外观
    remove_menu_page('users.php');             // 用户
    remove_menu_page('upload.php');            // 媒体（保留在产品编辑中）

    // 隐藏特定插件的设置页面
    remove_menu_page('wpcf7');                 // Contact Form 7 设置
    remove_menu_page('wpseo_workouts');        // Yoast SEO 高级
    remove_menu_page('wordfence');             // Wordfence 设置

    // 对日常管理员保留：产品、页面、Flamingo（询价记录）
}

// 隐藏管理工具栏中不需要的节点
add_action('admin_bar_menu', 'ceramic_tile_simplify_admin_bar', 999);
function ceramic_tile_simplify_admin_bar($wp_admin_bar) {
    $user = wp_get_current_user();
    if (in_array('administrator', $user->roles)) {
        return;
    }

    $wp_admin_bar->remove_node('new-post');
    $wp_admin_bar->remove_node('comments');
    $wp_admin_bar->remove_node('wp-logo');
    $wp_admin_bar->remove_node('customize');
    $wp_admin_bar->remove_node('updates');
}

// ============================================================
// 5. 品牌信息（供模板使用）
// ============================================================

// 获取公司名称（从 WordPress 站点标题）
function ceramic_tile_company_name() {
    return get_bloginfo('name');
}

// 获取品牌红色（便于在模板中引用）
function ceramic_tile_brand_red() {
    return '#c41230';
}

// ============================================================
// 6. 安全增强
// ============================================================

// 隐藏 WordPress 版本号
remove_action('wp_head', 'wp_generator');

// 禁止 XML-RPC（减少攻击面）
add_filter('xmlrpc_enabled', '__return_false');

// 限制登录尝试（简单版，正式环境建议用 Wordfence 管理）
add_filter('login_errors', function() {
    return '登录信息不正确，请重试。';
});
