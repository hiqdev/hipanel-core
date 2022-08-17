import { expect, Locator, Page } from "@playwright/test";

export default class Select2 {
  private dropdownSearchField: Locator;
  private combobox: Locator;

  private constructor(private page: Page, fieldId: string) {
    this.dropdownSearchField = page.locator(".select2-container--open input.select2-search__field");
    this.combobox = page.locator(`${fieldId} + .select2-container [role=combobox]`);
  }

  static field(page: Page, fieldId: string): Select2 {
    return new Select2(page, fieldId);
  }

  async setValue(value: string) {
    await this.combobox.click();
    await this.dropdownSearchField.fill(value);
    await this.page.locator("ul.select2-results__options .loading-results").waitFor({ state: "hidden" });
    await this.page.locator(`//ul[contains(@class, 'select2-results__options')]/li[normalize-space(text())='${value}']`).highlight();
    await this.page.waitForTimeout(2000);
  }
}


