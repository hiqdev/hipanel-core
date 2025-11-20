import { Locator, Page } from "@playwright/test";

export default class BulkActions {
  private bulkButtons: Locator;

  private constructor(readonly page: Page) {
    this.bulkButtons = page.locator(".box-bulk-actions");
  }

  static on(page: Page): BulkActions {
    return new BulkActions(page);
  }

  async dropdown(name: string): Promise<Locator> {
    await this.bulkButtons.getByRole("button", { name: name }).click();

    return this.bulkButtons.filter({ has: this.page.locator(".dropdown.open > ul.dropdown-menu") });
  }

  async click(name: string) {
    await this.bulkButtons.getByText(name).highlight();
  }
}
