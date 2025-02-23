import { Locator, Page } from "@playwright/test";

export default class SumWithCurrency {
  private page: Page;
  private sum: Locator;
  private currencyContainer: Locator;

  private constructor(page: Page, formId: string, k: number) {
    this.page = page;
    const sumInput = `${formId}-${k}-sum`;
    this.sum = page.locator(`#${sumInput}`);
    this.currencyContainer = page.locator(`//input[contains(@id, '${sumInput}')]/../div[contains(@class, 'input-group-btn')]`);
  }

  static field(page: Page, formId: string, k: number): SumWithCurrency {
    return new SumWithCurrency(page, formId, k);
  }

  async setSumAndCurrency(sum: number, currency: string) {
    await this.sum.fill(sum.toString());
    await this.currencyContainer.filter({ has: this.page.locator("button:has-text(\"Toggle dropdown\")") }).click();
    await this.page.locator(`.input-group-btn.open ul a:text-is("${currency}")`).first().click();
  }
}


