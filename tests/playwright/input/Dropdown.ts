import { Locator, Page } from "@playwright/test";

export default class Dropdown {
  private selector: Locator;

  private constructor(private page: Page, fieldId: string) {
    this.selector = page.locator(`${fieldId}`);
  }

  static field(page: Page, fieldId: string): Dropdown {
    return new Dropdown(page, fieldId);
  }

  async setValue(value: string) {
    await this.selector.selectOption(value);
  }

  async setLabel(label: string) {
    await this.selector.selectOption({ label: label });
  }
}
