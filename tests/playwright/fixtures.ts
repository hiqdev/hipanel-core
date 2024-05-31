import { type Browser, type Page, test as base, type TestInfo } from "@playwright/test";
import { login } from "@hipanel-core/common/auth";
import * as path from "path";
import * as fs from "fs";

const testClients = {
  client: { login: "hipanel_test_user", password: "random" },
  admin: { login: "hipanel_test_admin", password: "random" },
  manager: { login: "hipanel_test_manager", password: "random" },
  seller: { login: "hipanel_test_reseller", password: "random" },
  osrc: { login: "osrc_testuser", password: "random" },
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
  osrcPage: Page,
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
      throw new Error("Test role is not found, the role tag must be present in the test title, for example: @seller, @manager, @client, @admin, @osrc");
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
    await use(page);
  },
  managerPage: async ({ page }, use) => {
    await use(page);
  },
  adminPage: async ({ page }, use) => {
    await use(page);
  },
  clientPage: async ({ page }, use) => {
    await use(page);
  },
  osrcPage: async ({ page }, use) => {
    await use(page);
  },
});

export { expect } from "@playwright/test";
