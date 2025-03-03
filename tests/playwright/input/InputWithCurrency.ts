import { Locator, Page } from "@playwright/test";

/**
 * Abstract base class for handling sum and currency selection.
 * This allows extension for different selector strategies.
 */
export default abstract class InputWithCurrency {
    protected page: Page;
    protected sumField: Locator;
    protected currencyDropdown: Locator;

    protected constructor(page: Page, sumInputId: string) {
        this.page = page;
        this.sumField = this.getSumField(`#${sumInputId}`);
        this.currencyDropdown = this.getCurrencyDropdown(sumInputId);
    }

    /**
     * Abstract methods to be implemented in subclasses for defining sum and currency selectors.
     */
    protected getSumField(sumInputId: string): Locator
    {
        return this.page.locator(sumInputId);
    }

    protected getCurrencyDropdown(sumInputId: string): Locator {
        return this.page.locator(`//input[contains(@id, '${sumInputId}')]/../div[contains(@class, 'input-group-btn')]`);
    }

    /**
     * Fills the sum field and selects a currency from the dropdown.
     */
    public async setSumAndCurrency(amount: number, currency: string) {
        await this.fillSumField(amount);
        await this.selectCurrency(currency);
    }

    /**
     * Fills the sum input field with the given amount.
     */
    private async fillSumField(amount: number) {
        await this.sumField.fill(amount.toString());
    }

    /**
     * Selects the given currency from the dropdown.
     */
    private async selectCurrency(currency: string) {
        await this.currencyDropdown.locator("button:has-text(\"Toggle dropdown\")").click();
        await this.page.locator(`.input-group-btn.open ul a:text-is("${currency}")`).first().click();
    }
}
