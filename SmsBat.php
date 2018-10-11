<?php
namespace noetikosgroup\smsbat;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use noetikosgroup\smsbat\models\SmsBatMessage;

/**
 * @author Noetikos Group <support@noetikos.com.ua>
 * @version 0.1
 */
class SmsBat extends Component
{
    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $from;

    /**
     * Save messages to db
     *
     * @var bool
     */
    public $saveToDb = false;

    /**
     * @var string
     */
    private $url = "https://smsbat.com/api/http.php";

    /**
     * @var string
     */
    private $version = "http"; //http or https

    /**
     * @var cURL handle
     */
    private $ch;

    /**
     * Sends SMS
     *
     * @param $to
     * @param $message
     * @return bool
     */
    public function send($to, $message)
    {
        if ($this->connect()) {
            curl_setopt(
                $this->ch,
                CURLOPT_POSTFIELDS,
                [
                    'version' => $this->version,
                    'login' => $this->login,
                    'password' => $this->password,
                    'command' => 'send',
                    'from' => $this->from,
                    'to' => $to,
                    'message' => $message
                ]
            );
            $result = curl_exec($this->ch);
            $this->disconnect();

            return $result;
        } else
            return false;
    }

    /**
     * Starts the cURL session
     *
     * @return cURL handle
     * @throws InvalidConfigException
     */
    protected function connect()
    {
        if (!$this->ch) {
            $this->ch = curl_init();
            if ($this->ch) {
                curl_setopt($this->ch, CURLOPT_URL, $this->url);
                curl_setopt($this->ch, CURLOPT_POST, true);
                curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
            } else
                throw new InvalidConfigException('Failed to initialize a cURL session');
        }

        return $this->ch;
    }

    /**
     * Ends the cURL session
     */
    protected function disconnect()
    {
        curl_close($this->ch);
    }
}
