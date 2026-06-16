// เซิร์ฟเวอร์สำหรับรัน Smart Farm Dashboard
// รัน: node server.js
// เปิดเบราว์เซอร์: http://localhost:3000

const http = require('http');
const fs   = require('fs');
const path = require('path');

const PORT = 3000;
const DIR  = __dirname;

const MIME = {
  '.html': 'text/html; charset=utf-8',
  '.js':   'application/javascript',
  '.css':  'text/css',
  '.json': 'application/json',
  '.png':  'image/png',
  '.ico':  'image/x-icon'
};

http.createServer((req, res) => {
  // CORS headers — ให้ browser เรียก n8n localhost ได้
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

  if (req.method === 'OPTIONS') { res.writeHead(204); return res.end(); }

  const urlPath = req.url === '/' ? '/dashboard.html' : req.url;
  const filePath = path.join(DIR, urlPath);

  // ป้องกัน path traversal
  if (!filePath.startsWith(DIR)) {
    res.writeHead(403); return res.end('Forbidden');
  }

  fs.readFile(filePath, (err, data) => {
    if (err) {
      res.writeHead(404, { 'Content-Type': 'text/plain' });
      return res.end('Not found: ' + urlPath);
    }
    const ext  = path.extname(filePath);
    const mime = MIME[ext] || 'application/octet-stream';
    res.writeHead(200, { 'Content-Type': mime });
    res.end(data);
  });
}).listen(PORT, '127.0.0.1', () => {
  console.log('');
  console.log('🌱 Smart Farm Dashboard กำลังทำงานอยู่');
  console.log('');
  console.log('  📊 เปิด Dashboard ที่: http://localhost:' + PORT);
  console.log('');
  console.log('  กด Ctrl+C เพื่อหยุดเซิร์ฟเวอร์');
  console.log('');
});
