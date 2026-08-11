# 陶瓷瓷砖企业官网

B2B 陶瓷瓷砖企业官网，基于 WordPress + WooCommerce（目录模式），用于产品展示和线上询价。

## 技术栈

- WordPress 6.x（简体中文）
- WooCommerce 9.x（目录模式：只展示不购买）
- Astra 主题 + 自定义子主题
- Contact Form 7 + Flamingo（询价表单）
- Yoast SEO / WP Super Cache / Wordfence Security / UpdraftPlus

## 环境要求

- PHP 8.2+
- MySQL 8.0+ / MariaDB 10.6+
- Apache 2.4+（mod_rewrite 开启）
- 推荐服务器：阿里云/腾讯云轻量应用服务器 2核2G+

## 本地开发

### 1. 安装 XAMPP

下载 XAMPP（PHP 8.2）：https://www.apachefriends.org/download.html

### 2. 下载 WordPress

```bash
cd C:/xampp/htdocs
# 下载 WordPress 中文版
curl -o wordpress.zip https://cn.wordpress.org/latest-zh_CN.zip
unzip wordpress.zip
```

### 3. 导入数据库

```bash
mysql -u root -p < exports/database.sql
```

### 4. 复制项目文件

将本仓库文件覆盖到 WordPress 安装目录：
```bash
cp -r wp-content/themes/ceramic-tile/ C:/xampp/htdocs/wordpress/wp-content/themes/
```

### 5. 配置 wp-config.php

复制 `config/wp-config-sample.php` 为 `wp-config.php`，填入数据库信息。

### 6. 访问网站

浏览器打开 `http://localhost/wordpress/`  
后台：`http://localhost/wordpress/wp-admin/`

## 部署到生产服务器

1. 购买云服务器（阿里云/腾讯云轻量应用服务器，2核2G，约 600-1000 元/年）
2. 选择 WordPress 镜像或手动安装 LAMP + WordPress
3. 上传子主题：`wp-content/themes/ceramic-tile/` → 服务器相同路径
4. 导入数据库：`exports/database.sql`
5. 修改 `wp-config.php` 数据库连接
6. 在 WordPress 后台 → 外观 → 主题 → 启用 "Ceramic Tile"
7. 安装并启用 `plugins-list.txt` 中列出的所有插件
8. 绑定域名 → 申请 SSL 证书 → 强制 HTTPS
9. 提交域名备案（国内服务器必须，约 20 个工作日）

## 插件清单

见 `plugins-list.txt`

## 默认账号

| 角色 | 用户名 | 用途 |
|------|--------|------|
| 超级管理员 | admin | 完整后台管理 |
| 日常管理 | manager | 产品管理 + 查看询价（简化后台） |

> ⚠️ 首次登录后请立即修改密码！

## 管理员操作手册

见 `admin-manual.md`

---

© 2026 陶瓷瓷砖企业官网

## 项目文件结构

```
ceramic-tile-website/
├── wp-content/
│   └── themes/
│       └── ceramic-tile/          # 自定义子主题（品牌红色配色）
│           ├── style.css          # 主题声明
│           ├── functions.php      # 功能代码 + WooCommerce 目录模式
│           ├── screenshot.png     # 主题预览图
│           └── assets/
│               └── css/
│                   └── custom.css # 品牌自定义样式
├── exports/
│   └── database.sql               # 初始数据库导出
├── config/
│   └── wp-config-sample.php       # 配置文件模板
├── plugins-list.txt               # 必装插件清单
├── admin-manual.md                # 管理员操作手册
├── README.md                      # 本文件
└── .gitignore
```
