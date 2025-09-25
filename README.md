# Speed CRM - 客户关系管理系统

一个基于 [SpeedAdmin](https://atsep.top/docs) 开发的客户关系管理系统，提供线索管理、联系人管理、客户管理、商机管理、合同管理、产品管理等核心功能。

>开源不易，欢迎star

<div align="center">
    <p>
        <a href="https://crm.atsep.top/web" target="_blank">演示</a> |
        <a target="_blank" href="https://qm.qq.com/cgi-bin/qm/qr?k=iBE6f00ncZGbbtWN77DXBX8wj2xhtULA&jump_from=webapi&authKey=mN9IgHZ7tSmrGt29+RnuDvPnHyO4IH1Hp2lg3/bXPKT7yrwSHC7Y8SOdyvv30yhH">联系作者 | </a>
        <a href="/public/readme/pay.png" target="_blank">打赏作者</a> |
        <a href="https://atsep.top/docs" target="_blank">SpeedAdmin</a>
    </p>
</div>

## 演示

地址: [https://crm.atsep.top/web](https://crm.atsep.top/web)
账号：demo  密码：123456

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
git clone https://gitee.com/fantasyc/speedcrm.git
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

## 🤝 注意事项

项目仅用于个人学习，请勿用于商业用途。

## 📄 许可证

本项目基于 [MIT](LICENSE) 协议开源。

## 📞 联系方式

- 问题反馈: [Issues](https://gitee.com/fantasyc/speedcrm/issues)
- 邮箱: [atsep@qq.com](atsep@qq.com)

