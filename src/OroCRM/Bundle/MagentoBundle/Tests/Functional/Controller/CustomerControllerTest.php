<?php

namespace OroCRM\Bundle\MagentoBundle\Tests\Functional\Controller;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class CustomerControllerTest extends AbstractController
{
    /** @var \OroCRM\Bundle\MagentoBundle\Entity\Customer */
    public static $customer;

    protected function postFixtureLoad()
    {
        parent::postFixtureLoad();

        self::$customer = $this->getContainer()
            ->get('doctrine')
            ->getRepository('OroCRMMagentoBundle:Customer')
            ->findOneByChannel($this->channel);
    }

    protected function getMainEntityId()
    {
        return self::$customer->getid();
    }

    public function testView()
    {
        $this->client->request(
            'GET',
            $this->getUrl('orocrm_magento_customer_view', ['id' => $this->getMainEntityId()])
        );
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('Customers', $result->getContent());
        $this->assertContains('test@example.com', $result->getContent());
        $this->assertContains('John', $result->getContent());
        $this->assertContains('Doe', $result->getContent());
        $this->assertContains('John Doe', $result->getContent());
        $this->assertContains('Address Book', $result->getContent());
        $this->assertContains('Sales', $result->getContent());
        $this->assertContains('Orders', $result->getContent());
        $this->assertContains('Shopping Carts', $result->getContent());
        $this->assertContains('Demo Web store', $result->getContent());
        $this->assertContains('web site', $result->getContent());
    }

    public function gridProvider()
    {
        return [
            'Customers grid'                             => [
                [
                    'gridParameters' => [
                        'gridName' => 'magento-customers-grid'
                    ],
                    'gridFilters'    => [],
                    'channelName'    => 'Demo Web store',
                    'verifying'      => [
                        'firstName'   => 'John',
                        'lastName'    => 'Doe',
                        'email'       => 'test@example.com',
                        'lifetime'    => '$0.00',
                        'countryName' => null,
                        'regionName'  => null,
                    ],
                    'isResult'       => true
                ],
            ],
            'Customers grid with filters'                => [
                [
                    'gridParameters' => [
                        'gridName' => 'magento-customers-grid'
                    ],
                    'gridFilters'    => [
                        'magento-customers-grid[_filter][lastName][value]'  => 'Doe',
                        'magento-customers-grid[_filter][firstName][value]' => 'John',
                        'magento-customers-grid[_filter][email][value]'     => 'test@example.com',
                    ],
                    'channelName'    => 'Demo Web store',
                    'verifying'      => [
                        'firstName'   => 'John',
                        'lastName'    => 'Doe',
                        'email'       => 'test@example.com',
                        'lifetime'    => '$0.00',
                        'countryName' => null,
                        'regionName'  => null,
                    ],
                    'isResult'       => true
                ],
            ],
            'Customers grid with filters without result' => [
                [
                    'gridParameters' => [
                        'gridName' => 'magento-customers-grid'
                    ],
                    'gridFilters'    => [
                        'magento-customers-grid[_filter][lastName][value]'  => 'Doe1',
                        'magento-customers-grid[_filter][firstName][value]' => 'John1',
                        'magento-customers-grid[_filter][email][value]'     => 'test@example.com',
                    ],
                    'channelName'    => 'Demo Web store',
                    'verifying'      => [
                        'firstName'   => 'John',
                        'lastName'    => 'Doe',
                        'email'       => 'test@example.com',
                        'lifetime'    => '$0.00',
                        'countryName' => null,
                        'regionName'  => null,
                    ],
                    'isResult'       => false
                ],
            ],
            'Customer Cart grid'                         => [
                [
                    'gridParameters' => [
                        'gridName' => 'magento-customer-cart-widget-grid',
                        'id'       => 'customerId',
                        'channel'  => 'channelId'
                    ],
                    'gridFilters'    => [],
                    'channelName'    => 'Demo Web store',
                    'verifying'      => [
                        'grandTotal'  => '$2.54',
                        'statusLabel' => 'Open',
                        'stepLabel'   => 'Open',
                    ],
                    'isResult'       => true
                ],
            ],
            'Customer order grid'                        => [
                [
                    'gridParameters' => [
                        'gridName' => 'magento-customer-order-grid',
                        'id'       => 'customerId',
                        'channel'  => 'channelId'
                    ],
                    'gridFilters'    => [],
                    'channelName'    => 'Demo Web store',
                    'verifying'      => [
                        'totalAmount'     => '$0.00',
                        'totalPaidAmount' => '$17.85',
                        'status'          => 'open',
                        'stepLabel'       => 'Not contacted',
                    ],
                    'isResult'       => true
                ],
            ],
        ];
    }
}
