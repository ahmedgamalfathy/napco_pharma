<?php
namespace App\Enums\ContactUs;
enum ContactMessagesStatus:int{
    case OPENED=1;
    case CLOSED=0;
    public static function values()
    {
        return array_column(self::cases(),'value');
    }
    public static function orderedStatuses(): array
    {
        return self::cases();
    }
}
?>
