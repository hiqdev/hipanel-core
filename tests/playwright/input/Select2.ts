import { expect, Locator, Page } from "@playwright/test";

export default class Select2 {
  private dropdownSearchField: Locator;
  private searchResult: Locator;
  private combobox: Locator;

  private constructor(page: Page, fieldId: string) {
    this.dropdownSearchField = page.locator(`span.select2-search.select2-search--dropdown > input`);
    this.searchResult = page.locator(".select2-container .select2-results__option--highlighted");
    this.combobox = page.locator(`${fieldId} + .select2-container [role=combobox]`);
  }

  static field(page: Page, fieldId: string): Select2 {
    return new Select2(page, fieldId);
  }

  async setValue(value: string) {
    await this.combobox.click();
    await this.dropdownSearchField.fill(value);
    await expect(this.searchResult.first()).toContainText(value);
    await this.searchResult.first().click();
  }
}


