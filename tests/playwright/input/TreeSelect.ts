import { Locator, Page } from "@playwright/test";

export default class TreeSelect {
  private inputLocator: Locator;

  private constructor(private page: Page, fieldId: string) {
    this.inputLocator = page.locator(`label[for="${fieldId}"] + div > .vue-treeselect input[type="text"]`);
  }

  static field(page: Page, fieldId: string): TreeSelect {
    return new TreeSelect(page, fieldId);
  }

  async setValue(value: string) {
    await this.inputLocator.click();
    await this.inputLocator.fill(value);
    await this.page.locator(`label.vue-treeselect__label >> text=/^${value}$/i`).last().click();
  }
}
