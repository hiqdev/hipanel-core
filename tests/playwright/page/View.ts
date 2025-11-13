import { expect, Page } from "@playwright/test";
import DetailPageMenu from "@hipanel-core/ui/DetailPageMenu";

export default class View {
  readonly detailPageMenu: DetailPageMenu;

  constructor(readonly page: Page) {
    this.detailPageMenu = new DetailPageMenu(page);
  }

  async see(values: string[]): Promise<void> {
    for (const text of values) {
      await expect(this.page.locator(`:has-text("${text}")`).first()).toBeVisible();
    }
  }
}
