<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>管理後台 — 圖書館系統</title>
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
  --amber:        #e3b341;
  --radius:       10px;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
  background: var(--bg-deep);
  color: var(--text-primary);
  font-family: 'Noto Serif TC', serif;
  min-height: 100vh;
  background-image:
    radial-gradient(ellipse 80% 40% at 50% -10%, rgba(212,168,83,0.05) 0%, transparent 60%),
    url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2330363d' fill-opacity='0.25'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
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
  max-width: 1500px; margin: 0 auto;
  display: flex; align-items: center; justify-content: space-between;
  height: 68px;
}
.logo { display: flex; align-items: center; gap: 14px; }
.logo-icon {
  width: 40px; height: 40px;
  background: var(--gold-glow);
  border: 1px solid var(--gold-dim);
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  font-size: 20px;
}
.logo-text { font-family: 'Playfair Display', serif; font-size: 22px; color: var(--gold); letter-spacing: 0.04em; }
.logo-sub  { font-size: 11px; color: var(--text-muted); letter-spacing: 0.12em; text-transform: uppercase; }

.admin-badge {
  display: inline-flex; align-items: center; gap: 6px;
  background: rgba(212,168,83,0.12);
  border: 1px solid var(--gold-dim);
  border-radius: 20px;
  padding: 4px 12px;
  font-size: 12px;
  color: var(--gold);
  letter-spacing: 0.08em;
}

.header-right { display: flex; align-items: center; gap: 16px; }
.user-info { text-align: right; }
.user-name { font-size: 14px; color: var(--text-primary); }
.user-role { font-size: 11px; color: var(--text-muted); }

/* ===== Layout ===== */
.main-layout {
  max-width: 1500px; /* 從 1400px 調大一點點，容納三欄 */
  margin: 0 auto;
  padding: 32px 40px;
  display: grid;
  /* 原本是 240px 1fr，現在改成: 左邊導覽(240px) | 中間主畫面(1fr) | 右邊分類(260px) */
  grid-template-columns: 240px 1fr 260px;
  gap: 28px;
  animation: fadeUp 0.4s ease both;
  justify-content: center;
}
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(16px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ===== Sidebar ===== */
.sidebar { position: sticky; top: 100px; height: fit-content; }
.sidebar-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 24px;
  margin-bottom: 16px;
}
.sidebar-title {
  font-family: 'Playfair Display', serif;
  font-size: 12px; color: var(--gold);
  letter-spacing: 0.12em; text-transform: uppercase;
  margin-bottom: 16px; padding-bottom: 10px;
  border-bottom: 1px solid var(--border-light);
}

/* ===== 右側欄與分類列表設計 ===== */
.sidebar-right { position: sticky; top: 100px; height: fit-content; }
.category-list {
  max-height: 500px; /* 限制高度，超過可滾動 */
  overflow-y: auto;
  margin-right: -10px; 
  padding-right: 10px;
}
.category-list::-webkit-scrollbar { width: 4px; }
.category-list::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

/* 大分類 */
.cat-main {
  font-weight: 500;
  color: var(--text-primary);
  margin-top: 8px; 
  display: flex; align-items: center; gap: 6px; padding: 6px 12px;
}
/* 展開收合箭頭 */
.toggle-icon {
  display: inline-block; width: 14px; cursor: pointer;
  color: var(--text-muted); font-size: 10px; transition: color 0.2s; text-align: center;
}
.toggle-icon:hover { color: var(--gold); }
.cat-name { flex: 1; cursor: pointer; }

/* 子分類縮排 */
.cat-sub {
  padding-left: 36px !important; font-size: 12px !important;
  color: var(--text-sec) !important; margin-bottom: 2px !important;
}
.cat-sub:hover { color: var(--text-primary) !important; }
.cat-sub.active { color: var(--gold) !important; }

/* 全部分類點點樣式 */
.status-filter-btn {
  display: flex; align-items: center; gap: 8px;
  padding: 8px 12px; border-radius: 6px; cursor: pointer;
  margin-bottom: 4px; font-size: 13px; color: var(--text-sec);
  transition: all 0.2s; border: 1px solid transparent;
}
.status-filter-btn:hover { background: var(--bg-hover); color: var(--text-primary); }
.status-filter-btn.active { background: var(--gold-glow); border-color: var(--gold-dim); color: var(--gold); }
.status-dot { width: 8px; height: 8px; border-radius: 50%; }
.dot-all { background: var(--text-muted); }

.nav-item {
  display: flex; align-items: center; gap: 10px;
  padding: 9px 12px; border-radius: 7px;
  cursor: pointer; font-size: 13px; color: var(--text-sec);
  transition: all 0.2s; margin-bottom: 2px;
  border: 1px solid transparent;
}
.nav-item:hover { background: var(--bg-hover); color: var(--text-primary); }
.nav-item.active {
  background: var(--gold-glow);
  border-color: var(--gold-dim);
  color: var(--gold);
}
.nav-icon { font-size: 16px; width: 20px; text-align: center; }

/* Stat cards */
.stat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.stat-card {
  background: var(--bg-deep);
  border: 1px solid var(--border-light);
  border-radius: 8px;
  padding: 14px 12px;
  text-align: center;
}
.stat-num {
  font-family: 'Playfair Display', serif;
  font-size: 24px; color: var(--gold); line-height: 1;
}
.stat-label { font-size: 11px; color: var(--text-muted); margin-top: 4px; }

/* ===== Content ===== */
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
  display: flex; align-items: center; justify-content: space-between;
}
.section-title {
  font-family: 'Playfair Display', serif;
  font-size: 16px; color: var(--text-primary);
  display: flex; align-items: center; gap: 10px;
}
.section-count {
  font-size: 12px; color: var(--text-muted);
  background: var(--bg-hover);
  padding: 3px 10px; border-radius: 20px;
}

/* Toolbar */
.toolbar {
  padding: 16px 24px;
  width: 1265px;
  border-bottom: 1px solid var(--border-light);
  display: flex; gap: 12px; align-items: center;
  flex-wrap: wrap;
}

/* ===== Element Plus Overrides ===== */
/* ===== 終極覆蓋 Element Plus 表格預設樣式 ===== */
/* 1. 設定整體的 CSS 變數與字體 */
.el-table {
  --el-table-border-color: var(--border);
  --el-table-text-color: var(--text-primary);
  --el-table-header-text-color: var(--text-sec);
  --el-table-row-hover-bg-color: var(--bg-hover);
  background-color: transparent !important;
  font-family: 'Noto Serif TC', serif;
  font-size: 13px;
}

/* 2. 強制把「表頭 (Header)」的白底改成深黑色 */
.el-table th.el-table__cell,
.el-table__header-wrapper th {
  background-color: var(--bg-deep) !important;
  border-bottom: 1px solid var(--border) !important;
  font-size: 12px; 
  letter-spacing: 0.08em; 
  font-weight: 500;
}

/* 3. 強制把「表格內容 (Body)」的白底變透明，透出卡片的顏色 */
.el-table tr,
.el-table td.el-table__cell {
  background-color: transparent !important;
  border-bottom: 1px solid var(--border) !important;
}

/* 4. 處理滑鼠滑過 (Hover) 的高亮底色 */
.el-table tbody tr:hover > td.el-table__cell {
  background-color: var(--bg-hover) !important;
}

/* 5. 修正表格最底下那條預設的白線，以及空資料區塊 */
.el-table__inner-wrapper::before {
  background-color: var(--border) !important;
}
.el-table__empty-block { 
  background-color: transparent !important; 
}

/* ===== 徹底覆蓋 Input 輸入框與 Select 下拉選單預設樣式 ===== */

/* 1. 一般輸入框 (Input) */
.el-input__wrapper {
  background-color: var(--bg-deep) !important;
  box-shadow: 0 0 0 1px var(--border) inset !important; /* 用內陰影畫邊框 */
}
.el-input__wrapper:hover,
.el-input__wrapper.is-focus {
  box-shadow: 0 0 0 1px var(--gold-dim) inset !important;
}
.el-input__inner {
  color: var(--text-primary) !important;
}

/* 2. 下拉選單外框 (Select Wrapper - 新版 Element Plus 專用) */
.el-select__wrapper {
  background-color: var(--bg-deep) !important;
  box-shadow: 0 0 0 1px var(--border) inset !important;
}
.el-select__wrapper:hover,
.el-select__wrapper.is-focused {
  box-shadow: 0 0 0 1px var(--gold-dim) inset !important;
}
/* 下拉選單內顯示的文字 */
.el-select__placeholder,
.el-select__selected-item {
  color: var(--text-primary) !important;
}

/* 3. 展開後的下拉選單列表 (Dropdown Menu) */
.el-select-dropdown {
  background-color: var(--bg-card) !important;
  border: 1px solid var(--border) !important;
}
.el-select-dropdown__item {
  color: var(--text-sec) !important;
}
.el-select-dropdown__item.hover, 
.el-select-dropdown__item:hover {
  background-color: var(--bg-hover) !important;
  color: var(--text-primary) !important;
}

/* ===== 分類與分頁元件深色主題覆蓋 ===== */
.el-pagination {
  --el-pagination-bg-color: transparent !important;
  --el-pagination-text-color: var(--text-sec) !important;
  --el-pagination-button-color: var(--text-primary) !important;
  --el-pagination-button-disabled-bg-color: transparent !important;
}
/* 分頁按鈕背景 */
.el-pagination.is-background .el-pager li,
.el-pagination.is-background .btn-next, 
.el-pagination.is-background .btn-prev {
  background-color: var(--bg-hover) !important;
  color: var(--text-sec) !important;
  border: 1px solid var(--border) !important;
}
/* 滑鼠移過去 */
.el-pagination.is-background .el-pager li:not(.is-disabled):hover {
  color: var(--gold) !important;
}
/* 當前作用中的頁碼 */
.el-pagination.is-background .el-pager li.is-active {
  background-color: var(--gold) !important;
  color: #0d1117 !important;
  font-weight: bold;
  border-color: var(--gold) !important;
}
/* 跳頁輸入框 */
.el-pagination__editor.el-input {
  width: 50px;
}

.el-button--primary {
  background: var(--gold) !important; border-color: var(--gold) !important;
  color: #0d1117 !important; font-family: 'Noto Serif TC', serif; font-weight: 500;
}
.el-button--primary:hover { background: #e8bc5e !important; border-color: #e8bc5e !important; }
.el-button--danger, .el-button--success, .el-button--warning, .el-button--info {
  font-family: 'Noto Serif TC', serif;
}

.el-dialog {
  background: var(--bg-card) !important;
  border: 1px solid var(--border) !important;
  border-radius: 12px !important;
}
.el-dialog__header {
  border-bottom: 1px solid var(--border) !important;
  padding: 20px 24px !important;
}
.el-dialog__title {
  color: var(--text-primary) !important;
  font-family: 'Playfair Display', serif !important;
  font-size: 18px !important;
}
.el-dialog__headerbtn .el-dialog__close { color: var(--text-muted) !important; }
.el-dialog__body { padding: 24px !important; }
.el-dialog__footer { border-top: 1px solid var(--border) !important; padding: 16px 24px !important; }

.el-form-item__label { color: var(--text-sec) !important; font-family: 'Noto Serif TC', serif; font-size: 13px !important; }
.el-textarea__inner {
  background: var(--bg-deep) !important;
  border-color: var(--border) !important;
  color: var(--text-primary) !important;
  font-family: 'Noto Serif TC', serif;
  box-shadow: none !important;
}

/* Status Badge */
.badge-avail {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 3px 10px; border-radius: 20px; font-size: 12px;
  background: rgba(63,185,80,0.12); color: #3fb950;
  border: 1px solid rgba(63,185,80,0.3);
}
.badge-out {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 3px 10px; border-radius: 20px; font-size: 12px;
  background: rgba(248,81,73,0.12); color: #f85149;
  border: 1px solid rgba(248,81,73,0.3);
}
.badge-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

/* Action row */
.action-row { display: flex; gap: 6px; flex-wrap: wrap; }

/* Loading */
.loading-wrap {
  display: flex; align-items: center; justify-content: center;
  padding: 60px; color: var(--text-muted); gap: 12px;
}
.spinner {
  width: 20px; height: 20px; border-radius: 50%;
  border: 2px solid var(--border); border-top-color: var(--gold);
  animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* Borrower info */
.borrower-sub { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

/* View toggle */
.view-link {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: 13px; color: var(--text-sec);
  text-decoration: none; padding: 6px 12px;
  border: 1px solid var(--border); border-radius: 6px;
  transition: all 0.2s;
}
.view-link:hover { color: var(--gold); border-color: var(--gold-dim); background: var(--gold-glow); }
</style>
</head>
<body>
<div id="app" v-cloak>

  <!-- ===== Header ===== -->
  <header class="site-header">
    <div class="header-inner">
      <div class="logo">
        <div class="logo-icon">📚</div>
        <div>
          <div class="logo-text">圖書館管理系統</div>
          <div class="logo-sub">Admin Panel</div>
        </div>
      </div>
      <div class="header-right">
        <span class="admin-badge">⚙ 管理員後台</span>
        <div class="user-info">
          <div class="user-name">{{ currentUser.name }}</div>
          <div class="user-role">{{ currentUser.user_id }}</div>
        </div>
        <a href="index.php" class="view-link">📖 讀者視角</a>
        <el-button size="small" @click="handleLogout"
          style="background:var(--bg-hover);border-color:var(--border);color:var(--text-sec);">
          登出
        </el-button>
      </div>
    </div>
  </header>

  <!-- ===== Main Layout ===== -->
  <div class="main-layout">

    <!-- ===== Sidebar ===== -->
    <aside class="sidebar">
      <!-- 數據總覽 -->
      <div class="sidebar-card">
        <div class="sidebar-title">數據總覽</div>
        <div class="stat-grid">
          <div class="stat-card">
            <div class="stat-num">{{ stats.total }}</div>
            <div class="stat-label">館藏總數</div>
          </div>
          <div class="stat-card">
            <div class="stat-num" style="color:var(--green)">{{ stats.available }}</div>
            <div class="stat-label">可借閱</div>
          </div>
          <div class="stat-card">
            <div class="stat-num" style="color:var(--red)">{{ stats.borrowed }}</div>
            <div class="stat-label">借出中</div>
          </div>
          <div class="stat-card">
            <div class="stat-num" style="color:var(--blue)">{{ categories.length }}</div>
            <div class="stat-label">分類數</div>
          </div>
        </div>
      </div>

      <!-- 導覽 -->
      <div class="sidebar-card">
        <div class="sidebar-title">功能</div>
        <div class="nav-item active">
          <span class="nav-icon">📚</span> 書籍管理
        </div>
        <div class="nav-item" style="opacity:0.4; cursor:not-allowed;">
          <span class="nav-icon">👥</span> 使用者管理
          <span style="font-size:10px; margin-left:auto; color:var(--text-muted);">即將推出</span>
        </div>
      </div>

      <!-- 篩選 -->
      <div class="sidebar-card">
        <div class="sidebar-title">篩選狀態</div>
        <div
          v-for="f in statusFilters" :key="f.value"
          class="nav-item"
          :class="{ active: filterStatus === f.value }"
          @click="filterStatus = f.value; fetchBooks()"
        >
          <span class="nav-icon" style="font-size:10px;">
            <span :style="`display:inline-block;width:8px;height:8px;border-radius:50%;background:${f.color}`"></span>
          </span>
          {{ f.label }}
          <span style="margin-left:auto; font-size:11px;">{{ f.count }}</span>
        </div>
      </div>
    </aside>

    <!-- ===== Content ===== -->
    <main>

      <!-- Book Management Section -->
      <div class="section-card">
        <div class="section-header">
          <div class="section-title">
            📚 書籍管理
            <span class="section-count">共 {{ books.length }} 筆</span>
          </div>
          <el-button type="primary" @click="openAddDialog">＋ 新增書籍</el-button>
        </div>

        <!-- Toolbar -->
        <div class="toolbar">
          <el-input
            v-model="searchText"
            placeholder="搜尋書名、作者、ISBN、關鍵字…"
            @keyup.enter="fetchBooks"
            clearable @clear="fetchBooks"
            style="width:780px"
          >
            <template #prefix><span style="color:var(--text-muted)">🔍</span></template>
          </el-input>
          <el-select
            v-model="filterCategory"
            placeholder="篩選分類"
            clearable
            style="width:250px"
            @change="fetchBooks"
          >
            <el-option
              v-for="cat in categories"
              :key="cat.category_id"
              :label="`${cat.category_id} - ${cat.name}`"
              :value="cat.category_id"
            />
          </el-select>

          <el-button type="primary" @click="fetchBooks">搜尋</el-button>

          <el-button @click="resetFilters"
            style="background:var(--bg-hover);border-color:var(--border);color:var(--text-sec);">
            ↺ 重置
          </el-button>
        </div>

        <!-- Table -->
        <div v-if="loading" class="loading-wrap">
          <div class="spinner"></div><span>載入中…</span>
        </div>

        <el-table v-else :data="paginatedBooks" style="width:100%" row-key="book_id">
          <el-table-column prop="book_id" label="ISBN" width="150"></el-table-column>
          
          <el-table-column label="書名" width="380">
            <template #default="{ row }">
              <div>{{ row.title }}</div>
              <div class="borrower-sub" v-if="row.status == 1 && row.borrower_name">
                借閱者：{{ row.borrower_name }} ｜ 到期：{{ row.due_date }}
              </div>
            </template>
          </el-table-column>
          
          <el-table-column prop="keyword" label="關鍵字" width="200">
            <template #default="{ row }">
              <span style="color:var(--text-muted); font-size:12px;">{{ row.keyword || '—' }}</span>
            </template>
          </el-table-column>
          
          <el-table-column label="狀態" width="100">
            <template #default="{ row }">
              <span v-if="row.status == 0" class="badge-avail">
                <span class="badge-dot"></span> 可借閱
              </span>
              <span v-else class="badge-out">
                <span class="badge-dot"></span> 借出中
              </span>
            </template>
          </el-table-column>

          <el-table-column prop="author" label="作者" width="120"></el-table-column>
          <el-table-column prop="category_name" label="分類" width="100"></el-table-column>
          
          <el-table-column label="管理操作" width="215" fixed="right">
            <template #default="{ row }">
              <div class="action-row">
                <el-button type="success" size="small" @click="openEditDialog(row)">編輯</el-button>
                <el-button
                  v-if="row.status == 1"
                  type="primary" size="small"
                  @click="returnBook(row)"
                >還書</el-button>
                <el-popconfirm
                  :title="`確定刪除《${row.title}》？`"
                  confirm-button-text="刪除"
                  cancel-button-text="取消"
                  @confirm="deleteBook(row)"
                >
                  <template #reference>
                    <el-button
                      type="danger" size="small"
                      :disabled="row.status == 1"
                      :title="row.status == 1 ? '書籍借出中，無法刪除' : ''"
                    >刪除</el-button>
                  </template>
                </el-popconfirm>
              </div>
            </template>
          </el-table-column>
        </el-table>
        <div style="margin-top: 24px; margin-bottom: 24px; display: flex; justify-content: flex-end;">
          <el-pagination
            v-model:current-page="currentPage"
            v-model:page-size="pageSize"
            :page-sizes="[10, 20, 50, 100]"
            layout="total, sizes, prev, pager, next, jumper"
            :total="books.length"
            background
          />
        </div>
      </div>
    </main>
    <aside class="sidebar-right">
      <div class="sidebar-card">
        <div class="sidebar-title">分類目錄</div>
        
        <div
          class="status-filter-btn"
          :class="{ active: filterCategory === '' }"
          @click="filterCategory = ''; fetchBooks()"
        >
          <span class="status-dot dot-all"></span> 全部分類
        </div>
        
        <div class="category-list">
          <template v-for="cat in categories" :key="cat.category_id">
            
            <div
              v-if="String(cat.category_id).endsWith('0')"
              class="status-filter-btn cat-main"
              :class="{ 'active': filterCategory === cat.category_id }"
            >
              <span class="toggle-icon" @click.stop="toggleCategory(cat.category_id)">
                {{ expandedMains.includes(cat.category_id) ? '▼' : '▶' }}
              </span>
              <span class="cat-name" @click="filterCategory = cat.category_id; fetchBooks()">
                {{ String(cat.category_id).padStart(3, '0') }} - {{ cat.name }}
              </span>
            </div>

            <div
              v-else
              v-show="expandedMains.includes(getParentId(cat.category_id))"
              class="status-filter-btn cat-sub"
              :class="{ 'active': filterCategory === cat.category_id }"
              @click="filterCategory = cat.category_id; fetchBooks()"
            >
              {{ String(cat.category_id).padStart(3, '0') }} - {{ cat.name }}
            </div>

          </template>
        </div>
      </div>
    </aside>
  </div>

  <!-- ===== Dialog: 新增書籍 ===== -->
  <el-dialog v-model="addDialog.visible" title="新增書籍" width="500px" destroy-on-close>
    <el-form :model="addDialog.form" :rules="addDialog.rules" ref="addFormRef" label-width="90px">
      <el-form-item label="ISBN-13" prop="book_id">
        <el-input v-model="addDialog.form.book_id" placeholder="13 位數字" maxlength="13" />
      </el-form-item>
      <el-form-item label="書名" prop="title">
        <el-input v-model="addDialog.form.title" placeholder="請輸入書名" />
      </el-form-item>
      <el-form-item label="作者" prop="author">
        <el-input v-model="addDialog.form.author" placeholder="請輸入作者" />
      </el-form-item>
      <el-form-item label="分類" prop="category_id">
        <el-select v-model="addDialog.form.category_id" placeholder="選擇分類" style="width:100%">
          <el-option v-for="cat in categories" :key="cat.category_id" :label="`${cat.category_id} - ${cat.name}`" :value="cat.category_id" />
        </el-select>
      </el-form-item>
      <el-form-item label="關鍵字">
        <el-input v-model="addDialog.form.keyword" placeholder="選填，多個關鍵字可用逗號分隔" />
      </el-form-item>
    </el-form>
    <template #footer>
      <el-button @click="addDialog.visible = false">取消</el-button>
      <el-button type="primary" :loading="addDialog.loading" @click="submitAdd">確認新增</el-button>
    </template>
  </el-dialog>

  <!-- ===== Dialog: 編輯書籍 ===== -->
  <el-dialog v-model="editDialog.visible" title="編輯書籍" width="500px" destroy-on-close>
    <el-form :model="editDialog.form" :rules="editDialog.rules" ref="editFormRef" label-width="90px">
      <el-form-item label="ISBN-13">
        <el-input :value="editDialog.form.book_id" disabled />
        <div style="font-size:11px; color:var(--text-muted); margin-top:4px;">ISBN 為主鍵，不可修改</div>
      </el-form-item>
      <el-form-item label="書名" prop="title">
        <el-input v-model="editDialog.form.title" placeholder="請輸入書名" />
      </el-form-item>
      <el-form-item label="作者" prop="author">
        <el-input v-model="editDialog.form.author" placeholder="請輸入作者" />
      </el-form-item>
      <el-form-item label="分類" prop="category_id">
        <el-select v-model="editDialog.form.category_id" placeholder="選擇分類" style="width:100%">
          <el-option v-for="cat in categories" :key="cat.category_id" :label="`${cat.category_id} - ${cat.name}`" :value="cat.category_id" />
        </el-select>
      </el-form-item>
      <el-form-item label="關鍵字">
        <el-input v-model="editDialog.form.keyword" placeholder="選填" />
      </el-form-item>
    </el-form>
    <template #footer>
      <el-button @click="editDialog.visible = false">取消</el-button>
      <el-button type="primary" :loading="editDialog.loading" @click="submitEdit">儲存變更</el-button>
    </template>
  </el-dialog>

</div><!-- #app -->

<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script src="https://unpkg.com/element-plus/dist/index.full.min.js"></script>
<script>
const { createApp, ref, computed, onMounted } = Vue;

createApp({
  setup() {
    // ===== Auth State =====
    const currentUser = ref({ user_id: '', name: '', role: '' });

    // ===== Data State =====

    const books      = ref([]);
    // ===== Pagination State =====
    const currentPage = ref(1);
    const pageSize = ref(10); // 預設每頁顯示 10 筆

    // 計算目前頁面應該顯示的書籍
    const paginatedBooks = computed(() => {
      const start = (currentPage.value - 1) * pageSize.value;
      const end = start + pageSize.value;
      return books.value.slice(start, end);
    });

    const categories = ref([]);
    const loading    = ref(false);
    const searchText    = ref('');
    const filterStatus  = ref('');
    const filterCategory = ref('');
    const expandedMains = ref([]);

    const addFormRef  = ref(null);
    const editFormRef = ref(null);

    // 1. 改用 ref 來儲存獨立的統計資料，不受篩選影響
    const stats = ref({ total: 0, available: 0, borrowed: 0 });

    // 2. 新增一個函式，專門向後端要「全館」資料來計算統計數字
    async function fetchStats() {
      try {
        const res = await api('api/get_books.php'); // 不帶任何過濾參數
        if (res.success) {
          const allBooks = res.data ?? [];
          stats.value = {
            total: allBooks.length,
            available: allBooks.filter(b => b.status == 0).length,
            borrowed: allBooks.filter(b => b.status == 1).length,
          };
        }
      } catch (e) {
        console.error('統計數字更新失敗');
      }
    }

    const statusFilters = computed(() => [
      { value: '',  label: '全部',   color: '#484f58', count: stats.value.total },
      { value: '0', label: '可借閱', color: '#3fb950', count: stats.value.available },
      { value: '1', label: '借出中', color: '#f85149', count: stats.value.borrowed },
    ]);

    // ===== API Helper =====
    const api = (url, opts = {}) => fetch(url, opts).then(r => r.json());

    // ===== Session Check =====
    async function checkAuth() {
      try {
        const res = await api('api/check_session.php');
        if (!res.success) {
          window.location.href = 'login.php';
          return;
        }
        if (res.data.role !== 'admin') {
          ElementPlus.ElMessage.warning('權限不足，跳轉至讀者頁面');
          setTimeout(() => { window.location.href = 'index.php'; }, 1500);
          return;
        }
        currentUser.value = res.data;
      } catch {
        window.location.href = 'login.php';
      }
    }

    // ===== Fetch =====
    async function fetchBooks() {
      loading.value = true;
      currentPage.value = 1;
      const p = new URLSearchParams();
      if (searchText.value)    p.set('search', searchText.value);
      if (filterStatus.value)  p.set('status', filterStatus.value);
      if (filterCategory.value) p.set('category_id', filterCategory.value);
      try {
        const res = await api(`api/get_books.php?${p}`);
        if (res.success) books.value = res.data ?? [];
        else ElementPlus.ElMessage.error(res.message);
      } catch { ElementPlus.ElMessage.error('載入失敗'); }
      finally { loading.value = false; }
    }

    function resetFilters() {
      searchText.value = '';      // 清空搜尋文字
      filterStatus.value = '';    // 清空狀態篩選 (全部)
      filterCategory.value = '';  // 清空分類篩選 (全部分類)

      // 重整時將分類全部收合
      expandedMains.value = [];

      try {
        fetchBooks();               // 重新執行查詢
        fetchStats();               // 重新計算統計數字
      } catch {
        ElementPlus.ElMessage.error('重置篩選失敗');
      }
    }

    async function fetchCategories() {
      const res = await api('api/get_categories.php');
      if (res.success) categories.value = res.data ?? [];
    }

    // ===== 展開狀態的變數和函式 =====
    // 切換展開/收合狀態
    function toggleCategory(cat_id) {
      const idx = expandedMains.value.indexOf(cat_id);
      if (idx > -1) {
        expandedMains.value.splice(idx, 1);
      } else {
        expandedMains.value.push(cat_id);
      }
    }

    // 計算子分類屬於哪一個大分類
    function getParentId(cat_id) {
      return String(Math.floor(parseInt(cat_id, 10) / 10) * 10).padStart(3, '0');
    }

    // ===== Logout =====
    async function handleLogout() {
      await api('api/logout.php', { method: 'POST' });
      localStorage.clear();
      window.location.href = 'login.php';
    }

    // ===== Add Dialog =====
    const addDialog = ref({
      visible: false, loading: false,
      form: { book_id: '', title: '', author: '', category_id: '', keyword: '' },
      rules: {
        book_id:     [{ required: true, message: '請輸入 ISBN', trigger: 'blur' },
                      { pattern: /^\d{13}$/, message: '需為 13 位數字', trigger: 'blur' }],
        title:       [{ required: true, message: '請輸入書名', trigger: 'blur' }],
        author:      [{ required: true, message: '請輸入作者', trigger: 'blur' }],
        category_id: [{ required: true, message: '請選擇分類', trigger: 'change' }],
      }
    });

    function openAddDialog() {
      addDialog.value.form = { book_id: '', title: '', author: '', category_id: '', keyword: '' };
      addDialog.value.visible = true;
    }

    async function submitAdd() {
      try { await addFormRef.value.validate(); } catch { return; }
      addDialog.value.loading = true;
      try {
        const res = await api('api/add_book.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(addDialog.value.form),
        });
        if (res.success) {
          ElementPlus.ElMessage.success(res.message);
          addDialog.value.visible = false;
          fetchBooks();
          fetchStats();
        } else {
          ElementPlus.ElMessage.error(res.message);
        }
      } catch { ElementPlus.ElMessage.error('新增失敗'); }
      finally { addDialog.value.loading = false; }
    }

    // ===== Edit Dialog =====
    const editDialog = ref({
      visible: false, loading: false,
      form: { book_id: '', title: '', author: '', category_id: '', keyword: '' },
      rules: {
        title:       [{ required: true, message: '請輸入書名', trigger: 'blur' }],
        author:      [{ required: true, message: '請輸入作者', trigger: 'blur' }],
        category_id: [{ required: true, message: '請選擇分類', trigger: 'change' }],
      }
    });

    function openEditDialog(row) {
      editDialog.value.form = {
        book_id:     row.book_id,
        title:       row.title,
        author:      row.author,
        category_id: row.category_id,
        keyword:     row.keyword ?? '',
      };
      editDialog.value.visible = true;
    }

    async function submitEdit() {
      try { await editFormRef.value.validate(); } catch { return; }
      editDialog.value.loading = true;
      try {
        const res = await api('api/update_book.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(editDialog.value.form),
        });
        if (res.success) {
          ElementPlus.ElMessage.success(res.message);
          editDialog.value.visible = false;
          fetchBooks();
          fetchStats();
        } else {
          ElementPlus.ElMessage.error(res.message);
        }
      } catch { ElementPlus.ElMessage.error('儲存失敗'); }
      finally { editDialog.value.loading = false; }
    }

    // ===== Delete =====
    async function deleteBook(row) {
      try {
        const res = await api('api/delete_book.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ book_id: row.book_id }),
        });
        if (res.success) { ElementPlus.ElMessage.success(res.message); fetchBooks(); fetchStats(); }
        else ElementPlus.ElMessage.error(res.message);
      } catch { ElementPlus.ElMessage.error('刪除失敗'); }
    }

    // ===== Return (admin can return any book) =====
    async function returnBook(row) {
      try {
        await ElementPlus.ElMessageBox.confirm(
          `確定幫 ${row.borrower_name} 歸還《${row.title}》？`,
          '管理員代為還書',
          { confirmButtonText: '確認', cancelButtonText: '取消', type: 'warning' }
        );
        const res = await api('api/return_book.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ book_id: row.book_id }),
        });
        if (res.success) { ElementPlus.ElMessage.success(res.message); fetchBooks(); fetchStats(); }
        else ElementPlus.ElMessage.error(res.message);
      } catch (e) { if (e !== 'cancel') ElementPlus.ElMessage.error('還書失敗'); }
    }

    // ===== Init =====
    onMounted(async () => {
      await checkAuth();
      fetchBooks();
      fetchStats();
      fetchCategories();
    });

    return {
      currentUser, books, categories, loading,
      currentPage, pageSize, paginatedBooks,
      searchText, filterStatus, filterCategory,
      stats, statusFilters,
      addFormRef, editFormRef,
      addDialog, openAddDialog, submitAdd,
      editDialog, openEditDialog, submitEdit,
      deleteBook, returnBook,
      expandedMains, toggleCategory, getParentId,
      handleLogout, fetchBooks, resetFilters,
    };
  }
}).use(ElementPlus).mount('#app');
</script>
</body>
</html>
