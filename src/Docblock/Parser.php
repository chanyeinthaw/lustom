<?php


namespace Lumos\Lustom\Docblock;


class Parser {
    public function parseAnnotation(string $targetClass, array $annotations) {
        try {
            $reflection = new \ReflectionClass($targetClass);
            $docContent = $reflection->getDocComment();
            $parsedData = $this->parseDocContent($docContent);
            $processors = [];

            foreach($annotations as $processor) {
                /** @var Processor $p */
                $p = new $processor;
                $p->setParams($parsedData[$p->name()]);

                $processors []= $p;
            }

            return $processors;
        } catch (\Exception $e) {
            return $annotations;
        }
    }

    private function parseDocContent($docContent) {
        $lines = explode("\r\n", $docContent);
        $annotations = [];
        $lines = array_filter($lines, function($item) {
            $item = str_replace('*', '', $item);
            $item = trim($item);

            return strpos($item, '@') === 0;
        });

        foreach($lines as $item) {
            $item = str_replace('*', '', $item);
            $item = trim($item);
            /**
             * @var $processor
             * @var $params
             */
            extract($this->parseItem($item));
            $annotations[$processor] = $params;
        }

        return $annotations;
    }

    private function parseItem($item) {
        $item = substr($item, 1);
        $firstBraceIndex = strpos($item, '(');
        $lastBraceIndex = strpos($item, ')');

        if ($firstBraceIndex === false) return ['processor' => str_replace('@', '', $item), 'params' => []];
        if ($lastBraceIndex === false)  return ['processor' => str_replace('@', '', $item), 'params' => []];

        $processor = str_replace('@', '', substr($item, 0, $firstBraceIndex));
        $params = substr($item, $firstBraceIndex);
        $params = $this->parseParams($params);

        return compact('processor', 'params');
    }

    private function parseParams($params) {
        $params = substr($params, 1, strlen($params) - 2);
        $params = explode(',', $params);
        $parsedParams = [];

        foreach($params as $pair) {
            list($key, $value) = explode('=', $pair);
            $parsedParams[trim($key)] = $value;
        }

        return $parsedParams;
    }
}