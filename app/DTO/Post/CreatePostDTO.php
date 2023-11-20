<?php

namespace App\DTO\Post;

use App\Core\CoreDTO;
use DateTimeImmutable;

class CreatePostDTO extends CoreDTO
{
    public string $title;
    public string $body;
    public ?DateTimeImmutable $created_at;

    public function __construct(array $data)
    {
        $this->title = $data["title"];
        $this->body = $data["body"];

        if ($data['created_at'] instanceof DateTimeImmutable) 
        {
            $this->created_at = $data['created_at'];
        } 
        else 
        {
            $this->created_at = $this->createDateTimeImmutable($data['created_at']);
        }
    }

    private function createDateTimeImmutable(string $dateString): ?DateTimeImmutable
    {
        try {
            return new DateTimeImmutable($dateString);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function toArray(): array
    {
        return [
            "title" => $this->title,
            "body" => $this->body,
            "created_at" => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
