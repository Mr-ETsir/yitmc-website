import { chromium } from 'playwright';

const browser = await chromium.launch();
const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });

// Test homepage
await page.goto('http://localhost:25565/', { waitUntil: 'networkidle' });
await page.screenshot({ path: '/tmp/yitmc-home.png', fullPage: false });
console.log('✅ Homepage screenshot saved');

// Test join page
await page.goto('http://localhost:25565/join', { waitUntil: 'networkidle' });
await page.waitForTimeout(1000);
await page.screenshot({ path: '/tmp/yitmc-join.png', fullPage: true });
console.log('✅ Join page screenshot saved');

// Check QR codes
const wechatQr = page.locator('img[alt="微信群"]');
const visible = await wechatQr.isVisible();
console.log('微信群 QR visible:', visible);
if (visible) {
  const src = await wechatQr.getAttribute('src');
  console.log('微信群 QR src:', src);
  const box = await wechatQr.boundingBox();
  console.log('微信群 QR size:', box?.width, 'x', box?.height);
}

await browser.close();
console.log('✅ Done');
