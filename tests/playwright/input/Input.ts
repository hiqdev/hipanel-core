import { Locator, Page } from "@playwright/test";

export default class Input {
  private inputLocator: Locator;

  private constructor(private page: Page, selector: string) {
    this.inputLocator = page.locator(selector);
  }

  public static field(page: Page, selector: string): Input {
    return new Input(page, selector);
  }

  public async setValue(value?: string | null) {
    if (value) {
      await this.inputLocator.fill(value);
    } else {
      await this.inputLocator.fill("");
    }
    await this.inputLocator.blur();
    await this.page.waitForLoadState("networkidle");
  }

  public async fill(value: string | null) {
    await this.inputLocator.fill(value ?? "");
  }
}
