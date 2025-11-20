import { Page } from "@playwright/test";
import { DetailMenu } from "@hipanel-core/shared/ui/components";
import BasePage from "./BasePage";

export default class View extends BasePage {
  readonly detailMenu: DetailMenu;

  constructor(readonly page: Page) {
    super(page);
    this.detailMenu = new DetailMenu(page);
  }
}
