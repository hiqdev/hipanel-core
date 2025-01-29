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

const doLogin = async (fileName: string, actor: string, browser: Browser) => {
  const page = await browser.newPage({ storageState: undefined });
  await login(page, testClients[actor]);
  await page.context().storageState({ path: fileName });
  await page.close();
};

export const test = base.extend<{
  clientPage: Page,
  adminPage: Page,
  managerPage: Page,
  sellerPage: Page,
}>({
  storageState: async ({ browser }, use, testInfo: TestInfo) => {
    let actor;
    const testTitle = testInfo.title;
    Object.keys(testClients).forEach((role: string) => {
      if (!actor && testTitle.includes(`@${role}`)) {
        actor = role;
      }
    });
    if (!actor) {
      throw new Error("Test role is not found, the role tag must be present in the test title, for example: @seller, @manager, @client, @admin");
    }
    const fileName = path.join(process.cwd(), "tests/_data", `auth-storage-${actor}.json`);
    if (!fs.existsSync(fileName)) {
      await doLogin(fileName, actor, browser);
    } else {
      const { mtime } = fs.statSync(fileName);
      const timeWhenWeShouldReplaceTheOldOne = new Date(mtime).getTime() + 86400000; // 24 hours in milliseconds
      if (new Date().getTime() > timeWhenWeShouldReplaceTheOldOne) {
        await doLogin(fileName, actor, browser);
      }
    }
    await use(fileName);
  },
  // TODO: legacy auth flow compatibility, remove after refactoring from older implementation
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

function attachNetworkResponseListener(page: Page) {
  page.on("response", async (response) => {
    const resourceType = response.request().resourceType();

    // Filter for XMLHttpRequest (XHR) and Fetch requests
    if (resourceType === "xhr" || resourceType === "fetch") {
      const formattedDate = new Date().toUTCString().replace(/GMT/, "+0000")
          .replace(",", ""); // Format: 28/Jan/2025:13:46:29 +0000
      const url = new URL(response.url());
      const method = response.request().method();
      const path = url.pathname + (url.search || "");
      const status = response.status();

      // Fetch server IP address
      const serverInfo = await response.serverAddr();
      const serverIp = serverInfo?.ipAddress || "Unknown"; // If unavailable, fallback to "Unknown"

      console.log(`${serverIp} - ${formattedDate} "${method} ${path}" ${status}`);
    }
  });
}

export { expect } from "@playwright/test";
