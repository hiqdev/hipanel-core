import { expect, Locator, Page } from "@playwright/test";

export default class ColumnFilters {
  private grid: Locator;

  constructor(readonly page: Page) {
    this.grid = page.locator("table.table");
  }

  async hasFilter(inputName: string) {
    await this.page.waitForSelector(`tr.filters input[name*=${inputName}]`);
  }

  async clearFilter(inputName: string) {
    const filter = this.getFilter(inputName);
    await filter.fill("");
    await this.apply(filter);
  }

  async clearAllFilters() {
    const clearAllButton = this.page.locator("a#clear-filters");
    await clearAllButton.click();
  }

  async applyFilter(inputName: string, value: string) {
    const filter = this.getFilter(inputName);
    await filter.fill(value);
    await this.apply(filter);
    await this.page.waitForLoadState("networkidle");
  }

  private async apply(filter: Locator) {
    await filter.press("Enter");
    await expect(this.page).toHaveURL(
      /.*Search.*/,
      { timeout: 30000 },
    );
  }

  private getFilter(inputName: string) {
    return this.grid.locator(`tr.filters input[name*=${inputName}]`);
  }
}
