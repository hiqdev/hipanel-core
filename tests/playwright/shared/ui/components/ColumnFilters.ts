import { expect, Locator, Page } from "@playwright/test";
import { FilterInput } from "@hipanel-core/shared/ui/inputs";

export default class ColumnFilters {
  private grid: Locator;
  private filterInput: FilterInput;

  constructor(readonly page: Page) {
    this.grid = page.locator("table.table");
    this.filterInput = new FilterInput(this.page, this.grid);
  }

  async hasFilter(inputName: string) {
    await this.page.waitForSelector(`tr.filters input[name*=${inputName}]`);
  }

  async clearFilter(inputName: string) {
    await this.filterInput.setFilter(inputName, "");
    await this.apply();
  }

  async clearAllFilters() {
    const clearAllButton = this.page.locator("a#clear-filters");
    await clearAllButton.click();
  }

  async applyFilter(inputName: string, value: string) {
    await this.filterInput.setFilter(inputName, value);
    await this.apply();
  }

  private async apply() {
    await expect(this.page).toHaveURL(/.*Search.*/, { timeout: 30_000 });
    await this.page.waitForLoadState("networkidle");
  }
}
