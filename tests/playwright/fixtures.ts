import { test as base, type Page, type BrowserContext } from "@playwright/test";
import { login, type Credentials } from "@hipanel-core/common/auth";

export const test = base.extend<{
  clientContext: BrowserContext, clientPage: Page,
  adminContext: BrowserContext, adminPage: Page,
  managerContext: BrowserContext, managerPage: Page,
  sellerContext: BrowserContext, sellerPage: Page,
}, {
  clientState: any,
  adminState: any,
  managerState: any,
  sellerState: any,
} & Credentials>({
  client: [{ login: "", password: "" }, { scope: "worker", option: true }],
  admin: [{ login: "", password: "" }, { scope: "worker", option: true }],
  manager: [{ login: "", password: "" }, { scope: "worker", option: true }],
  seller: [{ login: "", password: "" }, { scope: "worker", option: true }],
  // Client
  clientState: [async ({ browser, client }, use) => {
    const page = await browser.newPage();
    await login(page, client);

    const cookies = await page.context().cookies();
    const state = { cookies };

    use(state);

  }, { scope: "worker" }],
  clientContext: async ({ context, clientState }, use) => {
    const { cookies } = clientState;
    await context.addCookies(cookies);

    use(context);
  },
  clientPage: async ({ clientContext }, use) => {
    const page = await clientContext.newPage();

    use(page);
  },
  // Admin
  adminState: [async ({ browser, admin }, use) => {
    const page = await browser.newPage();
    await login(page, admin);

    const cookies = await page.context().cookies();
    const state = { cookies };

    use(state);

  }, { scope: "worker" }],
  adminContext: async ({ context, adminState }, use) => {
    const { cookies } = adminState;
    await context.addCookies(cookies);

    use(context);
  },
  adminPage: async ({ adminContext }, use) => {
    const page = await adminContext.newPage();

    use(page);
  },
  // Manager
  managerState: [async ({ browser, manager }, use) => {
    const page = await browser.newPage();
    await login(page, manager);

    const cookies = await page.context().cookies();
    const state = { cookies };

    use(state);

  }, { scope: "worker" }],
  managerContext: async ({ context, managerState }, use) => {
    const { cookies } = managerState;
    await context.addCookies(cookies);

    use(context);
  },
  managerPage: async ({ managerContext }, use) => {
    const page = await managerContext.newPage();

    use(page);
  },
  // Seller
  sellerState: [async ({ browser, seller }, use) => {
    const page = await browser.newPage();
    await login(page, seller);

    const cookies = await page.context().cookies();
    const state = { cookies };

    use(state);

  }, { scope: "worker" }],
  sellerContext: async ({ context, sellerState }, use) => {
    const { cookies } = sellerState;
    await context.addCookies(cookies);

    use(context);
  },
  sellerPage: async ({ sellerContext }, use) => {
    const page = await sellerContext.newPage();

    use(page);
  },
});
