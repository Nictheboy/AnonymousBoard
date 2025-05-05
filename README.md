# 匿名墙 (Anonymous Board)

一个简单的匿名留言墙，使用 PHP 和 MySQL 构建。

## 功能特点

- 无需注册即可发布内容
- 支持匿名或使用昵称发布
- 简洁美观的界面
- 使用 Docker 容器化部署

## 技术栈

- PHP 8.0
- MySQL 8.0
- Docker & Docker Compose

## 如何运行

### 前提条件

- 安装 [Docker](https://www.docker.com/get-started)
- 安装 [Docker Compose](https://docs.docker.com/compose/install/)

### 步骤

1. 克隆仓库

```bash
git clone <repository-url>
cd AnonymousBoard
```

2. 启动 Docker 容器

```bash
docker-compose up -d
```

3. 访问应用

在浏览器中打开 [http://localhost:8080](http://localhost:8080)

4. 初始化数据库

首次运行时，点击页面底部的"初始化数据库"链接来创建必要的数据库表。

## 项目结构

```
.
├── docker-compose.yml        # Docker 配置文件
├── src                       # 源代码目录
│   ├── config                # 配置文件
│   │   └── database.php      # 数据库配置
│   ├── css                   # 样式文件
│   │   └── style.css         # 主样式表
│   ├── includes              # 公共函数和代码片段
│   │   └── functions.php     # 辅助函数
│   ├── index.php             # 主页
│   ├── init_db.php           # 数据库初始化脚本
│   └── post.php              # 处理帖子提交
```
