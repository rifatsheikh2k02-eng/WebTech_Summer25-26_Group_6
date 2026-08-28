<?php
header('Content-Type: text/css');
?>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { background: #f4f7f6; color: #24322d; font-family: Arial, sans-serif; line-height: 1.5; }

.main { width: 94%; max-width: 1200px; margin: auto; padding: 28px 0; }
.welcome { display: flex; align-items: center; justify-content: space-between; gap: 18px; margin-bottom: 20px; padding: 25px; background: #245a45; color: white; border-radius: 10px; }
.welcome h1 { margin: 0 0 6px; font-size: 27px; }
.welcome p { margin: 0; color: #d9eee4; }
.welcome-badge { font-size: 35px; }

.stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px; }
.stat { padding: 20px; border: 1px solid #dbe5e0; border-radius: 10px; background: #fff; box-shadow: 0 3px 12px rgba(27, 65, 48, .06); }
.stat span { color: #687a71; font-size: 13px; display: block; }
.stat strong { display: block; margin-top: 8px; color: #245a45; font-size: 27px; }

.quick-links { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 15px; margin-bottom: 20px; }
.quick-link { display: block; padding: 24px; border-radius: 10px; background: #fff; color: #245a45; text-decoration: none; box-shadow: 0 3px 12px rgba(27, 65, 48, .06); border: 1px solid #dbe5e0; }
.quick-link:hover { background: #e9f4ef; }
.quick-link strong { display: block; margin-bottom: 8px; font-size: 17px; }
.quick-link span { color: #687a71; font-size: 13px; }

.panel { padding: 24px; border: 1px solid #dbe5e0; border-radius: 10px; background: #fff; box-shadow: 0 3px 12px rgba(27, 65, 48, .06); margin-bottom: 20px; }
.panel h2 { margin: 0 0 18px; color: #245a45; font-size: 20px; }
.panel h3 { margin: 0 0 10px; font-size: 16px; }

.message { margin-bottom: 18px; padding: 11px 14px; border-radius: 6px; background: #dff3e8; color: #17633f; }
.message.error { background: #f8dddd; color: #8c3030; }

.table-container { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.data-table th, .data-table td { padding: 12px 14px; border-bottom: 1px solid #dbe5e0; text-align: left; }
.data-table th { background: #fbfdfc; color: #245a45; font-weight: 600; }
.data-table tr:hover { background: #fbfdfc; }
.data-table .no-data { text-align: center; color: #687a71; padding: 30px; }

.book-image { width: 50px; height: 70px; object-fit: cover; border-radius: 4px; border: 1px solid #dbe5e0; }

.search-bar { display: flex; gap: 10px; margin-bottom: 18px; flex-wrap: wrap; align-items: flex-end; }
.search-bar input, .search-bar select { padding: 9px 12px; border: 1px solid #bdcec5; border-radius: 5px; background: #fff; font-size: 14px; }
.search-bar input[type="text"] { flex: 1; min-width: 200px; }
.search-bar select { min-width: 180px; }

.btn { display: inline-block; padding: 9px 13px; border: 0; border-radius: 5px; background: #28795c; color: #fff; cursor: pointer; font-size: 14px; text-decoration: none; }
.btn:hover { background: #1d6248; }
.btn-sm { padding: 6px 10px; font-size: 13px; }
.btn-success { background: #28795c; }
.btn-success:hover { background: #1d6248; }
.btn-warning { background: #e6a500; color: #24322d; }
.btn-warning:hover { background: #cc8d00; }
.btn-danger { background: #b94747; }
.btn-danger:hover { background: #913737; }

.form-group { margin-bottom: 15px; }
.form-group label { display: block; margin: 11px 0 5px; font-size: 13px; font-weight: bold; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 9px; border: 1px solid #bdcec5; border-radius: 5px; background: #fff; font-size: 14px; }
.form-group textarea { min-height: 80px; resize: vertical; }

.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; }
.sub-panel { padding: 18px; border: 1px solid #dbe5e0; border-radius: 7px; background: #fbfdfc; }

.profile-info { margin-bottom: 14px; line-height: 1.8; color: #4c6157; }

.status-badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.status-active { background: #dff3e8; color: #17633f; }
.status-inactive { background: #fff3cd; color: #856404; }
.status-blocked { background: #f8dddd; color: #8c3030; }
.status-pending { background: #fff3cd; color: #856404; }
.status-confirmed { background: #cce5ff; color: #004085; }
.status-processing { background: #cce5ff; color: #004085; }
.status-shipped { background: #d1ecf1; color: #0c5460; }
.status-delivered { background: #dff3e8; color: #17633f; }
.status-cancelled { background: #f8dddd; color: #8c3030; }

.header { background: #245a45; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
.header h2 { margin: 0; font-size: 22px; }
.header .user-info { display: flex; align-items: center; gap: 15px; }
.header .user-info a { color: white; text-decoration: underline; }

.navbar { background: #1b4130; padding: 0 30px; }
.navbar a { display: inline-block; padding: 12px 18px; color: #d9eee4; text-decoration: none; font-size: 14px; }
.navbar a:hover, .navbar a.active { background: #245a45; color: white; }

.container { padding: 20px 30px; }
.page-title { margin: 0 0 20px; color: #245a45; font-size: 24px; }

.content-box { background: white; border-radius: 10px; padding: 24px; box-shadow: 0 3px 12px rgba(27, 65, 48, .06); border: 1px solid #dbe5e0; }

.actions-cell { display: flex; gap: 8px; flex-wrap: wrap; }
.actions-cell form { display: inline; }

.detail-row { display: flex; gap: 20px; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #dbe5e0; }
.detail-row:last-child { border-bottom: none; }
.detail-label { font-weight: bold; min-width: 160px; color: #4c6157; }
.detail-value { flex: 1; }

.item-row { display: grid; grid-template-columns: 50px 1fr 80px 80px 100px; gap: 10px; padding: 10px; border-bottom: 1px solid #dbe5e0; font-size: 14px; }
.item-row:first-child { font-weight: bold; background: #fbfdfc; border-radius: 5px 5px 0 0; }
.item-row:last-child { border-bottom: none; }

.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; }
.stat-card { display: block; padding: 20px; border-radius: 10px; background: #fff; color: #245a45; text-decoration: none; box-shadow: 0 3px 12px rgba(27, 65, 48, .06); border: 1px solid #dbe5e0; transition: transform 0.2s; }
.stat-card:hover { transform: translateY(-2px); background: #e9f4ef; }
.stat-icon { font-size: 32px; margin-bottom: 10px; }
.stat-content h3 { font-size: 28px; margin: 0 0 5px; }
.stat-content p { margin: 0; font-size: 14px; color: #4c6157; }
.stat-content small { color: #687a71; font-size: 12px; }

.ajax-message { margin-bottom: 15px; padding: 10px 14px; border-radius: 6px; display: none; }
.ajax-message.show { display: block; }
.ajax-message.success { background: #dff3e8; color: #17633f; }
.ajax-message.error { background: #f8dddd; color: #8c3030; }

@media (max-width: 700px) {
    .welcome { align-items: flex-start; flex-direction: column; }
    .stats, .quick-links, .form-grid { grid-template-columns: 1fr; }
    .item-row { grid-template-columns: 1fr; }
    .navbar { overflow-x: auto; white-space: nowrap; }
}