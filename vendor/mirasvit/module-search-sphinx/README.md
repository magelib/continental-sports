## 1.1.13
*(2017-08-11)*

#### Fixed
* Issue with category pages

---

## 1.1.12
*(2017-08-10)*

#### Fixed
* Support 'not-words' with sphinx search engine

---

## 1.1.11
*(2017-07-28)*

#### Fixed
* Issue with category pages

---

## 1.1.10
*(2017-07-21)*

#### Improvements
* Option to enable/disable sphinx daemon auto start

---

## 1.1.9
*(2017-06-29)*

#### Fixed
* Kb provider

---

## 1.1.8
*(2017-06-26)*

#### Fixed
* Issue with weight

---

## 1.1.7
*(2017-06-20)*

#### Fixed
* Issue with relevance

---

## 1.1.6
*(2017-05-19)*

#### Fixed
* Issue with infix len
* Issue with one char search

---

## 1.1.4
*(2017-05-05)*

#### Improvements
* Sphinx manage CLI

---

## 1.1.3
*(2017-04-14)*

#### Fixed
* Suggestions data provider

---

## 1.1.2
*(2017-04-13)*

#### Fixed
* Issues with indexation

---

## 1.1.1
*(2017-04-04)*

#### Fixed
* Fixed an issue with requirements

---

## 1.1.0
*(2017-04-04)*

#### Improvements
* Split modules

---

## 1.0.60
*(2017-02-06)*

#### Fixed
* Fixed an issue with default sort direction

---

## 1.0.59
*(2017-02-06)*

#### Fixed
* Fixed a set of issue related with data serialization
* Issue with feature "push out of stock products"

---

## 1.0.57
*(2017-01-24)*

#### Bugfixes
* Fixed singularize issue in Dutch language (affects all)
* Fixed an issue with catalog attribute index

---

## 1.0.56
*(2017-01-20)*

#### Improvements
* Increased number of sphinx client max_children

---

## 1.0.55
*(2017-01-20)*

#### Improvements
* Added new search index: Catalog Attributes

---

## 1.0.54
*(2017-01-13)*

#### Fixed
* Fixed an issue with store based configuration

---

## 1.0.53
*(2017-01-12)*

#### Improvements
* Added search index for Ves Brands
* Added search index for Ves Blog
* Backend interface

---

## 1.0.52
*(2016-12-23)*

#### Fixed
* Fixed an issue with out of stock products

---

## 1.0.51
*(2016-12-21)*

#### Fixed
* Fixed an issue with new block

#### Documentation
* updated docs

---

## 1.0.50
*(2016-12-16)*

#### Features
* Smart "No Results" page

---

## 1.0.49
*(2016-12-01)*

#### Improvements
* Improved stemming feature (stemming based on current store locale)
## 1.0.48
*(2016-11-30)*

#### Improvements
* Custom search weight for products

---

## 1.0.47
*(2016-11-23)*

#### Fixed
* Fixed an issue with terms highlighter

---

## 1.0.46
*(2016-11-21)*

#### Improvements
* Fixed possible issue with swatches

---

## 1.0.45
*(2016-11-21)*

#### Improvements
* Compatibility with M 2.2.0

---

## 1.0.44
*(2016-11-17)*

#### Fixed
* Fixed an issue with search terms highlighting (char &)
* Issue with compare option on search results page

---

## 1.0.43
*(2016-10-31)*

#### Fixed
* Fixed an issue with one char wildcard
* Fixed an issue with terms highlighter
* Fixed an issue with number in attribute code
#### Features
* Added ability to generate sphinx configuration file for another sphinx server

---

## 1.0.40
*(2016-10-12)*

#### Fixed
* Fixed an issue with memory limits during indexation
* Fixed an issue with built-in search by very large description

---

## 1.0.38
*(2016-10-10)*

#### Fixed
* Fixed an issue with indexes translations

---

## 1.0.37
*(2016-10-04)*

---

## 1.0.36
*(2016-09-27)*

#### Improvements
* Ability to set custom search weight for products

---

## 1.0.34
*(2016-09-07)*

#### Improvements
* Prepare cms block during categories reindex

#### Fixed
* Fixed an issue with multistore results + added redirect via native store switcher controller

---

## 1.0.32
*(2016-08-19)*

#### Improvements
* Ability to search by blocks content in cms pages

#### Fixed
* Fixed an sphinx issue related with attributes

---

## 1.0.31
*(2016-08-09)*

#### Fixed
* Fixed an issue with sphinx index attributes

---
## 1.0.30
*(2016-08-06)*

#### Fixed
* Fixed an issue with category index (multi-store configuration)

---

## 1.0.28
*(2016-07-07)*

#### Improvements
* Added pager to wordpress blog search results

#### Fixed
* Fixed an issue related with creating temporary table on external database (external wordpress blog)

---

## 1.0.27
*(2016-07-06)*

#### Fixed
* Fixed an issue with displaying inline blocks, when search by cms pages
* Search sphinx with 2.1
* Fixed an issue with multi-store configuration

---
## 1.0.26
*(2016-06-29)*

#### Fixed
* Fixed an issue with applying results limit on category page

---

## 1.0.25
*(2016-06-29)*

#### Improvements
* Added additional exceptions for 404 to redirect

---

## 1.0.24
*(2016-06-24)*

#### Fixed
* Compatibility with Magento 2.1
* Fixed an issue with "Enable redirect from 404 to search results"

---
## 1.0.23
*(2016-06-14)*

#### Features
* Ability to reset sphinx daemon

---

## 1.0.22
*(2016-06-08)*

#### Fixed
* Fixed an issue with multistore results

---

## 1.0.21
*(2016-06-07)*

#### Improvements
* Added ability to search by Magefan Blog module

---

## 1.0.20
*(2016-05-24)*

#### Improvements
* Added special chars to sphinx configuration charset table

---

## 1.0.19
*(2016-05-19)*

#### Improvements
* Moved SphinxQL lib to module

#### Fixed
* Fixed an issue with synonyms

---

## 1.0.18
*(2016-05-17)*

#### Improvements
* Added additional file extension exceptions to 404 observer

#### Fixed
* Fixed an issue with min_word_len (search with dashes 0-1)

---

## 1.0.17
*(2016-05-16)*

#### Bugfixes
* SSU2-13 - Fix issue with synonyms

---

## 1.0.15, 1.0.16
*(2016-05-12)*

#### Improvements
* Improved performance of query builder

#### Fixed
* Fixed an sphinx query error after adding new attribute

---

## 1.0.14
*(2016-04-26)*

#### Fixed
* Fixed an issue with cronjobs

---

## 1.0.13
*(2016-04-20)*

#### Improvements
* Added console command for reindex search indexes

#### Fixed
* Fixed an issue with search by child product SKU
* Fixed css issue with active search tab, when HTML minification is enabled
* Fixed an issue with menu
* Fixed an issue with score builder for mysql engine

---

## 1.0.12
*(2016-04-07)*

#### Fixed
* Fixed an issue with area code (cli mode)
* Fixed an javascript error when html minification is enabled
* Fixed an issue with plural queries

---

## 1.0.11
*(2016-03-25)*

#### Improvements
* Integrated Mirasvit Knowledge Base

---

## 1.0.10
*(2016-03-17)*

#### Improvements
* Default index configuration
* Ability to search products only in active categories

#### Fixed
* Fixed possible issue with score sql query
* Fixed an issue with results limit

#### Documentation
* Description for Search only by active categories
* Updated installation steps

---

## 1.0.9
*(2016-03-09)*

#### Improvements
* Default index configuration
* Improved feature 404 to search
* Console commands for import/remove synonyms/stopwords
* Added default lemmatizer for EN, DE
* Improved sphinx configuration file
* Fallback engine for sphinx
* SSU2-9 -- Search by Mirasvit Blog MX
* i18n

#### Documentation
* Updated installation steps
* Information about synonyms and stopwords

#### Fixed
* Fixed an issue with stopwords import controller
* Added Symfony/Yaml to required packages
* Fixed an issue with importing synonyms and stopwords
* Fixed an issue with product list toolbar
* Fixed compatibility issue with Manadev_LayeredNavigation
* SSU2-8 -- mysql2 not found, when save product

---

## 1.0.8
*(2016-02-24)*

#### Fixed
* Fixed an issue with segmentation fault (PHP7) during reindex

---

## 1.0.7
*(2016-02-15)*

#### Fixed
* Fixed an issue with special chars in sphinx query (@)
* Fixed an issue with "Default Category" in search results for category search index
* Updated MCore version
* Formatting
* Fixed an issue with number of products at category pages (limit, offset)

---

## 1.0.6
*(2016-02-02)*

#### Bugfixes
* Fixed an issue with NOT cacheable block "search.google.sitelinks"
* Fixed an issue with upgrade script (synonyms and stopwords)
* SSU2-3 -- Fixed an issue with sh output in console (sh: searchd: command not found)

---

## 1.0.5
*(2016-01-31)*

#### Features
* SSU2-1 - Multi-store search results

#### Bugfixes
* Itegration tests

---

------
# Submodule mirasvit/module-search

## 1.0.41
*(2017-08-08)*

#### Fixed
* Issue with slow js rendering (backend)

---
## 1.0.40
*(2017-08-04)*

#### Fixed
* Ability to sort products by stock status

---
## 1.0.39
*(2017-07-28)*

#### Fixed
* Synonyms

---

## 1.0.37
*(2017-07-21)*

#### Fixed
* Responsive styles for indexes
* Convert synonyms/stopwords to lowercase before save
* Issue with blog indexation

---
## 1.0.36
*(2017-06-30)*

#### Fixed
* Issue with local Synonyms/Stopword dicitonary

---
## 1.0.35
*(2017-06-27)*

#### Improvements
* Added additional params to build urls for wordpress blog

#### Fixed
* Issue with weights

---
## 1.0.34
*(2017-06-22)*

#### Fixed
* Issue with index invalidation

---

## 1.0.33
*(2017-06-21)*

#### Improvements
* Added option to force sort order for products

---

## 1.0.32
*(2017-06-19)*

#### Fixed
* Bundled Products (EE)
* Issue with synonyms

---

## 1.0.31
*(2017-06-19)*

#### Fixed
* Issue with mass delete

---

## 1.0.30
*(2017-06-16)*

#### Fixed
* Attribute
* Issue with attribute synchronization
* Issue with updating index status after change properties/attributes

---

## 1.0.27
*(2017-06-07)*

#### Improvements
* Media types for 404 to search

#### Fixed
* Installation script

---

## 1.0.26
*(2017-06-07)*

#### Improvements
* Backend UI

#### Fixed
* EE bundled

---

## 1.0.25
*(2017-05-29)*

#### Fixed
* CLI

---

## 1.0.24
*(2017-05-24)*

#### Fixed
* Issue with Replace Words

---

## 1.0.23
*(2017-05-24)*

#### Fixed
* Changed "Indices" to "Indexes"

---

## 1.0.22
*(2017-05-23)*

#### Fixed
* Issue with local synonyms/stopwords files

---

## 1.0.21
*(2017-05-18)*

#### Improvements
* Long tail hint

#### Fixed
* Issue with search_weight attribute
* Fixed an issue with custom search weight

---

## 1.0.20
*(2017-05-04)*

#### Improvements
* Reindex visualization

#### Fixed
* Issue with engine status checker

---

## 1.0.19
*(2017-04-26)*

#### Improvements
* New search index for Mageplaza blog

#### Fixed
* Issue with properties saving

---

## 1.0.18
*(2017-04-18)*

#### Fixed
* Fixed an issue with cms page reindex

---

## 1.0.17
*(2017-04-18)*

#### Fixed
* Fixed an issue with custom weights

---

## 1.0.16
*(2017-04-13)*

#### Fixed
* Fixed an issue with EngineResolver path

---

## 1.0.15
*(2017-04-12)*

#### Fixed
* Fixed an issue with EngineResolver path

---

## 1.0.14
*(2017-04-10)*

#### Fixed
* Issue with EE reindex
* Fixed an issue with autocomplete provider

---

## 1.0.13
*(2017-04-07)*

#### Fixed
* Fixed an error with index "Attribute"

---

## 1.0.12
*(2017-04-06)*

#### Fixed
* Issue with installation script

---

## 1.0.11
*(2017-04-06)*

#### Fixed
* Fixed an issue with saving index properties

---

## 1.0.10
*(2017-04-06)*

#### Improvements
* Added prefix for search indices tables

---

## 1.0.9
*(2017-04-05)*

#### Fixed
* Fixed an issue with clear installation

---

## 1.0.8
*(2017-04-05)*

#### Improvements
* Changed locale resolver interface for stemming

#### Fixed
* Fixed an issue with autocomplete provider

---

## 1.0.7
*(2017-04-04)*

#### Fixed
* Issue with autocomplete
* Fixed an issue with importing stopwords

---

## 1.0.6
*(2017-04-04)*

#### Fixed
* Minor fixes

---

## 1.0.5
*(2017-03-31)*

#### Fixed
* Issue with installation

---

## 1.0.4
*(2017-03-31)*

#### Fixed
* Fixed an issue with generators

---

## 1.0.3
*(2017-03-09)*

#### Fixed
* Fixed an issue with compilation
* Minor naming problem

---

## 1.0.2
*(2017-03-06)*

#### Improvements
* Improved synonyms import interface

#### Fixed
* Fixed an issue with synonyms

---

## 1.0.1
*(2017-03-03)*

#### Improvements
* Performance

#### Fixed
* Fixed an issue with indexation

---

## 1.0.0
*(2017-02-17)*

#### Improvements
* Cloud service for synonyms/stopwords
* Initial release after split mirasvit/module-search-sphinx

#### Fixed
* Fixed an issue with filter by out of stock products

------
# Submodule mirasvit/module-search-mysql
## 1.0.3
*(2017-05-04)*

#### Fixed
* Issue with empty query after applying stopwords

---

## 1.0.2
*(2017-04-13)*

#### Fixed
* Match logic

---

## 1.0.1
*(2017-04-10)*

#### Improvements
* Added suggestion provider for AdvancedSearch

