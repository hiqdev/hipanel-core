import Select2 from "@hipanel-core/input/Select2";
import { Locator, Page } from "@playwright/test";

export default class FilterInput {
  constructor(private readonly page: Page, private readonly context: Locator) {
  }

  async setFilter(name: string, value: string) {
    const fieldLocator = this.getFilterInput(name);

    if (!(await fieldLocator.count())) {
      throw new Error(`Filter field not found for: ${name}`);
    }

    const tagName = await fieldLocator.evaluate((el) => el.tagName.toLowerCase());

    if (tagName === "select") {
      const isSelect2 = await fieldLocator.evaluate((el) => el.classList.contains("select2-hidden-accessible"));
      if (isSelect2) {
        const id = await fieldLocator.getAttribute("id");
        await Select2.field(this.page, "#" + id).setValue(value);
      } else {
        await fieldLocator.selectOption(value);
      }
    } else if (tagName === "input" || tagName === "textarea") {
      const type = await fieldLocator.first().getAttribute("type");

      if (type === "checkbox" || type === "radio") {
        await fieldLocator.setChecked(true);
      } else {
        await fieldLocator.fill(value);
      }
    } else {
      throw new Error(`Unsupported field type: ${tagName} for name="${name}"`);
    }

    await fieldLocator.blur();
  }

  getFilterInput(name: string): Locator {
    return this.context.locator(`[name*=Search\\[${name}\\]]`);
  }
}
