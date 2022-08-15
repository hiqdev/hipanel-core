import { expect, Page } from "@playwright/test";

export default class Alert {
  readonly page: Page;

  private constructor(page: Page) {
    this.page = page;
  }

  static on(page: Page): Alert {
    return new Alert(page);
  }

  async hasText(text: string) {
    await this.page.waitForSelector("div[role=alert]");
    await expect(this.page.locator("div[role=alert]")).toContainText(text);
  }
}

