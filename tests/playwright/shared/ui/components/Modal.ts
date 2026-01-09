import { Locator, Page, expect } from '@playwright/test';
import Input from "@hipanel-core/input/Input";

export default class Modal {
    private readonly root: Locator;

    constructor(private readonly page: Page) {
        this.root = this.activeModal(page);
    }

    private activeModal(page: Page): Locator {
        return page.locator('[role="dialog"]:visible');
    }

    async waitForOpen(timeout = 5000) {
        await expect(this.root).toBeVisible({ timeout });
    }

    async waitForClose() {
        await expect(this.root).toBeHidden();
    }

    async isOpen(): Promise<boolean> {
        return await this.root.isVisible();
    }

    async isClosed(): Promise<boolean> {
        return !(await this.isOpen());
    }

    async clickButton(buttonText: string) {
        const button = this.root.getByRole('button', { name: buttonText });
        await button.click();

        await this.waitForClose();
    }

    async closeIfOpen() {
        if (await this.isOpen()) {
            // Optional: generic close logic
            await this.root.getByRole('button', { name: /close|cancel/i }).click();
            await this.waitForClose();
        }
    }

    async fillField(name: string, value: string) {
        await Input.field(this.page, `input[name="${name}"]`).fill(value);
    }
}
