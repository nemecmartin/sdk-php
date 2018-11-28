<?php

/**
 * Directus – <http://getdirectus.com>
 *
 * @link      The canonical repository – <https://github.com/directus/directus>
 * @copyright Copyright 2006-2016 RANGER Studio, LLC – <http://rangerstudio.com>
 * @license   GNU General Public License (v3) – <http://www.gnu.org/copyleft/gpl.html>
 */

namespace Directus\SDK;

use Directus\Util\ArrayUtils;

/**
 * Client Remote
 *
 * @author Welling Guzmán <welling@rngr.org>
 */
class ClientRemote extends BaseClientRemote
{
    /**
     * @inheritdoc
     */
    public function getActivityList(array $params = [])
    {
        return $this->performRequest('GET', 'activity', [
            'query' => $params
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getActivity($id, array $params = [])
    {
        return $this->performRequest('GET', 'activity/' . $this->parseId($id), [
            'query' => $params
        ]);
    }

    /**
     * @inheritdoc
     */
    public function createComment($collection, $item, $message)
    {
        return $this->performRequest('POST', 'activity/comment', [
            'body' => [
                'collection' => $collection,
                'item' => $item,
                'comment' => $message,
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function updateComment($id, $message)
    {
        return $this->performRequest('PATCH', 'activity/comment/' . $id, [
            'body' => [
                'comment' => $message,
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function deleteComment($id, array $params = [])
    {
        $this->performRequest('DELETE', 'activity/comment/' . $id);
    }

    /**
     * @inheritdoc
     */
    public function createCollection($name, array $data, array $params = [])
    {
        return $this->performRequest('POST', 'collections', [
            'body' => array_merge($data, [
                'collection' => $name
            ]),
            'query' => $params,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function updateCollection($name, array $data, array $params = [])
    {
        return $this->performRequest('PATCH', 'collections/' . $name, [
            'body' => array_merge($data, [
                'collection' => $name
            ]),
            'query' => $params,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getCollections(array $params = [])
    {
        return $this->performRequest('GET', 'collections', [
            'query' => $params,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getCollection($name, array $params = [])
    {
        return $this->performRequest('GET', 'collections/' . $this->parseId($name), [
            'query' => $params,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function deleteCollection($name)
    {
        return $this->performRequest('DELETE', 'collections/' . $name);
    }

    /**
     * @inheritdoc
     */
    public function createCollectionPresets(array $data, array $params = [])
    {
        return $this->performRequest('POST', 'collection_presets', [
            'body' => $data,
            'query' => $params,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getCollectionPreset($id, array $params = [])
    {
        return $this->performRequest('GET', 'collection_presets/' . $this->parseId($id), [
            'query' => $params,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getCollectionPresets(array $params = [])
    {
        return $this->performRequest('GET', 'collection_presets', [
            'query' => $params,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function updateCollectionPreset($id, array $data, array $params = [])
    {
        $this->performRequest('PATCH', 'collection_presets/' . $id, [
            'body' => $data,
            'query' => $params,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function deleteCollectionPresets($id, array $params = [])
    {
        $this->performRequest('DELETE', 'collection_presets/' . $id, [
            'query' => $params,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function createField($collection, $name, array $data, array $params = [])
    {
        return $this->performRequest('POST', 'fields/' . $collection, [
            'body' => array_merge($data, [
                'field' => $name
            ]),
            'query' => $params,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getFields($collection, array $params = [])
    {
        return $this->performRequest('GET', 'fields/' . $collection);
    }

    /**
     * @inheritdoc
     */
    public function getAllFields(array $params = [])
    {
        return $this->performRequest('GET', 'fields', [
            'query' => $params,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getField($collection, $name)
    {
        return $this->performRequest('GET', sprintf('fields/%s/%s', $collection, $name));
    }

    /**
     * @inheritdoc
     */
    public function updateField($collection, $name, array $data, array $params = [])
    {
        return $this->performRequest('PATCH', sprintf('fields/%s/%s', $collection, $name), [
            'body' => $data,
            'query' => $params,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function deleteField($collection, $name)
    {
        $this->performRequest('DELETE', sprintf('fields/%s/%s', $collection, $name));
    }

    /**
     * @inheritdoc
     */
    public function getItems($tableName, array $options = [])
    {
        $path = $this->buildPath(static::TABLE_ENTRIES_ENDPOINT, $tableName);

        return $this->performRequest('GET', $path, ['query' => $options]);
    }

    /**
     * @inheritdoc
     */
    public function getItem($tableName, $id, array $options = [])
    {
        $path = $this->buildPath(static::TABLE_ENTRY_ENDPOINT, [$tableName, $id]);

        return $this->performRequest('GET', $path, ['query' => $options]);
    }

    /**
     * @inheritdoc
     */
    public function getUsers(array $params = [])
    {
        return $this->performRequest('GET', $this->getProject() . '/users', ['query' => $params]);
    }

    /**
     * @inheritdoc
     */
    public function getUser($id, array $params = [])
    {
        return $this->performRequest('GET', $this->getProject() . '/users/' . $id, ['query' => $params]);
    }

    /**
     * @inheritdoc
     */
    public function getRoles()
    {
        return $this->performRequest('GET', static::GROUP_LIST_ENDPOINT);
    }

    /**
     * @inheritdoc
     */
    public function getRole($groupID)
    {
        $path = $this->buildPath(static::GROUP_INFORMATION_ENDPOINT, $groupID);

        return $this->performRequest('GET', $path);
    }

    /**
     * @inheritdoc
     */
    public function getRolePermissions($groupID)
    {
        $path = $this->buildPath(static::GROUP_PRIVILEGES_ENDPOINT, $groupID);

        return $this->performRequest('GET', $path);
    }

    /**
     * @inheritdoc
     */
    public function getFiles(array $params = [])
    {
        return $this->performRequest('GET', static::FILE_LIST_ENDPOINT, ['query' => $params]);
    }

    /**
     * @inheritdoc
     */
    public function getFile($fileID)
    {
        $path = $this->buildPath(static::FILE_INFORMATION_ENDPOINT, $fileID);

        return $this->performRequest('GET', $path);
    }

    /**
     * @inheritdoc
     */
    public function getSettings()
    {
        return $this->performRequest('GET', static::SETTING_LIST_ENDPOINT);
    }

    /**
     * @inheritdoc
     */
    public function updateSettings($collection, array $data)
    {
        $path = $this->buildPath(static::SETTING_COLLECTION_UPDATE_ENDPOINT, $collection);

        return $this->performRequest('PUT', $path, [
            'body' => $data
        ]);
    }

    /**
     * @inheritdoc
     */
    public function createItem($tableName, array $data)
    {
        $path = $this->buildPath(static::TABLE_ENTRY_CREATE_ENDPOINT, $tableName);
        $data = $this->processData($tableName, $data);

        return $this->performRequest('POST', $path, ['body' => $data]);
    }

    /**
     * @inheritdoc
     */
    public function updateItem($tableName, $id, array $data)
    {
        $path = $this->buildPath(static::TABLE_ENTRY_UPDATE_ENDPOINT, [$tableName, $id]);
        $data = $this->processData($tableName, $data);

        return $this->performRequest('PUT', $path, ['body' => $data]);
    }

    /**
     * @inheritdoc
     */
    public function deleteItem($tableName, $id, $hard = false)
    {
        $path = $this->buildPath(static::TABLE_ENTRY_DELETE_ENDPOINT, [$tableName, $id]);
        $options = [];

        if ($hard !== true) {
            $options = [
                'query' => ['soft' => true]
            ];
        }

        return $this->performRequest('DELETE', $path, $options);
    }

    /**
     * @inheritdoc
     */
    public function createUser(array $data)
    {
        return $this->createItem('directus_users', $data);
    }

    /**
     * @inheritdoc
     */
    public function updateUser($id, array $data)
    {
        return $this->updateItem('directus_users', $id, $data);
    }

    /**
     * @inheritdoc
     */
    public function deleteUser($ids, $hard = false)
    {
        return $this->deleteItem('directus_users', $ids, $hard);
    }

    /**
     * @inheritdoc
     */
    public function createFile(File $file)
    {
        $data = $this->processFile($file);

        return $this->performRequest('POST', static::FILE_CREATE_ENDPOINT, ['body' => $data]);
    }

    /**
     * @inheritdoc
     */
    public function updateFile($id, $data)
    {
        if ($data instanceof File) {
            $data = $data->toArray();
        }

        $data['id'] = $id;
        $path = $this->buildPath(static::FILE_UPDATE_ENDPOINT, $id);
        $data = $this->processData('directus_files', $data);

        return $this->performRequest('POST', $path, ['body' => $data]);
    }

    /**
     * @inheritdoc
     */
    public function deleteFile($id, $hard = false)
    {
        return $this->deleteItem('directus_files', $id, $hard);
    }

    /**
     * @inheritdoc
     */
    public function createRole(array $data)
    {
        return $this->performRequest('POST', static::GROUP_CREATE_ENDPOINT, [
            'body' => $data
        ]);
    }

    /**
     * @inheritdoc
     */
    public function createPermissions(array $data)
    {
        $this->requiredAttributes(['group_id', 'table_name'], $data);

        return $this->performRequest('POST', static::GROUP_PRIVILEGES_CREATE_ENDPOINT, [
            'body' => $data
        ]);
    }

    /**
     * @inheritdoc
     */
    public function deleteRole($id, $hard = false)
    {
        return $this->deleteItem('directus_groups', $id, $hard);
    }

    /**
     * @inheritdoc
     */
    public function getRandomString(array $options = [])
    {
        $path = $this->buildPath(static::UTILS_RANDOM_ENDPOINT);

        return $this->performRequest('POST', $path, ['body' => $options]);
    }

    /**
     * @inheritdoc
     */
    public function getHash($string, array $options = [])
    {
        $path = $this->buildPath(static::UTILS_HASH_ENDPOINT);

        $data = [
            'string' => $string
        ];

        if (ArrayUtils::has($options, 'hasher')) {
            $data['hasher'] = ArrayUtils::pull($options, 'hasher');
        }

        $data['options'] = $options;

        return $this->performRequest('POST', $path, ['body' => $data]);
    }

    /**
     * Glues array of IDs into a CSV
     *
     * @param mixed $id
     *
     * @return string
     */
    protected function parseId($id)
    {
        if (is_array($id)) {
            $id = implode(',', $id);
        }

        return $id;
    }
}
