import { Page } from "@playwright/test";
import InputWithCurrency from "@hipanel-core/input/InputWithCurrency";

export default class SumWithCurrency extends InputWithCurrency{
  static field(page: Page, formId: string, k: number): SumWithCurrency {
    return new SumWithCurrency(page, `${formId}-${k}-sum`);
  }
}
