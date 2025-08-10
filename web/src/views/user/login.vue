<template>
  <div class="auth-wrapper">
    <div class="login-background absolute left-0 top-0 size-full"></div>
    <div class="auth-content">
      <div class="auth-bg">
        <span class="r"></span>
        <span class="r s"></span>
        <span class="r s"></span>
        <span class="r"></span>
      </div>
      <div class="card">
        <div class="card-body">
          <h3 class="login-text text-center">登录</h3>
          <span class="text-muted text-center"> 欢迎使用{{ title }}系统</span>
          <a-form
            ref="form"
            :model="state.form"
            :rules="state.rules"
            class="login-form"
            @finish="submitForm"
          >
            <a-form-item name="username">
              <a-input
                size="large"
                allowClear
                v-model:value="state.form.username"
                placeholder="请输入用户名"
              >
                <template #prefix>
                  <s-icon type="UserOutlined" />
                </template>
              </a-input>
            </a-form-item>

            <a-form-item name="password">
              <a-input-password
                size="large"
                v-model:value="state.form.password"
                placeholder="请输入密码"
              >
                <template #prefix>
                  <s-icon type="LockOutlined" />
                </template>
              </a-input-password>
            </a-form-item>
            <a-form-item>
              <a-checkbox v-model:checked="state.remAccount">记住账号</a-checkbox>
            </a-form-item>
            <a-button
              :loading="state.loading"
              type="primary"
              size="large"
              htmlType="submit"
              style="width: 100%; margin-bottom: 30px"
              >登录</a-button
            >
          </a-form>
          <p class="mb-2 text-muted">Copyright © {{ copyright.company }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useUserStore } from "@/store/modules/user";
import { useRouter } from "vue-router";
import config from "@/config";
import storage from "@/utils/storage";
const { message } = useMessage();
const title = computed(() => config.appName);
const copyright = computed(() => config.copyright);
const state = reactive({
  form: {
    username: "",
    password: ""
  },
  rules: {
    username: [{ required: true, message: "请输入用户名" }],
    password: [{ required: true, message: "请输入密码" }]
  },
  remAccount: false,
  loading: false
});

const userStore = useUserStore();

//提交表单
const submitForm = () => {
  state.loading = true;
  userStore
    .login(state.form)
    .then(res => {
      res.code == 1 ? loginSuccess() : loginFail(res);
    })
    .catch(res => loginFail(res));
};

const route = useRouter();

const loginSuccess = () => {
  storage.set("account", {
    remember: state.remAccount,
    username: state.remAccount ? state.form.username : ""
  });
  route.push({ path: "/" });
  state.loading = false;
};

const loginFail = (res: ResponseBody) => {
  state.loading = false;
  message.error(res.msg, 1.5);
};

onMounted(() => {
  const data = storage.get("account");
  if (data?.remember) {
    state.remAccount = data.remember;
    state.form.username = data.username;
  }
});
</script>

<style lang="less" scoped>
@keyframes floating {
  from {
    transform: rotate(0deg) translate(-10px) rotate(0deg);
  }

  to {
    transform: rotate(360deg) translate(-10px) rotate(-360deg);
  }
}

.auth-wrapper {
  font-family: Open Sans, sans-serif;
  font-size: 14px;
  font-weight: 400;
  background: var(--ant-color-bg-layout);
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  min-width: 100%;
  min-height: 100vh;
}

.auth-wrapper .auth-content {
  position: relative;
  width: 390px;
  padding: 15px;
  z-index: 5;
}

.card {
  border-radius: 0;
  box-shadow: 0 1px 20px 0 rgb(69 90 100 / 8%);
  border: none;
  margin-bottom: 30px;
  transition: all 0.5s ease-in-out;
  position: relative;
  margin-bottom: 0;
  display: flex;
  flex-direction: column;
  min-width: 0;
  word-wrap: break-word;
  background-color: var(--ant-color-bg-container);
  background-clip: border-box;
  border-radius: var(--ant-border-radius);
}

.auth-wrapper .auth-bg .r {
  position: absolute;
  width: 300px;
  height: 300px;
  border-radius: 50%;
}

.auth-wrapper .auth-bg .r:nth-child(odd) {
  animation: floating 7s infinite;
}

.auth-wrapper .auth-bg .r:first-child {
  top: -100px;
  right: -100px;
  background: linear-gradient(-135deg, #1de9b6 0%, #1dc4e9 100%);
}

.auth-wrapper .auth-bg .r.s:nth-child(2) {
  top: 150px;
  right: -150px;
  background: #04a9f5;
}

.auth-wrapper .auth-bg .r.s {
  width: 20px;
  height: 20px;
}

.auth-wrapper .auth-bg .r:nth-child(even) {
  animation: floating 9s infinite;
}

.auth-wrapper .auth-bg .r.s:nth-child(3) {
  left: -150px;
  bottom: 150px;
  background: #1de9b6;
}

.auth-wrapper .auth-bg .r:last-child {
  left: -100px;
  bottom: -100px;
  background: linear-gradient(-135deg, #899fd4 0%, #a389d4 100%);
}

.card .card-block,
.card .card-body {
  padding: 30px 25px;
}

.login-text {
  margin-bottom: 1.5rem;
  font-size: 26px;
  font-weight: 400;
}

.text-muted {
  color: #6c757d;
  display: inline-block;
  margin-bottom: 20px;
  width: 100%;
  text-align: center;

  a {
    color: #6c757d;
  }
}
</style>
