<?php


namespace App\Http\Session;

use App\Model\MongoDB\Sessions;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Http\Session\CacheSession;

class MongoDbSession extends CacheSession
{
    public $cacheKey;

    private $sessionModel;

    protected $_timeout;

    public function __construct(array $config = [])
    {
        $this->sessionModel = new Sessions();
        $this->_timeout     = (int)ini_get('session.gc_maxlifetime');
        parent::__construct($config);
    }

    // Read data from the session.
    public function read($id)
    {
        try {
            $result = $this->sessionModel->findOne(['id' => $id]);
            if ($result && $result->data) {
                return json_decode($result->data);
            }
        } catch (\Exception $e) {
        }

        return '';
    }

    // Write data into the session.
    public function write($id, $data)
    {
        $oldData = $this->read($id);
        if ($oldData) {
            foreach ($data as $key => $val) {
                $oldData[$key] = $val;
            }
            $data = $oldData;
        } else {

        }
        $date = $this->sessionModel->getNowIsoFormat();
        $this->sessionModel->deleteOne(['id' => $id]);
        $this->sessionModel->insertOne([
            'id'       => $id,
            'data'     => $data,
            'expires'  => time() + $this->_timeout,
            'created'  => $date,
            'modified' => $date
        ]);

        return true;
    }

    // Destroy a session.
    public function destroy($id)
    {
        $this->sessionModel->deleteOne(['id' => $id]);

        return true;
    }

    // Removes expired sessions.
    public function gc($expires = null)
    {
//        $this->sessionModel->deleteMany([
//            'expires' => [
//
//            ]
//        ]);
//        return Cache::gc($this->cacheKey) && parent::gc($expires);
        return true;
    }
}
