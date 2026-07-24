import { expect, Locator, Page } from "@playwright/test";

export default class DetailMenu {
  public root: Locator;

  constructor(private readonly page: Page) {
    this.root = this.page.locator(".profile-usermenu .nav, .box-menu .nav");
  }

  detailMenuItem(item: string): Locator {
    return this.root.locator(`:scope a:text("${item}")`);
  }

  async clickDetailMenuItem(item: string) {
    await this.detailMenuItem(item).click();
  }

  async hasDetailMenuItem(item: string) {
    await expect(this.detailMenuItem(item)).toBeVisible();
  }
}
