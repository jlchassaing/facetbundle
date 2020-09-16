<?php
/**
 * Created by PhpStorm.
 * User: jlchassaing
 * Date: 12/12/2017
 * Time: 08:59
 */

namespace Gie\FacetBundle\FieldMapper;

use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use EzSystems\EzPlatformSolrSearchEngine\FieldMapper\ContentFieldMapper;
use eZ\Publish\Core\Persistence\Cache\ContentTypeHandler;
use eZ\Publish\SPI\Persistence\Content;
use eZ\Publish\SPI\Search;

class CustomTagFieldMapper extends ContentFieldMapper
{
    /** @var \eZ\Publish\API\Repository\ContentTypeService  */
    protected $contentTypeHandler;

    /**
     * @var Content\Type\FieldDefinition
     */
    private $fieldDefinition;


    /**
     * CustomTagFieldMapper constructor.
     *
     * @param \eZ\Publish\API\Repository\ContentTypeService $contentTypeHandler
     */
    public function __construct(
        ContentTypeService $contentTypeHandler
    ) {
        $this->contentTypeHandler = $contentTypeHandler;
    }

    public function accept(Content $content)
    {
        try {
            $contentType = $this->contentTypeHandler->loadContentType($content->versionInfo->contentInfo->contentTypeId);
            foreach ( $contentType->fieldDefinitions as $field ) {
                if ( $field->fieldTypeIdentifier == "eztags") {
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
