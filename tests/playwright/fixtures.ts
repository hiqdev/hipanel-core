import { type Browser, type Page, test as base, type TestInfo } from "@playwright/test";
import { login } from "@hipanel-core/common/auth";
import * as path from "path";
import * as fs from "fs";

const testClients = {
  client: { login: "hipanel_test_user", password: "random" },
  admin: { login: "hipanel_test_admin", password: "random" },
  manager: { login: "hipanel_test_manager", password: "random" },
  seller: { login: "hipanel_test_reseller", password: "random" },
};

function getAuthStoragePath(actor) {
  return path.join(process.cwd(), "tests/_data", `auth-storage-${actor}.json`);
}

function getUserIdStoragePath(actor) {
  return path.join(process.cwd(), "tests/_data", `userId-${actor}.json`);
}

async function performLogin(actor, browser) {
  console.log(`Performing login for: ${actor}`);
  const page = await browser.newPage({ storageState: undefined });
  await login(page, testClients[actor]);
  const userId = await fetchUserId(page);

  saveUserId(actor, userId);
  await saveAuthState(page, actor);
  await page.close();
}

async function fetchUserId(page) {
  await page.goto(`${process.env.URL}/site/healthcheck`);
  const userId = await page.textContent("userId");
  if (!userId) throw new Error("Failed to retrieve user ID after login");
  console.log(`User ID retrieved: ${userId}`);
  return userId;
}

function saveUserId(actor, userId) {
  const filePath = getUserIdStoragePath(actor);
  const dir = path.dirname(filePath);

  // Ensure the directory exists
  if (!fs.existsSync(dir)) {
    fs.mkdirSync(dir, { recursive: true });
    console.log(`Created missing directory: ${dir}`);
  }

  console.log(`Saving User ID for ${actor} to ${filePath}`);
  fs.writeFileSync(filePath, JSON.stringify({ userId }, null, 2));
}

async function saveAuthState(page, actor) {
  const filePath = getAuthStoragePath(actor);
  console.log(`Saving Auth State for ${actor} to ${filePath}`);
  await page.context().storageState({ path: filePath });
}

export function getUserId(actor) {
  const filePath = getUserIdStoragePath(actor);
  if (!fs.existsSync(filePath)) {
    throw new Error(`User ID file not found for ${actor}`);
  }
  return JSON.parse(fs.readFileSync(filePath, "utf-8")).userId;
}

async function determineActor(testTitle) {
  return Object.keys(testClients).find(role => testTitle.includes(`@${role}`)) || null;
}

async function handleStorageState({ browser }, use, testInfo) {
  const actor = await determineActor(testInfo.title);
  if (!actor) throw new Error("Role tag not found in test title. Expected: @seller, @manager, @client, @admin");

  const authFilePath = getAuthStoragePath(actor);
  console.log(`Checking authentication file: ${authFilePath}`);

  if (!fs.existsSync(authFilePath) || isAuthFileExpired(authFilePath)) {
    await performLogin(actor, browser);
  }
  await use(authFilePath);
}

function isAuthFileExpired(filePath) {
  const { mtime } = fs.statSync(filePath);
  return (Date.now() - new Date(mtime).getTime()) > 86400000; // 24 hours
}

function attachNetworkResponseListener(page) {
  page.on("response", async (response) => {
    const resourceType = response.request().resourceType();
    if (resourceType !== "xhr" && resourceType !== "fetch") return;

    const formattedDate = new Date().toUTCString().replace(/GMT/, "+0000").replace(",", "");
    const { pathname, search } = new URL(response.url());
    const path = pathname + (search || "");
    const method = response.request().method();
    const status = response.status();
    const serverInfo = await response.serverAddr();
    const serverIp = serverInfo?.ipAddress || "Unknown";

    console.log(`${serverIp} - ${formattedDate} "${method} ${path}" ${status}`);
  });
}

export const test = base.extend({
  storageState: handleStorageState,
  sellerPage: async ({ page }, use) => {
    attachNetworkResponseListener(page);
    await use(page);
  },
  managerPage: async ({ page }, use) => {
    attachNetworkResponseListener(page);
    await use(page);
  },
  adminPage: async ({ page }, use) => {
    attachNetworkResponseListener(page);
    await use(page);
  },
  clientPage: async ({ page }, use) => {
    attachNetworkResponseListener(page);
    await use(page);
  },
});

export { expect } from "@playwright/test";
