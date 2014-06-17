
***************
Architecture
***************

Who should read this?
=====================
If you are just interested in how pt_extlist works and want to see how things are put together, the following chapter could be a nice thing to read. If you are a developer and want to extend pt_extlist or want to use it as an API inside your extension, it's necessary to read this!

Overview
========
Take a look at the following figure to get a first impression of pt_extlist's architecture:

.. figure:: Images/architecture_01.png

Basic Architecture of pt_extlist


The red elements stand for data-related stuff. DataSource gets our data from whatever source we use. Sources can be TYPO3 databases as well as arbitrary MySQL databases and Extbase repositories.
The green elements are configuration-specific which means that they pull our configuration from TypoScript or Flexforms and do various merging on them to generate some nice-to-handle objects that are used for all configuration inside pt_extlist.
The blue elements are what we would like to call our Domain Models as they handle things like lists, filters, pagers etc. which is the core domain of our extension.
Finally the yellow elements stand for all objects that handle the rendering of our list data. As we will see, this can get quite complex.
Our extension architecture is surrounded by some out-of-the box framework stuff provided mainly by Extbase and TYPO3.

Basic Architecture
==================

Modell-View-Controller (MVC)
============================

Independent Components
======================

pt_extlist Lifecycle
====================

Configuration
=============
Regarding our extension in a bottom-up fashion, we can easily start with configuration as its the basic thing to have before we can set up anything else. As you might have suggested, most of our configuration is using in TypoScript. Besides there are some settings coming from Flexform or surrounding extensions. We covered this problem inside pt_extlist using what we call a configurationBuilder. Roughly speaking, it is an object that handles the creation of all configuration stuff we need. As we did not like fiddling around with arrays, we started to implement so called configuration objects for all the configuration that we needed. The main ascpects of those objects is to make sure that the required settings are there and correct. Configuration objects are passed to all other objects that require some kind of configuration.
Take a look at this diagram to get an idea of how everything works together:

.. figure:: Images/architecture_02.png

Configuration Builder scheme


So what's the basic idea of our so-called configuration objects?

1. TypoScript is a mighty tool for setting up configuration stuff. Unfortunatly there is no way to check for existence and correctness of settings. Our configuration objects jump in the gap here and present a single point of checking configuration.

2. Whenever you need a configuration set in TypoScript in your code, you have to check whether it is set or not. This probably has to be done several times and has to be changed several times whenever your configuration needs to be changed. Configuration objects enable you to keep your TS and your program logic synchronized by only changing code in one single class.

3. Configurations objects provide you with an object-oriented style of accessing configuration settings. You no longer have to fiddle around with arrays when you want to access configuration, now there are Getters and Setters and things like code-completion.

Before we had what we call the configuration builder, objects had to handle their configuration by themselves which made it necessary to implement huge init()-methods for setting up all configuration stuff. Whenever an object was not a singleton, the configuration stuff had to be run time and time again for every instantiation of an object.
As the configuration builder is a extension-wide singleton class which caches configuration objects once they are created, we now can simply grab a configuration from it, every time we need it and do not have to cope with checking the configuratin within our domain objects which makes them a lot simplier to implement and understand.

Handling State
==============
Since Extbase has been around, handling with persistence of domain objects isn't that a big problem anymore. We use our repositories for finding, updating and deleting objects and that's it. But what about session persistence. Let's say, a user filters a list with some complicated criterias, selects one single record of it to look at and then comes back to his filtered list. Shouldn't it show the state it had before he opened the single record?
Such functionality is handled in pt_extlist with what we called a "Session-Persistence-Manager". Roughly speaking it is a container to which you can register objects that get a portion of data from the session when the object is created and can store data back to the session when the lifecycle ends.
Here is a sketch of how session persistence is working within pt_extlist:

.. figure:: Images/architecture_03.png

Session Persistence in pt_extlist

Please mind, that an object creates its own namespace within the session using the IdentifiableInterface. As we cannot automatically create a namespace for an object this method is implemented individually in each object that wants to use session persistence.
Another place where objects can gather state from are GET and POST parameters. Coming back to our filter example from above, a user could change a previously session-persisted filter so the data coming from the POST vars should overwrite the session settings.
Therefore, we introduced a GET/POST-Var adapter that works somehow similar as the session persistence manager, of course without being able to write back data to it as this would not make any sense.

.. figure:: Images/architecture_04.png

GET/POST Var Adapter

The GET/POST-Var adapter also handles the extension and instance namespace. Think about the following situation: you have two instances of pt_extlist plugin on the same page. Each instance comes with its own filters, pagers etc. and the should not influence each other. As an example think about a shopping page, where you have a list of articles on the left and a overview of your shopping cart as a second list on the right.
What the GP-Var adapter does is adding the list identifier (e.g. 'articleList' and 'shoppingCartList') to the namespace of the objects to differentiate between the two instances of the plugin.

The Data Backend
================
You can talk about the Data Backend as the heart of the pt_extlist extension. Somehow almost everything comes together here. Most of the pt_extlist components like filters, lists, pagers and breadcrumbs etc. influence the data backend or are themselves influenced or created by the data backend.
Besides being the "glue" holding all components of the extension together, the data backend has the taks of communicating with the "outer-world". This means, that whatever data is displayed within the extension is gathered by the backend from whatever datasource it is working on. The data backend also knows how to translate generic queries and constraints created from the components before they are passed to the data source.
###TODO### insert diagram for data backend

The Query Object
================
Former implementations of pt_extlist where bound to SQL-databases as a databackend. All queries created were SQL queries so you could directly create SQL snippets within all classes that manipulated the query. E.g. a filter class created some WHERE-clauses, a pager created a LIMIT-clause and a column header created an ORDER BY-clause for sorting.
One big change that comes with pt_extlist was being able to use whatever data source you like as a data backend. We therefore hat to generalize all queries that are created within our classes.
For this purpose, we introduced a so-called query object that can handle common SQL-query like functionality in an object oriented manner. Queries can take constraints (WHERE-clauses), limitations (LIMIT-clausses), sortings (ORDER BY-clauses) and some other stuff. But it's kept in a form that enables us to transform the query to whatever backend we want to use. At the moment there is a TYPO3 backend, using the actual TYPO3-database as a datasource and an Extbase backend that uses Repositories as data sources.
In order to send a query to the corresponding datasource, we have to translate it to a language that can be handled by the backend. We therefore introduced interpreters. Compared to the Extbase query object, those interpreters are independent of the query object itself, so that you can implement your own interpreters for whatever backend you want to support (e.g. XML via XPath or XQuery, SOAP, REST...).

.. figure:: Images/architecture_05.png

Query Object

The translation is handled by Interpreters shipping with a data-backend. Not every data-backend requires its own Interpreter, for example the MySQL backend and the TYPO3 backend share a common Interpreter as they both use SQL.

.. figure:: Images/architecture_06.png

Data Sources
============

Data Mappers
============

The domain model
================

The List object
===============

Filters
=======

Pager
=====

BreadCrumbs
===========

Bookmarks
=========

List Rendering
==============

The Renderer Chain
==================