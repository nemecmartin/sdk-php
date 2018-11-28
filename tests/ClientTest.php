<?php

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Directus\SDK\ClientRemote
     */
    protected $client;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * @var \GuzzleHttp\Handler\MockHandler
     */
    protected $mockHandler;

    public function setUp()
    {
        parent::setUp();

        $this->client = \Directus\SDK\ClientFactory::create('token');
        $mockHandler = $this->mockHandler = new \GuzzleHttp\Handler\MockHandler();
        $this->client->setHTTPClient(new \GuzzleHttp\Client(['handler' => $mockHandler]));
        $this->httpClient = $this->client->getHTTPClient();
    }

    public function testClient()
    {
        $client = $this->client;
        $this->assertInstanceOf('\GuzzleHttp\Client', $client->getDefaultHTTPClient());
        $this->assertInstanceOf('\GuzzleHttp\Client', $client->getHTTPClient());
        $this->assertSame('token', $client->getAccessToken());

        $client->setAccessToken('newToken');
        $this->assertSame('newToken', $client->getAccessToken());

        $this->assertSame($client->getDefaultProject(), $client->getProject());
        $this->assertNull($client->getInstanceKey());
    }

    public function testOptions()
    {
        $client = \Directus\SDK\ClientFactory::create('token', [
            'base_url' => 'http://directus.local'
        ]);

        $this->assertSame('http://directus.local/api/' . $client->getDefaultProject(), $client->getBaseProjectUrl());

        $client = \Directus\SDK\ClientFactory::create('token', [
            'base_url' => 'http://directus.local',
            'version' => 2
        ]);

        $this->assertSame('http://directus.local/api/2', $client->getBaseProjectUrl());

        $this->assertEquals(2, $client->getProject());
    }

    public function testHostedClient()
    {
        $instanceKey = 'account--instance';
        $client = \Directus\SDK\ClientFactory::create('token', ['instance_key' => $instanceKey]);

        $expectedBaseUrl = 'https://'.$instanceKey.'.directus.io';
        $expectedEndpoint = $expectedBaseUrl . '/api/' . $client->getDefaultProject();
        $this->assertSame($expectedBaseUrl, $client->getBaseUrl());
        $this->assertSame($expectedEndpoint, $client->getBaseProjectUrl());

        $client = \Directus\SDK\ClientFactory::create('token', [
            'base_url' => 'http://directus.local',
            'instance_key' => $instanceKey
        ]);

        $this->assertSame($expectedEndpoint, $client->getBaseProjectUrl());
        $this->assertEquals($instanceKey, $client->getInstanceKey());
    }

    public function testRequest()
    {
        $client = $this->client;
        $path = $client->buildPath($client::TABLE_ENTRIES_ENDPOINT, 'articles');
        $request = $this->client->buildRequest('GET', $path);
        $this->assertInstanceOf(\GuzzleHttp\Psr7\Request::class, $request);
    }

    public function testEndpoints()
    {
        $client =  $this->client;

        $endpoint = $this->client->buildPath($client::TABLE_LIST_ENDPOINT);
        $this->assertSame($endpoint, 'tables');

        $endpoint = $this->client->buildPath($client::TABLE_INFORMATION_ENDPOINT, 'articles');
        $this->assertSame($endpoint, 'tables/articles');

        $endpoint = $this->client->buildPath($client::TABLE_ENTRIES_ENDPOINT, 'articles');
        $this->assertSame($endpoint, 'tables/articles/rows');

        $endpoint = $this->client->buildPath($client::TABLE_ENTRIES_ENDPOINT, ['articles']);
        $this->assertSame($endpoint, 'tables/articles/rows');

        $endpoint = $this->client->buildPath($client::TABLE_ENTRY_ENDPOINT, ['articles', 1]);
        $this->assertSame($endpoint, 'tables/articles/rows/1');

        $endpoint = $this->client->buildPath($client::TABLE_PREFERENCES_ENDPOINT, 'articles');
        $this->assertSame($endpoint, 'tables/articles/preferences');

        $endpoint = $this->client->buildPath($client::COLUMN_LIST_ENDPOINT, ['articles']);
        $this->assertSame($endpoint, 'tables/articles/columns');

        $endpoint = $this->client->buildPath($client::COLUMN_INFORMATION_ENDPOINT, ['articles', 'body']);
        $this->assertSame($endpoint, 'tables/articles/columns/body');

        $endpoint = $this->client->buildPath($client::GROUP_LIST_ENDPOINT);
        $this->assertSame($endpoint, 'groups');

        $endpoint = $this->client->buildPath($client::GROUP_INFORMATION_ENDPOINT, 1);
        $this->assertSame($endpoint, 'groups/1');

        $endpoint = $this->client->buildPath($client::GROUP_PRIVILEGES_ENDPOINT, 1);
        $this->assertSame($endpoint, 'privileges/1');

        $endpoint = $this->client->buildPath($client::FILE_LIST_ENDPOINT);
        $this->assertSame($endpoint, 'files');

        $endpoint = $this->client->buildPath($client::FILE_INFORMATION_ENDPOINT, 1);
        $this->assertSame($endpoint, 'files/1');

        $endpoint = $this->client->buildPath($client::SETTING_LIST_ENDPOINT);
        $this->assertSame($endpoint, 'settings');

        $endpoint = $this->client->buildPath($client::SETTING_COLLECTION_GET_ENDPOINT, 'global');
        $this->assertSame($endpoint, 'settings/global');
    }

    public function testFetchTables()
    {
        $this->mockResponse('fetchTables.txt');
        $response = $this->client->getCollections();
        $this->assertInstanceOf('\Directus\SDK\Response\Item', $response);

        $this->mockResponse('fetchTablesEmpty.txt');
        $response = $this->client->getCollections();
        $this->assertInstanceOf('\Directus\SDK\Response\Item', $response);
    }

    public function testFetchTableInformation()
    {
        $this->mockResponse('fetchTableInformation.txt');
        $response = $this->client->getCollection('articles');
        $this->assertInstanceOf('\Directus\SDK\Response\Item', $response);

        $this->mockResponse('fetchTableInformationEmpty.txt');
        $response = $this->client->getCollection('articles');
        $this->assertFalse($response->getRawData());
    }

    public function testFetchTablePreferences()
    {
        $this->mockResponse('fetchTablePreferences.txt');
        $response = $this->client->getCollection('articles');
        $this->assertInternalType('object', $response);

        $this->mockResponse('fetchTablePreferencesEmpty.txt');
        $response = $this->client->getCollection('articles');
        $this->assertFalse($response->getRawData());
    }

    public function testFetchItems()
    {
        $this->mockResponse('fetchItems.txt');
        $response = $this->client->getUsers();

        $this->assertInstanceOf('\Directus\SDK\Response\ItemCollection', $response);
        $this->assertArrayHasKey('Active', $response->getMetaData()->getRawData());
        $this->assertArrayHasKey('Draft', $response->getMetaData()->getRawData());
        $this->assertArrayHasKey('Delete', $response->getMetaData()->getRawData());

        $this->mockResponse('fetchItemsEmpty.txt');
        $response = $this->client->getUsers();

        $this->assertInstanceOf('\Directus\SDK\Response\ItemCollection', $response);
        $this->assertArrayHasKey('Active', $response->getMetaData()->getRawData());
        $this->assertArrayHasKey('Draft', $response->getMetaData()->getRawData());
        $this->assertArrayHasKey('Delete', $response->getMetaData()->getRawData());
    }

    public function testFetchItem()
    {
        $this->mockResponse('fetchItem.txt');
        $response = $this->client->getUser(3);
        $this->assertInternalType('object', $response);

        $this->mockResponse('fetchItemEmpty.txt');
        $response = $this->client->getUser(3);
        $this->assertNull($response->getRawData());
    }

    public function testFetchColumns()
    {
        $this->mockResponse('fetchColumns.txt');
        $response = $this->client->getFields('articles');
        $this->assertInstanceOf('\Directus\SDK\Response\Item', $response);

        $this->mockResponse('fetchColumnsEmpty.txt');
        $response = $this->client->getFields('articles');
        $this->assertInstanceOf('\Directus\SDK\Response\Item', $response);
    }

    public function testFetchColumnInformation()
    {
        $this->mockResponse('fetchColumnInfo.txt');
        $response = $this->client->getField('articles', 'title');
        $this->assertInternalType('object', $response);

        $this->mockResponse('fetchColumnInfoEmpty.txt');
        $response = $this->client->getField('articles', 'name');
        $this->assertInstanceOf('\Directus\SDK\Response\Item', $response);
        $this->assertArrayHasKey('message', $response->getRawData());
        $this->assertInternalType('array', $response->getRawData());
    }

    public function testFetchGroups()
    {
        $this->mockResponse('fetchGroups.txt');
        $response = $this->client->getRoles();
        $this->assertInstanceOf('\Directus\SDK\Response\ItemCollection', $response);
        $this->assertSame(1, $response->count());

        $this->mockResponse('fetchGroupsEmpty.txt');
        $response = $this->client->getRoles();
        $this->assertInstanceOf('\Directus\SDK\Response\ItemCollection', $response);
        $this->assertSame(0, $response->count());
    }

    public function testFetchGroupInformation()
    {
        $this->mockResponse('fetchGroupInfo.txt');
        $response = $this->client->getRole(1);
        $this->assertInternalType('object', $response);

        $this->mockResponse('fetchGroupInfoEmpty.txt');
        $response = $this->client->getRole(2);
        $this->assertFalse($response->getRawData());
    }

    public function testFetchGroupPrivileges()
    {
        $this->mockResponse('fetchGroupPrivileges.txt');
        $response = $this->client->getRolePermissions(1);
        $this->assertInstanceOf('\Directus\SDK\Response\Item', $response);
        $this->assertInstanceOf('\Directus\SDK\Response\Item', $response[0]);
        $this->assertArrayHasKey('allow_view', $response[0]->getRawData());

        $this->mockResponse('fetchGroupPrivilegesEmpty.txt');
        $response = $this->client->getRolePermissions(30);
        $this->assertInstanceOf('\Directus\SDK\Response\Item', $response);
        $this->assertInstanceOf('\Directus\SDK\Response\Item', $response[0]);
        $this->assertArrayNotHasKey('allow_view', $response[0]->getRawData());
    }

    public function testFetchFiles()
    {
        $this->mockResponse('fetchFiles.txt');
        $response = $this->client->getFiles();
        $this->assertInstanceOf('\Directus\SDK\Response\ItemCollection', $response);

        $this->mockResponse('fetchFilesEmpty.txt');
        $response = $this->client->getFiles();
        $this->assertInstanceOf('\Directus\SDK\Response\ItemCollection', $response);
    }

    public function testFetchFileInformation()
    {
        $this->mockResponse('fetchFileInformation.txt');
        $response = $this->client->getFile(1);
        $this->assertInternalType('object', $response);

        $this->mockResponse('fetchFileInformationEmpty.txt');
        $response = $this->client->getFile(2);
        $this->assertNull($response->getRawData());
    }

    public function testFetchSettings()
    {
        $this->mockResponse('fetchSettings.txt');
        $response = $this->client->getSettings();
        $this->assertInternalType('object', $response);
    }

    protected function mockResponse($path)
    {
        $mockPath = __DIR__ . '/Mock/'.$path;
        $mockContent = file_get_contents($mockPath);
        $response = \GuzzleHttp\Psr7\parse_response($mockContent);
        $this->mockHandler->append($response);
    }
}
