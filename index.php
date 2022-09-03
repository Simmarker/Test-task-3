<?php

class Parser
{
    /**
     * Приватное свойство, тут лежит информация о полученной странице. Эта инфа нигде и никогда не нужна, кроме как в этом классе
     */
    private $parserData = [];

    public static function getPage($url)
    {
        // Получение страницы
        $sContent = file_get_contents($url);

        if ($sContent === false) {
            $arData = false;
        } else {
            $arData["content"] = $sContent;
        }

        $parser = new Parser();
        $parser->parserData = [
            "data" => $arData
        ];

        return $parser;
    }

    public function getTag()
    {
        // регулярные выражения для парсинга
        $regexpTag = '/<\s*([^\/!A-Z1-9][a-z1-9]*)/';
        $regexpTagComment = '/<(!--)/';
        $regexpTagHTML = '/<\s*(!DOCTYPE)/';

        if (!empty($this->parserData["data"])) {

            $content = $this->parserData["data"]["content"];
            // парсинг содержимого
            preg_match_all($regexpTagHTML, $content, $matchesTagHTML);
            preg_match_all($regexpTagComment, $content, $matchesTagComment);
            preg_match_all($regexpTag, $content, $matchesTag);

            // слияние массивов
            // подсчет количества всех значений
            return array_count_values(array_merge($matchesTagHTML[1], $matchesTagComment[1], $matchesTag[1]));
        }
        return [];
    }

}

$parser = Parser::getPage('https://google.com/'); // вернет массив данных
$arrTag = $parser->getTag(); // вернет ассоциативный массив значений


echo "<pre>". print_r($arrTag, true) . "</pre>";
