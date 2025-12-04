<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Rekap Absen (Starter)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Arial,sans-serif;margin:24px;}
    table{border-collapse:collapse;width:100%;}
    th,td{border:1px solid #ddd;padding:8px;}
    th{background:#f5f5f5;text-align:left;}
    .muted{color:#666;}
  </style>
</head>
<body>
  <h2>Rekap Absen (Nama + Waktu)</h2>
  <div style="margin-bottom:12px">
    <label>IP Mesin:</label>
    <input id="ip" value="192.168.9.201">
    <button id="btn">Tarik Sekarang</button>
    <span id="status" class="muted"></span>
  </div>

  <table id="tbl">
    <thead>
      <tr>
        <th>#</th>
        <th>Nama</th>
        <th>Waktu</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

<script>
const btn = document.getElementById('btn');
const ip  = document.getElementById('ip');
const st  = document.getElementById('status');
const tb  = document.querySelector('#tbl tbody');

btn.onclick = async () => {
  tb.innerHTML = '';
  st.textContent = 'narik data...';
  try {
    const res = await fetch('<?= site_url('rekap/tarik_finger') ?>?ip=' + encodeURIComponent(ip.value));
    const json = await res.json();
    if(!json.ok){ st.textContent = 'gagal: ' + (json.msg||''); return; }
    json.rows.forEach((r, i) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `<td>${i+1}</td><td>${r.nama||'-'}</td><td>${r.waktu||'-'}</td>`;
      tb.appendChild(tr);
    });
    st.textContent = 'berhasil: ' + json.rows.length + ' baris';
  } catch(err){
    st.textContent = 'error js: ' + err.message;
  }
};
</script>
</body>
</html>
