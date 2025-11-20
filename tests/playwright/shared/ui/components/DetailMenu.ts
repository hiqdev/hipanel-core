import { Locator, Page } from "@playwright/test";

export default class DetailMenu {
  private menuLocator: Locator;

  constructor(page: Page) {
    this.menuLocator = page.locator(".widget-user-2");
  }

  async click(item: string) {
    await this.menuLocator.locator("a").filter({ hasText: item }).click();
  }

  async has(item: string) {
    await this.menuLocator.locator("a").filter({ hasText: item }).first().isVisible();
  }
}
