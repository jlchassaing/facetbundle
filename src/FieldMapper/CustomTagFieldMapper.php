<?php
/**
 * Created by PhpStorm.
 * User: jlchassaing
 * Date: 12/12/2017
 * Time: 08:59
 */

namespace Gie\FacetBundle\FieldMapper;



use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use EzSystems\EzPlatformSolrSearchEngine\FieldMapper\ContentFieldMapper;
use eZ\Publish\SPI\Persistence\Content\Handler as ContentHandler;
use eZ\Publish\SPI\Persistence\Content\Type\Handler as ContentTypeHandler;
use eZ\Publish\SPI\Persistence\Content\Location\Handler as LocationHandler;
use eZ\Publish\SPI\Persistence\Content;
use eZ\Publish\SPI\Search;
use Netgen\TagsBundle\Form\Type\FieldType\TagsFieldType;

class CustomTagFieldMapper extends ContentFieldMapper
{
    /**
     * @var \eZ\Publish\SPI\Persistence\Content\Type\Handler
     */
    protected $contentHandler;

    /**
     * @var \eZ\Publish\SPI\Persistence\Content\Location\Handler
     */
    protected $locationHandler;

    /**
     * @var ContentTypeHandler
     */
    protected $contentTypeHandler;

    /**
     * @var Content\Type\FieldDefinition
     */
    private $fieldDefinition;

    /**
     * @param \eZ\Publish\SPI\Persistence\Content\Handler $contentHandler
     * @param \eZ\Publish\SPI\Persistence\Content\Location\Handler $locationHandler
     */
    public function __construct(
        ContentHandler $contentHandler,
        LocationHandler $locationHandler,
        ContentTypeHandler $contentTypeHandler

    ) {
        $this->contentHandler = $contentHandler;
        $this->locationHandler = $locationHandler;
        $this->contentTypeHandler = $contentTypeHandler;
    }

    public function accept(Content $content)
    {
        // ContentType with ID 42 is webinar event
        try {
            $contentType = $this->contentTypeHandler->load($content->versionInfo->contentInfo->contentTypeId);
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




    public function mapFields(Content $content)
    {

        $value = null;
        foreach ($content->fields as $field )
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