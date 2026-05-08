<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>登入與註冊 — 圖書館系統</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Noto+Serif+TC:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/element-plus/dist/index.css">

<style>
:root {
  --bg-deep:    #0d1117;
  --bg-card:    #161b22;
  --bg-hover:   #1c2333;
  --border:     #30363d;
  --gold:       #d4a853;
  --gold-dim:   #8b6914;
  --gold-glow:  rgba(212, 168, 83, 0.15);
  --text-primary: #e6edf3;
  --text-sec:   #8b949e;
  --text-muted: #484f58;
  --red:        #f85149;
  --green:      #3fb950; /* 新增綠色用於成功訊息 */
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
  background: var(--bg-deep);
  font-family: 'Noto Serif TC', serif;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background-image:
    radial-gradient(ellipse 60% 60% at 50% 0%, rgba(212,168,83,0.08) 0%, transparent 70%),
    url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2330363d' fill-opacity='0.25'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

/* ===== Login Card ===== */
.login-wrap {
  width: 100%;
  max-width: 420px;
  padding: 20px;
  animation: fadeUp 0.5s ease both;
}

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(24px); }
  to   { opacity: 1; transform: translateY(0); }
}

.login-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 44px 40px 40px;
  box-shadow: 0 24px 64px rgba(0,0,0,0.5);
}

/* Header */
.login-header {
  text-align: center;
  margin-bottom: 36px;
}
.login-icon {
  width: 56px; height: 56px;
  background: var(--gold-glow);
  border: 1px solid var(--gold-dim);
  border-radius: 14px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 26px;
  margin-bottom: 16px;
}
.login-title {
  font-family: 'Playfair Display', serif;
  font-size: 26px;
  color: var(--gold);
  letter-spacing: 0.04em;
  margin-bottom: 6px;
}
.login-subtitle {
  font-size: 12px;
  color: var(--text-muted);
  letter-spacing: 0.1em;
  text-transform: uppercase;
}

/* Form */
.form-group {
  margin-bottom: 18px;
}
.form-label {
  display: block;
  font-size: 12px;
  color: var(--text-muted);
  letter-spacing: 0.08em;
  text-transform: uppercase;
  margin-bottom: 8px;
}

/* Element Plus overrides */
.el-input__wrapper {
  background: var(--bg-deep) !important;
  border-color: var(--border) !important;
  box-shadow: none !important;
  border-radius: 8px !important;
  padding: 4px 12px !important;
}
.el-input__wrapper:hover,
.el-input__wrapper.is-focus {
  border-color: var(--gold-dim) !important;
  box-shadow: 0 0 0 2px var(--gold-glow) !important;
}
.el-input__inner {
  color: var(--text-primary) !important;
  font-family: 'Noto Serif TC', serif;
  font-size: 14px !important;
  height: 38px !important;
}
.el-input__prefix-inner { color: var(--text-muted) !important; }

.btn-login {
  width: 100%;
  height: 44px;
  background: var(--gold) !important;
  border-color: var(--gold) !important;
  color: #0d1117 !important;
  font-family: 'Noto Serif TC', serif;
  font-size: 15px !important;
  font-weight: 600 !important;
  border-radius: 8px !important;
  letter-spacing: 0.06em;
  transition: all 0.2s;
  margin-top: 8px;
}
.btn-login:hover:not(:disabled) {
  background: #e8bc5e !important;
  border-color: #e8bc5e !important;
  transform: translateY(-1px);
  box-shadow: 0 4px 16px rgba(212,168,83,0.3) !important;
}

/* Messages */
.error-msg {
  background: rgba(248,81,73,0.1);
  border: 1px solid rgba(248,81,73,0.3);
  border-radius: 8px;
  padding: 10px 14px;
  color: var(--red);
  font-size: 13px;
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.success-msg {
  background: rgba(63,185,80,0.1);
  border: 1px solid rgba(63,185,80,0.3);
  border-radius: 8px;
  padding: 10px 14px;
  color: var(--green);
  font-size: 13px;
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  gap: 8px;
}

/* Divider */
.login-divider {
  border: none;
  border-top: 1px solid var(--border);
  margin: 28px 0 20px;
}

.login-hint {
  text-align: center;
  font-size: 12px;
  color: var(--text-muted);
  line-height: 1.8;
}
.hint-badge {
  display: inline-block;
  background: var(--bg-hover);
  border: 1px solid var(--border);
  border-radius: 4px;
  padding: 1px 7px;
  font-size: 11px;
  color: var(--text-sec);
  margin: 0 2px;
}

/* 註冊專用樣式 */
.verify-group { display: flex; gap: 8px; }
.btn-verify {
  height: 46px; background: var(--bg-hover) !important;
  border: 1px solid var(--border) !important; color: var(--text-primary) !important;
  border-radius: 8px !important; font-family: 'Noto Serif TC', serif;
}
.btn-verify:not(:disabled):hover { border-color: var(--gold-dim) !important; color: var(--gold) !important; }
.switch-mode { text-align: center; font-size: 13px; color: var(--text-sec); margin-top: 10px; }
.switch-mode a { color: var(--gold); text-decoration: none; cursor: pointer; font-weight: 500; }
.switch-mode a:hover { text-decoration: underline; }
</style>
</head>
<body>

<div id="app">
  <div class="login-wrap">
    <div class="login-card">

      <div class="login-header">
        <div class="login-icon">📚</div>
        <div class="login-title">圖書館系統</div>
        <div class="login-subtitle">{{ isRegister ? 'Create an Account' : 'Library Management System' }}</div>
      </div>

      <div v-if="errorMsg" class="error-msg"><span>⚠</span> {{ errorMsg }}</div>
      <div v-if="successMsg" class="success-msg"><span>✓</span> {{ successMsg }}</div>

      <div v-if="!isRegister">
        <div class="form-group">
          <label class="form-label">學號 / 帳號</label>
          <el-input v-model="form.user_id" placeholder="請輸入學號" @keyup.enter="handleLogin" :disabled="loading" clearable>
            <template #prefix><span style="font-size:15px">👤</span></template>
          </el-input>
        </div>
        <div class="form-group">
          <label class="form-label">密碼</label>
          <el-input v-model="form.password" type="password" placeholder="請輸入密碼" show-password @keyup.enter="handleLogin" :disabled="loading">
            <template #prefix><span style="font-size:15px">🔑</span></template>
          </el-input>
        </div>
        <el-button class="btn-login" :loading="loading" @click="handleLogin">
          {{ loading ? '登入中…' : '登　入' }}
        </el-button>
      </div>

      <div v-else>
        <div class="form-group">
          <label class="form-label">學號 (10碼)</label>
          <el-input v-model="regForm.user_id" placeholder="請輸入 10 碼學號" maxlength="10" :disabled="loading">
            <template #prefix><span style="font-size:15px">👤</span></template>
          </el-input>
        </div>
        <div class="form-group">
          <label class="form-label">姓名</label>
          <el-input v-model="regForm.name" placeholder="請輸入姓名" :disabled="loading">
            <template #prefix><span style="font-size:15px">📝</span></template>
          </el-input>
        </div>
        <div class="form-group">
          <label class="form-label">Email</label>
          <div class="verify-group">
            <el-input v-model="regForm.email" placeholder="輸入常用信箱" :disabled="loading || countdown > 0">
              <template #prefix><span style="font-size:15px">✉</span></template>
            </el-input>
            <el-button class="btn-verify" :disabled="countdown > 0 || !regForm.email" @click="sendVerifyCode" :loading="sendingCode">
              {{ countdown > 0 ? `${countdown}s` : '發送驗證碼' }}
            </el-button>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">6 位數驗證碼</label>
          <el-input v-model="regForm.code" placeholder="請輸入信箱驗證碼" maxlength="6" :disabled="loading">
            <template #prefix><span style="font-size:15px">🛡</span></template>
          </el-input>
        </div>
        <div class="form-group">
          <label class="form-label">設定密碼</label>
          <el-input v-model="regForm.password" type="password" placeholder="請設定登入密碼" show-password :disabled="loading">
            <template #prefix><span style="font-size:15px">🔑</span></template>
          </el-input>
        </div>
        <el-button class="btn-login" :loading="loading" @click="handleRegister">
          {{ loading ? '註冊中…' : '註冊帳號' }}
        </el-button>
      </div>

      <hr class="login-divider">

      <div class="switch-mode">
        <span v-if="!isRegister">還沒有帳號嗎？ <a @click="isRegister = true; clearMsgs()">立即註冊</a></span>
        <span v-else>已經有帳號了？ <a @click="isRegister = false; clearMsgs()">返回登入</a></span>
      </div>

      <div class="login-hint" style="margin-top: 20px;">
        學生登入後可瀏覽書目、借書與還書<br>
        管理員另可新增、編輯、刪除書籍<br>
        <span class="hint-badge">角色由系統管理員設定</span>
      </div>

    </div>
  </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script src="https://unpkg.com/element-plus/dist/index.full.min.js"></script>
<script>
const { createApp, ref, onMounted } = Vue;

createApp({
  setup() {
    // 狀態
    const isRegister = ref(false);
    const loading    = ref(false);
    const sendingCode = ref(false);
    const countdown  = ref(0);
    const errorMsg   = ref('');
    const successMsg = ref('');

    // 表單資料
    const form    = ref({ user_id: '', password: '' });
    const regForm = ref({ user_id: '', name: '', email: '', password: '', code: '' });

    onMounted(async () => {
      try {
        const res = await fetch('api/check_session.php').then(r => r.json());
        if (res.success) redirect(res.data.role);
      } catch {}
    });

    function redirect(role) {
      window.location.href = role === 'admin' ? 'admin.php' : 'index.php';
    }

    function clearMsgs() { errorMsg.value = ''; successMsg.value = ''; }

    // 發送驗證碼
    async function sendVerifyCode() {
      if (!regForm.value.email) { errorMsg.value = '請填寫 Email'; return; }
      sendingCode.value = true;
      clearMsgs();
      try {
        const response = await fetch('api/send_verification.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email: regForm.value.email }),
        });
        
        // 1. 先把後端回傳的東西當作純文字拿出來
        const rawText = await response.text();
        
        // 2. 用正規表達式把 JSON 括號 {} 外面的所有隱形字元、BOM、報錯雜訊全部濾掉
        const cleanText = rawText.substring(rawText.indexOf('{'), rawText.lastIndexOf('}') + 1);
        
        // 3. 安全解析 JSON
        const res = JSON.parse(cleanText);

        if (res.success) {
          successMsg.value = '驗證碼已寄出，請檢查信箱';
          countdown.value = 60;
          const timer = setInterval(() => {
            countdown.value--;
            if (countdown.value <= 0) clearInterval(timer);
          }, 1000);
        } else { 
          errorMsg.value = res.message; 
        }
      } catch (e) { 
        console.error("解析失敗，原始回傳內容可能非JSON", e);
        errorMsg.value = '解析失敗，請按 F12 查看 Console'; 
      }
      finally { sendingCode.value = false; }
    }

    // 註冊邏輯
    async function handleRegister() {
      if (!regForm.value.user_id || !regForm.value.name || !regForm.value.password || !regForm.value.code) {
        errorMsg.value = '請填寫所有欄位'; return;
      }
      loading.value = true;
      clearMsgs();
      try {
        const res = await fetch('api/register.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(regForm.value),
        }).then(r => r.json());

        if (res.success) {
          successMsg.value = '註冊成功！請返回登入';
          setTimeout(() => { isRegister.value = false; clearMsgs(); }, 2000);
        } else { errorMsg.value = res.message; }
      } catch { errorMsg.value = '註冊失敗'; }
      finally { loading.value = false; }
    }

    // 登入邏輯
    async function handleLogin() {
      clearMsgs();
      if (!form.value.user_id || !form.value.password) { errorMsg.value = '請輸入學號與密碼'; return; }
      loading.value = true;
      try {
        const res = await fetch('api/login.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(form.value),
        }).then(r => r.json());

        if (res.success) {
          localStorage.setItem('user_id',   res.data.user_id);
          localStorage.setItem('user_name', res.data.name);
          redirect(res.data.role);
        } else { errorMsg.value = res.message || '登入失敗'; }
      } catch { errorMsg.value = '網路錯誤'; }
      finally { loading.value = false; }
    }

    return { 
      isRegister, loading, sendingCode, countdown, errorMsg, successMsg,
      form, regForm, handleLogin, handleRegister, sendVerifyCode, clearMsgs 
    };
  }
}).use(ElementPlus).mount('#app');
</script>
</body>
</html>