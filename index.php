<?php
// index.php - 圖書館系統前端入口
// 所有畫面邏輯由 Vue.js 3 + Element Plus 處理
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>圖書館管理系統</title>

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Noto+Serif+TC:wght@300;400;500&display=swap" rel="stylesheet">

<!-- Element Plus CSS -->
<link rel="stylesheet" href="https://unpkg.com/element-plus/dist/index.css">

<style>
/* ===== CSS Variables ===== */
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
  --shadow:       0 8px 32px rgba(0,0,0,0.4);
}

/* ===== Reset & Base ===== */
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
  max-width: 1400px; margin: 0 auto;
  display: flex; align-items: center; justify-content: space-between;
  height: 68px;
}
.logo {
  display: flex; align-items: center; gap: 14px;
}
.logo-icon {
  width: 40px; height: 40px;
  background: var(--gold-glow);
  border: 1px solid var(--gold-dim);
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  font-size: 20px;
}
.logo-text {
  font-family: 'Playfair Display', serif;
  font-size: 22px;
  color: var(--gold);
  letter-spacing: 0.04em;
}
.logo-sub {
  font-size: 11px;
  color: var(--text-muted);
  letter-spacing: 0.12em;
  text-transform: uppercase;
}
.header-stats {
  display: flex; gap: 28px;
}
.stat-item {
  text-align: center;
}
.stat-num {
  font-family: 'Playfair Display', serif;
  font-size: 22px;
  color: var(--gold);
  line-height: 1;
}
.stat-label {
  font-size: 11px;
  color: var(--text-muted);
  letter-spacing: 0.08em;
}

/* ===== Layout ===== */
.main-layout {
  max-width: 1400px;
  margin: 0 auto;
  padding: 32px 40px;
  display: grid;
  grid-template-columns: 260px 1fr;
  gap: 28px;
  justify-content: center;
}

/* ===== Sidebar ===== */
.sidebar {
  position: sticky;
  top: 100px;
  height: fit-content;
}
.sidebar-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 24px;
  margin-bottom: 16px;
}
.sidebar-title {
  font-family: 'Playfair Display', serif;
  font-size: 13px;
  color: var(--gold);
  letter-spacing: 0.12em;
  text-transform: uppercase;
  margin-bottom: 16px;
  padding-bottom: 10px;
  border-bottom: 1px solid var(--border-light);
}
.filter-group {
  margin-bottom: 14px;
}
.filter-label {
  font-size: 12px;
  color: var(--text-muted);
  margin-bottom: 6px;
  letter-spacing: 0.06em;
}

/* Status badges */
.status-filter-btn {
  display: flex; align-items: center; gap: 8px;
  padding: 8px 12px; border-radius: 6px;
  cursor: pointer; margin-bottom: 4px;
  font-size: 13px; color: var(--text-sec);
  transition: all 0.2s;
  border: 1px solid transparent;
}
.status-filter-btn:hover {
  background: var(--bg-hover);
  color: var(--text-primary);
}
.status-filter-btn.active {
  background: var(--gold-glow);
  border-color: var(--gold-dim);
  color: var(--gold);
}
.status-dot {
  width: 8px; height: 8px; border-radius: 50%;
}
.dot-all    { background: var(--text-muted); }
.dot-avail  { background: var(--green); box-shadow: 0 0 6px var(--green); }
.dot-out    { background: var(--red);   box-shadow: 0 0 6px var(--red); }

/* ===== Main Content ===== */
.content-area {}

/* Search bar */
.search-bar {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 20px 24px;
  margin-bottom: 20px;
  display: flex;
  gap: 12px;
  align-items: center;
}

/* Book Table */
.table-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  overflow: hidden;
}
.table-header {
  padding: 18px 24px;
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; justify-content: space-between;
}
.table-title {
  font-family: 'Playfair Display', serif;
  font-size: 16px;
  color: var(--text-primary);
}
.table-count {
  font-size: 12px;
  color: var(--text-muted);
  background: var(--bg-hover);
  padding: 3px 10px;
  border-radius: 20px;
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
  background: var(--gold) !important;
  border-color: var(--gold) !important;
  color: #0d1117 !important;
  font-family: 'Noto Serif TC', serif;
  font-weight: 500;
}
.el-button--primary:hover {
  background: #e8bc5e !important;
  border-color: #e8bc5e !important;
}
.el-button--danger { font-family: 'Noto Serif TC', serif; }
.el-button--success { font-family: 'Noto Serif TC', serif; }
.el-button--warning { font-family: 'Noto Serif TC', serif; }

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

.el-form-item__label {
  color: var(--text-sec) !important;
  font-family: 'Noto Serif TC', serif;
  font-size: 13px !important;
}
.el-tag { font-family: 'Noto Serif TC', serif; }

/* ===== Custom Tag ===== */
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

/* ===== Action Buttons Row ===== */
.action-row { display: flex; gap: 6px; }

/* ===== Loading ===== */
.loading-wrap {
  display: flex; align-items: center; justify-content: center;
  padding: 60px;
  color: var(--text-muted);
  gap: 12px;
}
.spinner {
  width: 20px; height: 20px; border-radius: 50%;
  border: 2px solid var(--border);
  border-top-color: var(--gold);
  animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ===== Tooltip ===== */
.borrower-info {
  font-size: 12px;
  color: var(--text-muted);
  margin-top: 2px;
}

/* ===== 分類列表捲軸與縮排設計 ===== */
.category-list {
  max-height: 400px; /* 限制側邊欄高度，超過可滾動 */
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
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
}
/* 展開收合箭頭 */
.toggle-icon {
  display: inline-block;
  width: 14px;
  cursor: pointer;
  color: var(--text-muted);
  font-size: 10px;
  transition: color 0.2s;
  text-align: center;
}
.toggle-icon:hover {
  color: var(--gold);
}
.cat-name {
  flex: 1;
  cursor: pointer;
}

/* 子分類縮排 */
.cat-sub {
  padding-left: 36px !important; 
  font-size: 12px !important;
  color: var(--text-sec) !important;
  margin-bottom: 2px !important;
}
.cat-sub:hover {
  color: var(--text-primary) !important;
}
.cat-sub.active {
  color: var(--gold) !important;
}

/* ===== Page fade-in ===== */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(16px); }
  to   { opacity: 1; transform: translateY(0); }
}
.main-layout { animation: fadeUp 0.5s ease both; }
</style>
</head>
<body>
<div id="app">

  <!-- ===== Header ===== -->
  <header class="site-header">
    <div class="header-inner">
      <div class="logo">
        <div class="logo-icon">📚</div>
        <div>
          <div class="logo-text">圖書館管理系統</div>
          <div class="logo-sub">Library Management System</div>
        </div>
      </div>
      <div class="header-stats">
        <div class="stat-item">
          <div class="stat-num">{{ stats.total }}</div>
          <div class="stat-label">館藏總數</div>
        </div>
        <div class="stat-item">
          <div class="stat-num" style="color: var(--green)">{{ stats.available }}</div>
          <div class="stat-label">可借閱</div>
        </div>
        <div class="stat-item">
          <div class="stat-num" style="color: var(--red)">{{ stats.borrowed }}</div>
          <div class="stat-label">借出中</div>
        </div>
      </div>
      <div style="display:flex; align-items:center; gap:14px;">
        <div style="text-align:right;">
          <div style="font-size:14px; color:var(--text-primary);">{{ currentUser.name }}</div>
          <div style="font-size:11px; color:var(--text-muted);">{{ currentUser.user_id }}</div>
        </div>
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

      <!-- 動作區 -->
      <div class="sidebar-card" v-if="currentUser.role === 'admin'">
        <div class="sidebar-title">操作</div>
        <!-- 管理員：顯示前往後台按鈕 -->
        <a href="admin.php"
          style="display:block; text-decoration:none; margin-bottom:10px;">
          <el-button type="primary" style="width:100%;">⚙ 管理後台</el-button>
        </a>
        <el-button style="width:100%; background:var(--bg-hover); border-color:var(--border); color:var(--text-sec);" @click="fetchBooks">
          ↺ 重新整理
        </el-button>
      </div>

      <!-- 篩選區 -->
      <div class="sidebar-card">
        <div class="sidebar-title">篩選狀態</div>
        <div
          v-for="f in statusFilters"
          :key="f.value"
          class="status-filter-btn"
          :class="{ active: filterStatus === f.value }"
          @click="filterStatus = f.value; fetchBooks()"
        >
          <span class="status-dot" :class="f.dotClass"></span>
          {{ f.label }}
          <span style="margin-left:auto; font-size:11px;">{{ f.count }}</span>
        </div>
      </div>

      <!-- 分類篩選 -->
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

    <!-- ===== Content ===== -->
    <main class="content-area">

      <!-- 搜尋列 -->
      <div class="search-bar" style="padding: 16px 24px; display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <el-input
          v-model="searchText"
          placeholder="搜尋書名、作者、ISBN、關鍵字…"
          @keyup.enter="fetchBooks"
          clearable
          @clear="fetchBooks"
          style="width: 640px;"
        >
          <template #prefix><span style="color:var(--text-muted)">🔍</span></template>
        </el-input>
        
        <el-select
          v-model="filterCategory"
          placeholder="篩選分類"
          clearable
          style="width: 250px;"
          @change="fetchBooks"
        >
          <el-option
            v-for="cat in categories"
            :key="cat.category_id"
            :label="`${String(cat.category_id).padStart(3, '0')} - ${cat.name}`"
            :value="cat.category_id"
          />
        </el-select>

        <el-button type="primary" @click="fetchBooks">搜尋</el-button>

        <el-button @click="resetFilters"
          style="background:var(--bg-hover); border-color:var(--border); color:var(--text-sec);">
          ↺ 重置
        </el-button>
      </div>

      <!-- 書籍表格 -->
      <div class="table-card">
        <div class="table-header">
          <span class="table-title">館藏書目</span>
          <span class="table-count">共 {{ books.length }} 筆</span>
        </div>

        <div v-if="loading" class="loading-wrap">
          <div class="spinner"></div>
          <span>載入中…</span>
        </div>

        <el-table
          v-else
          :data="paginatedBooks"
          style="width:100%"
          row-key="book_id"
        >
          <el-table-column prop="book_id" label="ISBN" width="150"></el-table-column>
          
          <el-table-column label="書名" width="400">
            <template #default="{ row }">
              <div>{{ row.title }}</div>
              <div class="borrower-info" v-if="row.status == 1 && row.borrower_name">
                借閱者：{{ row.borrower_name }}｜到期：{{ row.due_date }}
              </div>
            </template>
          </el-table-column>

          <el-table-column prop="keyword" label="關鍵字" width="150">
            <template #default="{ row }">
              <span style="color:var(--text-muted); font-size:12px;">{{ row.keyword || '—' }}</span>
            </template>
          </el-table-column>

          <el-table-column label="狀態" width="110">
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

          <el-table-column label="操作" width="100" fixed="right">
            <template #default="{ row }">
              <div class="action-row">
                <el-button
                  v-if="row.status == 0"
                  type="success" size="small"
                  @click="openBorrowDialog(row)"
                >借閱</el-button>
                <template v-else>
                  <el-button
                    v-if="row.borrower_id === currentUser.user_id"
                    type="primary" size="small"
                    @click="returnBook(row)"
                  >還書</el-button>
                  <el-button
                    v-else
                    type="danger" size="small"
                    @click="notifyReturn(row)"
                  >催還</el-button>
                </template>
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
  </div>

  <!-- ===== Dialog: 借書 ===== -->
  <el-dialog v-model="borrowDialog.visible" title="借閱書籍" width="440px" destroy-on-close>
    <div style="margin-bottom:20px; padding:16px; background:var(--bg-deep); border-radius:8px; border:1px solid var(--border);">
      <div style="font-family:'Playfair Display',serif; font-size:16px; margin-bottom:4px;">{{ borrowDialog.book?.title }}</div>
      <div style="font-size:13px; color:var(--text-muted);">{{ borrowDialog.book?.author }} ｜ ISBN: {{ borrowDialog.book?.book_id }}</div>
    </div>
    <el-form :model="borrowDialog.form" label-width="90px">
      <el-form-item label="借閱者">
        <div style="padding: 8px 12px; background: var(--bg-deep); border: 1px solid var(--border); border-radius: 6px; font-size:14px;">
          {{ currentUser.name }} <span style="color:var(--text-muted); font-size:12px;">（{{ currentUser.user_id }}）</span>
        </div>
      </el-form-item>
      <el-form-item label="借閱天數">
        <el-input-number v-model="borrowDialog.form.due_days" :min="1" :max="90" />
        <span style="margin-left:8px; font-size:12px; color:var(--text-muted);">天（到期：{{ borrowDialogDueDate }}）</span>
      </el-form-item>
    </el-form>
    <template #footer>
      <el-button @click="borrowDialog.visible = false">取消</el-button>
      <el-button type="primary" :loading="borrowDialog.loading" @click="submitBorrow">確認借閱</el-button>
    </template>
  </el-dialog>

</div><!-- #app -->

<!-- Vue 3 + Element Plus CDN -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script src="https://unpkg.com/element-plus/dist/index.full.min.js"></script>
<script src="https://unpkg.com/@element-plus/icons-vue"></script>

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
    const searchText     = ref('');
    const filterStatus   = ref('');
    const filterCategory = ref('');

    // ===== Stats =====
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
      { value: '',  label: '全部',   dotClass: 'dot-all',   count: stats.value.total },
      { value: '0', label: '可借閱', dotClass: 'dot-avail', count: stats.value.available },
      { value: '1', label: '借出中', dotClass: 'dot-out',   count: stats.value.borrowed },
    ]);

    // 記錄目前被展開的大分類 ID
    const expandedMains = ref([]);

    // 切換展開/收合狀態
    function toggleCategory(cat_id) {
      const idx = expandedMains.value.indexOf(cat_id);
      if (idx > -1) {
        expandedMains.value.splice(idx, 1); // 已經展開就收合
      } else {
        expandedMains.value.push(cat_id);   // 沒展開就加入展開列表
      }
    }

    // 計算子分類屬於哪一個大分類 (例如 015 -> 010)
    function getParentId(cat_id) {
      return String(Math.floor(parseInt(cat_id, 10) / 10) * 10).padStart(3, '0');
    }

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
        currentUser.value = res.data;
        // 管理員導向後台（但也可以留在此頁瀏覽）
      } catch {
        window.location.href = 'login.php';
      }
    }

    // ===== Fetch =====
    async function fetchBooks() {
      loading.value = true;
      currentPage.value = 1;
      const params = new URLSearchParams();
      if (searchText.value)     params.set('search', searchText.value);
      if (filterStatus.value)   params.set('status', filterStatus.value);
      if (filterCategory.value) params.set('category_id', filterCategory.value);
      try {
        const res = await api(`api/get_books.php?${params}`);
        if (res.success) books.value = res.data ?? [];
        else ElementPlus.ElMessage.error(res.message || '載入失敗');
      } catch { ElementPlus.ElMessage.error('網路錯誤，請確認伺服器是否運行'); }
      finally { loading.value = false; }
    }

    // 清空並重新抓取的函式
    function resetFilters() {
      searchText.value = '';      // 清空搜尋文字
      filterStatus.value = '';    // 清空狀態篩選 (全部)
      filterCategory.value = '';  // 清空分類篩選 (全部分類)
      
      // 重置時連左側展開的分類也全部收合，可以加上下面這行
      expandedMains.value = []; 
      try {
        fetchBooks();               // 重新執行查詢
      } catch {
        ElementPlus.ElMessage.error('重置篩選失敗');
      }
    }

    async function fetchCategories() {
      const res = await api('api/get_categories.php');
      if (res.success) {
        let data = res.data ?? [];
        // 將分類依照數字大小排序
        data.sort((a, b) => parseInt(a.category_id, 10) - parseInt(b.category_id, 10));
        categories.value = data;
      }
    }

    // ===== Logout =====
    async function handleLogout() {
      await api('api/logout.php', { method: 'POST' });
      localStorage.clear();
      window.location.href = 'login.php';
    }

    // ===== Borrow Dialog =====
    const borrowDialog = ref({
      visible: false,
      loading: false,
      book: null,
      form: { due_days: 14 },
    });

    const borrowDialogDueDate = computed(() => {
      const d = new Date();
      d.setDate(d.getDate() + borrowDialog.value.form.due_days);
      return d.toLocaleDateString('zh-TW');
    });

    function openBorrowDialog(row) {
      borrowDialog.value.book = row;
      borrowDialog.value.form = { due_days: 14 };
      borrowDialog.value.visible = true;
    }

    async function submitBorrow() {
      borrowDialog.value.loading = true;
      try {
        const res = await api('api/borrow_book.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            book_id:  borrowDialog.value.book.book_id,
            due_days: borrowDialog.value.form.due_days,
          }),
        });
        if (res.success) {
          ElementPlus.ElMessage.success(res.message);
          borrowDialog.value.visible = false;
          fetchBooks();
          fetchStats(); // 借出成功後更新統計數字
        } else {
          ElementPlus.ElMessage.error(res.message);
        }
      } catch { ElementPlus.ElMessage.error('借閱失敗'); }
      finally { borrowDialog.value.loading = false; }
    }

    // ===== Return =====
    async function returnBook(row) {
      try {
        await ElementPlus.ElMessageBox.confirm(
          `確定要歸還《${row.title}》嗎？`,
          '確認還書',
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

    // ===== Notify Return (直接發送，不需 Dialog) =====
    async function notifyReturn(row) {
      try {
        await ElementPlus.ElMessageBox.confirm(
          `將發送 Email 通知借書人歸還《${row.title}》，確定嗎？`,
          '發送催還通知',
          { confirmButtonText: '發送', cancelButtonText: '取消', type: 'warning' }
        );
        const res = await api('api/notify_return.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ book_id: row.book_id }),
        });
        if (res.success) ElementPlus.ElMessage.success(res.message);
        else ElementPlus.ElMessage.error(res.message);
      } catch (e) { if (e !== 'cancel') ElementPlus.ElMessage.error('發送失敗'); }
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
      borrowDialog, borrowDialogDueDate, openBorrowDialog, submitBorrow,
      returnBook, notifyReturn,
      expandedMains, toggleCategory, getParentId, // 分類展開相關
      handleLogout, fetchBooks, resetFilters,
    };
  }
}).use(ElementPlus).mount('#app');
</script>
</body>
</html>
