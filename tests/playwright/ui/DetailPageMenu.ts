import { Locator, Page } from "@playwright/test";

export default class DetailPageMenu {
  private menuLocator: Locator;

  constructor(page: Page) {
    this.menuLocator = page.locator(".widget-user-2");
  }

  async clickMenuItem(item: string) {
    await this.menuLocator.locator(`:scope a:text("${item}")`).click();
  }
}
