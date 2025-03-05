import {expect, Locator, Page} from "@playwright/test";

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

    public get submitButton(): Locator {
        return this.root.locator("button[type=submit]");
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
          await fieldLocator.selectOption(value);
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
        await this.submitButton.click();
    }
}
