import { expect, Locator, Page } from "@playwright/test";

export default class Alert {
  private alertLocator: Locator;

  private constructor(readonly page: Page) {
    this.alertLocator = page.locator("div[role=alert]");
  }

  static on(page: Page): Alert {
    return new Alert(page);
  }

  async hasText(text: string): Promise<void> {
    await expect(this.alertLocator).toContainText(text);
  }

  async close(): Promise<void> {
    await this.page.getByTitle("Close").click();
  }

  async isVisible(): Promise<boolean> {
    return this.alertLocator.waitFor({ state: "visible" }).then(() => true).catch(() => false);
  }
}
