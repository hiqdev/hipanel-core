import {expect, Locator, Page} from "@playwright/test";
import Select2 from "@hipanel-core/input/Select2";

export default class AdvancedSearch {
    public root: Locator;

    constructor(private readonly page: Page) {
        this.root = this.page.locator("div.advanced-search");
    }

    async hasInputsByNames(inputNames: Array<string>) {
        for (const name of inputNames) {
            await expect(this.root.locator(`*[name='${name}']`)).toBeVisible();
        }
    }

    public async submitButton() {
        await this.root.locator("button[type=submit]").click();
        await expect(this.page).toHaveURL(
            /.*Search.*/,
            {timeout: 30000}  // Increase timeout to 30 seconds becuase search on pages like /finance/bill/index is really slow
        );
    }

    public get cancelButton(): Locator {
        return this.root.locator("a:has-text('Clear')");
    }

    public async setFilter(name: string, value: string) {
        const fieldLocator = this.root.locator(`[name*=Search\\[${name}\\]]`);

        if (!(await fieldLocator.count())) {
            throw new Error(`Filter field not found for: ${name}`);
        }

        const tagName = await fieldLocator.evaluate((el) => el.tagName.toLowerCase());

        if (tagName === "select") {
          const isSelect2 = await fieldLocator.evaluate((el) => el.classList.contains('select2-hidden-accessible'));
          if (isSelect2) {
            const id = await fieldLocator.getAttribute('id');
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

    public async applyFilter(name: string, value: string) {
        await this.setFilter(name, value);
        await this.submitButton();
    }
}
