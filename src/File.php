<?php

namespace Directus\SDK;

class File implements \JsonSerializable
{
    protected $path;
    protected $title;
    protected $caption;
    protected $tags;

    public function __construct($path, $title = null, $caption = null, $tags = null)
    {
        $this->path = $path;
        $this->title = $title;
        $this->caption = $caption;
        $this->tags = $caption;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray()
    {
        return array_merge([
            'title' => $this->title,
            'caption' => $this->caption,
            'tags' => $this->tags,
        ], $this->parseFile());
    }

    protected function parseFile()
    {
        $attributes = [];
        $path = $this->path;
        if (file_exists($path)) {
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $mimeType = mime_content_type($path);
            $attributes['name'] = pathinfo($path, PATHINFO_FILENAME) . '.' . $ext;
            $attributes['type'] = $mimeType;
            $content = file_get_contents($path);
            $base64 = 'data:' . $mimeType . ';base64,' . base64_encode($content);
            $attributes['data'] = $base64;
        } else {
            throw new \Exception('Missing "file" or "data" attribute.');
        }

        return $attributes;
    }
}