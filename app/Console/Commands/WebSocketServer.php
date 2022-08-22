<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

class WebSocketServer extends Command
{
    public $host = null;
    public $port = null;
    public $auth = false;
    public $debug = false;
    public $callback = [];
    protected $config = [];
    protected $callback_type = ['auth', 'event', 'saveMessage'];

    protected $signature = 'websocket:init';
    protected $description = 'Initializing Websocket server to receive and manage connections';

    public function __construct() {
        parent::__construct();
        echo '   <br> --> WebSocketServer __construct called'; 
        $this->config = (!empty($config)) ? $config : [];
        $this->host = env('RC_HOST');
        $this->port = env('RC_PORT');
        $this->auth = env('RC_AUTH');
        $this->debug = env('RC_DEBUG');
    }

    public function handle() {
        echo '  <br> -->  WebSocketServer handle called'; 
        $server = IoServer::factory( 
            new HttpServer( 
                new WsServer( 
                    new RatChetController() 
                ) 
            ), 
            $this->port, $this->host
        );
        $server->run();
    }

    public function set_callback($type = null, array $callback = [] ) {
        if (!empty($type) && in_array($type, $this->callback_type)) {
            if (is_callable($callback)) {
                $this->callback[$type] = $callback;
            } else {
                output('fatal', 'Method ' . $callback[1] . ' is not defined');
            }
        }
    }

}

class RatChetController implements MessageComponentInterface
{
    protected $clients;
    protected $subscribers = [];
    protected $myUsers = [];
    protected $webSocketServer;

    public function __construct() {
        echo '   <br>--> RatChetController __construct called';
        $this->clients = new SplObjectStorage;
        $this->webSocketServer = new WebSocketServer;
        // var_dump($this->webSocketServer->callback);
        // if ($this->webSocketServer->auth && empty($this->webSocketServer->callback['auth'])) {
        //     output('fatal', 'Authentication callback is required, you must set it before run server, aborting..');
        // }

        if ($this->webSocketServer->debug) {
            output('success', 'Running server on host ' . $this->webSocketServer->host . ':' . $this->webSocketServer->port);
        }

        if ($this->webSocketServer->debug && !empty($this->webSocketServer->callback['auth']) ) {
            output('success', 'Authentication activated');
        }

    }

    function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);        
        if ($this->webSocketServer->debug) {
            output('info', 'New client connected as (' . $conn->resourceId . ')');
        }
    }

    function onClose(ConnectionInterface $conn) {
        if ($this->webSocketServer->debug) {
            output('info', 'Client (' . $conn->resourceId ." : ". (isset($conn->subscriber_id) && !empty($conn->subscriber_id) ? $conn->subscriber_id : '') . ') disconnected');
        }
        
        if(isset($conn->subscriber_id) && !empty($conn->subscriber_id)){
            unset($this->myUsers[$conn->subscriber_id]);
        }
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";

        // foreach ($this->userresources as &$userId) {
        //     foreach ($userId as $key => $resourceId) {
        //         if ($resourceId==$conn->resourceId) {
        //             unset( $userId[ $key ] );
        //         }
        //     }
        // }
    }

    function onError(ConnectionInterface $conn, \Exception $e) {
        if ($this->webSocketServer->debug) {
            output('fatal', 'An error has occurred: ' . $e->getMessage());
        }
        if(isset($conn->subscriber_id) && !empty($conn->subscriber_id)) {
            unset($this->myUsers[$conn->subscriber_id]);
        }
        $conn->close();
    }

    function onMessage(ConnectionInterface $conn, $msg) {
        var_dump($msg);
        var_dump(valid_json($msg));
        if (valid_json($msg)) {
            $data = json_decode($msg);
            if(!empty($data) && isset($data->nameValuePairs)){
                $data = $data->nameValuePairs;
            }
        }
        // print_r($conn);die;
        if(!isset($data) || empty($data) || !isset($data->hookMethod) || empty($data->hookMethod)){
            return false;
        }
        
        if($data->hookMethod != "registration" && (!isset($conn->subscriber_id) || empty($conn->subscriber_id))){
            if ($this->webSocketServer->debug) {
                output('info', 'Client (' . $conn->resourceId .") is not authenticated.");
            }
            return false;
        }
        
        // $broadcast = (!empty($data->broadcast) and $data->broadcast == true) ? true : false; // not use as now
        // $clients = count($this->clients) - 1; // not use as now

        switch ($data->hookMethod) {
            case 'registration':
                // $this->CI->db->reconnect(); // \DB::beginTransaction();
                print_r([$this->webSocketServer->callback['auth']]);die;
                $auth = call_user_func_array($this->webSocketServer->callback['auth'], array($data));
                if (empty($auth)) {
                    output('error', 'Client (' . $conn->resourceId . " : " . $data->token . ') authentication failure');
                    $conn->close(1006);
                    return false;
                }

                $conn->subscriber_id = $auth->id;
                $conn->timeZone = $auth->timeZone;
                $conn->subscriber_auth_id = $auth->auth_id;
                $conn->userData = $auth;
                $this->myUsers[$auth->id][$auth->auth_id] = $conn;
                if ($this->webSocketServer->debug) {
                    output('success', 'Client (' . $conn->resourceId ." : ". $conn->subscriber_id . ') authentication success');
                }
                break;
        }
    }
}
