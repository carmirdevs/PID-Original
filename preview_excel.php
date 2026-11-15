<?php
/**
 * Vista de previsualización de Excel con SheetJS (mejorada con diagnóstico).
 * Uso: /preview_excel.php?file=nombre.xlsx
 */
$file = isset($_GET['file']) ? basename($_GET['file']) : '';
if (!$file) { http_response_code(400); echo "Falta parámetro 'file'"; exit; }
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Vista previa: <?php echo htmlspecialchars($file, ENT_QUOTES, 'UTF-8'); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root { --border:#e5e7eb; --bg:#f8fafc; --text:#111827; }
    *{ box-sizing:border-box; }
    body{ font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu; margin:0; color:var(--text); }
    header{ padding:12px 16px; border-bottom:1px solid var(--border); background:var(--bg); display:flex; align-items:center; justify-content:space-between; gap:8px; flex-wrap:wrap; }
    h1{ font-size:16px; margin:0; font-weight:600; }
    .actions{ display:flex; gap:8px; flex-wrap:wrap; }
    .btn{ border:1px solid var(--border); border-radius:8px; padding:8px 12px; text-decoration:none; color:inherit; background:#fff; }
    main{ padding:12px 16px; }
    #status{ margin-bottom:10px; font-size:14px; }
    .table-wrap{ overflow:auto; border:1px solid var(--border); border-radius:8px; padding:8px; }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js" defer></script>
</head>
<body>
  <header>
    <h1>Vista previa: <?php echo htmlspecialchars($file, ENT_QUOTES, 'UTF-8'); ?></h1>
    <div class="actions">
      <a class="btn" id="download" href="#">Descargar</a>
      <a class="btn" id="open-raw" href="#">Abrir original</a>
      <a class="btn" id="debug-info" href="#">Diagnóstico</a>
    </div>
  </header>
  <main>
    <div id="status">Cargando archivo…</div>
    <pre id="diag" style="display:none; padding:10px; background:#f9fafb; border:1px solid var(--border); border-radius:8px; white-space:pre-wrap;"></pre>
    <div class="table-wrap" id="out"></div>
  </main>

  <script>
  document.addEventListener('DOMContentLoaded', async () => {
    const file = <?php echo json_encode($file, JSON_UNESCAPED_SLASHES); ?>;
    const raw = 'excel_raw.php?file=' + encodeURIComponent(file);
    const debug = 'excel_raw.php?mode=info&file=' + encodeURIComponent(file);

    document.getElementById('download').href = raw;
    document.getElementById('open-raw').href = raw;
    document.getElementById('debug-info').href = debug;
    document.getElementById('debug-info').addEventListener('click', async (e) => {
      e.preventDefault();
      const res = await fetch(debug, { credentials: 'include' });
      const text = await res.text();
      const diag = document.getElementById('diag');
      diag.textContent = text;
      diag.style.display = 'block';
    });

    const statusEl = document.getElementById('status');
    const out = document.getElementById('out');

    try {
      const res = await fetch(raw, { credentials: 'include' });
      if (!res.ok) throw new Error('HTTP ' + res.status);
      const buf = await res.arrayBuffer();
      const wb = XLSX.read(buf, { type: 'array' });

      out.innerHTML = '';
      for (const name of wb.SheetNames) {
        const h2 = document.createElement('h2');
        h2.textContent = 'Hoja: ' + name;
        h2.style.fontSize = '14px';
        h2.style.margin = '12px 0 6px';
        out.appendChild(h2);

        const html = XLSX.utils.sheet_to_html(wb.Sheets[name], { editable: false });
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        const table = tmp.querySelector('table');
        if (table) {
          table.style.borderCollapse = 'collapse';
          table.style.width = '100%';
          table.querySelectorAll('th,td').forEach(td => {
            td.style.border = '1px solid var(--border)';
            td.style.padding = '6px 8px';
            td.style.whiteSpace = 'nowrap';
          });
          const ths = table.querySelectorAll('th');
          ths.forEach(th => { th.style.background = '#f3f4f6'; });
          out.appendChild(table);
        }
      }
      statusEl.textContent = 'Listo';
    } catch (err) {
      console.error(err);
      statusEl.textContent = 'No se pudo cargar el archivo. Revisa permisos o ruta.';
    }
  });
  </script>
</body>
</html>
