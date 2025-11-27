import { expect, Locator, Page } from "@playwright/test";
import Select2 from "@hipanel-core/input/Select2";

export default class AdvancedSearch {
  public blockLocator: Locator;
  private searchButton: Locator;
  private clearButton: Locator;

  constructor(private readonly page: Page) {
    this.blockLocator = this.page.locator("div.advanced-search");
    this.searchButton = this.blockLocator.getByRole("button", { name: "Search" }).last();
    this.clearButton = this.blockLocator.getByRole("button", { name: "Clear" }).last();
  }

  async hasInputsByNames(inputNames: Array<string>) {
    for (const name of inputNames) {
      await expect(this.blockLocator.locator(`*[name='${name}']`)).toBeVisible();
    }
  }

  async search() {
    await this.searchButton.waitFor({ state: "visible" });
    await expect(this.searchButton).toBeEnabled();
    await this.page.waitForLoadState("networkidle");

    await this.searchButton.focus();

    await this.searchButton.dispatchEvent('click', { timeout: 10_000 });

    /* Increase timeout to 30 seconds becuase search on pages like /finance/bill/index is really slow */
    await expect(this.page).toHaveURL(/.*Search.*/, { timeout: 30_000 });
  }

  async setFilter(name: string, value: string) {
    const fieldLocator = this.blockLocator.locator(`[name*=Search\\[${name}\\]]`);

    if (!(await fieldLocator.count())) {
      throw new Error(`Filter field not found for: ${name}`);
    }

    const tagName = await fieldLocator.evaluate((el) => el.tagName.toLowerCase());

    if (tagName === "select") {
      const isSelect2 = await fieldLocator.evaluate((el) => el.classList.contains("select2-hidden-accessible"));
      if (isSelect2) {
        const id = await fieldLocator.getAttribute("id");
        await Select2.field(this.page, "#" + id).setValue(value);
      } else {
        await fieldLocator.selectOption(value);
      }
    } else if (tagName === "input") {
      const type = await fieldLocator.first().getAttribute("type");

      if (type === "checkbox" || type === "radio") {
        await fieldLocator.setChecked(true);
      } else {
        await fieldLocator.fill(value);
      }
    } else {
      throw new Error(`Unsupported field type: ${tagName} for name="${name}"`);
    }
  }

  async applyFilter(name: string, value: string) {
    await this.setFilter(name, value);
    await this.search();
  }
}
