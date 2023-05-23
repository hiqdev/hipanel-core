import { expect, Locator, Page } from "@playwright/test";

export default class Dropdown {
    private selector: Locator;

    private constructor(private page: Page, fieldId: string) {
        this.selector = page.locator(`${fieldId}`);
    }

    static field(page: Page, fieldId: string): Dropdown {
        return new Dropdown(page, fieldId);
    }

    async setValue(value: string) {
        console.log(value);
        await this.selector.selectOption(value);
    }
}
