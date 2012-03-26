
Pt_extlist can be extended in multiple ways. Many of its classes are configured via TypoScript so you can easily exchange them with your own classes to fit your needs. Common types of extensions are changing Data-Backends or writing your own filter classes. We will start with the latter one.

Writing your own filter classes
Recapitulating what has been told about filters in the Architecture chapter, we reintroduce the following class diagram to understand what filters are actually doing:

Filter Interface

By taking a look at the Interface for filters, you see that there are mainly three main purposes:

Configuration and State-related stuff

Returning a filter query that determines what the filter is actually filtering on the data

Creating a filter breadcrumb information

Keeping in mind that there are some helpers - namely abstract classes - that do a lot of work for us we do not have to implement much logic when creating a new filter class:

Abstract Filter Classes

So as you can see  - all that's left for you to implement in your concrete filter class is a method that creates the actual filter criteria.

String Filter Example
One of the most simple filters shipping with pt_extlist is the String filter. It can filter a string value based on a user input which is also a string. You can find the String-Filter class in the Classes/Domain/Model/Filter/StringFilter.php file.
Here is the PHP source code:
class Tx_PtExtlist_Domain_Model_Filter_StringFilter extends Tx_PtExtlist_Domain_Model_Filter_AbstractSingleValueFilter {

/\**
* Creates filter query from filter value and settings
*
* @return Tx_PtExtlist_Domain_QueryObject_Criteria Criteria for current filter value (null, if empty)
\*/
protected function buildFilterCriteria(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldIdentifier) {

if ($this->filterValue == '') {
return NULL;
}

$fieldName = Tx_PtExtlist_Utility_DbUtils::getSelectPartByFieldConfig($fieldIdentifier);
$filterValue = '%'.$this->filterValue.'%';

$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::like($fieldName, $filterValue);

return $criteria;
}
}
The most important function is buildFilterCriteria() where the filter creates a constraint on how the data filtered by this filter should look like. We use our generic query criteria Tx_PtExtlist_Domain_QueryObject_SimpleCriteria with an operator like here to implement a string filter that uses a LIKE-comparison in its built criteria. Tx_PtExtlist_Domain_QueryObject_Criteria::like($fieldName, $filterValue) is nothing more but a factory method that returns a criteria object.
As we mentioned above, a lot of functionality is given to us by our abstract classes, so to get some more information about what the String-Filter does and how it is configured, ake a look at its TypoScript prototype located in Configuration/TypoScript/BaseConfig/Prototype/Filter.txt:
string {
filterClassName = Tx_PtExtlist_Domain_Model_Filter_StringFilter
partialPath = Filter/String/StringFilter
defaultValue =
accessGroups =

breadCrumbString = TEXT
breadCrumbString {
# Fields that can be used are "label" and "value"
dataWrap = {field:label} equals {field:value}
}
}
You find a lot more configuration possibilities here than you would assume after looking at the filter class above. First of all, there is a filterClassName, that determines which filter class to instantiate in order to create a string filter object. The partial path leeds us to the HTML template that is used for the filter's user interface. defaultValue lets us set a predefined value when the filter is shown for the first time and accessGroups restricts the filter to certain fe_groups that are allowed to see the filter.
breadCrumbString enables us to create a TS template for rendering the breadcrumb text of the filter.
The last thing we have to know, when we want to implement our own filter class is how to actually configure them within our list configuration. Therefore you should take a look at one of the demolists' filterbox configurations. There we find something like this:
filters {
filterbox1 {
filterConfigs {
10 < plugin.tx_ptextlist.prototype.filter.string
10 {
filterIdentifier = filter1
label = LLL:EXT:pt_extlist/Configuration/TypoScript/Demolist/locallang.xml:filter_nameField
fieldIdentifier = name_local
}
}
}
}
All the filters of a list configuration are configured in the filters section of your configuration. Within this section you have to set up a arbitrary key for the name of your filterbox. In the example above, this is filterbox1. For each filterbox, you have to set up a list of filters within filterConfigs and in there we finally have our String-Filter. The basic settings are copied from the prototype above, then we have to change the settings that are unique for our usasge of the filter like filterIdentifier, label and the fieldIdentifier we want to let our filter operate on.

Extending the RenderChain

RenderChain

Using extlist in the TYPO3 backend

Extbase enables you to write backend modules the same easy way as you do in the frontend.
The main difference however is that in the frontend you can have multiple plugins with controller / action pair fired at each rendering, whereas in the backend you can only call one controller / action at a time.
As pt_extlist in the frontend by default uses one plugin each for filter, list and pager, we have to use the extension in the backend in a different way to cope with the one controller/action restriction.

Use the extlistContext

Derive from the Tx_PtExtlist_Controller_AbstractBackendListController


