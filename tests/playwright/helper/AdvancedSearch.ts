import { expect, Locator, Page } from "@playwright/test";
import { FilterInput } from "@hipanel-core/shared/ui/inputs";

export default class AdvancedSearch {
  public context: Locator;
  private searchButton: Locator;
  private clearButton: Locator;
  private filterInput: FilterInput;

  constructor(private readonly page: Page) {
    this.context = this.page.locator("div.advanced-search");
    this.searchButton = this.context.getByRole("button", { name: "Search" }).last();
    this.clearButton = this.context.getByRole("button", { name: "Clear" }).last();
    this.filterInput = new FilterInput(this.page, this.context);
  }

  async hasInputsByNames(inputNames: Array<string>) {
    for (const name of inputNames) {
      await expect(this.context.locator(`*[name='${name}']`)).toBeVisible();
    }
  }

  async search() {
    await this.searchButton.waitFor({ state: "visible" });
    await expect(this.searchButton).toBeEnabled();
    await this.page.waitForLoadState("networkidle");

    await this.searchButton.focus();

    await this.searchButton.dispatchEvent("click", { timeout: 10_000 });

    /* Increase timeout to 30 seconds becuase search on pages like /finance/bill/index is really slow */
    await expect(this.page).toHaveURL(/.*Search.*/, { timeout: 30_000 });
  }

  async setFilter(name: string, value: string) {
    await this.filterInput.setFilter(name, value);
  }

  async applyFilter(name: string, value: string) {
    await this.setFilter(name, value);
    await this.search();
  }
}
