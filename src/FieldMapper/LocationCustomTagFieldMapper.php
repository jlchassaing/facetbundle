<?php
/**
 * Created by PhpStorm.
 * User: jlchassaing
 * Date: 12/12/2017
 * Time: 08:59
 */

namespace Gie\FacetBundle\FieldMapper;



use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\LocationService;
use EzSystems\EzPlatformSolrSearchEngine\FieldMapper\ContentFieldMapper;
use eZ\Publish\Core\Persistence\Cache\ContentHandler;

use eZ\Publish\Core\Persistence\Cache\ContentTypeHandler;
use eZ\Publish\Core\Persistence\Cache\LocationHandler;
use eZ\Publish\SPI\Persistence\Content;
use eZ\Publish\SPI\Search;
use EzSystems\EzPlatformSolrSearchEngine\FieldMapper\LocationFieldMapper;

class LocationCustomTagFieldMapper extends LocationFieldMapper
{
    /** @var \eZ\Publish\API\Repository\ContentService  */
    protected $contentHandler;

    /** @var \eZ\Publish\API\Repository\LocationService  */
    protected $locationHandler;

    /** @var \eZ\Publish\API\Repository\ContentTypeService  */
    protected $contentTypeHandler;

    /**
     * @var Content\Type\FieldDefinition
     */
    private $fieldDefinition;

    /**
     * @var Content
     */
    private $content;


    /**
     * LocationCustomTagFieldMapper constructor.
     *
     * @param \eZ\Publish\API\Repository\ContentService $contentHandler
     * @param \eZ\Publish\API\Repository\LocationService $locationHandler
     * @param \eZ\Publish\API\Repository\ContentTypeService $contentTypeHandler
     */
    public function __construct(
        ContentService $contentHandler,
        LocationService $locationHandler,
        ContentTypeService $contentTypeHandler

    ) {
        $this->contentHandler = $contentHandler;
        $this->locationHandler = $locationHandler;
        $this->contentTypeHandler = $contentTypeHandler;
    }

    public function accept(Content\Location $location)
    {
        // ContentType with ID 42 is webinar event
        try {

            $contentInfo = $this->contentHandler->loadContentInfo($location->contentId);

            $this->content = $this->contentHandler->load($location->contentId,$contentInfo->currentVersionNo);
            $contentType = $this->contentTypeHandler->load($contentInfo->contentTypeId);
            foreach ( $contentType->fieldDefinitions as $field ) {
                if ( $field->fieldType == "eztags") {
                    $this->fieldDefinition = $field;
                    return true;
                }
            }

            return false;
        } catch (NotFoundException $e) {
            return false;
        }

    }

    public function mapFields(Content\Location $location)
    {

        $value = null;
        foreach ($this->content->fields as $field )
        {
            if ( $field->fieldDefinitionId == $this->fieldDefinition->id)
            {

                $value = [];
                foreach ( $field->value->externalData as $keyWord )
                {
                    $value[] = $keyWord["id"];
                }

            }
        }


        return [
            new Search\Field(
                'content_tags_ids',
                $value,
                new Search\FieldType\MultipleIntegerField()

            ),
        ];
    }
}