<?php

class Telegram {
    protected $token;
    protected $api = "https://api.telegram.org/bot";
    protected $idChat;

    public function __construct($token, $idChat = ''){
        $this->token = $token;
        $this->api .= $this->token;
        $this->idChat = $idChat;
    }

    protected function getUrl($command, array $params= []) {
        $res = $this->api . '/' . $command;
        if (!empty($params)) {
            $res .= '?' . http_build_query($params);
        }
        return $res;
    }
    protected function getResult($command, array $params= []) {
        try {
            return file_get_contents($this->getUrl($command, $params));
        } catch (Exception $e) {
            return '';
        }
    }

    public function setIdChat($idChat) {
        $this->idChat = $idChat;
    }

    public function sendMessage($message) {
        $dataGet = array(
            'chat_id' => $this->idChat,
            'text' => $message,
            'parse_mode' => 'HTML',
        );
        return $this->getResult('sendMessage', $dataGet);
    }

    public function sendFile($file) {
        $url = $this->getUrl('sendDocument');
        $document = new CURLFile(realpath($file));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ["chat_id" => $this->idChat, "document" => $document]);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $out = curl_exec($ch);
        curl_close($ch);
    }

    public function getUpdates() {
        return $this->getResult('getUpdates');
    }
}