<?php
namespace noetikosgroup\smsbat;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use noetikosgroup\smsbat\models\SmsBatMessage;

/**
 * @author Noetikos Group <support@noetikos.com.ua>
 * @version 0.4
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
    private $url = "https://api.smsbat.com.ua/alphasmshttp";

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
     * @param string $to
     * @param string $message
     * @return bool
     * @throws InvalidConfigException
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
            if ($result) {
                $this->saveToDb($to, $message, $result);
                return true;
            } else
                throw new InvalidConfigException('Failed to perform a cURL session');
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
        $this->ch = null;
    }

    /**
     * Saves the message to the database
     *
     * @param string $phone
     * @param string $text
     * @param string $message
     *
     * @return bool
     */
    public function saveToDb($phone, $text, $message)
    {
        if (!$this->saveToDb) {
            return false;
        }
        $result = explode("\n", trim($message));
        $messageId = explode(':', $result[0]);

        $model = new SmsBatMessage();
        $model->text = $text;
        $model->phone = $phone;
        $model->status = $this->getMessageStatus($messageId[1]);
        $model->message_id = $messageId[1];

        return $model->save();
    }

    /**
     * Gets user balance
     *
     * @return string|bool
     */
    public function getBalance()
    {
        if ($this->connect()) {
            curl_setopt(
                $this->ch,
                CURLOPT_POSTFIELDS,
                [
                    'version' => $this->version,
                    'login' => $this->login,
                    'password' => $this->password,
                    'command' => 'balance'
                ]
            );
            $result = curl_exec($this->ch);
            $this->disconnect();

            if ($result)
                return $result;
            else
                throw new InvalidConfigException('Failed to initialize a cURL session');
        } else
            return false;
    }

    /**
     * Gets message status
     *
     * @param string $messageId
     * @return string|bool
     * @throws InvalidConfigException
     */
    public function getMessageStatus($messageId)
    {
        if ($this->connect()) {
            curl_setopt(
                $this->ch,
                CURLOPT_POSTFIELDS,
                [
                    'version' => $this->version,
                    'login' => $this->login,
                    'password' => $this->password,
                    'command' => 'receive',
                    'id' => $messageId
                ]
            );
            $result = curl_exec($this->ch);
            $this->disconnect();

            if ($result) {
                $result = explode("\n", trim($result));
                $messageStatus = explode(':', $result[0]);

                return $messageStatus[1];
            } else
                throw new InvalidConfigException('Failed to initialize a cURL session');
        } else
            return false;
    }
}
