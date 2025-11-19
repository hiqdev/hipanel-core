import { expect, Page } from "@playwright/test";
import { DetailMenu } from "@hipanel-core/shared/ui/components";

export default class View {
  readonly detailMenu: DetailMenu;

  constructor(readonly page: Page) {
    this.detailMenu = new DetailMenu(page);
  }

  async see(values: string[]): Promise<void> {
    for (const text of values) {
      await expect(this.page.getByText(text, { exact: false }).first()).toBeVisible();
    }
  }
}
