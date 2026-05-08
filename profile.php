<?php // profile.php - 個人資料管理 ?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>個人資料 — 圖書館系統</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Noto+Serif+TC:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/element-plus/dist/index.css">

<style>
:root {
  --bg-deep:      #0d1117;
  --bg-card:      #161b22;
  --bg-hover:     #1c2333;
  --border:       #30363d;
  --border-light: #21262d;
  --gold:         #d4a853;
  --gold-dim:     #8b6914;
  --gold-glow:    rgba(212, 168, 83, 0.15);
  --text-primary: #e6edf3;
  --text-sec:     #d4d4d4;
  --text-muted:   #cacaca;
  --green:        #3fb950;
  --red:          #f85149;
  --blue:         #58a6ff;
  --radius:       10px;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
  background: var(--bg-deep);
  color: var(--text-primary);
  font-family: 'Noto Serif TC', serif;
  min-height: 100vh;
  background-image:
    radial-gradient(ellipse 80% 50% at 50% -20%, rgba(212,168,83,0.06) 0%, transparent 60%),
    url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2330363d' fill-opacity='0.3'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

/* ===== Header ===== */
.site-header {
  background: linear-gradient(180deg, #0a0e14 0%, var(--bg-deep) 100%);
  border-bottom: 1px solid var(--border);
  padding: 0 40px;
  position: sticky; top: 0; z-index: 100;
  backdrop-filter: blur(12px);
}
.header-inner {
  max-width: 1000px; margin: 0 auto;
  display: flex; align-items: center; justify-content: space-between;
  height: 68px;
}
.logo { display: flex; align-items: center; gap: 14px; }
.logo-icon {
  width: 40px; height: 40px;
  background: var(--gold-glow); border: 1px solid var(--gold-dim);
  border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 20px;
}
.logo-text { font-family: 'Playfair Display', serif; font-size: 22px; color: var(--gold); letter-spacing: 0.04em; }
.logo-sub  { font-size: 11px; color: var(--text-muted); letter-spacing: 0.12em; text-transform: uppercase; }
.header-right { display: flex; align-items: center; gap: 12px; }

/* ===== Layout ===== */
.page-wrap {
  max-width: 1000px; margin: 0 auto;
  padding: 40px 40px;
  display: grid;
  grid-template-columns: 260px 1fr;
  gap: 28px;
  animation: fadeUp 0.45s ease both;
}
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(18px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ===== Left Panel — Avatar & Info ===== */
.profile-panel {
  position: sticky; top: 96px; height: fit-content;
}
.avatar-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 32px 24px;
  text-align: center;
  margin-bottom: 16px;
}
.avatar {
  width: 80px; height: 80px; border-radius: 50%;
  background: var(--gold-glow);
  border: 2px solid var(--gold-dim);
  display: flex; align-items: center; justify-content: center;
  font-size: 36px; margin: 0 auto 16px;
}
.profile-name {
  font-family: 'Playfair Display', serif;
  font-size: 20px; color: var(--text-primary);
  margin-bottom: 4px;
}
.profile-id {
  font-size: 12px; color: var(--text-muted);
  letter-spacing: 0.1em;
}
.role-badge {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 3px 12px; border-radius: 20px; font-size: 12px;
  margin-top: 10px;
}
.role-admin   { background: var(--gold-glow); border: 1px solid var(--gold-dim); color: var(--gold); }
.role-student { background: rgba(88,166,255,0.1); border: 1px solid rgba(88,166,255,0.3); color: var(--blue); }

.info-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 20px 24px;
}
.info-row {
  display: flex; justify-content: space-between; align-items: center;
  padding: 10px 0;
  border-bottom: 1px solid var(--border-light);
  font-size: 13px;
}
.info-row:last-child { border-bottom: none; }
.info-label { color: var(--text-muted); }
.info-value { color: var(--text-primary); font-weight: 500; }

/* ===== Right Panel — Settings Sections ===== */
.settings-area {}
.section-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  overflow: hidden;
  margin-bottom: 20px;
}
.section-header {
  padding: 18px 24px;
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; gap: 10px;
}
.section-icon { font-size: 18px; }
.section-title {
  font-family: 'Playfair Display', serif;
  font-size: 16px; color: var(--text-primary);
}
.section-desc { font-size: 12px; color: var(--text-muted); margin-left: auto; }
.section-body { padding: 24px; }

/* ===== Form Styles ===== */
.field-group { margin-bottom: 20px; }
.field-label {
  display: block; font-size: 12px; color: var(--text-muted);
  letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 8px;
}
.field-hint { font-size: 11px; color: var(--text-muted); margin-top: 5px; }

/* verify-row */
.verify-row { display: flex; gap: 8px; }

/* Submit row */
.submit-row { display: flex; justify-content: flex-end; margin-top: 8px; }

/* ===== Element Plus Overrides ===== */
.el-input__wrapper {
  background-color: var(--bg-deep) !important;
  box-shadow: 0 0 0 1px var(--border) inset !important;
}
.el-input__wrapper:hover,
.el-input__wrapper.is-focus {
  box-shadow: 0 0 0 1px var(--gold-dim) inset !important;
}
.el-input__inner { color: var(--text-primary) !important; font-family: 'Noto Serif TC', serif; }
.el-input__prefix-inner { color: var(--text-muted) !important; }

.el-button--primary {
  background: var(--gold) !important; border-color: var(--gold) !important;
  color: #0d1117 !important; font-family: 'Noto Serif TC', serif; font-weight: 500;
}
.el-button--primary:hover { background: #e8bc5e !important; border-color: #e8bc5e !important; }
.el-button, .el-button--default { font-family: 'Noto Serif TC', serif; }

.el-alert { border-radius: 8px !important; }

/* back link */
.back-link {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: 13px; color: var(--text-sec);
  text-decoration: none; padding: 6px 12px;
  border: 1px solid var(--border); border-radius: 6px;
  transition: all 0.2s;
}
.back-link:hover { color: var(--gold); border-color: var(--gold-dim); background: var(--gold-glow); }

/* page title */
.page-title {
  font-family: 'Playfair Display', serif;
  font-size: 28px; color: var(--text-primary);
  margin-bottom: 4px;
}
.page-subtitle { font-size: 13px; color: var(--text-muted); margin-bottom: 28px; }
</style>
</head>
<body>
<div id="app">

  <!-- Header -->
  <header class="site-header">
    <div class="header-inner">
      <div class="logo">
        <div class="logo-icon">📚</div>
        <div>
          <div class="logo-text">圖書館管理系統</div>
          <div class="logo-sub">Personal Profile</div>
        </div>
      </div>
      <div class="header-right">
        <a href="index.php" class="back-link">← 回書目列表</a>
        <el-button size="small" @click="handleLogout"
          style="background:var(--bg-hover);border-color:var(--border);color:var(--text-sec);">
          登出
        </el-button>
      </div>
    </div>
  </header>

  <!-- Page Body -->
  <div class="page-wrap">

    <!-- ===== Left: Profile Info ===== -->
    <aside class="profile-panel">
      <div class="avatar-card">
        <div class="avatar">👤</div>
        <div class="profile-name">{{ user.name }}</div>
        <div class="profile-id">{{ user.user_id }}</div>
        <div :class="['role-badge', user.role === 'admin' ? 'role-admin' : 'role-student']">
          {{ user.role === 'admin' ? '⚙ 管理員' : '🎓 學生' }}
        </div>
      </div>
      <div class="info-card">
        <div class="info-row">
          <span class="info-label">學號</span>
          <span class="info-value">{{ user.user_id }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">姓名</span>
          <span class="info-value">{{ user.name }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">Email</span>
          <span class="info-value" style="font-size:12px; word-break:break-all;">{{ user.email }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">角色</span>
          <span class="info-value">{{ user.role === 'admin' ? '管理員' : '學生' }}</span>
        </div>
      </div>
    </aside>

    <!-- ===== Right: Settings ===== -->
    <main class="settings-area">
      <div class="page-title">個人資料設定</div>
      <div class="page-subtitle">管理您的帳號資訊、修改密碼與 Email</div>

      <!-- === 修改姓名 === -->
      <div class="section-card">
        <div class="section-header">
          <span class="section-icon">✏️</span>
          <span class="section-title">修改姓名</span>
        </div>
        <div class="section-body">
          <div class="field-group">
            <label class="field-label">顯示姓名</label>
            <el-input v-model="nameForm.name" placeholder="請輸入新姓名" maxlength="10" show-word-limit>
              <template #prefix><span style="font-size:14px">👤</span></template>
            </el-input>
            <div class="field-hint">姓名最多 10 個字</div>
          </div>
          <div class="submit-row">
            <el-button type="primary" :loading="nameForm.loading" @click="submitName">
              儲存姓名
            </el-button>
          </div>
        </div>
      </div>

      <!-- === 修改密碼 === -->
      <div class="section-card">
        <div class="section-header">
          <span class="section-icon">🔑</span>
          <span class="section-title">修改密碼</span>
          <span class="section-desc">需輸入原密碼驗證</span>
        </div>
        <div class="section-body">
          <div class="field-group">
            <label class="field-label">原密碼</label>
            <el-input v-model="pwdForm.old_password" type="password" show-password placeholder="請輸入目前的密碼">
              <template #prefix><span style="font-size:14px">🔒</span></template>
            </el-input>
          </div>
          <div class="field-group">
            <label class="field-label">新密碼</label>
            <el-input v-model="pwdForm.new_password" type="password" show-password placeholder="請輸入新密碼（至少 6 位）">
              <template #prefix><span style="font-size:14px">🔑</span></template>
            </el-input>
          </div>
          <div class="field-group">
            <label class="field-label">確認新密碼</label>
            <el-input v-model="pwdForm.confirm_password" type="password" show-password placeholder="再次輸入新密碼">
              <template #prefix><span style="font-size:14px">✅</span></template>
            </el-input>
            <div class="field-hint" v-if="pwdForm.new_password && pwdForm.confirm_password">
              <span v-if="pwdForm.new_password === pwdForm.confirm_password" style="color:var(--green)">✓ 兩次密碼一致</span>
              <span v-else style="color:var(--red)">✗ 兩次密碼不一致</span>
            </div>
          </div>
          <div class="submit-row">
            <el-button type="primary" :loading="pwdForm.loading" @click="submitPassword">
              更新密碼
            </el-button>
          </div>
        </div>
      </div>

      <!-- === 修改 Email === -->
      <div class="section-card">
        <div class="section-header">
          <span class="section-icon">✉️</span>
          <span class="section-title">修改 Email</span>
          <span class="section-desc">需透過驗證碼確認</span>
        </div>
        <div class="section-body">
          <div class="field-group">
            <label class="field-label">新 Email</label>
            <div class="verify-row">
              <el-input
                v-model="emailForm.new_email"
                placeholder="請輸入新的 Email"
                :disabled="emailForm.codeSent"
                style="flex:1"
              >
                <template #prefix><span style="font-size:14px">✉</span></template>
              </el-input>
              <el-button
                :disabled="emailForm.countdown > 0 || !emailForm.new_email || emailForm.loading"
                :loading="emailForm.sending"
                @click="sendEmailCode"
                style="background:var(--bg-hover);border-color:var(--border);color:var(--text-sec);white-space:nowrap;"
              >
                {{ emailForm.countdown > 0 ? `${emailForm.countdown}s 後重送` : (emailForm.codeSent ? '重新發送' : '發送驗證碼') }}
              </el-button>
            </div>
            <div class="field-hint" style="color:var(--blue)" v-if="emailForm.codeSent">
              ✓ 驗證碼已寄至 {{ emailForm.new_email }}，請於 5 分鐘內輸入
            </div>
          </div>
          <div class="field-group" v-if="emailForm.codeSent">
            <label class="field-label">驗證碼</label>
            <el-input v-model="emailForm.code" placeholder="請輸入 6 位數驗證碼" maxlength="6">
              <template #prefix><span style="font-size:14px">🛡</span></template>
            </el-input>
          </div>
          <div class="submit-row" v-if="emailForm.codeSent">
            <el-button type="primary" :loading="emailForm.loading" @click="submitEmail">
              驗證並更新 Email
            </el-button>
          </div>
        </div>
      </div>

    </main>
  </div>

</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script src="https://unpkg.com/element-plus/dist/index.full.min.js"></script>
<script>
const { createApp, ref, onMounted } = Vue;

createApp({
  setup() {
    const user = ref({ user_id: '', name: '', email: '', role: '' });

    // ===== Forms =====
    const nameForm = ref({ name: '', loading: false });
    const pwdForm  = ref({ old_password: '', new_password: '', confirm_password: '', loading: false });
    const emailForm = ref({
      new_email: '', code: '', codeSent: false,
      sending: false, loading: false, countdown: 0,
    });

    // ===== API =====
    const api = (url, opts = {}) => fetch(url, opts).then(r => r.json());

    // ===== Auth =====
    async function checkAuth() {
      try {
        const res = await api('api/check_session.php');
        if (!res.success) { window.location.href = 'login.php'; return; }
        user.value = res.data;
        nameForm.value.name = res.data.name;
      } catch { window.location.href = 'login.php'; }
    }

    async function handleLogout() {
      await api('api/logout.php', { method: 'POST' });
      localStorage.clear();
      window.location.href = 'login.php';
    }

    // ===== 修改姓名 =====
    async function submitName() {
      const name = nameForm.value.name.trim();
      if (!name) return ElementPlus.ElMessage.warning('姓名不可為空');
      nameForm.value.loading = true;
      try {
        const res = await api('api/update_profile.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ name }),
        });
        if (res.success) {
          ElementPlus.ElMessage.success(res.message);
          user.value.name = name;
        } else {
          ElementPlus.ElMessage.error(res.message);
        }
      } catch { ElementPlus.ElMessage.error('更新失敗'); }
      finally { nameForm.value.loading = false; }
    }

    // ===== 修改密碼 =====
    async function submitPassword() {
      const { old_password, new_password, confirm_password } = pwdForm.value;
      if (!old_password || !new_password || !confirm_password) {
        return ElementPlus.ElMessage.warning('請填寫所有密碼欄位');
      }
      if (new_password !== confirm_password) {
        return ElementPlus.ElMessage.error('兩次輸入的新密碼不一致');
      }
      pwdForm.value.loading = true;
      try {
        const res = await api('api/update_password.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ old_password, new_password }),
        });
        if (res.success) {
          ElementPlus.ElMessage.success(res.message);
          pwdForm.value = { old_password: '', new_password: '', confirm_password: '', loading: false };
        } else {
          ElementPlus.ElMessage.error(res.message);
        }
      } catch { ElementPlus.ElMessage.error('更新失敗'); }
      finally { pwdForm.value.loading = false; }
    }

    // ===== 發送 Email 驗證碼 =====
    async function sendEmailCode() {
      const email = emailForm.value.new_email.trim();
      if (!email) return ElementPlus.ElMessage.warning('請先輸入新 Email');
      emailForm.value.sending = true;
      try {
        const res = await api('api/send_email_verify.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ new_email: email }),
        });
        if (res.success) {
          ElementPlus.ElMessage.success(res.message);
          emailForm.value.codeSent  = true;
          emailForm.value.countdown = 60;
          const timer = setInterval(() => {
            emailForm.value.countdown--;
            if (emailForm.value.countdown <= 0) clearInterval(timer);
          }, 1000);
        } else {
          ElementPlus.ElMessage.error(res.message);
        }
      } catch { ElementPlus.ElMessage.error('發送失敗'); }
      finally { emailForm.value.sending = false; }
    }

    // ===== 驗證並更新 Email =====
    async function submitEmail() {
      if (!emailForm.value.code) return ElementPlus.ElMessage.warning('請輸入驗證碼');
      emailForm.value.loading = true;
      try {
        const res = await api('api/update_email.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ code: emailForm.value.code }),
        });
        if (res.success) {
          ElementPlus.ElMessage.success(res.message);
          user.value.email = emailForm.value.new_email;
          emailForm.value = {
            new_email: '', code: '', codeSent: false,
            sending: false, loading: false, countdown: 0,
          };
        } else {
          ElementPlus.ElMessage.error(res.message);
        }
      } catch { ElementPlus.ElMessage.error('更新失敗'); }
      finally { emailForm.value.loading = false; }
    }

    onMounted(checkAuth);

    return {
      user, nameForm, pwdForm, emailForm,
      submitName, submitPassword, sendEmailCode, submitEmail,
      handleLogout,
    };
  }
}).use(ElementPlus).mount('#app');
</script>
</body>
</html>
