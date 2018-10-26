# Additional Classes for testing HiPanel with Codecept

All additional classes are divided by directories where dir-name is short name of class functionality. All methods aggregating in one file _\_support/\_generated/AcceptanceTesterActoions.php_

## \_support/Helper

- [CredentialsProvider] helps to authorize different roles.
- [PnotifyHelper] helps to close the modal window after the event.
- [PressButtonHelper] helps to press the buttons by the value of the text in the button.
- [WaitHelper] helps to check if the Ajax requests have ended.

[CredentialsProvider]: _support/Helper/CredentialsProvider.php

## \_support/Page

* **_class Authenticated_** helps to log in and go to the tested page.

* **_class Login_** helps to log in.

* **_class IndexPage extends Authenticated_** helps to check the presence of all blocks (legend, filters, columns) on the index page of the module, check the operation of filters, select a row of a table by ID or number, check the drop-down row-menu in the table.

* **_class class SidebarMenu extends Authenticated_** helps to check the presence of menu items in the sidebar.


## \_support/Page/Widget/Input

- [TestableInput] is basic class for input element. The elements can be created in three different ways: free input element, as Advanced search element, as Table filter element of index page.
    - [Dropdown] helps to check visible dropdown, set value, etc.
    - [Input] helps to search or filter by selector and set value in input.
    - [Select2] helps to test dropdown select with query, search or filter by selector, set value in select, etc.
    - [Textarea] search or filter by selector and set value in textarea.

## \_support/Step

* **_class AcceptanceTester extends \root\AcceptanceTester_** helps to login and init creditionals.


## \_support/Step/Acceptance

* **_class Client extends AcceptanceTester_** helps to login Client Role and init creditionals.
* **_class Admin extends Client** helps to login Admin Role and init creditionals.
* **_class Manager extends Client** helps to login Manager Role and init creditionals.
* **_class Seller extends Client** helps to login Seller Role and init creditionals.
