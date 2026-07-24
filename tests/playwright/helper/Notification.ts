import { expect, Locator, Page } from "@playwright/test";

export default class Notification {
    public root: Locator;

    constructor(private readonly page: Page) {}

    public async hasNotification(message: string) {
        const notification = this.notification();
        const successMessage = notification.locator('.alert', { hasText: message });

        await expect(successMessage).toBeVisible();
    }

    notification(): Locator {
        return this.page.locator('.ui-pnotify');
    }

    public async closeNotification() {
        const notification = this.notification();
        if (await notification.isVisible()) {
            await notification.hover();

            const closeButton = notification.locator('.ui-pnotify-closer');

            if (await closeButton.isVisible()) {
                await closeButton.click();
            }
        }
    }
}
