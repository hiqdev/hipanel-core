import { expect, Page } from "@playwright/test";
import * as fs from "fs";
import AdvancedSearch from "@hipanel-core/helper/AdvancedSearch";
import { Alert, BulkActions, ColumnFilters } from "@hipanel-core/shared/ui/components";
import {Download} from "@playwright/test";

export default class Index {
  advancedSearch: AdvancedSearch;
  columnFilters: ColumnFilters;
  bulkActions: BulkActions;
  alert: Alert;

  constructor(readonly page: Page) {
    this.advancedSearch = new AdvancedSearch(page);
    this.columnFilters = new ColumnFilters(page);
    this.alert = Alert.on(page);
    this.bulkActions = BulkActions.on(page);
  }

  async hasAdvancedSearchInputs(names: Array<string>) {
    await this.advancedSearch.hasInputsByNames(names);
  }

  async setFilter(name: string, value: string) {
    await this.advancedSearch.setFilter(name, value);
  }

  async submitSearchButton() {
    await this.advancedSearch.search();
  }

  async applyFilter(name: string, value: string) {
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

  async chooseNumberRowOnTable(number: number) {
    await this.page.locator("input[name=\"selection[]\"]").nth(number - 1).highlight();
    await this.page.locator("input[name=\"selection[]\"]").nth(number - 1).click();
  }

  async chooseRangeOfRowsOnTable(start: number, end: number) {
    for (let i = start; i <= end; i++) {
      await this.chooseNumberRowOnTable(i);
    }
  }

  async clickButton(name: string) {
    await this.page.locator(`button[type='submit']:has-text("${name}")`).click();
  }

  async clickBulkButton(name: string) {
    // use locator + filter with RegExp to match by substring (not exact)
    await this.page.locator("fieldset button").filter({ hasText: new RegExp(name) }).first().click();
  }

  async clickDropdownBulkButton(buttonName: string, selectName: string) {
    await this.clickBulkButton(buttonName);
    const button = this.page.locator(`fieldset a`).filter({ hasText: new RegExp(`${selectName}$`) });

    await button.highlight();
    await button.click();
  }

  async clickColumnOnTable(columnName: string, row: number, timeout: number = 50_000) {
    const column = await this.getColumnNumberByName(columnName);
    await this.page.locator(`//tr[${row}]//td[${column}]//a`).first().click({ timeout: timeout });
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

  /**
   * @deprecated use DetailPageMenu ui component instead
   */
  async clickProfileMenuOnViewPage(menuName: string) {
    await this.page.locator(`a:has-text("${menuName}")`).click();
  }

  async clickPopoverMenu(row: number, menuName: string) {
    await this.page.locator("tr td button").nth(row - 1).click();

    await Promise.all([
      this.page.waitForNavigation({ waitUntil: "domcontentloaded" }), // Wait for the page to start loading
      this.page.locator(`div[role="tooltip"] >> text=${menuName}`).click(),
    ]);
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
      expect(false, `column name "${columnName}" does not exist`).toBeTruthy();
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

  getParameterFromCurrentUrl(parameterName: string) {
    const currentUrl = new URL(this.page.url());
    const searchParams = currentUrl.searchParams;

    return searchParams.get(parameterName);
  }

  async checkFieldInTable(columnName: string, fieldName: string) {
    const rowNumber = await this.getRowNumberInColumnByValue(columnName, fieldName);
    const column = await this.getColumnNumberByName(columnName);
    await expect(this.page.locator(`//section[@class='content container-fluid']//tbody//tr[${rowNumber}]//td[${column}]`)).toHaveText(fieldName);
  }
  async hasNotification(message: string) {
    await this.alert.hasText(message);
  }
  async getRowDataKeyByNumber(rowNumber: number): Promise<string | null> {
    const selector = `//section[@class='content container-fluid']//tbody//tr[${rowNumber}]`;

    return await this.page.locator(selector).getAttribute("data-key");
  }
  public async testExport() {
    const linkNames = ["CSV", "TSV", "Excel XLSX", "Clipboard MD"];
    const exportButton = this.page.locator("#export-btn");

    await expect(exportButton).toBeVisible();

    await exportButton.click();

    for (const linkName of linkNames) {
      const linkLocator = this.page.locator(`a:has-text("${linkName}")`);

      await expect(linkLocator).toBeVisible();

      if (linkName === "Clipboard MD") {
        continue;
      }

      await this.checkDownloadByLinkName(linkName);

      await exportButton.click();
    }
  }

  private async checkDownloadByLinkName(linkName: string): Promise<void> {
    const download = await this.triggerDownloadByLinkName(linkName);
    const downloadPath = this.buildDownloadPath(download);

    await this.saveDownload(download, downloadPath);

    this.assertFileWasDownloaded(downloadPath);
    this.assertFileIsNotEmpty(downloadPath, download);
  }

  private async triggerDownloadByLinkName(linkName: string) {
    const link = this.page.locator(`a:has-text("${linkName}")`);
    await link.highlight();

    const [download] = await Promise.all([
      this.page.waitForEvent("download"),
      link.click(),
    ]);

    return download;
  }

  private buildDownloadPath(download: Download): string {
    return `runtime/${download.suggestedFilename()}`;
  }

  private async saveDownload(download: Download, filePath: string): Promise<void> {
    await download.saveAs(filePath);

    console.log("Temporary download path:", await download.path());
  }

  private assertFileWasDownloaded(filePath: string): void {
    const isFileDownloaded = fs.existsSync(filePath);

    expect(
      isFileDownloaded,
      `Expected downloaded file to exist at: ${filePath}`
    ).toBeTruthy();
  }

  private assertFileIsNotEmpty(filePath: string, download: Download): void {
    const stats = fs.statSync(filePath);

    console.log(`Saved file ${filePath}: ${stats.size} bytes`);

    expect(
      stats.size,
      `Downloaded file "${download.suggestedFilename()}" is empty`
    ).toBeGreaterThan(0);
  }
}
