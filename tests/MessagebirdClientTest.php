<?php

namespace NotificationChannels\Messagebird {

    // Mock Laravel config function
    function config($config)
    {
        return '';
    }

    use GuzzleHttp\Client;
    use Mockery;
    use NotificationChannels\Messagebird\MessagebirdClient;
    use NotificationChannels\Messagebird\MessagebirdMessage;
    use PHPUnit_Framework_TestCase;

    class MessagebirdClientTest extends PHPUnit_Framework_TestCase
    {
        protected $guzzle;
        protected $client;
        protected $message;

        public function setUp()
        {
            $this->guzzle = Mockery::mock(new Client());
            $this->client = Mockery::mock(new MessagebirdClient($this->guzzle, 'test_ek1qBbKbHoA20gZHM40RBjxzX'));
            $this->message = (new MessagebirdMessage('Message content'))->setOriginator('APPNAME')->setRecipients('31650520659')->setReference('000123');
        }

        public function tearDown()
        {
            Mockery::close();
            parent::tearDown();
        }

        /** @test */
        public function it_can_be_instantiated()
        {
            $this->assertInstanceOf(MessagebirdClient::class, $this->client);
            $this->assertInstanceOf(MessagebirdMessage::class, $this->message);
        }

        /** @test */
        public function it_can_send_message()
        {
            $this->client->send($this->message);
        }
    }
}
