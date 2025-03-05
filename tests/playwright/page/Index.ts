import { expect, Page } from "@playwright/test";
import AdvancedSearch from "@hipanel-core/helper/AdvancedSearch";
import Notification from "@hipanel-core/helper/Notification";

export default class Index {
  public advancedSearch: AdvancedSearch;
  private notification: Notification;

  constructor(private page: Page) {
    this.advancedSearch = new AdvancedSearch(page);
    this.notification = new Notification(page);
  }

  public async hasAdvancedSearchInputs(names: Array<string>) {
    await this.advancedSearch.hasInputsByNames(names);
  }

  public async setFilter(name: string, value: string) {
    await this.advancedSearch.setFilter(name, value);
  }

  public async submitSearchButton()
  {
    await this.advancedSearch.submitButton();
  }

  public async applyFilter(name: string, value: string) {
    await this.advancedSearch.applyFilter(name, value);
  }

  async hasBulkButtons(names: Array<string>) {
    for (const name of names) {
      await expect(this.page.locator(`div.box-bulk-actions :has-text("${name}")`).first()).toBeVisible();
    }
  }

  async hasColumns(names: Array<string>) {
    for (const name of names) {
      await expect(this.page.locator(`[role=grid] th:has-text("${name}")`).first()).toBeVisible();
    }
  }

  async hasRowsOnTable(count: number) {
    await expect(this.page.locator("input[name=\"selection[]\"]")).toHaveCount(count);
  }

  async seeTextOnTable(columnName: string, row: number, text: string) {
    const column = await this.getColumnNumberByName(columnName);
    await expect(this.page.locator(`//tbody//tr[${row}]//td[${column}]`)).toHaveText(text);
  }

  public async chooseNumberRowOnTable(number: number) {
    await this.page.locator("input[name=\"selection[]\"]").nth(number - 1).highlight();
    await this.page.locator("input[name=\"selection[]\"]").nth(number - 1).click();
  }

  public async chooseRangeOfRowsOnTable(start: number, end: number) {
    for (let i = start; i <= end; i++) {
      await this.chooseNumberRowOnTable(i);
    }
  }

  async clickButton(name: string) {
    await this.page.locator(`button[type='submit']:has-text("${name}")`).click();
  }

  async clickBulkButton(name: string) {
    await this.page.locator(`fieldset button:has-text("${name}")`).click();
  }

  public async clickDropdownBulkButton(buttonName: string, selectName: string) {
    await this.clickBulkButton(buttonName);
    const button = this.page.locator(`fieldset a`)
        .filter({ hasText: new RegExp(`^${selectName}$`) });

    await button.highlight();
    await button.click();
  }

  async clickColumnOnTable(columnName: string, row: number) {
    const column = await this.getColumnNumberByName(columnName);
    await this.page.locator(`//tr[${row}]//td[${column}]//a`).first().click();
  }

  async clickColumnOnTableByName(columnName: string, value: string) {
    await this.page.pause();
    const column = await this.getColumnNumberByName(columnName);
    const row = await this.getRowNumberInColumnByValue(columnName, value);
    await this.page.locator(`//tr[${row}]//td[${column}]//a`).first().click();
  }

  async clickLinkOnTable(columnName: string, link: string) {
    const row = await this.getRowNumberInColumnByValue(columnName, link);
    await this.clickColumnOnTable(columnName, row);
  }

  async clickProfileMenuOnViewPage(menuName: string) {
    await this.page.locator(`a:has-text("${menuName}")`).click();
  }

  async clickPopoverMenu(row: number, menuName: string) {
    await this.page.locator('tr td button').nth(row - 1).click();
    await this.page.locator(`div[role="tooltip"] >> text=${menuName}`).click();
  }

  async getColumnNumberByName(columnName: string) {
    const allColumns = await this.page.locator("//th[not(./input)]").allInnerTexts();
    return this.getColumnNumber(allColumns, columnName);
  }

  private getColumnNumber(columns: Array<string>, columnName: string) {
    let columnNumber = 0;
    columns.forEach((column, index) => {
      if (columnName === column) {
        columnNumber = index + 2;
      }
    });
    if (columnNumber === 0) {
      expect(false, `column by name "${columnName}" does not exist`).toBeTruthy();
    }

    return columnNumber;
  }

  private getRowNumber(rows: Array<string>, value: string) {
    let rowNumber = 0;
    rows.forEach((rowValue, index) => {
      if (rowValue.includes(value)) {
        rowNumber = index + 1;
        return;
      }
    });
    if (rowNumber === 0) {
      expect(false, `column by name "${value}" does not exist`).toBeTruthy();
    }

    return rowNumber;
  }

  async getRowNumberInColumnByValue(columnName: string, value: string, withCheckBox: boolean = true) {
    let column = await this.getColumnNumberByName(columnName);
    column = withCheckBox ? column : column - 1;
    const allRows = await this.page.locator(`//section[@class='content container-fluid']//tbody//td[${column}]`).allInnerTexts();

    return this.getRowNumber(allRows, value);
  }

  async getValueInColumnByNumberRow(columnName: string, numberRow: number) {
    const column = await this.getColumnNumberByName(columnName);
    let value = await this.page.locator(`//section[@class='content container-fluid']//tbody//tr[${numberRow}]//td[${column}]`).innerText();

    return value.trim();
  }

  async getAuthenticatedUserId() {
    await this.page.goto("/site/healthcheck");

    return await this.page.locator("userid").innerText();
  }

  async getParameterFromCurrentUrl(parameterName) {
    const currentUrl = new URL(await this.page.url());
    const searchParams = currentUrl.searchParams;

    return searchParams.get(parameterName);
  }

  async checkFieldInTable(columnName, fieldName) {
    const rowNumber = await this.getRowNumberInColumnByValue(columnName, fieldName);
    const column = await this.getColumnNumberByName(columnName);
    expect(await this.page.locator(`//section[@class='content container-fluid']//tbody//tr[${rowNumber}]//td[${column}]`)).toHaveText(fieldName);
  }

  public async hasNotification(message: string)
  {
    await this.notification.hasNotification(message);
  }

  public async closeNotification()
  {
    await this.notification.closeNotification();
  }

  public async getRowDataKeyByNumber(rowNumber: number): Promise<string | null> {
    const selector = `//section[@class='content container-fluid']//tbody//tr[${rowNumber}]`;

    return await this.page.locator(selector).getAttribute("data-key");
  }
}
