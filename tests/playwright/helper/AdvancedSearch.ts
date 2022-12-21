import { expect, Locator, Page } from "@playwright/test";

export default class AdvancedSearch {
  public root: Locator;

  constructor(private readonly page: Page) {
    this.root = this.page.locator("div.advanced-search");
  }

  async hasInputsByNames(inputNames: Array<string>) {
    for (const name of inputNames) {
      await expect(this.root.locator(`*[name='${name}']`)).toBeVisible();
    }
  }

  public get submitButton(): Locator {
    return this.root.locator("button[type=submit]");
  }

  public get cancelButton(): Locator {
    return this.root.locator("a:has-text('Clear')");
  }
}
