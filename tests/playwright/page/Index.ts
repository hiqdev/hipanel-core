import { expect, Page } from "@playwright/test";

export default class Index {
  constructor(private page: Page) {
  }

  async hasAdvancedSearchInputs(names: Array<string>) {
    for (const name of names) {
      await expect(this.page.locator(`div.advanced-search *[name='${name}']`)).toBeVisible();
    }
  }

  async hasBulkButtons(names: Array<string>) {
    for (const name of names) {
      await expect(this.page.locator(`div.box-bulk-actions :has-text("${name}")`).first()).toBeVisible();
    }
  }

  async hasColumns(names: Array<string>) {
    for (const name of names) {
      await expect(this.page.locator(`[role=grid] th:has-text("${name}")`).first()).toBeVisible();
    }
  }

  async hasRowsOnTable(count: number) {
    await expect(this.page.locator('input[name="selection[]"]')).toHaveCount(count);
  }

  async chooseNumberRowOnTable(number: number) {
    await this.page.locator('input[name="selection[]"]').nth(number - 1).highlight();
    await this.page.locator('input[name="selection[]"]').nth(number - 1).click();
  }

  async chooseRangeOfRowsOnTable(start: number, end: number) {
    for (let i = start - 1; i < end; i++) {
      await this.page.locator('input[name="selection[]"]').nth(i).highlight();
      await this.page.locator('input[name="selection[]"]').nth(i).click();
    }
  }

  async clickBulkButton(name: string) {
    await this.page.locator(`fieldset button:has-text("${name}")`).click();
  }

  async clickDropdownBulkButton(buttonName: string, selectName: string) {
    await this.page.locator(`fieldset button:has-text("${buttonName}")`).click();
    await this.page.locator(`fieldset a:has-text("${selectName}")`).highlight();
    await this.page.locator(`fieldset a:has-text("${selectName}")`).click();
  }
}
