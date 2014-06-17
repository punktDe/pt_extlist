********
Examples
********

In this section, some examples will be provided to describe the functionality of pt_extlist. Before you can start, make sure, that pt_extlist is installed and loaded using Extension Manager.

Setting up a demo list based on static_countries table
======================================================

In this example, you will learn how to create a list by using a TYPO3 table as data source. We will use static_countries table as it is available on all TYPO3 installations.
We will set up a page showing filters, list and pager for static countries.

1. Create a new page inside your page tree and open the template module. Open Template module and create new extension template:


.. figure:: Images/examples_1_1_01.png
	:scale: 50%

Create new extension template ###TODO### insert 1,2,3 for showing what to do in image

2. Give your extension template a proper name:

.. figure:: Images/examples_1_1_02.png
	:scale: 50%

Give your extension template a proper name

3. Switch to the "Includes" Tab and select the following templates:

.. figure:: Images/examples_1_1_03.png
	:scale: 50%

Select Basic settings and demolist package as static templates

4. Save your template and switch to the page module.

5. Select the page you just created and insert a new content element of type "plugin":

.. figure:: Images/examples_1_1_04.png
	:scale: 50%


Insert plugin as content element

Select ExtList from the page content's "Selected Plugin" list:

Select ExtList as content type

In the flexform for ExtList select "demolist"

Select "demolist" as list identifier

As plugin type select "Filterbox":

Select "Filterbox" as Plugin Type

Switch to the "Filterbox settings" Tab and input "filterbox1" as Filterbox Identifier:

Setting the filterbox identifier

Save your content element and create another one just below. Select "Plugin" as content type and "ExtList" as plugin type just as you did before. Again select "demolist" as list identifier (steps 5 - 7 above), but this time select "list" as plugin type:

Select "List" as Plugin Type

Save and create a third content element. Repeat steps 5 - 7 from above, the select "demolist" as List Identifier and select "Pager" as Plugin Type:

Select "Pager" as Plugin Type

Save content element and take a look at the page in the Frontend. Depending on your CSS Styles, it should look somehow like that:

Frontend view of ExtList widgets

Now let's do a little more advanced stuff and change the number of records shown per page. Therefore switch to the Template module and select the page where you added the content elements above. Write the following line of code into your setup field:
plugin.tx_ptextlist.settings.listConfig.demolist.pager.itemsPerPage = 4
Now reload your page in the Frontend and look what's happening - there should be only 4 records per page anymore:

List after changing items per page

So that's it - you just set up your first list! Feel free to test the other sample configurations shipping with pt_extlist to see some more features.