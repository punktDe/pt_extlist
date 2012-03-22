

Setting up export
pt_extlist offers several exporters for lists. You can select from a list of pre-defined export formats or implement your own exporters. Here is a step-by-step explanation on how to set up export:

Go to the page on which you have your list set up.

You have to include a static template for export settings on the page you want to export list data. Go to the Template module and modify the template of the page. Switch to the "Includes" tab of your template record and select "\[pt_extlist] Export settings (pt_extlist)". Save your template and switch to page module.

Inclusion of static template for export

Insert a new content element of type "General Plugin". Select "ExtList" as selected Plugin.

Switch to the "General Options" tab and select list identifier of the list you want to export. Select "Export" as Plugin Type.

Set plugin type to "Export"

Switch to the "Export Settings" tab and select list identifier of the list you want to export. Select Export Type and Download Type. Hint: If you cannot select an Export Type, you most likely forgot to include static template for export on the page you are currently working. See step 2!

Configuration for exporting a list as Excel sheet

Save your content element and switch to frontend view.

You will now see a download in your Frontend that enables you to download configured export document with your list data.

Why are there 2 list identifiers?
Almost everytime you want to export some data from your list, you also want to change the way the list looks like in your export. Therefore you can select a different list identifier for export than for your "normal" list. This way you can configure the changes for the exported list on the same page you have your "normal" list. In previous versions of pt_list, you had to create a special page with special TS-settings for your exported list. By chosing a second identifier for your exported list, this is no longer required!

Requirements for Excel export
There are special requirements for setting up Excel Export. You have to install the module PHPExcel which is available via a special PEAR channel. In order to install PHPExcel, refer to their website: http://phpexcel.codeplex.com/releases/view/45412
The current installation process looks like this:

Set up PEAR on your system.

Use the following command to make PEAR channel known to your PEAR:pear channel-discover pear.pearplex.net

Use the following command to install PHPExcel on your system:pear install pearplex/PHPExcel


