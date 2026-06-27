# Super CRM - 客户关系管理系统

一个基于 [SpeedAdmin](https://atsep.top/docs) 开发的客户关系管理系统，提供线索管理、联系人管理、客户管理、商机管理、合同管理、产品管理等核心功能。

## 特性

### 🔥 现代化全栈开发技术栈

#### Thinkphp8 + Vue3 + TypeScript + Ant Design Vue

基于最新技术生态构建，提供高效、稳定、类型安全的开发体验。

---

#### 🔐 安全与身份认证

**JWT 身份认证：** 基于 Token 的无状态鉴权，保障系统安全。

**Token 无感刷新：** 自动续签 Token，提升用户体验。

**RBAC 权限控制：** 基于角色的访问控制，精细化权限管理（菜单、按钮、API）。

---

#### 📊 系统功能

**✅ 操作日志记录：** 完整追踪用户行为，支持操作审计。

**🚀 代码生成器：** 一键生成 CRUD 代码，提升开发效率。

**🎨 多主题 & 暗黑模式：** 支持动态切换主题，适配不同场景需求。

**⚙️ 个性化配置：** 支持界面布局、路由动画灵活配置。

---

#### 🛠 技术特色

**前后端分离架构：** 职责清晰，便于协作与维护。

**TypeScript 支持：** 前端类型安全，减少运行时错误。

**Ant Design Vue：** 企业级 UI 组件库，开箱即用，风格统一。

**高效开发体验：** 丰富的前端组件 + 一键生成 CRUD 代码，提升开发效率。

## 页面预览

  <img src="/public/readme/1.png" alt="" />
  <img src="/public/readme/2.png" alt="" />
  <img src="/public/readme/3.png" alt="" />
  <img src="/public/readme/4.png" alt="" />
  <img src="/public/readme/5.png" alt="" />

## 🚀 快速开始

### 环境要求

- **PHP**: >= 8.2
- **MySQL**: >= 5.7
- **Node.js**: >= 18

### 后端安装
1. 拉取代码 
```sh
git clone https://github.com/nickywu/junlin-crm.git
```

2. 安装依赖 

```sh
composer install
```
3. 初始化数据

```sh
# 执行之前请先配置好你的数据库连接信息
php think install:database
```
4. 启动 
```sh
php think run
```

### 前端安装

1. **进入前端目录**
```bash
cd web
```

2. **安装依赖**
```bash
npm install
```
#### 配置接口地址
在启动前需要先配置接口地址，在前端项目根目录下的 `.env.development` 文件中，设置 `VITE_API_BASE_URL` 变量，指定开发环境的 API 请求地址


```js
#请求api
VITE_API_BASE_URL='http://127.0.0.1:8000/adminapi' #你的实际接口地址

```
3. 启动 
```sh
npm run dev
```

## 📄 许可证

本项目基于 [MIT](LICENSE) 协议开源。
