<?php namespace Userdesk\Tests\Subscription;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Userdesk\Subscription\Subscription;
use Userdesk\Subscription\SubscriptionFactory;

use Config;
use Mockery;

class SubscriptionTest extends \Orchestra\Testbench\TestCase
{

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    protected function getPackageProviders($app)
    {
        return array('Userdesk\Subscription\SubscriptionServiceProvider');
    }

    protected function getPackageAliases($app)
    {
        return array(
            'Subscription' => 'Userdesk\Subscription\Facades\Subscription'
        );
    }

    /**
     * @covers Userdesk\Subscription\Subscription::processor
     */
    public function testCreateProcessor()
    {
        $subscriptionFactory = Mockery::mock('Userdesk\Subscription\SubscriptionFactory[createService]');
        $subscriptionFactory->shouldReceive('createService')->passthru();

        $subscription = new Subscription($subscriptionFactory);
        $processor = $subscription->processor('TwoCheckout');
        $this->assertInstanceOf('Userdesk\Subscription\Services\TwoCheckout', $processor);
    }
}
