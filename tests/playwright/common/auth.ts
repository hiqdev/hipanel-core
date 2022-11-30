import { expect, type Page } from "@playwright/test";

export type Credentials = {
  client: Credential,
  admin: Credential,
  manager: Credential,
  seller: Credential,
}

export type Credential = {
  login: string,
  password: string,
}

export async function login(page: Page, credential: Credential) {
  await page.goto(`${process.env.URL}/site/login`);
  await page.fill("#loginform-username", credential.login);
  await page.fill("#loginform-password", credential.password);
  await page.click("#login-form button[type=submit]");
  await expect(page.locator(".user.user-menu span")).toHaveText(credential.login);
}
