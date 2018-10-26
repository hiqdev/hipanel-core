# Additional Classes for testing HiPanel with Codecept

All additional classes are divided by directories where dir-name is short name of class functionality. All methods aggregating in one file _\_support/\_generated/AcceptanceTesterActoions.php_

## \_support/Helper

* **_class CredentialsProvider_** helps to authorize different roles.

* **_class PnotifyHelper_** helps to close the modal window after the event.

* **_class PressButtonHelper_** helps to press the buttons by the value of the text in the button.

* **_class WaitHelper_** helps to check if the Ajax requests have ended.


## \_support/Page

* **_class Authenticated_** helps to log in and go to the tested page.

* **_class Login_** helps to log in.

* **_class IndexPage extends Authenticated_** helps to check the presence of all blocks (legend, filters, columns) on the index page of the module, check the operation of filters, select a row of a table by ID or number, check the drop-down row-menu in the table.

* **_class class SidebarMenu extends Authenticated_** helps to check the presence of menu items in the sidebar.


## \_support/Page/Widget/Input

* **_abstract class TestableInput_** is ...
* **_class Dropdown extends TestableInput_** is ...
* **_class Input extends TestableInput_** is ...
* **_class Select2 extends TestableInput_** is ...
* **_class Textarea extends TestableInput_** is ...


## \_support/Step

* **_class AcceptanceTester extends \root\AcceptanceTester_** is ...


## \_support/Step/Acceptance

* **_class Client extends AcceptanceTester_** is ...
* **_class Admin extends Client** is ...
* **_class Manager extends Client** is ...
* **_class Seller extends Client** is ...
