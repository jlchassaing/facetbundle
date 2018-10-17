<?php
/**
 * Created by PhpStorm.
 * User: jlchassaing
 * Date: 02/06/18
 * Time: 07:57
 */
namespace Gie\FacetBundle\Tests\FieldMapper;

use eZ\Publish\Core\Base\Exceptions\NotFoundException;
use eZ\Publish\SPI\Persistence\Content\Handler as ContentHandler;
use eZ\Publish\SPI\Persistence\Content\Location\Handler as LocationHandler;
use eZ\Publish\SPI\Persistence\Content\Type\Handler as ContentTypeHandler;
use eZ\Publish\SPI\Persistence\Content;
use eZ\Publish\SPI\Persistence\Content\Type as ContentType;
use Gie\FacetBundle\FieldMapper\CustomTagFieldMapper;
use eZ\Publish\SPI\Search;
use PHPUnit\Framework\TestCase;

class CustomTagFieldMapperTest extends TestCase
{

    private $contentHandler;

    private $locationHandler;

    private $contentTypeHandler;

    private $contentType;

    /**
     * @var ContentType\FieldDefinition()
     */
    private $fieldDefinition;

    /**
     * @var Content
     */
    private $content;

    public function setUp()
    {
        $this->contentHandler = $this->createMock(ContentHandler::class);
        $this->locationHandler = $this->createMock(LocationHandler::class);
        $this->contentTypeHandler = $this->createMock(ContentTypeHandler::class);

        $this->contentType = new ContentType();
        $this->fieldDefinition = new ContentType\FieldDefinition();

        $versionInfo = new Content\VersionInfo();
        $versionInfo->contentInfo = new Content\ContentInfo();
        $versionInfo->contentInfo->contentTypeId = 4;

        $this->content = new Content();
        $this->content->versionInfo = $versionInfo;

    }

    public function testAccept()
    {
        $this->fieldDefinition->fieldType = "eztags";
        $this->fieldDefinition->id = 4;


        $field = new Content\Field();

        $field->type =  "eztags";
        $field->fieldDefinitionId = 4;
        $field->value = new Content\FieldValue();
        $field->value->externalData = [['id' => 'id']];

        $this->content->fields = [$field];

        $this->contentType->fieldDefinitions = [$this->fieldDefinition];

        $this->contentTypeHandler->method('load')
                           ->willReturn($this->contentType);

        $customTagFieldMapper = new CustomTagFieldMapper(
            $this->contentTypeHandler);

        $this->assertTrue($customTagFieldMapper->accept($this->content));

        $fieldMap = $customTagFieldMapper->mapFields($this->content);
        $this->assertInternalType('array' , $fieldMap);
        $this->assertInstanceOf(Search\Field::class,$fieldMap[0]);
    }

    public function testAcceptUseException()
    {
        $this->contentTypeHandler->method('load')->willThrowException(new NotFoundException("error", "id"));

        $customTagFieldMapper = new CustomTagFieldMapper(
            $this->contentTypeHandler);

        $this->assertFalse($customTagFieldMapper->accept($this->content));
    }


}