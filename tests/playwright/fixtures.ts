import { type Page, test as base, type TestInfo } from "@playwright/test";
import * as path from "path";
import * as fs from "fs";
import { login } from "@hipanel-core/common/auth";

const testClients = {
  client: { login: "hipanel_test_user", password: "random" },
  admin: { login: "hipanel_test_admin", password: "random" },
  manager: { login: "hipanel_test_manager", password: "random" },
  seller: { login: "hipanel_test_reseller", password: "random" },
};

export { expect } from "@playwright/test";
export const test = base.extend<{
  clientPage: Page,
  adminPage: Page,
  managerPage: Page,
  sellerPage: Page,
}>({
  storageState: async ({ browser }, use, testInfo: TestInfo) => {
    let actor;
    const testTitle = testInfo.title;
    ["seller", "manager", "client", "admin"].forEach((role: string) => {
      if (testTitle.includes(`@${role}`)) {
        actor = role;
        return;
      }
    });
    if (!actor) {
      throw new Error("Test role is not found, the role tag must be present in the test title, for example: @seller, @manager, @client, @admin");
    }
    // const fileName = path.join(testInfo.project.outputDir, `auth-storage-${actor}`);
    const fileName = path.join(process.cwd(), 'tests/_data', `auth-storage-${actor}`);
    if (!fs.existsSync(fileName)) {
      const page = await browser.newPage({ storageState: undefined });
      await login(page, testClients[actor]);
      await page.context().storageState({ path: fileName });
      await page.close();
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
});
