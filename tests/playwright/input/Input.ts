import { expect, Locator, Page } from "@playwright/test";

export default class Input {
  private inputLocator: Locator;

  private constructor(private page: Page, selector: string) {
    this.inputLocator = page.locator(selector);
  }

  static filterBy(page: Page, columnName: string): Input {
    const fieldId = columnName.toLowerCase().substring(0, 5);
    return new Input(page, `tr.filters input[name*=${fieldId}]`);
  }

  async setValue(value: string) {
    await this.inputLocator.fill(value);
    await this.page.keyboard.press('Enter');
    await this.page.waitForLoadState('networkidle');
  }
}


