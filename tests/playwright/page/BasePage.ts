import { expect, Page } from "@playwright/test";

export default abstract class BasePage {
  constructor(readonly page: Page) {
  }

  async isVisible(selector: string): Promise<boolean> {
    return await this.page.locator(selector).isVisible({ timeout: 5000 }).catch(() => false);
  }

  async see(values: string[]): Promise<void> {
    for (const text of values) {
      await expect(this.page.getByText(text, { exact: false }).first()).toBeVisible();
    }
  }

  async notSee(values: string[]): Promise<void> {
    for (const text of values) {
      await expect(this.page.getByText(text, { exact: false }).first()).not.toBeVisible();
    }
  }
}
