## 1.1.2
*(2017-05-30)*

#### Improvements
* Reports Module

---

# Search Sphinx
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

# Search Autocomplete & Suggest
## 1.1.14
*(2017-05-25)*

#### Improvements
* Performance

#### Fixed
* Issue with image placeholder
* Issue with calculation final price

---

## 1.1.13
*(2017-05-12)*

#### Fixed
* Issue with sorting queries

---

## 1.1.12
*(2017-05-11)*

#### Improvements
* Ability to set queries limit for "Hot Searches"

#### Fixed
* Possible issue with results sorting

---

## 1.1.11
*(2017-05-03)*

#### Fixed
* Issue with sku

---

## 1.1.9
*(2017-05-02)*

#### Fixed
* For landing pages

---

## 1.1.8
*(2017-04-27)*

#### Fixed
* Issue with mageplaza blog

---

## 1.1.7
*(2017-04-26)*

#### Improvements
* Mageplaza blog post

---

## 1.1.6
*(2017-04-26)*

#### Improvements
* Added sku

#### Fixed
* Issue with Mirasvit indices

---

## 1.1.5
*(2017-04-26)*

#### Improvements
* Mouse paste event

---


## 1.1.4
*(2017-04-24)*

#### Fixed
* Issue with DI
* Product name is empty in search results

---

## 1.1.3
*(2017-04-06)*

#### Fixed
* Fixed an issue with disabled submit button after use hot / popular queries

---


## 1.1.2
*(2017-04-05)*

#### Fixed
* Fixed an issue with provider

---

## 1.1.1
*(2017-04-04)*

#### Fixed
* Fixed an issue with results

---

## 1.0.48
*(2017-01-20)*

#### Improvements
* Added new index for catalog attributes

---

## 1.0.47
*(2017-01-15)*

#### Fixed
* Fixed an issue with submit

---

## 1.0.46
*(2017-01-12)*

#### Fixed
* Fixed an issue with default search bar behaviour

---

## 1.0.45
*(2016-12-23)*

#### Fixed
* Fixed an issue witk key navigation
* Fixed possible issue with product price (tax)
* Fixed an issue with recent queries

---

## 1.0.44
*(2016-12-08)*

#### Fixed
* Fixed few possible JS issue with search results behaviour
* Fixed an issue with selecting most popular search terms

---

## 1.0.43
*(2016-11-21)*

#### Improvements
* Compatibility with M 2.2.0

---

## 1.0.42
*(2016-10-28)*

#### Fixed
* Fixed an issue with "Hot searches"

---

## 1.0.41
*(2016-10-25)*

#### Improvements
* Added user agent to warmer

---

## 1.0.40
*(2016-10-12)*

#### Fixed
* Fixed an issue with hide search results

---

## 1.0.39
*(2016-10-06)*

#### Fixed
* Fixed an issue with autocomplete position

---

## 1.0.38
*(2016-09-30)*

#### Fixed
* Fixed an issue with minimum query length
* Ability to translate index names

---

## 1.0.36
*(2016-09-20)*

#### Fixed
* Fixed an issue with translations

---


## 1.0.34
*(2016-09-16)*

#### Fixed
* Fixed an issue with reset cursor position after change query

---

## 1.0.33
*(2016-08-30)*

#### Fixed
* Fixed an issue with html markup (style tag)
* Fixed an issue with popular suggestions

---

## 1.0.32
*(2016-07-29)*

#### Fixed
* Fixed an issue with cache special chars

---

## 1.0.31
*(2016-07-28)*

#### Fixed
* Fixed a possible issue with number of search results

---

## 1.0.30
*(2016-07-07)*

#### Fixed
* Fixed an issue with translations (M2.1)

---

## 1.0.29
*(2016-06-29)*

#### Fixed
* Fixed an issue with "Show all results" link

---

## 1.0.28
*(2016-06-24)*

#### Fixed
* Compatibility with Magento 2.1

---

## 1.0.27
*(2016-06-22)*

#### Fixed
* Fixed an issue with ajax loader

---


## 1.0.26
*(2016-05-30)*

#### Fixed
* Fixed an issue with catalog layer

---

## 1.0.25
*(2016-05-26)*

#### Fixed
* Fixed an issue with duplicating Popular suggestions (letter register)

---

## 1.0.24
*(2016-05-20)*

#### Improvements
* Image selection for products autocomplete
* Ability to define ignored words for "Hot Searches"

---

## 1.0.23
*(2016-05-17)*

#### Fixed
* Fixed an issue with possible search layer exception

---

## 1.0.21, 1.0.22
*(2016-05-11)*

#### Fixed
* Fixed an issue with translations .html templates

---

## 1.0.20
*(2016-05-08)*

#### Fixed
* Fixed an issue with wrong autocomplete position on mobile devices

---

## 1.0.19
*(2016-05-06)*

#### Fixed
* Fixed an issue with wrong currency convert rate
* Fixed an issue with multi-store configuration

---

## 1.0.17
*(2016-04-29)*

#### Fixed
* Fixed an issue with tax

---

## 1.0.16
*(2016-04-27)*

#### Fixed
* Fixed an issue with html entity chars

---

## 1.0.15
*(2016-04-11)*

#### Fixed
* Fixed possible issue with http/https ajax urls
* Fixed an issue with cache warmer
* Fixed an issue with behaviour for popular search queries

---

## 1.0.14
*(2016-04-06)*

#### Improvements
* Hot Searches

---

## 1.0.13
*(2016-04-1)*

#### Improvements
* Performance and Styles

---

## 1.0.12
*(2016-03-25)*

#### Improvements
* Integrated Mirasvit Knowledge Base

---

## 1.0.11
*(2016-03-23)*

#### Improvements
* Display full category path for categories
* Default configuration for indexes

#### Fixed
* Fixed an issue with hiding placeholder before redirect to "View all results"
* Fixed an issue with selection not active indexes

---


## 1.0.10
*(2016-03-11)*

#### Improvements
* Improved loader logic

#### Fixed
* Fixed issue with FrontController headers

---

## 1.0.9
*(2016-03-09)*

#### Fixed
* Fixed an issue with price formatting
* Fixed issue with FrontController headers

#### Documentation
* Updated installation steps

---

## 1.0.8
*(2016-03-06)*

#### Improvements
* SSU2-9 -- Search by Mirasvit Blog MX
* Added ability to set-up custom css styles in magento backend
* i18n

#### Fixed
* Fixed compatibility issue with Amasty_Shopby
* Fixed an issue with cache
* Fixed an issue related with autocomplete position on some devices

---

## 1.0.7
*(2016-02-22)*

#### Fixed
* Fixed an issue related with case sensitive search results (should be same for both registers)
* Fixed an bug with undefined configuration for search index
* Cache id for results

---


## 1.0.6
*(2016-02-15)*

#### Improvements
* Added caching for results (tag FULL Page Cache)
* Added link/url for Popular Suggestions
* Changed autocomplete block (added injection). Removed form.
* Added form loaded state

#### Fixed
* Fixed issue with broken product image url, if image not assigned to image
* Fixed issues related with autocomlete injection
* Fixed an issue with page cache (increased TTFB)
* Fixed an performance issue related with locale/currency (temporary fix)

#### Documentation
* Added user manual

---

## 1.0.5
*(2016-02-02)*

#### Fixed
* Fixed an performance issue related with locale/currency (temporary fix)

#### Improvements
* Added form loaded state

---


# Search Spell Correction
## 1.0.12
*(2017-05-10)*

#### Improvements
* Remove spell correction index if it disabled

---

## 1.0.11
*(2017-04-11)*

#### Improvements
* Switched to API interfaces

---

## 1.0.10
*(2017-02-20)*

#### Improvements
* Changed all string fuctions to mb_*

---


## 1.0.9
*(2017-02-03)*

#### Improvements
* Added Recurring setup script for check fulltext indices

---

## 1.0.8
*(2016-11-21)*

#### Improvements
* Compatibility with M 2.2.0

---

## 1.0.7
*(2016-06-24)*

#### Fixed
* Compatibility with Magento 2.1

---

## 1.0.6
*(2016-06-16)*

#### Fixed
* Fixed an issue with changing index mode for misspell index

---

## 1.0.5
*(2016-04-27)*

#### Improvements
* Improved extension performance
* i18n

#### Documentation
* Updated installation steps

---

## 1.0.4
*(2016-02-23)*

#### Fixed
* Fixed an issue with segmentation fault during reindex (PHP7)

---

## 1.0.3
*(2016-02-07)*

#### Documentation
* Added user manual

---



------
# Search Sphinx
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

# Search Autocomplete & Suggest
## 1.1.14
*(2017-05-25)*

#### Improvements
* Performance

#### Fixed
* Issue with image placeholder
* Issue with calculation final price

---

## 1.1.13
*(2017-05-12)*

#### Fixed
* Issue with sorting queries

---

## 1.1.12
*(2017-05-11)*

#### Improvements
* Ability to set queries limit for "Hot Searches"

#### Fixed
* Possible issue with results sorting

---

## 1.1.11
*(2017-05-03)*

#### Fixed
* Issue with sku

---

## 1.1.9
*(2017-05-02)*

#### Fixed
* For landing pages

---

## 1.1.8
*(2017-04-27)*

#### Fixed
* Issue with mageplaza blog

---

## 1.1.7
*(2017-04-26)*

#### Improvements
* Mageplaza blog post

---

## 1.1.6
*(2017-04-26)*

#### Improvements
* Added sku

#### Fixed
* Issue with Mirasvit indices

---

## 1.1.5
*(2017-04-26)*

#### Improvements
* Mouse paste event

---


## 1.1.4
*(2017-04-24)*

#### Fixed
* Issue with DI
* Product name is empty in search results

---

## 1.1.3
*(2017-04-06)*

#### Fixed
* Fixed an issue with disabled submit button after use hot / popular queries

---


## 1.1.2
*(2017-04-05)*

#### Fixed
* Fixed an issue with provider

---

## 1.1.1
*(2017-04-04)*

#### Fixed
* Fixed an issue with results

---

## 1.0.48
*(2017-01-20)*

#### Improvements
* Added new index for catalog attributes

---

## 1.0.47
*(2017-01-15)*

#### Fixed
* Fixed an issue with submit

---

## 1.0.46
*(2017-01-12)*

#### Fixed
* Fixed an issue with default search bar behaviour

---

## 1.0.45
*(2016-12-23)*

#### Fixed
* Fixed an issue witk key navigation
* Fixed possible issue with product price (tax)
* Fixed an issue with recent queries

---

## 1.0.44
*(2016-12-08)*

#### Fixed
* Fixed few possible JS issue with search results behaviour
* Fixed an issue with selecting most popular search terms

---

## 1.0.43
*(2016-11-21)*

#### Improvements
* Compatibility with M 2.2.0

---

## 1.0.42
*(2016-10-28)*

#### Fixed
* Fixed an issue with "Hot searches"

---

## 1.0.41
*(2016-10-25)*

#### Improvements
* Added user agent to warmer

---

## 1.0.40
*(2016-10-12)*

#### Fixed
* Fixed an issue with hide search results

---

## 1.0.39
*(2016-10-06)*

#### Fixed
* Fixed an issue with autocomplete position

---

## 1.0.38
*(2016-09-30)*

#### Fixed
* Fixed an issue with minimum query length
* Ability to translate index names

---

## 1.0.36
*(2016-09-20)*

#### Fixed
* Fixed an issue with translations

---


## 1.0.34
*(2016-09-16)*

#### Fixed
* Fixed an issue with reset cursor position after change query

---

## 1.0.33
*(2016-08-30)*

#### Fixed
* Fixed an issue with html markup (style tag)
* Fixed an issue with popular suggestions

---

## 1.0.32
*(2016-07-29)*

#### Fixed
* Fixed an issue with cache special chars

---

## 1.0.31
*(2016-07-28)*

#### Fixed
* Fixed a possible issue with number of search results

---

## 1.0.30
*(2016-07-07)*

#### Fixed
* Fixed an issue with translations (M2.1)

---

## 1.0.29
*(2016-06-29)*

#### Fixed
* Fixed an issue with "Show all results" link

---

## 1.0.28
*(2016-06-24)*

#### Fixed
* Compatibility with Magento 2.1

---

## 1.0.27
*(2016-06-22)*

#### Fixed
* Fixed an issue with ajax loader

---


## 1.0.26
*(2016-05-30)*

#### Fixed
* Fixed an issue with catalog layer

---

## 1.0.25
*(2016-05-26)*

#### Fixed
* Fixed an issue with duplicating Popular suggestions (letter register)

---

## 1.0.24
*(2016-05-20)*

#### Improvements
* Image selection for products autocomplete
* Ability to define ignored words for "Hot Searches"

---

## 1.0.23
*(2016-05-17)*

#### Fixed
* Fixed an issue with possible search layer exception

---

## 1.0.21, 1.0.22
*(2016-05-11)*

#### Fixed
* Fixed an issue with translations .html templates

---

## 1.0.20
*(2016-05-08)*

#### Fixed
* Fixed an issue with wrong autocomplete position on mobile devices

---

## 1.0.19
*(2016-05-06)*

#### Fixed
* Fixed an issue with wrong currency convert rate
* Fixed an issue with multi-store configuration

---

## 1.0.17
*(2016-04-29)*

#### Fixed
* Fixed an issue with tax

---

## 1.0.16
*(2016-04-27)*

#### Fixed
* Fixed an issue with html entity chars

---

## 1.0.15
*(2016-04-11)*

#### Fixed
* Fixed possible issue with http/https ajax urls
* Fixed an issue with cache warmer
* Fixed an issue with behaviour for popular search queries

---

## 1.0.14
*(2016-04-06)*

#### Improvements
* Hot Searches

---

## 1.0.13
*(2016-04-1)*

#### Improvements
* Performance and Styles

---

## 1.0.12
*(2016-03-25)*

#### Improvements
* Integrated Mirasvit Knowledge Base

---

## 1.0.11
*(2016-03-23)*

#### Improvements
* Display full category path for categories
* Default configuration for indexes

#### Fixed
* Fixed an issue with hiding placeholder before redirect to "View all results"
* Fixed an issue with selection not active indexes

---


## 1.0.10
*(2016-03-11)*

#### Improvements
* Improved loader logic

#### Fixed
* Fixed issue with FrontController headers

---

## 1.0.9
*(2016-03-09)*

#### Fixed
* Fixed an issue with price formatting
* Fixed issue with FrontController headers

#### Documentation
* Updated installation steps

---

## 1.0.8
*(2016-03-06)*

#### Improvements
* SSU2-9 -- Search by Mirasvit Blog MX
* Added ability to set-up custom css styles in magento backend
* i18n

#### Fixed
* Fixed compatibility issue with Amasty_Shopby
* Fixed an issue with cache
* Fixed an issue related with autocomplete position on some devices

---

## 1.0.7
*(2016-02-22)*

#### Fixed
* Fixed an issue related with case sensitive search results (should be same for both registers)
* Fixed an bug with undefined configuration for search index
* Cache id for results

---


## 1.0.6
*(2016-02-15)*

#### Improvements
* Added caching for results (tag FULL Page Cache)
* Added link/url for Popular Suggestions
* Changed autocomplete block (added injection). Removed form.
* Added form loaded state

#### Fixed
* Fixed issue with broken product image url, if image not assigned to image
* Fixed issues related with autocomlete injection
* Fixed an issue with page cache (increased TTFB)
* Fixed an performance issue related with locale/currency (temporary fix)

#### Documentation
* Added user manual

---

## 1.0.5
*(2016-02-02)*

#### Fixed
* Fixed an performance issue related with locale/currency (temporary fix)

#### Improvements
* Added form loaded state

---


# Search Spell Correction
## 1.0.12
*(2017-05-10)*

#### Improvements
* Remove spell correction index if it disabled

---

## 1.0.11
*(2017-04-11)*

#### Improvements
* Switched to API interfaces

---

## 1.0.10
*(2017-02-20)*

#### Improvements
* Changed all string fuctions to mb_*

---


## 1.0.9
*(2017-02-03)*

#### Improvements
* Added Recurring setup script for check fulltext indices

---

## 1.0.8
*(2016-11-21)*

#### Improvements
* Compatibility with M 2.2.0

---

## 1.0.7
*(2016-06-24)*

#### Fixed
* Compatibility with Magento 2.1

---

## 1.0.6
*(2016-06-16)*

#### Fixed
* Fixed an issue with changing index mode for misspell index

---

## 1.0.5
*(2016-04-27)*

#### Improvements
* Improved extension performance
* i18n

#### Documentation
* Updated installation steps

---

## 1.0.4
*(2016-02-23)*

#### Fixed
* Fixed an issue with segmentation fault during reindex (PHP7)

---

## 1.0.3
*(2016-02-07)*

#### Documentation
* Added user manual

---
