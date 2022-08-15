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
}
