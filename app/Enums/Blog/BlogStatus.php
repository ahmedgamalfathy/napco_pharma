<?php
namespace App\Enums\Blog;
enum BlogStatus :int{
    case NOT_PUBLISHED = 0;
    case PUBLISHED = 1;
    public function values()
    {
        return array_column(self::cases(),'value');
    }
    public static function orderedStatuses(): array
    {
        return self::cases();
    }
}

