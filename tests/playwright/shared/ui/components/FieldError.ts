import { expect, Page } from "@playwright/test";

export default class FieldError {
  private constructor(private readonly page: Page) {
  }

  static on(page: Page): FieldError {
    return new FieldError(page);
  }

  async hasError(field: string) {
    await expect(this.page.locator(`label:has-text("${field}")`).locator("..").locator(":scope .help-block-error").first()).not.toBeEmpty();
  }

  async hasErrorMessage(field: string, msg: string) {
    await expect(this.page.locator(`label:has-text("${field}")`).locator("..").locator(`:scope .help-block-error:has-text("${msg}")`).first()).toBeVisible();
  }
}

