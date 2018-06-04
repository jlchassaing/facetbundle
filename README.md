
[![Build Status](https://img.shields.io/travis/jlchassaing/facetbundle.svg?style=flat-square)](https://travis-ci.org/jlchassaing/facetbundle)
[![Code Coverage](https://img.shields.io/codecov/c/github/jlchassaing/facetbundle.svg?style=flat-square)](https://codecov.io/gh/jlchassaing/facetbundle)
[![Latest stable](https://img.shields.io/packagist/v/jlchassaing/facetbundle.svg?style=flat-square)](https://packagist.org/packages/jlchassaing/facetbundle)
[![License](https://img.shields.io/packagist/l/jlchassaing/facetbundle.svg?style=flat-square)](https://packagist.org/packages/jlchassaing/facetbundle)


# Gie Facet Bundle

This bundle will help you perform and retreive facet searches.

## how to use it

### 1. Init the facet search helper
 
In your Controller action just call the gie.facet.search.helper

By default two facet search helpers are set in this bundle :
* the ContentTypeFacetSearchHelper who's alias is content_type 
* thie TagsFacetSearchHelper who's alias is tags and is based on the netgen/tagsbundle

after calling the facet search helper, you'll have to call the init method with two parameter :
* an array with facet FacetConfig objects 
* the current Request 

FacetCongig objects are build with the facet helper alias a title and an array of facet parameters

The solr_field facet can facet the query on any solr field defined by the field parameter.
The format function can help format the value of user display 

The init method will also look at the queryString and init the selected facets. If you don't want it to do so, you can avoid passing it the request object. And then use de setFacetFilters() method to set what ever selected facet you want. 

```php

$facetSearchHelper = $this->container->get('gie.facet.search.helper')
                                     ->init(new FacetConfig('content_type',
                                                            'Content types',  [
                                                            'minCount' => 2,
                                                            'limit' => 5,
                                                            ]),
                                            new FacetConfig('tags',
                                                            'Key Words', [
                                                            'minCount' => 1,
                                                            'limit' => 5,
                                                            ]),
                                            new FacetConfig('custom_date',
                                                            'Publication date'),
                                            new FacetConfig('solr_field',
                                                            'Year',
                                                            ['field' => 'meta_year_date_dt',
                                                             'format' => function($value){
                                                             $date = new \DateTime($value);
                                                             return $date->format('Y');}
                                                            ]),
            ], $request);

```

### 2. Build query

once done, build your query without any facet or filter condition and pass it down to the facet helper.

````php

 $query = $facetSearchHelper->addQueryFacets($query);
````

### 3. get the facet array that will be sent to the template

After updating the query with the facet settings and the filters from the eventually selected facets, you need to call the getFacets method to get the facets array.

#### 1. Using PagerFanta

If you need the PagerFanta tool then you can pass it to the facetHelper.

When using PagerFanta, you'll have to use the bundle ContentSearchAdapter otherwise you won't be able to get the facets from the query beeing executed.

```php
use Gie\FacetBuilder\Pagination\Pagerfanta\ContentSearchAdapter;
```

```php
$facets = $facetSearchHelper->getFacetsFromPager($pager);
```

#### 2. From a QueryResult

If you are just using a ContentQuery then you can pass the result to the facetHelper and call the 

```php
$facets = $facetHelper->getFacetsFromQuery($queryResult);
```


#### 4. pass the facets to the template

Last thing you'll have to do is retrieve  the facets array that you'll pass to the template to display them as you want.

````php


array:2 [▼
  "Facet Name" => array [▼
    0 => array [▼
      "name" => "Facet Value Name"
      "key" => "facet_generate_key"
      "count" => count 
      "querystring" => "querystring" /* query string to add to the request */
      "selected" => true|false 
    ]
    .../...
  ]
]
````


