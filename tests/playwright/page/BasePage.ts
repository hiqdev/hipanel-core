import { Page } from "@playwright/test";

export default abstract class BasePage {
  constructor(readonly page: Page) {
  }

  public async isVisible(selector: string): Promise<boolean> {
    return await this.page.locator(selector).isVisible({ timeout: 5000 }).catch(() => false);
  }
}
