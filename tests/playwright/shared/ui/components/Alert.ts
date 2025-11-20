import { expect, Page } from "@playwright/test";

export default class Alert {
  private constructor(readonly page: Page) {
  }

  static on(page: Page): Alert {
    return new Alert(page);
  }

  async hasText(text: string): Promise<void> {
    await this.page.waitForSelector("div[role=alert]");
    await expect(this.page.locator("div[role=alert]")).toContainText(text);
  }

  async close(): Promise<void> {
    const isVisible = await this.isVisible();
    if (isVisible) {
      await this.page.locator("button[aria-label=Close]").click();
    }
  }

  async isVisible(): Promise<boolean> {
    await this.page.waitForSelector("div[role=alert]");

    return this.page.locator("div[role=alert]").isVisible();
  }
}
