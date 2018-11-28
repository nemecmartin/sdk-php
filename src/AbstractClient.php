<?php

namespace Directus\SDK;

use Directus\SDK\Response\ItemCollection;
use Directus\SDK\Response\Item;
use Directus\Util\ArrayUtils;
use Directus\Util\StringUtils;

abstract class AbstractClient implements RequestsInterface
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Creates a response object
     *
     * @param $data
     *
     * @return \Directus\SDK\Response\ItemCollection|\Directus\SDK\Response\Item
     */
    protected function createResponseFromData($data)
    {
        if (isset($data['rows']) || (isset($data['data']) && ArrayUtils::isNumericKeys($data['data']))) {
            $response = new ItemCollection($data);
        } else {
            $response = new Item($data);
        }

        return $response;
    }

    protected function processData($tableName, array $data)
    {
        $method = 'processDataOn' . StringUtils::underscoreToCamelCase($tableName, true);
        if (method_exists($this, $method)) {
            $data = call_user_func_array([$this, $method], [$data]);
        }

        return $data;
    }

    protected function processFile(File $file)
    {
        $data = $file->toArray();
        // Not container, we are using remote :)
        if (!$this->container) {
            return $data;
        }

        $Files = $this->container->get('files');

        if (!array_key_exists('type', $data) || strpos($data['type'], 'embed/') === 0) {
            $recordData = $Files->saveEmbedData($data);
        } else {
            $recordData = $Files->saveData($data['data'], $data['name']);
        }

        return array_merge($recordData, ArrayUtils::omit($data, ['data', 'name']));
    }

    protected function processDataOnDirectusUsers($data)
    {
        $data = ArrayUtils::omit($data, ['id', 'user', 'access_token', 'last_login', 'last_access', 'last_page']);

        if (ArrayUtils::has($data, 'avatar_file_id') && $data['avatar_file_id'] instanceof File) {
            $data['avatar_file_id'] = $this->processFile($data['avatar_file_id']);
        }

        return $data;
    }

    protected function processOnDirectusFiles($data)
    {
        // @NOTE: omit columns such id or user.
        $data = ArrayUtils::omit($data, ['id', 'user']);

        return $data;
    }

    protected function processDataOnDirectusPreferences($data)
    {
        // @NOTE: omit columns such id or user.
        $data = ArrayUtils::omit($data, ['id']);

        return $data;
    }

    protected function processDataOnDirectusBookmarks($data)
    {
        // @NOTE: omit columns such id or user.
        $data = ArrayUtils::omit($data, ['id']);

        return $data;
    }

    protected function requiredAttributes(array $attributes, array $data)
    {
        if (!ArrayUtils::contains($data, $attributes)) {
            throw new \Exception(sprintf('These attributes are required: %s', implode(',', $attributes)));
        }
    }

    protected function requiredOneAttribute(array $attributes, array $data)
    {
        if (!ArrayUtils::containsSome($data, $attributes)) {
            throw new \Exception(sprintf('These attributes are required: %s', implode(',', $attributes)));
        }
    }
}
