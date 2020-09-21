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

use eZ\Publish\SPI\Persistence\Content;
use eZ\Publish\SPI\Search;
use EzSystems\EzPlatformSolrSearchEngine\FieldMapper\LocationFieldMapper;

use eZ\Publish\SPI\Persistence\Content\Handler as ContentHandler;
use eZ\Publish\SPI\Persistence\Content\Type\Handler as ContentTypeHandler;



class LocationCustomTagFieldMapper extends LocationFieldMapper
{
    /**
     * @var Content\Type\FieldDefinition
     */
    private $fieldDefinition;

    /** @var  */
    private $content;

   /** @var \eZ\Publish\SPI\Persistence\Content\Handler  */
    private $contentHandler;

    /** @var \eZ\Publish\SPI\Persistence\Content\Type\Handler  */
    private $contentTypeHandler;

    /**
     * LocationCustomTagFieldMapper constructor.
     *
     * @param \eZ\Publish\SPI\Persistence\Handler $persistenceHandler
     */
    public function __construct(
        ContentHandler $contentHandler,
        ContentTypeHandler $contentTypeHandler
    ) {
        $this->contentHandler = $contentHandler;
        $this->contentTypeHandler = $contentTypeHandler;
    }

    public function accept(Content\Location $location)
    {
        // ContentType with ID 42 is webinar event
        try {
            $contentInfo = $this->contentHandler->loadContentInfo($location->contentId);
            $this->content = $this->contentHandler->load($contentInfo->id);
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