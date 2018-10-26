# Additional Classes for testing HiPanel with Codecept

All additional classes are divided by directories where dir-name is short name of class functionality. All methods aggregating in one file _\_support/\_generated/AcceptanceTesterActoions.php_

## \_support/Helper

- [CredentialsProvider] helps to authorize different roles.
- [PnotifyHelper] helps to close the modal window after the event.
- [PressButtonHelper] helps to press the buttons by the value of the text in the button.
- [WaitHelper] helps to check if the Ajax requests have ended.

[CredentialsProvider]: _support/Helper/CredentialsProvider.php
[PnotifyHelper]: _support/Helper/PnotifyHelper.php
[PressButtonHelper]: _support/Helper/PressButtonHelper.php
[WaitHelper]: _support/Helper/WaitHelper.php

## \_support/Page
- [Authenticated] helps to log in and go to the tested page.
    - [IndexPage] helps to check the presence of all blocks (legend, filters, columns) on the index page of the module, check the operation of filters, select a row of a table by ID or number, check the drop-down row-menu in the table.
    - [SidebarMenu] helps to check the presence of menu items in the sidebar.
- [Login] helps to log in.

[Authenticated]: _support/Page/Authenticated.php
[IndexPage]: _support/Page/IndexPage.php
[SidebarMenu]: _support/Page/SidebarMenu.php
[Login]: _support/Page/Login.php

## \_support/Page/Widget/Input

- [TestableInput] is basic class for input element. The elements can be created in three different ways: free input element, as Advanced search element, as Table filter element of index page.
    - [Dropdown] helps to check visible dropdown, set value, etc.
    - [Input] helps to search or filter by selector and set value in input.
    - [Select2] helps to test dropdown select with query, search or filter by selector, set value in select, etc.
    - [Textarea] search or filter by selector and set value in textarea.

[TestableInput]: _support/Page/Widget/Input/TestableInput.php
[Dropdown]: _support/Page/Widget/Input/Dropdown.php
[Input]: _support/Page/Widget/Input/Input.php
[Select2]: _support/Page/Widget/Input/Select2.php
[Textarea]: _support/Page/Widget/Input/Textarea.php


## \_support/Step

-[AcceptanceTester] helps to login and init creditionals.

[AcceptanceTester]: _support/Step/AcceptanceTester.php

## \_support/Step/Acceptance

- [Client] helps to login Client Role and init creditionals.
    - [Admin] helps to login Admin Role and init creditionals.
    - [Manager] helps to login Manager Role and init creditionals.
    - [Seller] helps to login Seller Role and init creditionals.

[Client]: _support/Step/Acceptance/Client.php
[Admin]: _support/Step/Acceptance/Client.php
[Manager]: _support/Step/Acceptance/Client.php
[Seller]: _support/Step/Acceptance/Client.php
